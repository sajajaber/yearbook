<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', ['users' => $users]);
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role_id' => $validated['role_id'],
            'status' => 'active',
        ]);

        AuditLog::record('created', $user);

        return redirect()->back()
            ->with('success', 'User added.')
            ->with('active_tab', $request->input('tab', 'users'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', ['user' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,suspended',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);
        AuditLog::record('updated', $user);

        return redirect()->back()
            ->with('success', 'User updated.')
            ->with('active_tab', $request->input('tab', 'users'));
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        AuditLog::record('deleted', $user);

        return redirect()->back()
            ->with('success', 'User deleted.')
            ->with('active_tab', request()->input('tab', 'users'));
    }
}
