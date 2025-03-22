<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array|exists:roles,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->roles);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        if ($user->id === 1) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Cannot edit super admin user');
        }

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === 1) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Cannot edit super admin user');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array|exists:roles,id'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->roles()->sync($request->roles);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if ($user->id === 1) {
            return back()->with('error', 'Cannot delete super admin user');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
