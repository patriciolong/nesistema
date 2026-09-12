<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-sky-50 text-sky-600 rounded-xl border border-sky-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Reporte Global y Auditoría de Cajas') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Supervisión administrativa de turnos, arqueos, recaudación y cuadres</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('reportes.cajas.desempeno') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-all shadow-sm">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Métricas de Desempeño
                </a>
                <a href="{{ route('reportes.cajas.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-200 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar a Excel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Tarjetas de Métricas Globales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Recaudado</span>
                        <h4 class="text-2xl font-black text-slate-900 mt-1">${{ number_format($stats['total_recaudado'], 2) }}</h4>
                    </div>
                    <span class="text-xs text-slate-400 mt-2 font-medium">En el período filtrado</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm flex flex-col justify-between bg-emerald-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Total Efectivo</span>
                        <h4 class="text-2xl font-black text-emerald-600 mt-1">${{ number_format($stats['total_efectivo'], 2) }}</h4>
                    </div>
                    <span class="text-xs text-emerald-600 font-medium">Recaudación física</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-pink-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-pink-600">Total Tarjetas</span>
                        <h4 class="text-2xl font-black text-pink-600 mt-1">${{ number_format($stats['total_tarjeta'], 2) }}</h4>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">POS y datáfonos</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Transferencias</span>
                        <h4 class="text-2xl font-black text-blue-600 mt-1">${{ number_format($stats['total_transferencia'], 2) }}</h4>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">Bancos / depósitos</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-purple-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-600">Total Cheques</span>
                        <h4 class="text-2xl font-black text-purple-600 mt-1">${{ number_format($stats['total_cheque'], 2) }}</h4>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">Cheques cobrados</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Sesiones</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-black text-emerald-600">{{ $stats['cajas_abiertas'] }} Abiertas</span>
                            <span class="text-xs text-slate-300">/</span>
                            <span class="text-sm font-black text-slate-700">{{ $stats['cajas_cerradas'] }} Cerradas</span>
                        </div>
                    </div>
                    <span class="text-xs text-slate-400 mt-2 font-medium">Total de turnos</span>
                </div>
            </div>

            <!-- Barra de Búsqueda y Filtros -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                <form method="GET" action="{{ route('reportes.cajas.index') }}" class="flex flex-wrap items-center gap-3">
                    
                    <!-- Fecha Desde -->
                    <div class="min-w-[155px] flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 focus-within:bg-white focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-100 transition-all">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 shrink-0">Desde</span>
                        <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}"
                               class="w-full border-0 p-0 text-xs bg-transparent focus:ring-0 font-medium text-slate-800">
                    </div>

                    <!-- Fecha Hasta -->
                    <div class="min-w-[155px] flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 focus-within:bg-white focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-100 transition-all">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 shrink-0">Hasta</span>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}"
                               class="w-full border-0 p-0 text-xs bg-transparent focus:ring-0 font-medium text-slate-800">
                    </div>

                    <!-- Cajero / Usuario -->
                    <div class="min-w-[180px]">
                        <select name="user_id" id="user_id" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="">👤 Todos los cajeros</option>
                            @foreach($usuarios as $usr)
                                <option value="{{ $usr->id }}" {{ request('user_id') == $usr->id ? 'selected' : '' }}>
                                    {{ $usr->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Oficina -->
                    <div class="min-w-[170px]">
                        <select name="oficina" id="oficina" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="">🏢 Todas las oficinas</option>
                            @foreach($oficinas as $ofi)
                                <option value="{{ $ofi->nombre }}" {{ request('oficina') == $ofi->nombre ? 'selected' : '' }}>
                                    {{ $ofi->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado de Cuadre -->
                    <div class="min-w-[170px]">
                        <select name="cuadre" id="cuadre" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="">⚡ Todos los cuadres</option>
                            <option value="cuadrado" {{ request('cuadre') == 'cuadrado' ? 'selected' : '' }}>✅ Solo Cuadradas</option>
                            <option value="descuadrado" {{ request('cuadre') == 'descuadrado' ? 'selected' : '' }}>⚠️ Con Descuadre</option>
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-indigo-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span>Filtrar</span>
                        </button>
                        @if(request()->anyFilled(['fecha_desde', 'fecha_hasta', 'user_id', 'oficina', 'cuadre']))
                            <a href="{{ route('reportes.cajas.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span>Limpiar</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabla de Reportes de Caja -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 pb-4 border-b border-slate-100">
                    <h3 class="text-base font-extrabold text-slate-800">Auditoría de Cajas Registradas</h3>
                    <p class="text-xs text-slate-400">Detalle de cada turno con importes del sistema, montos de cierre y resultado de cuadre</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600 divide-y divide-slate-100">
                        <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold tracking-wider">
                            <tr>
                                <th class="py-3.5 px-6">ID</th>
                                <th class="py-3.5 px-6">Cajero / Asesor</th>
                                <th class="py-3.5 px-6">Oficina</th>
                                <th class="py-3.5 px-6">Apertura / Cierre</th>
                                <th class="py-3.5 px-6 text-right">Fondo Inicial ($)</th>
                                <th class="py-3.5 px-6 text-right">Total Sistema ($)</th>
                                <th class="py-3.5 px-6 text-right">Declarado ($)</th>
                                <th class="py-3.5 px-6 text-center">Cuadre</th>
                                <th class="py-3.5 px-6 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($cajas as $caja)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6 font-mono text-xs font-bold text-slate-400">#{{ $caja->id }}</td>
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-800 text-sm">{{ $caja->user->name ?? 'Usuario Eliminado' }}</div>
                                        <span class="text-xs text-slate-400">{{ $caja->user->email ?? '' }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-xs font-semibold text-slate-700">
                                        {{ $caja->oficina ?? 'General' }}
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap text-xs">
                                        <p class="font-medium text-slate-800">Inició: {{ $caja->fecha_apertura->format('d/m/Y h:i A') }}</p>
                                        <p class="text-slate-400">
                                            @if($caja->fecha_cierre)
                                                Cerró: {{ $caja->fecha_cierre->format('d/m/Y h:i A') }}
                                            @else
                                                <span class="text-emerald-600 font-bold">En curso (Abierta)</span>
                                            @endif
                                        </p>
                                    </td>
                                    <td class="py-4 px-6 text-right font-semibold text-slate-600 whitespace-nowrap">
                                        ${{ number_format($caja->monto_apertura, 2) }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-slate-900 whitespace-nowrap">
                                        ${{ number_format($caja->total_sistema_total, 2) }}
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap font-black">
                                        @if($caja->monto_cierre_total !== null)
                                            <span class="text-indigo-600">${{ number_format($caja->monto_cierre_total, 2) }}</span>
                                        @else
                                            <span class="text-slate-400 italic">En proceso</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        @if($caja->estado === 'abierta')
                                            <span class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">
                                                Abierta
                                            </span>
                                        @elseif($caja->cuadrado)
                                            <span class="px-2.5 py-1 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-full">
                                                Cuadrada
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 rounded-full" title="Diferencia: ${{ number_format($caja->diferencia_total, 2) }}">
                                                Descuadre (${{ number_format($caja->diferencia_total, 2) }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('reportes.cajas.show', $caja->id) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Ver Auditoría Detallada">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('cajas.acta_pdf', $caja->id) }}" target="_blank" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Imprimir Acta PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            <p class="text-sm font-semibold">No se encontraron cajas con los filtros especificados.</p>
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
