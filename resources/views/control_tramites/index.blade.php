<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-amber-50 text-amber-600 rounded-2xl border border-amber-200 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none flex items-center gap-2">
                        {{ __('Control de Precios & Auditoría de Desviaciones') }}
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-200">
                            ±{{ number_format($umbralPorc, 1) }}%
                        </span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Detección y control de cobros con sobreprecios (> +{{ number_format($umbralPorc, 0) }}%) o tarifas con descuento (< -{{ number_format($umbralPorc, 0) }}%) respecto a la tarifa base (${{ number_format($precioBase, 2) }})
                    </p>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('cartera.index') }}" class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-sm transition-all">
                    &larr; Cartera & Cobros
                </a>
                <a href="{{ route('reportes.cartera.index') }}" class="px-3.5 py-2.5 bg-rose-50 border border-rose-200 rounded-xl text-xs font-bold text-rose-700 hover:bg-rose-100 shadow-sm transition-all">
                    📊 Reportería Cartera
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- 4 Tarjetas de Indicadores Clave de Auditoría -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- 1. Total Trámites con Desviación -->
            <div class="bg-white p-5 rounded-2xl border border-amber-200/90 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Trámites Fuera de Rango</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($totalConDesviacion) }}</h3>
                    <p class="text-xs font-bold text-amber-800 mt-1.5 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        De {{ number_format($totalAnalizados) }} trámites analizados
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- 2. Alertas de Exceso (> +5%) -->
            <div class="bg-white p-5 rounded-2xl border border-rose-200/90 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Excesos (> ${{ number_format($limiteSuperior, 2) }})</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-rose-600 mt-1">{{ number_format($totalExcesosCount) }}</h3>
                    <p class="text-xs text-rose-700 font-bold mt-1.5">
                        +${{ number_format($totalExcesosMonto, 2) }} sobreprecio total
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>

            <!-- 3. Alertas de Descuento (< -5%) -->
            <div class="bg-white p-5 rounded-2xl border border-blue-200/90 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Descuentos (< ${{ number_format($limiteInferior, 2) }})</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-blue-600 mt-1">{{ number_format($totalDescuentosCount) }}</h3>
                    <p class="text-xs text-blue-700 font-bold mt-1.5">
                        -${{ number_format($totalDescuentosMonto, 2) }} descuento total
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>

            <!-- 4. Promedio Facturado vs Tarifa Base -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Precio Promedio Facturado</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">${{ number_format($promedioFacturado, 2) }}</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1.5">
                        Base: ${{ number_format($precioBase, 2) }} &bull; {{ $totalRegularesCount }} estándar
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Barra de Configuración de Tarifa Base & Filtros de Auditoría -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-4">
            <form method="GET" action="{{ route('reportes.control_precios.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 items-end">
                
                <!-- Buscador por Cliente / ID -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Buscar Trámite o Cliente</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, cédula, Nº trámite..." class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-900 placeholder-slate-400">
                    </div>
                </div>

                <!-- Filtro Tipo de Desviación -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Filtro Desviación</label>
                    <select name="filtro_desviacion" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-bold text-slate-800 focus:bg-white focus:border-indigo-500">
                        <option value="con_desviacion" {{ request('filtro_desviacion', 'con_desviacion') === 'con_desviacion' ? 'selected' : '' }}>⚠️ Con Desviación (Exceso/Desc)</option>
                        <option value="exceso" {{ request('filtro_desviacion') === 'exceso' ? 'selected' : '' }}>🔴 Solo Excesos (> +{{ number_format($umbralPorc, 0) }}%)</option>
                        <option value="descuento" {{ request('filtro_desviacion') === 'descuento' ? 'selected' : '' }}>🔵 Solo Descuentos (< -{{ number_format($umbralPorc, 0) }}%)</option>
                        <option value="todos" {{ request('filtro_desviacion') === 'todos' ? 'selected' : '' }}>👥 Todos los Trámites</option>
                    </select>
                </div>

                <!-- Tipo de Trámite -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tipo de Trámite</label>
                    <select name="tipo_tramite" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-semibold text-slate-800 focus:bg-white focus:border-indigo-500">
                        <option value="todos" {{ request('tipo_tramite', 'todos') === 'todos' ? 'selected' : '' }}>Todos los Tipos</option>
                        <option value="poder" {{ request('tipo_tramite') === 'poder' ? 'selected' : '' }}>📜 Poderes</option>
                        <option value="divorcio" {{ request('tipo_tramite') === 'divorcio' ? 'selected' : '' }}>⚖️ Divorcios</option>
                        <option value="impuesto" {{ request('tipo_tramite') === 'impuesto' ? 'selected' : '' }}>📑 Impuestos (Taxes)</option>
                        <option value="vario" {{ request('tipo_tramite') === 'vario' ? 'selected' : '' }}>📂 Trámites Varios</option>
                        <option value="personalizado" {{ request('tipo_tramite') === 'personalizado' ? 'selected' : '' }}>✨ Personalizados</option>
                    </select>
                </div>

                <!-- Tarifa Base ($) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tarifa Base ($)</label>
                    <input type="number" step="10" min="1" name="precio_base" value="{{ request('precio_base', '200') }}" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-bold text-slate-900 focus:bg-white focus:border-indigo-500">
                </div>

                <!-- Umbral % -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Umbral Tolerancia (%)</label>
                    <select name="umbral_porc" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-bold text-slate-800 focus:bg-white focus:border-indigo-500">
                        <option value="5" {{ request('umbral_porc', '5') == '5' ? 'selected' : '' }}>± 5% (${{ number_format($precioBase*0.95,0) }} - ${{ number_format($precioBase*1.05,0) }})</option>
                        <option value="10" {{ request('umbral_porc') == '10' ? 'selected' : '' }}>± 10% (${{ number_format($precioBase*0.9,0) }} - ${{ number_format($precioBase*1.1,0) }})</option>
                        <option value="15" {{ request('umbral_porc') == '15' ? 'selected' : '' }}>± 15% (${{ number_format($precioBase*0.85,0) }} - ${{ number_format($precioBase*1.15,0) }})</option>
                        <option value="20" {{ request('umbral_porc') == '20' ? 'selected' : '' }}>± 20% (${{ number_format($precioBase*0.8,0) }} - ${{ number_format($precioBase*1.2,0) }})</option>
                    </select>
                </div>

                <!-- Usuario / Asesor -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Asesor / Usuario</label>
                    <select name="usuario_id" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-semibold text-slate-800 focus:bg-white focus:border-indigo-500">
                        <option value="">Todos los Asesores</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Oficina -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Oficina</label>
                    <select name="oficina" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-semibold text-slate-800 focus:bg-white focus:border-indigo-500">
                        <option value="">Todas las Oficinas</option>
                        @foreach($oficinas as $ofi)
                            <option value="{{ $ofi }}" {{ request('oficina') == $ofi ? 'selected' : '' }}>{{ $ofi }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Orden -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Ordenar Por</label>
                    <select name="orden" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-semibold text-slate-800 focus:bg-white focus:border-indigo-500">
                        <option value="mayor_desviacion" {{ request('orden', 'mayor_desviacion') === 'mayor_desviacion' ? 'selected' : '' }}>Mayor % Desviación</option>
                        <option value="recientes" {{ request('orden') === 'recientes' ? 'selected' : '' }}>Más Recientes</option>
                        <option value="mayor_precio" {{ request('orden') === 'mayor_precio' ? 'selected' : '' }}>Mayor Precio Cobrado</option>
                        <option value="menor_precio" {{ request('orden') === 'menor_precio' ? 'selected' : '' }}>Menor Precio Cobrado</option>
                    </select>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all h-[38px] flex items-center justify-center cursor-pointer">
                        Filtrar Auditoría
                    </button>
                    @if(request()->hasAny(['search', 'filtro_desviacion', 'tipo_tramite', 'precio_base', 'umbral_porc', 'usuario_id', 'oficina', 'orden']))
                        <a href="{{ route('reportes.control_precios.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all h-[38px] flex items-center justify-center" title="Limpiar Filtros">
                            ✕
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Tabla de Trámites Auditados -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        Listado de Trámites Auditados
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Haz clic en cualquier fila o en "Ver Detalle" para abrir la auditoría completa de fecha, hora, caja y usuario</p>
                </div>
                <span class="text-xs font-bold text-slate-700 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">
                    Mostrando {{ $tramites->count() }} de {{ $tramites->total() }} registros
                </span>
            </div>

            @if($tramites->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-800">
                        <thead class="bg-slate-50 uppercase text-[11px] font-bold text-slate-700 border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 px-4">Trámite / Folio</th>
                                <th class="py-3.5 px-4">Fecha & Hora</th>
                                <th class="py-3.5 px-4">Cliente / Contacto</th>
                                <th class="py-3.5 px-4">Asesor / Oficina</th>
                                <th class="py-3.5 px-4 text-right">Tarifa Base</th>
                                <th class="py-3.5 px-4 text-right">Valor Cobrado</th>
                                <th class="py-3.5 px-4 text-center">Desviación</th>
                                <th class="py-3.5 px-4 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($tramites as $t)
                                @php
                                    $jsonPayload = htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8');
                                @endphp
                                <tr class="hover:bg-amber-50/40 cursor-pointer transition-colors" onclick="abrirModalAuditoria({{ $jsonPayload }})">
                                    
                                    <!-- Trámite & Tipo -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-xl shrink-0">{{ $t['tipo_icono'] }}</span>
                                            <div>
                                                <span class="font-extrabold text-slate-900 block leading-tight text-xs">
                                                    {{ $t['tipo_nombre'] }}
                                                </span>
                                                <span class="text-[11px] font-bold text-indigo-600">
                                                    #{{ str_pad($t['id'], 5, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Fecha & Hora -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="space-y-0.5">
                                            <p class="font-bold text-slate-800">{{ $t['fecha_hora_formatted'] }}</p>
                                            <span class="text-[11px] text-slate-500 font-medium">{{ $t['hora'] }}</span>
                                        </div>
                                    </td>

                                    <!-- Cliente -->
                                    <td class="py-3.5 px-4">
                                        <p class="font-bold text-slate-900 text-xs truncate max-w-[180px]">{{ $t['cliente_nombre'] }}</p>
                                        <span class="text-[11px] text-slate-500 font-medium">ID: {{ $t['cliente_identificacion'] }}</span>
                                    </td>

                                    <!-- Asesor & Oficina -->
                                    <td class="py-3.5 px-4">
                                        <p class="font-bold text-slate-800 text-xs">{{ $t['usuario'] }}</p>
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 mt-0.5">
                                            🏢 {{ $t['oficina'] }}
                                        </span>
                                    </td>

                                    <!-- Tarifa Base -->
                                    <td class="py-3.5 px-4 text-right font-medium text-slate-500">
                                        ${{ number_format($t['precio_base'], 2) }}
                                    </td>

                                    <!-- Valor Cobrado Real -->
                                    <td class="py-3.5 px-4 text-right">
                                        <span class="text-sm font-black text-slate-900">
                                            ${{ number_format($t['costo'], 2) }}
                                        </span>
                                    </td>

                                    <!-- Desviación % y Badge -->
                                    <td class="py-3.5 px-4 text-center">
                                        @if($t['tipo_desviacion'] === 'exceso')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black bg-rose-50 text-rose-700 border border-rose-200 shadow-sm">
                                                <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                                +${{ number_format($t['diferencia'], 2) }} (+{{ number_format($t['desviacion_porc'], 1) }}%)
                                            </span>
                                        @elseif($t['tipo_desviacion'] === 'descuento')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-700 border border-blue-200 shadow-sm">
                                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                -${{ number_format(abs($t['diferencia']), 2) }} ({{ number_format($t['desviacion_porc'], 1) }}%)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                🟢 Tarifa Estándar
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Acción Botón -->
                                    <td class="py-3.5 px-4 text-center" onclick="event.stopPropagation()">
                                        <button type="button" onclick="abrirModalAuditoria({{ $jsonPayload }})" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-indigo-600 text-slate-700 hover:text-white text-xs font-bold transition-all shadow-sm">
                                            <span>🔍 Ver Detalle</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                    {{ $tramites->links() }}
                </div>
            @else
                <div class="text-center py-12 px-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800">No se encontraron trámites con los criterios seleccionados</h4>
                    <p class="text-xs text-slate-500 mt-1">Todos los trámites se encuentran dentro de los parámetros de tarifa o ajusta los filtros de búsqueda.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- MODAL INTERACTIVO DE AUDITORÍA DE TRÁMITE -->
    <div id="modalAuditoria" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalAuditoria()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                
                <!-- Modal Header -->
                <div class="bg-slate-900 px-6 py-5 text-white flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span id="modalIcono" class="text-2xl">📜</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold text-white" id="modalTipoNombre">Trámite de Poder</h3>
                                <span id="modalFolioBadge" class="px-2 py-0.5 rounded-full text-xs font-black bg-indigo-500 text-white">#00000</span>
                            </div>
                            <p class="text-xs text-slate-300 mt-0.5" id="modalSubtitulo">Auditoría detallada de tarifa, cobro y responsable</p>
                        </div>
                    </div>
                    <button type="button" onclick="cerrarModalAuditoria()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                        ✕
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    
                    <!-- Tarjeta Comparativa de Desviación -->
                    <div id="modalDesviacionCard" class="p-4 rounded-2xl border flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50 border-slate-200">
                        <div class="space-y-1">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Análisis de Tarifa</span>
                            <div class="flex items-baseline gap-3">
                                <span class="text-2xl font-black text-slate-900" id="modalValorCobrado">$0.00</span>
                                <span class="text-xs text-slate-500 line-through" id="modalValorBase">Base: $200.00</span>
                            </div>
                            <p class="text-xs font-bold" id="modalDesviacionTexto">0% Desviación</p>
                        </div>
                        <div id="modalDesviacionBadgeContainer">
                            <!-- Inyectado por JS -->
                        </div>
                    </div>

                    <!-- 4 Bloques de Auditoría en Grilla 2x2 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- 1. Auditoría de Registro & Asesor -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-indigo-700 flex items-center gap-1.5">
                                <span>👤</span> Registro & Emisión
                            </h4>
                            <div class="space-y-1 text-xs">
                                <p><strong class="text-slate-500">Asesor / Usuario:</strong> <span class="font-bold text-slate-900" id="modalAsesor">N/A</span></p>
                                <p><strong class="text-slate-500">Fecha de Emisión:</strong> <span class="font-bold text-slate-900" id="modalFecha">00/00/0000</span></p>
                                <p><strong class="text-slate-500">Hora de Registro:</strong> <span class="font-bold text-slate-900" id="modalHora">--:--</span></p>
                                <p><strong class="text-slate-500">Oficina / Sucursal:</strong> <span class="font-bold text-slate-900" id="modalOficina">General</span></p>
                            </div>
                        </div>

                        <!-- 2. Auditoría de Caja & Ventanilla -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-emerald-700 flex items-center gap-1.5">
                                <span>💵</span> Caja & Recaudación
                            </h4>
                            <div class="space-y-1 text-xs">
                                <p><strong class="text-slate-500">Turno de Caja:</strong> <span class="font-bold text-slate-900" id="modalCaja">Sin caja asociada / Cartera</span></p>
                                <p><strong class="text-slate-500">Cajero Receptor:</strong> <span class="font-bold text-slate-900" id="modalCajero">N/A</span></p>
                                <p><strong class="text-slate-500">Método de Pago:</strong> <span class="font-bold text-slate-900" id="modalMetodoPago">Efectivo</span></p>
                                <p><strong class="text-slate-500">Fecha de Cobro:</strong> <span class="font-bold text-slate-900" id="modalFechaPago">N/A</span></p>
                            </div>
                        </div>

                        <!-- 3. Información del Cliente -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                <span>👥</span> Cliente / Titular
                            </h4>
                            <div class="space-y-1 text-xs">
                                <p><strong class="text-slate-500">Nombre:</strong> <span class="font-bold text-slate-900" id="modalClienteNombre">N/A</span></p>
                                <p><strong class="text-slate-500">Cédula / ID:</strong> <span class="font-bold text-slate-900" id="modalClienteId">S/I</span></p>
                                <p><strong class="text-slate-500">Teléfono:</strong> <span class="font-bold text-slate-900" id="modalClienteTel">S/T</span></p>
                                <p><strong class="text-slate-500">Dirección:</strong> <span class="font-bold text-slate-900 truncate block" id="modalClienteDir">N/E</span></p>
                            </div>
                        </div>

                        <!-- 4. Liquidación Financiera -->
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-rose-700 flex items-center gap-1.5">
                                <span>📊</span> Estado de Pago
                            </h4>
                            <div class="space-y-1 text-xs">
                                <p><strong class="text-slate-500">Valor Trámite:</strong> <span class="font-bold text-slate-900" id="modalFinCosto">$0.00</span></p>
                                <p><strong class="text-slate-500">Abono Inicial:</strong> <span class="font-bold text-emerald-700" id="modalFinAbono">$0.00</span></p>
                                <p><strong class="text-slate-500">Saldo Pendiente:</strong> <span class="font-bold text-rose-700" id="modalFinSaldo">$0.00</span></p>
                            </div>
                        </div>

                    </div>

                    <!-- Observaciones Registradas -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-600">Observaciones / Motivo del Trámite</h4>
                        <p class="text-xs text-slate-800 leading-relaxed" id="modalObservaciones">Sin observaciones adicionales.</p>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3">
                    <a id="modalPdfLink" href="#" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Imprimir Comprobante PDF (1 Hoja)</span>
                    </a>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <a id="modalClienteTramitesLink" href="#" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold shadow-sm transition-all">
                            Ver Trámites Cliente
                        </a>
                        <button type="button" onclick="cerrarModalAuditoria()" class="w-full sm:w-auto px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-xl text-xs font-bold transition-all cursor-pointer">
                            Cerrar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script para control del Modal -->
    <script>
        function abrirModalAuditoria(tramite) {
            document.getElementById('modalIcono').innerText = tramite.tipo_icono || '📜';
            document.getElementById('modalTipoNombre').innerText = tramite.tipo_nombre || 'Trámite';
            document.getElementById('modalFolioBadge').innerText = '#' + String(tramite.id).padStart(5, '0');
            
            // Valores financieros
            document.getElementById('modalValorCobrado').innerText = '$' + Number(tramite.costo).toFixed(2);
            document.getElementById('modalValorBase').innerText = 'Tarifa Base: $' + Number(tramite.precio_base).toFixed(2);
            
            const card = document.getElementById('modalDesviacionCard');
            const badgeContainer = document.getElementById('modalDesviacionBadgeContainer');
            const textoDesviacion = document.getElementById('modalDesviacionTexto');

            if (tramite.tipo_desviacion === 'exceso') {
                card.className = 'p-4 rounded-2xl border flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-rose-50 border-rose-200';
                textoDesviacion.className = 'text-xs font-bold text-rose-700';
                textoDesviacion.innerText = '⚠️ Sobreprecio: +$' + Number(tramite.diferencia).toFixed(2) + ' (+' + Number(tramite.desviacion_porc).toFixed(1) + '% sobre la tarifa)';
                badgeContainer.innerHTML = '<span class="px-3.5 py-1.5 rounded-full text-xs font-black bg-rose-600 text-white shadow-sm">🔴 ALERTA EXCESO</span>';
            } else if (tramite.tipo_desviacion === 'descuento') {
                card.className = 'p-4 rounded-2xl border flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-blue-50 border-blue-200';
                textoDesviacion.className = 'text-xs font-bold text-blue-700';
                textoDesviacion.innerText = '🏷️ Descuento: -$' + Math.abs(Number(tramite.diferencia)).toFixed(2) + ' (' + Number(tramite.desviacion_porc).toFixed(1) + '% respecto a la tarifa)';
                badgeContainer.innerHTML = '<span class="px-3.5 py-1.5 rounded-full text-xs font-black bg-blue-600 text-white shadow-sm">🔵 TARIFA DESCUENTO</span>';
            } else {
                card.className = 'p-4 rounded-2xl border flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-emerald-50 border-emerald-200';
                textoDesviacion.className = 'text-xs font-bold text-emerald-700';
                textoDesviacion.innerText = '🟢 Tarifa Estándar dentro del rango de tolerancia';
                badgeContainer.innerHTML = '<span class="px-3.5 py-1.5 rounded-full text-xs font-black bg-emerald-600 text-white shadow-sm">🟢 PRECIO NORMAL</span>';
            }

            // Datos de Registro
            document.getElementById('modalAsesor').innerText = tramite.usuario || 'N/A';
            document.getElementById('modalFecha').innerText = tramite.fecha_hora_formatted || tramite.fecha || 'N/A';
            document.getElementById('modalHora').innerText = tramite.hora || '09:00 AM';
            document.getElementById('modalOficina').innerText = tramite.oficina || 'General';

            // Datos de Caja
            if (tramite.caja_id) {
                document.getElementById('modalCaja').innerText = 'Caja Turno #' + tramite.caja_id + ' (' + (tramite.caja_estado || 'Cerrada') + ')';
            } else {
                document.getElementById('modalCaja').innerText = 'Pendiente en Cartera / Cobro Diferido';
            }
            document.getElementById('modalCajero').innerText = tramite.cajero || 'N/A';
            document.getElementById('modalMetodoPago').innerText = tramite.metodo_pago || 'Efectivo / Cartera';
            document.getElementById('modalFechaPago').innerText = tramite.fecha_pago || 'No registrada';

            // Cliente
            document.getElementById('modalClienteNombre').innerText = tramite.cliente_nombre || 'N/A';
            document.getElementById('modalClienteId').innerText = tramite.cliente_identificacion || 'S/I';
            document.getElementById('modalClienteTel').innerText = tramite.cliente_telefono || 'S/T';
            document.getElementById('modalClienteDir').innerText = tramite.cliente_direccion || 'No especificada';

            // Finanzas
            document.getElementById('modalFinCosto').innerText = '$' + Number(tramite.costo).toFixed(2);
            document.getElementById('modalFinAbono').innerText = '$' + Number(tramite.abono).toFixed(2);
            document.getElementById('modalFinSaldo').innerText = '$' + Number(tramite.saldo).toFixed(2);

            // Observaciones
            document.getElementById('modalObservaciones').innerText = tramite.observaciones || 'Sin observaciones registradas.';

            // Links
            document.getElementById('modalPdfLink').href = tramite.route_pdf || '#';
            document.getElementById('modalClienteTramitesLink').href = '/clientes/' + tramite.cliente_id + '/tramites';

            // Mostrar modal
            document.getElementById('modalAuditoria').classList.remove('hidden');
        }

        function cerrarModalAuditoria() {
            document.getElementById('modalAuditoria').classList.add('hidden');
        }

        // Cerrar con Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarModalAuditoria();
            }
        });
    </script>
</x-app-layout>
