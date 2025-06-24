<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->latest()->paginate(10);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'required|array|exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        $role->permissions()->attach($request->permissions);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        if ($role->id === 1) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Cannot edit admin role');
        }

        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->id === 1) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Cannot edit admin role');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array|exists:permissions,id'
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        $role->permissions()->sync($request->permissions);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if ($role->id === 1) {
            return back()->with('error', 'Cannot delete admin role');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete role that has users');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
