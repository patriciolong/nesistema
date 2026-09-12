<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-slate-100 text-slate-700 rounded-xl border border-slate-200 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Historial de mis Cajas') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Registro histórico de todas tus aperturas, cierres y actas de arqueo</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if(Auth::user()->hasPermission('reportes.cajas'))
                    <a href="{{ route('reportes.cajas.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-xl text-xs font-bold transition-all shadow-sm">
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Auditoría Global
                    </a>
                @endif
                <a href="{{ route('cajas.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-200 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Ir a Caja de Turno
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Tarjetas Estadísticas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Sesiones Registradas</span>
                        <h4 class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_sesiones'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm flex items-center justify-between bg-emerald-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Total Recaudado</span>
                        <h4 class="text-2xl font-black text-emerald-700 mt-1">${{ number_format($stats['total_recaudado'], 2) }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex items-center justify-between bg-blue-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Cajas Cuadradas</span>
                        <h4 class="text-2xl font-black text-blue-700 mt-1">{{ $stats['cuadradas_count'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center justify-between bg-amber-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Cajas Abiertas</span>
                        <h4 class="text-2xl font-black text-amber-700 mt-1">{{ $stats['abiertas_count'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Barra de Búsqueda y Filtros -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                <form method="GET" action="{{ route('cajas.historial') }}" class="flex flex-wrap items-center gap-3">
                    
                    <!-- Input Búsqueda -->
                    <div class="relative flex-1 min-w-[220px]">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="buscar" value="{{ request('buscar') }}" 
                               placeholder="Buscar por # ID o nota..." 
                               class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-800 transition-all placeholder-slate-400">
                    </div>

                    <!-- Filtro Estado -->
                    <div class="min-w-[170px]">
                        <select name="estado" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="todos">⚡ Todos los estados</option>
                            <option value="abierta" {{ request('estado') === 'abierta' ? 'selected' : '' }}>🟢 En curso (Abiertas)</option>
                            <option value="cerrada" {{ request('estado') === 'cerrada' ? 'selected' : '' }}>🔒 Todas las Cerradas</option>
                            <option value="cuadrada" {{ request('estado') === 'cuadrada' ? 'selected' : '' }}>✅ Cerradas Cuadradas</option>
                            <option value="descuadre" {{ request('estado') === 'descuadre' ? 'selected' : '' }}>⚠️ Cerradas con Descuadre</option>
                        </select>
                    </div>

                    <!-- Fecha Desde -->
                    <div class="min-w-[145px] flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 focus-within:bg-white focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-100 transition-all">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 shrink-0">Desde</span>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" 
                               class="w-full border-0 p-0 text-xs bg-transparent focus:ring-0 font-medium text-slate-800">
                    </div>

                    <!-- Fecha Hasta -->
                    <div class="min-w-[145px] flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 focus-within:bg-white focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-100 transition-all">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 shrink-0">Hasta</span>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" 
                               class="w-full border-0 p-0 text-xs bg-transparent focus:ring-0 font-medium text-slate-800">
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-indigo-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span>Filtrar</span>
                        </button>
                        @if(request()->anyFilled(['buscar', 'estado', 'fecha_desde', 'fecha_hasta']))
                            <a href="{{ route('cajas.historial') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span>Limpiar</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabla de Cajas -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">Sesiones de Caja Registradas</h3>
                        <p class="text-xs text-slate-400">Total mostrando: {{ $cajas->total() }} turnos</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600 divide-y divide-slate-100">
                        <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold tracking-wider">
                            <tr>
                                <th class="py-3.5 px-6">ID Sesión</th>
                                <th class="py-3.5 px-6">Apertura</th>
                                <th class="py-3.5 px-6">Cierre</th>
                                <th class="py-3.5 px-6">Oficina</th>
                                <th class="py-3.5 px-6 text-right">Fondo Inicial ($)</th>
                                <th class="py-3.5 px-6 text-right">Total Recaudado ($)</th>
                                <th class="py-3.5 px-6 text-center">Estado / Cuadre</th>
                                <th class="py-3.5 px-6 text-center">Acta PDF</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($cajas as $caja)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6 font-mono text-xs font-bold text-slate-500">
                                        #{{ $caja->id }}
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap text-xs font-semibold text-slate-800">
                                        {{ $caja->fecha_apertura->format('d/m/Y h:i A') }}
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap text-xs text-slate-500">
                                        @if($caja->fecha_cierre)
                                            {{ $caja->fecha_cierre->format('d/m/Y h:i A') }}
                                        @else
                                            <span class="inline-flex items-center gap-1 text-emerald-600 font-bold">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                                En curso
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-xs font-medium text-slate-700">
                                        {{ $caja->oficina }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-semibold text-slate-600 whitespace-nowrap">
                                        ${{ number_format($caja->monto_apertura, 2) }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-slate-900 whitespace-nowrap">
                                        ${{ number_format($caja->total_sistema_total, 2) }}
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        @if($caja->estado === 'abierta')
                                            <span class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">
                                                Abierta
                                            </span>
                                        @elseif($caja->cuadrado)
                                            <span class="px-2.5 py-1 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-full">
                                                Cerrada (Cuadrada)
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 rounded-full">
                                                Cerrada (Descuadre)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <a href="{{ route('cajas.acta_pdf', $caja->id) }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-indigo-600 text-slate-700 hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            Acta PDF
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-sm font-semibold">No se encontraron sesiones de caja con los filtros seleccionados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($cajas->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $cajas->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
