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
use Illuminate\Support\Facades\Log;

class StudentImportController extends Controller
{
    private $importStats = [
        'total' => 0,
        'successful' => 0,
        'failed' => 0,
        'skipped' => 0,
        'errors' => [],
        'users_created' => false
    ];
    
    private $defaultPasswordHash;
    
    public function __construct()
    {
        // Pre-compute default password hash once for performance
        // Default password: "Student@2024" - users should change on first login
        $this->defaultPasswordHash = '$2y$12$ZqVlPxFqHCqE0yrKGOqPQ.MqYvL6wBhQ5kMC0YNj3FV3z0vHEZLJ2';
    }

    public function index()
    {
        return view('students.import.index');
    }

    public function preview(Request $request)
    {
        // If GET request, redirect to import index
        if ($request->isMethod('get')) {
            return redirect()->route('students.import.index')
                ->with('error', 'Please upload a CSV file first.');
        }

        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            ]);

            $file = $request->file('file');
            
            // Validate file can be read
            if (!is_readable($file->getRealPath())) {
                throw new \Exception('Unable to read uploaded file. Please try again.');
            }
            
            $csvData = array_map('str_getcsv', file($file->getRealPath()));
            
            if (empty($csvData)) {
                throw new \Exception('The uploaded file is empty.');
            }
            
            // Get header row
            $header = array_shift($csvData);
            
            if (empty($header)) {
                throw new \Exception('No header row found in CSV file.');
            }
            
            // Validate required columns exist
            $requiredColumns = ['admission_number', 'first_name', 'last_name', 'date_of_birth', 'gender'];
            $missingColumns = array_diff($requiredColumns, $header);
            
            if (!empty($missingColumns)) {
                throw new \Exception('Missing required columns: ' . implode(', ', $missingColumns));
            }
            
            // Preview first 10 rows
            $preview = array_slice($csvData, 0, 10);
            $totalRows = count($csvData);
            
            if ($totalRows === 0) {
                throw new \Exception('No data rows found in CSV file (only header present).');
            }
            
            // Ensure temp-imports directory exists
            $tempDir = storage_path('app/temp-imports');
            if (!file_exists($tempDir)) {
                if (!mkdir($tempDir, 0755, true)) {
                    throw new \Exception('Unable to create temporary storage directory.');
                }
            }
            
            // Store file temporarily
            $filename = Str::random(40) . '.csv';
            $destinationPath = $tempDir . '/' . $filename;
            
            if (!copy($file->getRealPath(), $destinationPath)) {
                throw new \Exception('Failed to save uploaded file. Please try again.');
            }
            
            Log::info('CSV file uploaded successfully', [
                'filename' => $filename,
                'rows' => $totalRows,
                'size' => filesize($destinationPath)
            ]);
            
            // Get available streams for mapping
            $streams = Stream::with('schoolClass')
                ->where('academic_year_id', AcademicYear::active()->first()?->id)
                ->get();
            
            $expectedFields = [
                'admission_number', 'first_name', 'last_name', 'other_names',
                'date_of_birth', 'gender', 'email', 'phone_number',
                'stream_id', 'admission_date', 'guardian_name', 'guardian_phone', 
                'guardian_email', 'guardian_relationship'
            ];
            
            return view('students.import.preview', compact('header', 'preview', 'totalRows', 'filename', 'expectedFields', 'streams'));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('CSV preview failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName() ?? 'unknown'
            ]);
            
            return back()->with('error', 'Failed to preview file: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        // Reset stats for this import
        $this->importStats = [
            'total' => 0,
            'successful' => 0,
            'failed' => 0,
            'skipped' => 0,
            'errors' => []
        ];
        
        // Increase execution time for large imports
        set_time_limit(300); // 5 minutes
        
        try {
            $request->validate([
                'filename' => 'required|string',
                'create_users' => 'nullable',
                'create_guardians' => 'nullable',
                'enroll_in_streams' => 'nullable',
            ]);

            $filePath = storage_path('app/temp-imports/' . $request->filename);
            
            // Validate file exists
            if (!file_exists($filePath)) {
                throw new \Exception('Import file not found. Please upload the file again.');
            }
            
            if (!is_readable($filePath)) {
                throw new \Exception('Import file cannot be read. Please check file permissions.');
            }

            $csvData = array_map('str_getcsv', file($filePath));
            $header = array_shift($csvData);
            
            $this->importStats['total'] = count($csvData);
            $activeYear = AcademicYear::active()->first();
            
            // Track if users were created for the success message
            if ($request->create_users) {
                $this->importStats['users_created'] = true;
            }

            Log::info('Starting student import', [
                'filename' => $request->filename,
                'total_rows' => $this->importStats['total'],
                'create_users' => (bool)$request->create_users,
                'create_guardians' => (bool)$request->create_guardians,
                'enroll_in_streams' => (bool)$request->enroll_in_streams,
            ]);

            DB::beginTransaction();
            
            foreach ($csvData as $index => $row) {
                $rowNumber = $index + 2; // +2 because array is 0-indexed and we removed header
                
                try {
                    // Map row to associative array
                    $data = array_combine($header, $row);
                    
                    if ($data === false || empty($data['admission_number'])) {
                        $this->addError($rowNumber, 'Invalid or empty row');
                        continue;
                    }
                    
                    // Process this row
                    $this->processStudentRow($data, $rowNumber, $request, $activeYear);
                    
                    $this->importStats['successful']++;
                    
                } catch (\Exception $e) {
                    $this->addError($rowNumber, $e->getMessage());
                    Log::warning("Row $rowNumber failed", [
                        'admission_number' => $data['admission_number'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            DB::commit();
            
            // Clean up temp file
            @unlink($filePath);
            
            Log::info('Student import completed', $this->importStats);
            
            return $this->redirectWithResults();
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (isset($filePath)) {
                @unlink($filePath);
            }
            
            Log::error('Student import failed completely', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $request->filename ?? 'unknown'
            ]);
            
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
    
    private function processStudentRow(array $data, int $rowNumber, Request $request, $activeYear)
    {
        // Validate required fields
        $validator = Validator::make($data, [
            'admission_number' => 'required|string|unique:students,admission_number',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            throw new \Exception(implode('; ', $validator->errors()->all()));
        }
        
        // Normalize gender
        $gender = $this->normalizeGender($data['gender']);
        if (!$gender) {
            throw new \Exception("Invalid gender value '{$data['gender']}'. Must be Male, Female, or Other.");
        }

        // Create user if requested
        $user = null;
        if ($request->create_users) {
            $user = $this->createUserForStudent($data);
        }

        // Parse dates
        $dateOfBirth = $this->parseDate($data['date_of_birth']);
        if (!$dateOfBirth) {
            throw new \Exception("Invalid date of birth format: {$data['date_of_birth']}");
        }
        
        $admissionDate = $this->parseDate($data['admission_date'] ?? null) ?? now()->format('Y-m-d');

        // Create student
        $student = Student::create([
            'user_id' => $user?->id,
            'admission_number' => $data['admission_number'],
            'date_of_birth' => $dateOfBirth,
            'gender' => $gender,
            'admission_date' => $admissionDate,
        ]);

        // Create/attach guardian if requested
        if ($request->create_guardians && !empty($data['guardian_name'])) {
            $this->attachGuardianToStudent($student, $data);
        }

        // Enroll in stream if requested
        if ($request->enroll_in_streams && !empty($data['stream_id']) && $activeYear) {
            $this->enrollStudentInStream($student, $data['stream_id'], $activeYear);
        }
    }
    
    private function createUserForStudent(array $data): User
    {
        $email = !empty($data['email']) 
            ? $data['email'] 
            : strtolower($data['admission_number']) . '@student.school.com';
        
        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            throw new \Exception("Email '$email' already exists");
        }
        
        // Use pre-computed password hash for performance during bulk import
        // All students get the default password "Student@2024" - they should change it on first login
        return User::create([
            'name' => trim($data['first_name'] . ' ' . ($data['other_names'] ?? '') . ' ' . $data['last_name']),
            'email' => $email,
            'password' => $this->defaultPasswordHash,
            'role_id' => 3, // Student role
        ]);
    }
    
    private function attachGuardianToStudent(Student $student, array $data): void
    {
        $guardianPhone = $data['guardian_phone'] ?? null;
        $guardianRelationship = $data['guardian_relationship'] ?? 'Parent';
        
        if (empty($guardianPhone)) {
            throw new \Exception('Guardian phone number is required when creating guardians');
        }
        
        // Check if guardian already exists by phone
        $guardian = Guardian::where('phone_number', $guardianPhone)->first();
        
        if (!$guardian) {
            // Create guardian user first
            $guardianEmail = $data['guardian_email'] ?? strtolower(str_replace(' ', '.', $data['guardian_name'])) . '@guardian.school.com';
            
            $guardianUser = User::firstOrCreate(
                ['email' => $guardianEmail],
                [
                    'name' => $data['guardian_name'],
                    'password' => $this->defaultPasswordHash, // Use same default password
                    'role_id' => 5, // Guardian role (adjust if different)
                ]
            );
            
            $guardian = Guardian::create([
                'user_id' => $guardianUser->id,
                'relationship' => $guardianRelationship,
                'phone_number' => $guardianPhone,
                'is_primary' => true,
            ]);
        }
        
        // Attach guardian to student if not already attached
        if (!$student->guardians()->where('guardian_id', $guardian->id)->exists()) {
            $student->guardians()->attach($guardian->id, [
                'is_primary_contact' => true,
                'can_pickup' => true,
            ]);
        }
    }
    
    private function enrollStudentInStream(Student $student, $streamId, $activeYear): void
    {
        $stream = Stream::find($streamId);
        
        if (!$stream) {
            throw new \Exception("Stream with ID $streamId not found");
        }
        
        $activeTerm = $activeYear->terms()->where('is_active', true)->first();
        
        if (!$activeTerm) {
            throw new \Exception('No active term found for enrollment');
        }
        
        // Check if already enrolled
        if (!$student->streams()->where('stream_id', $streamId)->exists()) {
            $student->streams()->attach($streamId, [
                'academic_year_id' => $activeYear->id,
                'term_id' => $activeTerm->id,
                'enrollment_date' => now(),
                'is_active' => true,
            ]);
        }
    }
    
    private function normalizeGender(string $gender): ?string
    {
        $gender = strtolower(trim($gender));
        
        $genderMap = [
            'male' => 'male',
            'm' => 'male',
            'female' => 'female',
            'f' => 'female',
            'other' => 'other',
            'o' => 'other',
        ];
        
        return $genderMap[$gender] ?? null;
    }
    
    private function parseDate(?string $dateString): ?string
    {
        if (empty($dateString)) {
            return null;
        }
        
        $dateString = trim($dateString);
        
        // Try DD/MM/YYYY format
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateString, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }
        
        // Try YYYY-MM-DD format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            return $dateString;
        }
        
        // Try strtotime as fallback
        $timestamp = strtotime($dateString);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        return null;
    }
    
    private function addError(int $rowNumber, string $message): void
    {
        $this->importStats['failed']++;
        $this->importStats['errors'][] = [
            'row' => $rowNumber,
            'message' => $message
        ];
    }
    
    private function redirectWithResults()
    {
        $stats = $this->importStats;
        $hasErrors = $stats['failed'] > 0;
        
        if ($stats['successful'] === 0 && $hasErrors) {
            // Complete failure
            $errorList = collect($stats['errors'])->take(5)->map(function($error) {
                return "Row {$error['row']}: {$error['message']}";
            })->implode("\n");
            
            $message = "Import failed completely. No students were imported.\n\n";
            $message .= "First 5 errors:\n" . $errorList;
            
            if ($stats['failed'] > 5) {
                $message .= "\n\n... and " . ($stats['failed'] - 5) . " more errors.";
            }
            
            return back()->with('error', $message);
        }
        
        if ($hasErrors) {
            // Partial success
            $message = "Import completed with warnings:\n";
            $message .= "✓ Successfully imported: {$stats['successful']} students\n";
            $message .= "✗ Failed: {$stats['failed']} rows\n\n";
            
            $errorList = collect($stats['errors'])->take(3)->map(function($error) {
                return "Row {$error['row']}: {$error['message']}";
            })->implode("\n");
            
            $message .= "Sample errors:\n" . $errorList;
            
            if ($stats['failed'] > 3) {
                $message .= "\n\n... and " . ($stats['failed'] - 3) . " more errors.";
            }
            
            return redirect()->route('students.index')
                ->with('warning', $message);
        }
        
        // Complete success
        $message = "Successfully imported all {$stats['successful']} students!\n\n";
        if ($stats['users_created']) {
            $message .= "ℹ️ All users have been assigned the default password: Student@2024\n";
            $message .= "Students should change their password on first login.";
        }
        
        return redirect()->route('students.index')
            ->with('success', $message);
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
