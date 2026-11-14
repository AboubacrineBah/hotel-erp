<?php

namespace App\Http\Controllers\ERP;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserPrivilegeController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('name')->get();
        $roles = Role::with('permissions')->get();

        return view('erp.admin.users_rbac', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'exists:roles,name'],
            'hotel_assignment' => ['required', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'] ?? 'Azalai2026'),
            'hotel_assignment' => $validated['hotel_assignment'],
            'nomad_points' => $validated['role'] === 'vip_client' ? 5000 : 0,
            'is_active' => true,
        ]);

        $user->assignRole($validated['role']);

        return back()->with('success', "Compte créé pour {$user->name} avec le rôle {$validated['role']}.");
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        if ($user->id === Auth::id() && $validated['role'] !== 'super_admin') {
            return back()->withErrors(['role' => 'Vous ne pouvez pas retirer votre propre rôle Super Admin.']);
        }

        $user->syncRoles([$validated['role']]);

        return back()->with('success', "Privilèges de {$user->name} mis à jour ({$validated['role']}).");
    }

    public function toggleActive(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['user' => 'Impossible de suspendre votre propre compte actif.']);
        }

        $user->update(['is_active' => ! $user->is_active]);
        $state = $user->is_active ? 'activé' : 'suspendu';

        return back()->with('success', "Le compte de {$user->name} a été {$state}.");
    }
}
