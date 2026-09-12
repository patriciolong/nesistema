<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-purple-50 text-purple-600 rounded-xl border border-purple-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Gestión de Usuarios') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Administración de cuentas, roles, oficinas asignadas y credenciales</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('permisos.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition-all shadow-sm">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Matriz de Permisos
                </a>
                <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    + Nuevo Usuario
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-semibold shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-sm font-semibold shadow-sm">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Tarjetas de Resumen Estadístico -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Usuarios</span>
                        <h4 class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm flex items-center justify-between bg-emerald-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Usuarios Activos</span>
                        <h4 class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['activos'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm flex items-center justify-between bg-rose-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Usuarios Inactivos</span>
                        <h4 class="text-2xl font-black text-rose-600 mt-1">{{ $stats['inactivos'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-indigo-100 shadow-sm flex items-center justify-between bg-indigo-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Administradores</span>
                        <h4 class="text-2xl font-black text-indigo-700 mt-1">{{ $stats['admins'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Barra de Búsqueda y Filtros -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-3">
                    
                    <!-- Input Búsqueda -->
                    <div class="relative flex-1 min-w-[240px]">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="buscar" value="{{ request('buscar') }}" 
                               placeholder="Buscar por nombre, usuario o correo..." 
                               class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-800 transition-all placeholder-slate-400">
                    </div>

                    <!-- Filtro Rol -->
                    <div class="min-w-[160px]">
                        <select name="role" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="todos">🎭 Todos los roles</option>
                            @foreach($roles as $r)
                                <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Oficina -->
                    <div class="min-w-[170px]">
                        <select name="office" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="todas">🏢 Todas las oficinas</option>
                            @foreach($oficinas as $ofi)
                                <option value="{{ $ofi->nombre }}" {{ request('office') === $ofi->nombre ? 'selected' : '' }}>{{ $ofi->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Estado -->
                    <div class="min-w-[140px]">
                        <select name="status" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="todos">⚡ Todos los estados</option>
                            <option value="Activo" {{ request('status') === 'Activo' ? 'selected' : '' }}>🟢 Activo</option>
                            <option value="Inactivo" {{ request('status') === 'Inactivo' ? 'selected' : '' }}>🔴 Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-indigo-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span>Filtrar</span>
                        </button>
                        @if(request()->anyFilled(['buscar', 'role', 'office', 'status']))
                            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span>Limpiar</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">Listado de Usuarios Registrados</h3>
                        <p class="text-xs text-slate-400">Total mostrando: {{ $users->total() }} registros</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600 divide-y divide-slate-100">
                        <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold tracking-wider">
                            <tr>
                                <th class="py-3.5 px-6">Usuario / Colaborador</th>
                                <th class="py-3.5 px-6">Usuario Acceso</th>
                                <th class="py-3.5 px-6">Rol</th>
                                <th class="py-3.5 px-6">Oficina Asignada</th>
                                <th class="py-3.5 px-6 text-center">Estado</th>
                                <th class="py-3.5 px-6 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($users as $user)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white font-black flex items-center justify-center text-sm shadow-sm shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 text-sm">{{ $user->name }}</div>
                                                <div class="text-xs text-slate-400">{{ $user->email ?? 'Sin correo registrado' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs font-bold text-slate-600">
                                        {{ $user->username }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                            @if($user->role == 'Administrador') bg-purple-50 text-purple-700 border border-purple-200 
                                            @elseif($user->role == 'Supervisor') bg-blue-50 text-blue-700 border border-blue-200 
                                            @elseif($user->role == 'Asesor') bg-amber-50 text-amber-700 border border-amber-200 
                                            @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-xs font-semibold text-slate-700">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-lg text-slate-800">
                                            🏢 {{ $user->office }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                            @if($user->status == 'Activo') bg-emerald-50 text-emerald-700 border border-emerald-200 
                                            @else bg-rose-50 text-rose-700 border border-rose-200 @endif">
                                            {{ $user->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('permisos.edit', $user) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm" title="Gestionar Permisos">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                Permisos
                                            </a>

                                            <a href="{{ route('users.edit', $user) }}" class="p-1.5 bg-slate-100 hover:bg-blue-600 text-slate-600 hover:text-white rounded-xl transition-all shadow-sm" title="Editar Usuario">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            
                                            @if(auth()->id() !== $user->id)
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este usuario?');" class="inline-block m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white rounded-xl transition-all shadow-sm" title="Eliminar Usuario">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <p class="text-sm font-semibold">No se encontraron usuarios con los filtros aplicados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
