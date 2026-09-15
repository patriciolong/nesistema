<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-rose-50 text-rose-600 rounded-2xl border border-rose-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Reportería Ejecutiva de Cartera & Créditos') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Auditoría de cuentas por cobrar, análisis de morosidad (Aging) y recuperación de cartera</p>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('reportes.control_precios.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-amber-50 text-amber-800 hover:bg-amber-600 hover:text-white border border-amber-200 rounded-xl text-xs font-bold shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Control Precios (±5%)</span>
                </a>
                <a href="{{ route('reportes.cartera.excel', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Exportar Excel (.xlsx)</span>
                </a>
                <a href="{{ route('reportes.cartera.pdf', request()->all()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Imprimir Reporte PDF</span>
                </a>
                <a href="{{ route('cartera.index') }}" class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-sm transition-all">
                    &larr; Ir a Cobros
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        
        <!-- 4 Tarjetas de Métricas Ejecutivas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- 1. Total Saldo en Cartera -->
            <div class="bg-white p-5 rounded-2xl border border-rose-200/90 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Saldo Total por Cobrar</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">${{ number_format($totalCarteraPorCobrar, 2) }}</h3>
                    <p class="text-xs font-bold text-rose-700 mt-1.5 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        {{ number_format($clientesDeudoresCount) }} deudores activos
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
            </div>

            <!-- 2. Total Recaudado / Cobrado -->
            <div class="bg-white p-5 rounded-2xl border border-emerald-200/90 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Total Recaudado en Caja</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1">${{ number_format($totalAbonado, 2) }}</h3>
                    <p class="text-xs text-emerald-800 font-semibold mt-1.5">Abonos e ingresos reales</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- 3. Total Facturado Histórico -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Facturado Acumulado</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">${{ number_format($totalDeudaHistorica, 2) }}</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1.5">Volumen global de trámites</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                    </svg>
                </div>
            </div>

            <!-- 4. Tasa de Recuperación / Efectividad -->
            <div class="bg-white p-5 rounded-2xl border border-indigo-200/90 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-700">Tasa de Recuperación</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-indigo-600 mt-1">{{ number_format($tasaRecuperacion, 1) }}%</h3>
                    <p class="text-xs text-indigo-800 font-semibold mt-1.5">{{ number_format($clientesAlDiaCount) }} clientes al día ($0)</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Sección: Antigüedad de la Deuda (Aging Analysis) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        Análisis de Antigüedad de Cartera (Aging de Deudas)
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Distribución cronológica de saldos según el tiempo de emisión de los trámites no cancelados</p>
                </div>
                <span class="text-xs font-extrabold text-slate-700 bg-slate-100 px-3 py-1 rounded-full">
                    Total Analizado: ${{ number_format($aging['total'], 2) }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- 0 - 30 Días (Corriente) -->
                <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/40 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-extrabold text-emerald-900">0 - 30 Días (Corriente)</span>
                        <span class="font-bold text-emerald-700">{{ number_format($aging['0_30']['porcentaje'], 1) }}%</span>
                    </div>
                    <p class="text-xl font-black text-emerald-800">${{ number_format($aging['0_30']['monto'], 2) }}</p>
                    <div class="w-full bg-emerald-200/70 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-600 h-full rounded-full" style="width: {{ min(100, $aging['0_30']['porcentaje']) }}%"></div>
                    </div>
                    <p class="text-[11px] text-emerald-700 font-medium">Deuda reciente dentro del ciclo</p>
                </div>

                <!-- 31 - 60 Días -->
                <div class="p-4 rounded-xl border border-blue-200 bg-blue-50/40 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-extrabold text-blue-900">31 - 60 Días</span>
                        <span class="font-bold text-blue-700">{{ number_format($aging['31_60']['porcentaje'], 1) }}%</span>
                    </div>
                    <p class="text-xl font-black text-blue-800">${{ number_format($aging['31_60']['monto'], 2) }}</p>
                    <div class="w-full bg-blue-200/70 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full" style="width: {{ min(100, $aging['31_60']['porcentaje']) }}%"></div>
                    </div>
                    <p class="text-[11px] text-blue-700 font-medium">Requiere recordatorio de cobro</p>
                </div>

                <!-- 61 - 90 Días -->
                <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/40 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-extrabold text-amber-900">61 - 90 Días</span>
                        <span class="font-bold text-amber-700">{{ number_format($aging['61_90']['porcentaje'], 1) }}%</span>
                    </div>
                    <p class="text-xl font-black text-amber-800">${{ number_format($aging['61_90']['monto'], 2) }}</p>
                    <div class="w-full bg-amber-200/70 h-2 rounded-full overflow-hidden">
                        <div class="bg-amber-600 h-full rounded-full" style="width: {{ min(100, $aging['61_90']['porcentaje']) }}%"></div>
                    </div>
                    <p class="text-[11px] text-amber-700 font-medium">Gestión activa de cobranza</p>
                </div>

                <!-- +90 Días (Cartera Vencida) -->
                <div class="p-4 rounded-xl border border-rose-200 bg-rose-50/40 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-extrabold text-rose-900">+90 Días (Vencida)</span>
                        <span class="font-bold text-rose-700">{{ number_format($aging['mas_90']['porcentaje'], 1) }}%</span>
                    </div>
                    <p class="text-xl font-black text-rose-800">${{ number_format($aging['mas_90']['monto'], 2) }}</p>
                    <div class="w-full bg-rose-200/70 h-2 rounded-full overflow-hidden">
                        <div class="bg-rose-600 h-full rounded-full" style="width: {{ min(100, $aging['mas_90']['porcentaje']) }}%"></div>
                    </div>
                    <p class="text-[11px] text-rose-700 font-medium">Alerta: Cartera morosa</p>
                </div>

            </div>
        </div>

        <!-- 2 Bloques: Deuda por Tipo de Trámite & Deuda por Oficina -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Bloque 1: Desglose por Tipo de Trámite -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    Saldos Pendientes por Tipo de Trámite
                </h3>
                
                <div class="space-y-3">
                    @foreach($desgloseTramites as $nombre => $item)
                        @php
                            $porc = $totalCarteraPorCobrar > 0 ? ($item['saldo'] / $totalCarteraPorCobrar) * 100 : 0;
                        @endphp
                        <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="text-xl">{{ $item['icon'] }}</span>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-800 truncate">{{ $nombre }}</h4>
                                    <div class="w-32 sm:w-44 bg-slate-200 h-1.5 rounded-full mt-1 overflow-hidden">
                                        <div class="bg-indigo-600 h-full rounded-full" style="width: {{ min(100, $porc) }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm font-black text-slate-900">${{ number_format($item['saldo'], 2) }}</span>
                                <span class="text-[11px] text-slate-500 font-semibold block">{{ number_format($porc, 1) }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bloque 2: Desglose por Sucursal / Oficina -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    Distribución de Cartera por Oficina de Registro
                </h3>

                @if($desgloseOficinas->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-700 font-bold uppercase text-[11px] border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3">Oficina</th>
                                    <th class="py-2.5 px-3 text-center">Deudores</th>
                                    <th class="py-2.5 px-3 text-right">Saldo por Cobrar</th>
                                    <th class="py-2.5 px-3 text-right">% Cartera</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($desgloseOficinas as $ofi)
                                    @php
                                        $porcOfi = $totalCarteraPorCobrar > 0 ? ($ofi->total_saldo / $totalCarteraPorCobrar) * 100 : 0;
                                    @endphp
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="py-2.5 px-3 font-bold text-slate-800">
                                            🏢 {{ $ofi->c_oficina_registro ?: 'Oficina General' }}
                                        </td>
                                        <td class="py-2.5 px-3 text-center font-bold text-slate-700">
                                            {{ $ofi->total_deudores }}
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-black text-rose-700">
                                            ${{ number_format($ofi->total_saldo, 2) }}
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-bold text-slate-600">
                                            {{ number_format($porcOfi, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-xs text-slate-500 py-6 text-center">No hay registros de oficinas con saldo pendiente.</p>
                @endif
            </div>

        </div>

        <!-- Barra de Búsqueda y Filtros de la Tabla -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-4">
            <form method="GET" action="{{ route('reportes.cartera.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                
                <!-- Buscador -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Buscar Cliente</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, cédula, teléfono..." class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-900 placeholder-slate-400">
                    </div>
                </div>

                <!-- Filtro Estado -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Estado</label>
                    <select name="filtro_estado" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-bold text-slate-800 focus:bg-white focus:border-indigo-500">
                        <option value="deudores" {{ request('filtro_estado', 'deudores') === 'deudores' ? 'selected' : '' }}>🔴 Solo Deudores</option>
                        <option value="al_dia" {{ request('filtro_estado') === 'al_dia' ? 'selected' : '' }}>🟢 Al Día ($0.00)</option>
                        <option value="todos" {{ request('filtro_estado') === 'todos' ? 'selected' : '' }}>👥 Todos</option>
                    </select>
                </div>

                <!-- Filtro Oficina -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Oficina</label>
                    <select name="oficina" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-semibold text-slate-800 focus:bg-white focus:border-indigo-500">
                        <option value="">🏢 Todas</option>
                        @foreach($oficinas as $ofi)
                            <option value="{{ $ofi }}" {{ request('oficina') == $ofi ? 'selected' : '' }}>{{ $ofi }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Orden -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Ordenar Por</label>
                    <select name="orden" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-semibold text-slate-800 focus:bg-white focus:border-indigo-500">
                        <option value="mayor_saldo" {{ request('orden', 'mayor_saldo') === 'mayor_saldo' ? 'selected' : '' }}>Mayor Saldo</option>
                        <option value="menor_saldo" {{ request('orden') === 'menor_saldo' ? 'selected' : '' }}>Menor Saldo</option>
                        <option value="mayor_facturado" {{ request('orden') === 'mayor_facturado' ? 'selected' : '' }}>Mayor Facturado</option>
                        <option value="nombre" {{ request('orden') === 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all h-[38px] flex items-center justify-center cursor-pointer">
                        Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'oficina', 'filtro_estado', 'orden']))
                        <a href="{{ route('reportes.cartera.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all h-[38px] flex items-center justify-center" title="Limpiar">
                            ✕
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Tabla de Clientes y Detalle de Cartera -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    Listado Detallado de Clientes en Cartera
                </h3>
                <span class="text-xs font-bold text-slate-600 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">
                    Mostrando {{ $clientes->count() }} de {{ $clientes->total() }} registros
                </span>
            </div>

            @if($clientes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-800">
                        <thead class="bg-slate-50 uppercase text-xs font-bold text-slate-700 border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 px-6">Cliente</th>
                                <th class="py-3.5 px-6">Contacto / Oficina</th>
                                <th class="py-3.5 px-6 text-right">Total Facturado</th>
                                <th class="py-3.5 px-6 text-right">Total Cobrado</th>
                                <th class="py-3.5 px-6 text-right">Saldo Pendiente</th>
                                <th class="py-3.5 px-6 text-center">% Cobro</th>
                                <th class="py-3.5 px-6 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($clientes as $c)
                                @php
                                    $porcCobro = $c->c_deuda > 0 ? ($c->c_abonado / $c->c_deuda) * 100 : 100;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6">
                                        <a href="{{ route('clientes.tramites', $c->id_cliente) }}" class="font-bold text-slate-900 hover:text-indigo-600 text-sm transition-colors block">
                                            {{ $c->c_nombre }} {{ $c->c_apellido }}
                                        </a>
                                        <span class="text-xs text-slate-500 font-medium">ID: {{ $c->c_identificacion ?: 'Sin C.I.' }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="space-y-0.5">
                                            <p class="font-semibold text-slate-800">{{ $c->c_telefono ?: 'Sin teléfono' }}</p>
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700">
                                                {{ $c->c_oficina_registro ?: 'Oficina General' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-slate-900 text-sm">
                                        ${{ number_format($c->c_deuda, 2) }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-emerald-700 text-sm">
                                        ${{ number_format($c->c_abonado, 2) }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        @if($c->c_saldo > 0)
                                            <span class="inline-block px-3 py-1 rounded-xl text-sm font-black bg-rose-50 text-rose-700 border border-rose-200">
                                                ${{ number_format($c->c_saldo, 2) }}
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-600">
                                                $0.00
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="inline-flex items-center gap-1 text-xs font-bold {{ $porcCobro >= 100 ? 'text-emerald-700' : ($porcCobro >= 50 ? 'text-amber-700' : 'text-rose-700') }}">
                                            <span>{{ number_format($porcCobro, 0) }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('clientes.tramites', $c->id_cliente) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-bold transition-all border border-indigo-200">
                                            <span>Ver Trámites</span> &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                    {{ $clientes->links() }}
                </div>
            @else
                <div class="text-center py-12 px-4">
                    <p class="text-sm font-bold text-slate-700">No se encontraron clientes bajo los filtros seleccionados.</p>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
