<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with('user')->latest()->paginate(10);
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Create User
            $password = Str::random(8); // Temporary password, typically send via email
            $role = Role::where('slug', 'teacher')->firstOrFail();

            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($password), // In production, send invite link or temporary password
                'role_id' => $role->id,
            ]);

            // 2. Create Teacher Profile
            $teacherData = $request->except(['first_name', 'last_name', 'email']);
            $teacherData['user_id'] = $user->id;
            
            $teacher = Teacher::create($teacherData);
            
            // TODO: Dispatch event to send welcome email with password
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('user', 'subjects', 'streams');
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        DB::transaction(function () use ($request, $teacher) {
            // Update User
            $teacher->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
            ]);

            // Update Teacher Profile
            $teacher->update($request->except(['first_name', 'last_name', 'email']));
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {
            $user = $teacher->user;
            $teacher->delete(); // Soft delete teacher
            $user->delete(); // Soft delete user (if user implements SoftDeletes, which it might not, so standard delete for user or deactive)
            // Ideally we might just want to deactivate the user or soft delete if User model has SoftDeletes.
            // For now, let's keep user primarily but deactivate login?
            // Or just deleting profile logic. 
            // In many systems, we soft delete both.
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
