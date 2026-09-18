<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-rose-50 text-rose-600 rounded-xl border border-rose-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Desempeño y Productividad por Cajero') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Análisis gerencial de recaudación, volumen de transacciones y efectividad de cuadre</p>
                </div>
            </div>

            <a href="{{ route('reportes.cajas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-sm transition-all">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Reporte de Cajas
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Barra de Filtros por Rango de Fechas -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <form method="GET" action="{{ route('reportes.cajas.desempeno') }}" class="flex flex-col sm:flex-row items-end gap-4">
                    <div class="flex-1">
                        <label for="fecha_desde" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Fecha Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" value="{{ $fechaDesde }}"
                               class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 font-medium text-slate-800">
                    </div>

                    <div class="flex-1">
                        <label for="fecha_hasta" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Fecha Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ $fechaHasta }}"
                               class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 font-medium text-slate-800">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-200 transition-all">
                            Actualizar Análisis
                        </button>
                    </div>
                </form>
            </div>

            <!-- Grid de Tarjetas de Rendimiento de Cajeros -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($cajeros as $cajero)
                    @php
                        $totalRecaudado = (float) $cajero->total_recaudado;
                        $sesiones = $cajero->caja_sesiones_count;
                        $cuadradas = $cajero->cajas_cuadradas_count;
                        $tasaCuadre = $sesiones > 0 ? round(($cuadradas / $sesiones) * 100) : 0;
                        $transacciones = $movimientosTotales[$cajero->id] ?? 0;
                        $promedioSesion = $sesiones > 0 ? round($totalRecaudado / $sesiones, 2) : 0;
                    @endphp

                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition-all">
                        <!-- Cabecera del Cajero -->
                        <div class="p-6 pb-4 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-slate-900 to-indigo-950 text-white flex items-center justify-center font-black text-base shadow-md">
                                        {{ strtoupper(substr($cajero->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-base text-slate-800">{{ $cajero->name }}</h4>
                                        <p class="text-xs text-slate-400">{{ $cajero->role }} • <span class="font-semibold text-slate-600">{{ $cajero->office ?? 'General' }}</span></p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-bold {{ $cajero->status === 'Activo' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }} rounded-full">
                                    {{ $cajero->status }}
                                </span>
                            </div>
                        </div>

                        <!-- Métricas Clave -->
                        <div class="p-6 space-y-4">
                            <!-- Recaudación Total -->
                            <div class="flex justify-between items-center p-3.5 bg-indigo-50/40 border border-indigo-100 rounded-2xl">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-700">Total Recaudado</span>
                                    <h3 class="text-2xl font-black text-slate-900">${{ number_format($totalRecaudado, 2) }}</h3>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-slate-500 block">Promedio/Turno</span>
                                    <span class="text-xs font-bold text-indigo-600">${{ number_format($promedioSesion, 2) }}</span>
                                </div>
                            </div>

                            <!-- Barra de Efectividad de Cuadre -->
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-600">Tasa de Cajas Cuadradas:</span>
                                    <span class="font-black {{ $tasaCuadre == 100 ? 'text-emerald-600' : ($tasaCuadre >= 80 ? 'text-amber-600' : 'text-rose-600') }}">
                                        {{ $tasaCuadre }}% ({{ $cuadradas }}/{{ $sesiones }})
                                    </span>
                                </div>
                                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all {{ $tasaCuadre == 100 ? 'bg-emerald-500' : ($tasaCuadre >= 80 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                         style="width: {{ $tasaCuadre }}%"></div>
                                </div>
                            </div>

                            <!-- Desglose por Método -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-xs">
                                <div class="p-2.5 bg-slate-50 rounded-xl">
                                    <span class="text-slate-400 block text-[10px] font-bold uppercase">Efectivo</span>
                                    <span class="font-black text-emerald-600 text-sm">${{ number_format((float)$cajero->total_efectivo, 2) }}</span>
                                </div>
                                <div class="p-2.5 bg-slate-50 rounded-xl">
                                    <span class="text-slate-400 block text-[10px] font-bold uppercase">Tarjetas / POS</span>
                                    <span class="font-black text-pink-600 text-sm">${{ number_format((float)$cajero->total_tarjeta, 2) }}</span>
                                </div>
                                <div class="p-2.5 bg-slate-50 rounded-xl">
                                    <span class="text-slate-400 block text-[10px] font-bold uppercase">Transferencias</span>
                                    <span class="font-black text-blue-600 text-sm">${{ number_format((float)$cajero->total_transferencia, 2) }}</span>
                                </div>
                                <div class="p-2.5 bg-slate-50 rounded-xl">
                                    <span class="text-slate-400 block text-[10px] font-bold uppercase">Zelle</span>
                                    <span class="font-black text-violet-600 text-sm">${{ number_format((float)$cajero->total_zelle, 2) }}</span>
                                </div>
                                <div class="p-2.5 bg-slate-50 rounded-xl col-span-2 sm:col-span-1">
                                    <span class="text-slate-400 block text-[10px] font-bold uppercase">Cheques</span>
                                    <span class="font-black text-purple-600 text-sm">${{ number_format((float)$cajero->total_cheque, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer de la Tarjeta -->
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-bold">
                                <strong>{{ $transacciones }}</strong> transacciones
                            </span>
                            <a href="{{ route('reportes.cajas.index', ['user_id' => $cajero->id, 'fecha_desde' => $fechaDesde, 'fecha_hasta' => $fechaHasta]) }}" 
                               class="font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                Ver Cajas
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-slate-400">
                        No hay datos de cajeros en el rango de fechas seleccionado.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
