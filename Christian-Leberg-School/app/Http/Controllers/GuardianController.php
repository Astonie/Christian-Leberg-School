<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Guardian::with(['user', 'students.user']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $guardians = $query->paginate(15);
        return view('guardians.index', compact('guardians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // For linking existing students, we might want a search or list.
        // For now, load all students to select from (could be heavy, consider ajax search later).
        $students = Student::with('user')->get();
        return view('guardians.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone_number' => ['required', 'string', 'max:20'],
            'relationship' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? 'password'), // Default password if not provided
                'role_id' => Role::where('slug', 'guardian')->first()->id,
            ]);

            $guardian = Guardian::create([
                'user_id' => $user->id,
                'phone_number' => $request->phone_number,
                'relationship' => $request->relationship,
                'address' => $request->address,
                'occupation' => $request->occupation,
            ]);

            $user->profile()->associate($guardian);
            $user->save();

            if ($request->has('student_ids')) {
                // Pivot data requires is_primary_contact? defaulting false for now
                $guardian->students()->attach($request->student_ids, ['is_primary_contact' => false, 'can_pickup' => true]);
            }
        });

        return redirect()->route('guardians.index')->with('success', 'Guardian created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guardian $guardian)
    {
        $guardian->load('user', 'students.user');
        return view('guardians.show', compact('guardian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guardian $guardian)
    {
        $students = Student::with('user')->get();
        return view('guardians.edit', compact('guardian', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guardian $guardian)
    {
         $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $guardian->user_id],
            'phone_number' => ['required', 'string', 'max:20'],
            'relationship' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        DB::transaction(function () use ($request, $guardian) {
            $guardian->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $guardian->update([
                'phone_number' => $request->phone_number,
                'relationship' => $request->relationship,
                'address' => $request->address,
                'occupation' => $request->occupation,
            ]);

            if ($request->has('student_ids')) {
                $guardian->students()->syncWithPivotValues($request->student_ids, ['is_primary_contact' => false, 'can_pickup' => true]);
            }
        });

        return redirect()->route('guardians.index')->with('success', 'Guardian updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guardian $guardian)
    {
        DB::transaction(function () use ($guardian) {
            $guardian->user->delete();
            $guardian->delete();
        });

        return redirect()->route('guardians.index')->with('success', 'Guardian deleted successfully.');
    }
}
