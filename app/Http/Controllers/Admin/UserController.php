<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'schedule_manager']);
        })
        ->when($request->search, function ($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->with('roles')
        ->latest()
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::whereIn('name', ['admin', 'schedule_manager'])->get();
        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id|in:' . Role::whereIn('name', ['admin', 'schedule_manager'])->pluck('id')->implode(','),
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Sync roles properly
        if ($request->has('roles')) {
            $roleIds = array_map('intval', $request->roles);
            $user->syncRoles($roleIds);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // We typically don't need a separate show page for users in this context, 
        // usually edit is enough. But if needed, we can implement it.
        // For now, redirect to edit or implementing a read-only view. 
        // The Blade version had a show view. Let's redirect to Edit for simplicity or standard.
         return redirect()->route('admin.users.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'schedule_manager']);
        })->with('roles')->findOrFail($id);
        
        $roles = Role::whereIn('name', ['admin', 'schedule_manager'])->get();
        
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
            // Pass current role IDs for easy binding
            'currentRoles' => $user->roles->pluck('id')->toArray() 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'schedule_manager']);
        })->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id|in:' . Role::whereIn('name', ['admin', 'schedule_manager'])->pluck('id')->implode(','),
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();

        // Sync roles properly
        if ($request->has('roles')) {
            $roleIds = array_map('intval', $request->roles);
            $user->syncRoles($roleIds);
        } else {
            // Be careful, this removes all roles if not sent. 
            // In our Edit form we will ensure roles are sent.
            $user->syncRoles([]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'schedule_manager']);
        })->findOrFail($id);
        
        // Prevent deleting the current user
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus diri sendiri.');
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
