<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        
        $permissions = Permission::withCount('roles')->orderBy('name')->get();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        $this->authorize('create', Permission::class);
        
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Permission::class);
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'slug' => ['required', 'string', 'max:255', 'unique:permissions,slug'],
            'description' => ['nullable', 'string'],
        ]);

        Permission::create($data);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
        
        $permission->load('roles');
        return view('admin.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);
        
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $permission->id],
            'slug' => ['required', 'string', 'max:255', 'unique:permissions,slug,' . $permission->id],
            'description' => ['nullable', 'string'],
        ]);

        $permission->update($data);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Cannot delete permission that is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
