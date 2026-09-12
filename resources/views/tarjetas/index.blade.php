<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-pink-50 text-pink-600 rounded-xl border border-pink-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Gestión de Tarjetas y Datáfonos / POS') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Configuración de tarjetas y terminales de cobro vinculadas a cada banco</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('bancos.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                    Ver Bancos
                </a>
                <a href="{{ route('tarjetas.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-md shadow-pink-200 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    + Nueva Tarjeta / POS
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alertas Flash -->
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm font-semibold shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-800 text-sm font-semibold shadow-sm">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Tarjetas Estadísticas -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Tarjetas / POS</span>
                        <h4 class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm flex items-center justify-between bg-emerald-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Tarjetas Activas</span>
                        <h4 class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['activas'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm flex items-center justify-between bg-rose-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Tarjetas Inactivas</span>
                        <h4 class="text-2xl font-black text-rose-600 mt-1">{{ $stats['inactivas'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Barra de Búsqueda y Filtros -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                <form method="GET" action="{{ route('tarjetas.index') }}" class="flex flex-wrap items-center gap-3">
                    
                    <!-- Input Búsqueda -->
                    <div class="relative flex-1 min-w-[240px]">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="buscar" value="{{ request('buscar') }}" 
                               placeholder="Buscar por nombre, franquicia o 4 dígitos..." 
                               class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-100 font-medium text-slate-800 transition-all placeholder-slate-400">
                    </div>

                    <!-- Filtro Banco -->
                    <div class="min-w-[180px]">
                        <select name="banco_id" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="todos">🏦 Todos los Bancos</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}" {{ request('banco_id') == $banco->id ? 'selected' : '' }}>{{ $banco->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Tipo -->
                    <div class="min-w-[150px]">
                        <select name="tipo" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="todos">💳 Todos los Tipos</option>
                            <option value="Débito" {{ request('tipo') === 'Débito' ? 'selected' : '' }}>Débito</option>
                            <option value="Crédito" {{ request('tipo') === 'Crédito' ? 'selected' : '' }}>Crédito</option>
                            <option value="POS / Datáfono" {{ request('tipo') === 'POS / Datáfono' ? 'selected' : '' }}>POS / Datáfono</option>
                        </select>
                    </div>

                    <!-- Filtro Estado -->
                    <div class="min-w-[140px]">
                        <select name="estado" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="todos">⚡ Todos los Estados</option>
                            <option value="Activo" {{ request('estado') === 'Activo' ? 'selected' : '' }}>🟢 Activo</option>
                            <option value="Inactivo" {{ request('estado') === 'Inactivo' ? 'selected' : '' }}>🔴 Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-pink-600 hover:bg-pink-700 active:bg-pink-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-pink-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span>Filtrar</span>
                        </button>
                        @if(request()->anyFilled(['buscar', 'banco_id', 'tipo', 'estado']))
                            <a href="{{ route('tarjetas.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span>Limpiar</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabla de Tarjetas -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">Listado de Tarjetas y Terminales Registradas</h3>
                        <p class="text-xs text-slate-400">Total mostrando: {{ $tarjetas->total() }} tarjetas / POS</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600 divide-y divide-slate-100">
                        <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold tracking-wider">
                            <tr>
                                <th class="py-3.5 px-6">ID</th>
                                <th class="py-3.5 px-6">Nombre de la Tarjeta / POS</th>
                                <th class="py-3.5 px-6">Banco Asociado</th>
                                <th class="py-3.5 px-6">Tipo</th>
                                <th class="py-3.5 px-6">Franquicia</th>
                                <th class="py-3.5 px-6 text-center">Estado</th>
                                <th class="py-3.5 px-6 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($tarjetas as $tarjeta)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6 font-mono text-xs font-bold text-slate-400">#{{ $tarjeta->id }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-pink-50 border border-pink-100 flex items-center justify-center font-black text-pink-600 text-xs">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm">{{ $tarjeta->nombre }}</p>
                                                @if($tarjeta->ultimos_digitos)
                                                    <p class="text-xs font-mono text-slate-400">•••• {{ $tarjeta->ultimos_digitos }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-cyan-50 text-cyan-800 border border-cyan-200">
                                            <svg class="w-3.5 h-3.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                            </svg>
                                            {{ $tarjeta->banco->nombre ?? 'Sin Banco' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $tarjeta->tipo === 'Crédito' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            {{ $tarjeta->tipo }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-slate-700 font-bold">
                                        {{ $tarjeta->franquicia }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($tarjeta->estado === 'Activo')
                                            <span class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">
                                                Activo
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold text-slate-500 bg-slate-100 border border-slate-200 rounded-full">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('tarjetas.edit', $tarjeta->id) }}" class="p-1.5 bg-slate-100 hover:bg-pink-600 text-slate-600 hover:text-white rounded-xl transition-all shadow-sm" title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>

                                            <form action="{{ route('tarjetas.destroy', $tarjeta->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta tarjeta?');" class="inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-600 text-slate-600 hover:text-white rounded-xl transition-all shadow-sm" title="Eliminar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            <p class="text-sm font-semibold">No se encontraron tarjetas ni datáfonos con los filtros aplicados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tarjetas->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $tarjetas->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
