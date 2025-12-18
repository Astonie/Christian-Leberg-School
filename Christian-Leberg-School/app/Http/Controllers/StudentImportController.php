<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Stream;
use App\Models\Guardian;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentImportController extends Controller
{
    public function index()
    {
        return view('students.import.index');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        
        // Get header row
        $header = array_shift($csvData);
        
        // Map header to expected fields
        $expectedFields = [
            'admission_number', 'first_name', 'last_name', 'other_names',
            'date_of_birth', 'gender', 'email', 'phone_number',
            'stream_id', 'admission_date', 'guardian_name', 'guardian_phone', 'guardian_email'
        ];
        
        // Preview first 10 rows
        $preview = array_slice($csvData, 0, 10);
        $totalRows = count($csvData);
        
        // Store file temporarily
        $filename = Str::random(40) . '.csv';
        $request->file('file')->storeAs('temp-imports', $filename);
        
        // Get available streams for mapping
        $streams = Stream::with('schoolClass')
            ->where('academic_year_id', AcademicYear::active()->first()?->id)
            ->get();
        
        return view('students.import.preview', compact('header', 'preview', 'totalRows', 'filename', 'expectedFields', 'streams'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'create_users' => 'boolean',
            'create_guardians' => 'boolean',
            'enroll_in_streams' => 'boolean',
        ]);

        $filePath = storage_path('app/temp-imports/' . $request->filename);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'Import file not found. Please upload again.');
        }

        $csvData = array_map('str_getcsv', file($filePath));
        $header = array_shift($csvData);
        
        $imported = 0;
        $failed = 0;
        $errors = [];
        $activeYear = AcademicYear::active()->first();

        DB::beginTransaction();
        
        try {
            foreach ($csvData as $index => $row) {
                $rowNumber = $index + 2; // +2 because array is 0-indexed and we removed header
                
                // Map row to associative array
                $data = array_combine($header, $row);
                
                // Validate required fields
                $validator = Validator::make($data, [
                    'admission_number' => 'required|string|unique:students,admission_number',
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'date_of_birth' => 'required|date',
                    'gender' => 'required|in:Male,Female,male,female,M,F',
                    'stream_id' => 'nullable|exists:streams,id',
                ]);

                if ($validator->fails()) {
                    $failed++;
                    $errors[] = "Row $rowNumber: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Create user if requested
                $user = null;
                if ($request->create_users) {
                    $email = !empty($data['email']) ? $data['email'] : strtolower($data['admission_number']) . '@student.school.com';
                    
                    $user = User::create([
                        'name' => trim($data['first_name'] . ' ' . $data['last_name']),
                        'email' => $email,
                        'password' => Hash::make($data['admission_number']), // Default password is admission number
                        'role_id' => 4, // Student role
                    ]);
                }

                // Create student
                $student = Student::create([
                    'user_id' => $user?->id,
                    'admission_number' => $data['admission_number'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'other_names' => $data['other_names'] ?? null,
                    'date_of_birth' => $data['date_of_birth'],
                    'gender' => ucfirst(strtolower($data['gender'])),
                    'email' => $data['email'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
                    'admission_date' => $data['admission_date'] ?? now(),
                ]);

                // Create guardian if requested and data provided
                if ($request->create_guardians && !empty($data['guardian_name'])) {
                    $guardianEmail = $data['guardian_email'] ?? null;
                    $guardianPhone = $data['guardian_phone'] ?? null;
                    
                    // Check if guardian already exists
                    $guardian = null;
                    if ($guardianEmail) {
                        $guardian = Guardian::where('email', $guardianEmail)->first();
                    } elseif ($guardianPhone) {
                        $guardian = Guardian::where('phone_number', $guardianPhone)->first();
                    }
                    
                    if (!$guardian) {
                        $guardian = Guardian::create([
                            'name' => $data['guardian_name'],
                            'email' => $guardianEmail,
                            'phone_number' => $guardianPhone,
                            'relationship' => $data['guardian_relationship'] ?? 'Parent',
                        ]);
                    }
                    
                    // Attach guardian to student
                    $student->guardians()->attach($guardian->id, [
                        'is_primary_contact' => true,
                        'can_pickup' => true,
                    ]);
                }

                // Enroll in stream if requested and stream_id provided
                if ($request->enroll_in_streams && !empty($data['stream_id']) && $activeYear) {
                    $student->streams()->attach($data['stream_id'], [
                        'academic_year_id' => $activeYear->id,
                        'term_id' => $activeYear->terms()->where('is_active', true)->first()?->id,
                        'enrollment_date' => now(),
                        'is_active' => true,
                    ]);
                }

                $imported++;
            }

            DB::commit();
            
            // Delete temp file
            @unlink($filePath);
            
            $message = "Successfully imported $imported students.";
            if ($failed > 0) {
                $message .= " $failed rows failed.";
            }
            
            return redirect()->route('students.index')
                ->with('success', $message)
                ->with('import_errors', $errors);
                
        } catch (\Exception $e) {
            DB::rollBack();
            @unlink($filePath);
            
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filename = 'student_import_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'admission_number',
            'first_name',
            'last_name',
            'other_names',
            'date_of_birth',
            'gender',
            'email',
            'phone_number',
            'stream_id',
            'admission_date',
            'guardian_name',
            'guardian_phone',
            'guardian_email',
            'guardian_relationship'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add sample row
            fputcsv($file, [
                'ADM001',
                'John',
                'Doe',
                'Michael',
                '2008-05-15',
                'Male',
                'john.doe@example.com',
                '+254712345678',
                '1',
                date('Y-m-d'),
                'Jane Doe',
                '+254712345679',
                'jane.doe@example.com',
                'Mother'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
