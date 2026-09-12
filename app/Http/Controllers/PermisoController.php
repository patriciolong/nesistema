<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermisoController extends Controller
{
    /**
     * Display a listing of users and their permission statuses.
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser->hasPermission('permisos.manage')) {
            abort(403, 'Acceso restringido: No tienes autorización para gestionar permisos de usuarios.');
        }

        $query = User::query();

        if ($request->filled('buscar')) {
            $search = $request->input('buscar');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('office', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'todos') {
            $query->where('role', $request->role);
        }

        if ($request->filled('office') && $request->office !== 'todas') {
            $query->where('office', $request->office);
        }

        if ($request->filled('status') && $request->status !== 'todos') {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();
        $roles = ['Administrador', 'Supervisor', 'Asesor', 'Empleado'];
        $oficinas = \App\Models\Oficina::orderBy('nombre')->get();
        $availableGroups = User::AVAILABLE_PERMISSIONS;

        // Calculate total available permissions count
        $totalPermissionsCount = 0;
        foreach ($availableGroups as $group) {
            $totalPermissionsCount += count($group['permisos']);
        }

        return view('permisos.index', compact('users', 'roles', 'oficinas', 'availableGroups', 'totalPermissionsCount'));
    }

    /**
     * Show the form for editing the specified user's permissions.
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();
        if (!$currentUser->hasPermission('permisos.manage')) {
            abort(403, 'Acceso restringido: No tienes autorización para gestionar permisos de usuarios.');
        }

        $availableGroups = User::AVAILABLE_PERMISSIONS;
        $effectivePermissions = $user->getEffectivePermissions();
        $isCustom = is_array($user->permissions);

        return view('permisos.edit', compact('user', 'availableGroups', 'effectivePermissions', 'isCustom'));
    }

    /**
     * Update the specified user's permissions in storage.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        if (!$currentUser->hasPermission('permisos.manage')) {
            abort(403, 'Acceso restringido: No tienes autorización para gestionar permisos de usuarios.');
        }

        // Action: Reset to role default
        if ($request->has('reset_to_role')) {
            $user->permissions = null;
            $user->save();

            return redirect()->route('permisos.index')
                ->with('success', "Permisos de '{$user->name}' restablecidos a los valores predeterminados del rol ({$user->role}).");
        }

        $selectedPermissions = $request->input('permissions', []);
        if (!is_array($selectedPermissions)) {
            $selectedPermissions = [];
        }

        // Validate that each submitted permission exists in our master catalog
        $validKeys = [];
        foreach (User::AVAILABLE_PERMISSIONS as $group) {
            foreach (array_keys($group['permisos']) as $key) {
                $validKeys[] = $key;
            }
        }

        $cleanPermissions = array_values(array_intersect($selectedPermissions, $validKeys));

        $user->permissions = $cleanPermissions;
        $user->save();

        return redirect()->route('permisos.index')
            ->with('success', "Permisos actualizados exitosamente para el usuario '{$user->name}' (" . count($cleanPermissions) . " módulos activos).");
    }

    /**
     * Apply a quick permission preset template to a user.
     */
    public function applyPreset(Request $request, User $user)
    {
        $currentUser = Auth::user();
        if (!$currentUser->hasPermission('permisos.manage')) {
            abort(403, 'Acceso restringido: No tienes autorización para gestionar permisos.');
        }

        $preset = $request->input('preset');

        switch ($preset) {
            case 'admin':
                $user->permissions = User::getRoleDefaultPermissions('Administrador');
                break;
            case 'supervisor':
                $user->permissions = User::getRoleDefaultPermissions('Supervisor');
                break;
            case 'cajero':
            case 'empleado':
                $user->permissions = User::getRoleDefaultPermissions('Empleado');
                break;
            case 'solo_caja':
                $user->permissions = [
                    'dashboard.view',
                    'cajas.operar', 'cajas.historial',
                    'clientes.view', 'clientes.abonar'
                ];
                break;
            case 'reset':
                $user->permissions = null;
                break;
            default:
                return redirect()->back()->with('error', 'Plantilla de permisos no válida.');
        }

        $user->save();

        return redirect()->back()->with('success', "Plantilla '{$preset}' aplicada correctamente a {$user->name}.");
    }
}
