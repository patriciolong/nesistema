<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Oficina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource with advanced filters.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('users.manage')) {
            abort(403, 'Acceso denegado: No cuentas con el permiso necesario para gestionar usuarios.');
        }

        $query = User::query();

        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('username', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'todos') {
            $query->where('role', $request->role);
        }

        if ($request->filled('status') && $request->status !== 'todos') {
            $query->where('status', $request->status);
        }

        if ($request->filled('office') && $request->office !== 'todas') {
            $query->where('office', $request->office);
        }

        $stats = [
            'total' => User::count(),
            'activos' => User::where('status', 'Activo')->count(),
            'inactivos' => User::where('status', 'Inactivo')->count(),
            'admins' => User::where('role', 'Administrador')->count(),
        ];

        $users = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();
        $oficinas = Oficina::orderBy('nombre')->get();
        $roles = ['Administrador', 'Supervisor', 'Asesor', 'Empleado'];

        return view('users.index', compact('users', 'oficinas', 'roles', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasPermission('users.manage')) {
            abort(403, 'Acceso denegado: No cuentas con el permiso necesario para crear usuarios.');
        }

        $oficinas = Oficina::where('status', 'Activa')->orderBy('nombre')->get();
        return view('users.create', compact('oficinas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('users.manage')) {
            abort(403, 'Acceso denegado.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', 'string', 'in:Administrador,Supervisor,Asesor,Empleado'],
            'office' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Activo,Inactivo'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (!auth()->user()->hasPermission('users.manage')) {
            abort(403, 'Acceso denegado.');
        }

        $oficinas = Oficina::where('status', 'Activa')->orderBy('nombre')->get();
        // Include the user's current office even if inactive
        if (! $oficinas->contains('nombre', $user->office)) {
            $currentOffice = Oficina::where('nombre', $user->office)->first();
            if ($currentOffice) {
                $oficinas->push($currentOffice);
            } else {
                $oficinas->push(new Oficina(['nombre' => $user->office]));
            }
        }

        return view('users.edit', compact('user', 'oficinas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasPermission('users.manage')) {
            abort(403, 'Acceso denegado.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', 'in:Administrador,Supervisor,Asesor,Empleado'],
            'office' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Activo,Inactivo'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:4'],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->hasPermission('users.manage')) {
            abort(403, 'Acceso denegado.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
