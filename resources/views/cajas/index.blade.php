<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Módulo Operativo de Caja') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Control de aperturas, movimientos en tiempo real, arqueos y cierres de turno</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('cajas.historial') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-sm transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mis Cajas Anteriores
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alertas Flash -->
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between gap-3 text-emerald-800 text-sm font-semibold shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    @if(session('imprimir_cierre'))
                        <a href="{{ session('imprimir_cierre') }}" target="_blank" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                            Imprimir Acta de Cierre PDF
                        </a>
                    @endif
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-800 text-sm font-semibold shadow-sm">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(!$cajaActual)
                <!-- ================= ESTADO 1: CAJA CERRADA / PANTALLA DE APERTURA ================= -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden">
                        <!-- Banner Superior -->
                        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-8 text-white text-center relative overflow-hidden">
                            <div class="w-16 h-16 bg-emerald-500/20 text-emerald-400 rounded-2xl border border-emerald-500/30 flex items-center justify-center mx-auto mb-3 shadow-inner">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black tracking-tight text-white">Apertura de Caja de Turno</h3>
                            <p class="text-xs text-slate-300 max-w-md mx-auto mt-1">
                                No tienes una caja abierta actualmente. Para poder realizar cobros, recibir abonos y emitir trámites debes abrir tu caja de turno.
                            </p>
                        </div>

                        <!-- Formulario de Apertura -->
                        <form action="{{ route('cajas.abrir') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                            @csrf

                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-2">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-500 font-bold uppercase">Cajero / Asesor:</span>
                                    <span class="font-extrabold text-slate-800">{{ Auth::user()->name }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-500 font-bold uppercase">Oficina de Registro:</span>
                                    <span class="font-extrabold text-indigo-600">{{ Auth::user()->office ?? 'Oficina Principal' }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-500 font-bold uppercase">Fecha y Hora de Apertura:</span>
                                    <span class="font-bold text-slate-700">{{ now()->format('d/m/Y h:i A') }}</span>
                                </div>
                            </div>

                            <div>
                                <label for="monto_apertura" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Monto Inicial en Efectivo (Fondo de Caja) *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-black text-lg">
                                        $
                                    </div>
                                    <input type="number" step="0.01" min="0" name="monto_apertura" id="monto_apertura" value="0.00" required
                                           class="w-full pl-9 pr-4 py-3.5 text-xl font-black bg-white border border-slate-300 rounded-2xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-slate-800 transition-all">
                                </div>
                                <p class="text-xs text-slate-400 mt-1">
                                    * Puedes abrir con $0.00 o con el efectivo inicial destinado para cambio.
                                </p>
                            </div>

                            <div>
                                <label for="observaciones" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Observaciones de Apertura (Opcional)
                                </label>
                                <textarea name="observaciones" id="observaciones" rows="2" placeholder="Notas o novedades al iniciar turno..."
                                          class="w-full text-sm bg-white border border-slate-300 rounded-2xl p-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all font-medium text-slate-800"></textarea>
                            </div>

                            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-2xl text-sm font-black tracking-wide shadow-lg shadow-emerald-200 hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Iniciar Turno y Abrir Caja
                            </button>
                        </form>
                    </div>
                </div>

            @else
                <!-- ================= ESTADO 2: CAJA ABIERTA (PANEL EN VIVO) ================= -->
                
                <!-- Encabezado de Sesión Activa -->
                <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border border-slate-800">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center shrink-0 shadow-inner">
                            <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-500 text-white tracking-wide uppercase">
                                    Caja Abierta #{{ $cajaActual->id }}
                                </span>
                                <span class="text-xs text-slate-300 font-medium">Oficina: <strong class="text-white">{{ $cajaActual->oficina }}</strong></span>
                            </div>
                            <h3 class="text-2xl font-black tracking-tight text-white mt-1">Cajero: {{ $cajaActual->user->name }}</h3>
                            <p class="text-xs text-slate-400">
                                Abierta el {{ $cajaActual->fecha_apertura->format('d/m/Y \a \l\a\s h:i A') }} • Fondo Inicial: <strong class="text-emerald-400">${{ number_format($cajaActual->monto_apertura, 2) }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <button onclick="openModalMovimientoManual()" type="button" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-2xl text-xs font-bold transition-all">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Movimiento Extra / Gasto
                        </button>

                        <a href="{{ route('cajas.cerrar') }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-2xl text-xs font-black tracking-wide shadow-lg shadow-rose-900/30 hover:shadow-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Cerrar y Cuadrar Caja
                        </a>
                    </div>
                </div>

                <!-- Tarjetas Métricas en Tiempo Real -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- Total General -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total en Caja (Global)</span>
                            <h4 class="text-2xl font-black text-slate-900 mt-1">${{ number_format($totales['total_general'], 2) }}</h4>
                        </div>
                        <span class="text-xs text-slate-500 mt-2 font-medium">Efectivo + Tarjetas + Zelle + Transf.</span>
                    </div>

                    <!-- Efectivo en Mano -->
                    <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm flex flex-col justify-between bg-emerald-50/20">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Efectivo en Mano</span>
                            <h4 class="text-2xl font-black text-emerald-600 mt-1">${{ number_format($totales['efectivo'], 2) }}</h4>
                        </div>
                        <span class="text-xs text-slate-500 mt-2 font-medium">Fondo inicial + Cobros efectivo</span>
                    </div>

                    <!-- Tarjetas / Datáfono -->
                    <div class="bg-white p-5 rounded-2xl border border-pink-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-pink-600">Tarjetas / POS</span>
                            <h4 class="text-2xl font-black text-pink-600 mt-1">${{ number_format($totales['tarjeta'], 2) }}</h4>
                        </div>
                        <span class="text-xs text-slate-500 mt-2 font-medium">Vouchers de débito y crédito</span>
                    </div>

                    <!-- Transferencias -->
                    <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Transferencias</span>
                            <h4 class="text-2xl font-black text-blue-600 mt-1">${{ number_format($totales['transferencia'], 2) }}</h4>
                        </div>
                        <span class="text-xs text-slate-500 mt-2 font-medium">Depósitos y bancos</span>
                    </div>

                    <!-- Zelle -->
                    <div class="bg-white p-5 rounded-2xl border border-violet-100 shadow-sm flex flex-col justify-between bg-violet-50/10">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-violet-600">Zelle</span>
                            <h4 class="text-2xl font-black text-violet-600 mt-1">${{ number_format($totales['zelle'], 2) }}</h4>
                        </div>
                        <span class="text-xs text-slate-500 mt-2 font-medium">Pagos y confirmaciones Zelle</span>
                    </div>

                    <!-- Cheques -->
                    <div class="bg-white p-5 rounded-2xl border border-purple-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-purple-600">Cheques</span>
                            <h4 class="text-2xl font-black text-purple-600 mt-1">${{ number_format($totales['cheque'], 2) }}</h4>
                        </div>
                        <span class="text-xs text-slate-500 mt-2 font-medium">Cheques en custodia</span>
                    </div>
                </div>

                <!-- Tabla de Movimientos de la Caja Actual -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 pb-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-800">Transacciones de la Sesión Actual ({{ $movimientos->count() }})</h3>
                            <p class="text-xs text-slate-400">Todos los cobros de trámites, abonos y movimientos registrados en este turno</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-600 divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold tracking-wider">
                                <tr>
                                    <th class="py-3.5 px-6">Hora</th>
                                    <th class="py-3.5 px-6">Tipo / Concepto</th>
                                    <th class="py-3.5 px-6">Cliente / Trámite</th>
                                    <th class="py-3.5 px-6">Método de Pago</th>
                                    <th class="py-3.5 px-6">Banco / Tarjeta / Ref</th>
                                    <th class="py-3.5 px-6 text-right">Monto ($)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($movimientos as $mov)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3.5 px-6 font-mono text-xs text-slate-500 whitespace-nowrap">
                                             {{ $mov->created_at->format('h:i:s A') }}
                                        </td>
                                        <td class="py-3.5 px-6">
                                            <div class="font-bold text-slate-800 text-xs">{{ $mov->concepto }}</div>
                                            <span class="text-[10px] font-semibold uppercase text-slate-400">{{ str_replace('_', ' ', $mov->tipo) }}</span>
                                        </td>
                                        <td class="py-3.5 px-6 text-xs font-semibold text-slate-700">
                                            @if($mov->cliente)
                                                <a href="{{ route('clientes.pagos', $mov->cliente->id_cliente) }}" class="text-indigo-600 hover:underline">
                                                    {{ $mov->cliente->c_nombre }} {{ $mov->cliente->c_apellido }}
                                                </a>
                                            @else
                                                <span class="text-slate-400">N/A</span>
                                            @endif
                                            @if($mov->tramite_tipo)
                                                <span class="block text-[10px] text-slate-400 font-normal">Trámite: {{ $mov->tramite_tipo }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            @if($mov->metodo_pago === 'Efectivo')
                                                <span class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">Efectivo</span>
                                            @elseif($mov->metodo_pago === 'Tarjeta')
                                                <span class="px-2.5 py-1 text-xs font-bold text-pink-700 bg-pink-50 border border-pink-200 rounded-lg">Tarjeta</span>
                                            @elseif($mov->metodo_pago === 'Transferencia')
                                                <span class="px-2.5 py-1 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg">Transferencia</span>
                                            @elseif($mov->metodo_pago === 'Zelle')
                                                <span class="px-2.5 py-1 text-xs font-bold text-violet-700 bg-violet-50 border border-violet-200 rounded-lg">Zelle</span>
                                            @elseif($mov->metodo_pago === 'Cheque')
                                                <span class="px-2.5 py-1 text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 rounded-lg">Cheque</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-lg">Crédito</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-6 text-xs text-slate-600">
                                            @if($mov->banco)
                                                <span class="font-bold text-slate-700">{{ $mov->banco->nombre }}</span>
                                            @endif
                                            @if($mov->tarjeta)
                                                <span class="text-slate-500">• {{ $mov->tarjeta->nombre }}</span>
                                            @endif
                                            @if($mov->numero_referencia)
                                                <span class="block font-mono text-[11px] text-indigo-600">Ref: {{ $mov->numero_referencia }}</span>
                                            @endif
                                            @if(!$mov->banco && !$mov->tarjeta && !$mov->numero_referencia)
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                            @if(in_array($mov->tipo, ['ingreso_tramite', 'ingreso_abono', 'ingreso_extra']))
                                                <span class="text-sm font-black text-emerald-600">+${{ number_format($mov->monto, 2) }}</span>
                                            @else
                                                <span class="text-sm font-black text-rose-600">-${{ number_format($mov->monto, 2) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-sm font-semibold">No se han registrado transacciones en esta sesión todavía.</p>
                                                <p class="text-xs text-slate-400 mt-0.5">Los cobros de trámites y abonos se reflejarán aquí automáticamente.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal para Movimiento Extra / Gasto Menor -->
                <div id="modalMovimientoManual" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalMovimientoManual()"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                        
                        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                            <form action="{{ route('cajas.movimiento_manual') }}" method="POST">
                                @csrf

                                <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 px-6 py-5 text-white flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-amber-500/20 text-amber-400 rounded-xl border border-amber-500/30">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-white">Registrar Movimiento de Caja</h3>
                                            <p class="text-xs text-slate-300">Ingreso adicional o egreso/gasto menor de turno</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="closeModalMovimientoManual()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div>
                                        <label for="tipo_mov" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tipo de Movimiento *</label>
                                        <select name="tipo" id="tipo_mov" required class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 font-medium text-slate-800">
                                            <option value="egreso_gasto">Egreso: Gasto Menor de Oficina</option>
                                            <option value="egreso_retiro">Egreso: Retiro de Efectivo</option>
                                            <option value="ingreso_extra">Ingreso: Inyección Extra de Efectivo</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="monto_mov" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Monto ($) *</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">$</span>
                                            <input type="number" step="0.01" min="0.01" name="monto" id="monto_mov" placeholder="0.00" required
                                                   class="w-full pl-8 pr-4 py-2.5 text-base font-bold bg-white border border-slate-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-slate-800">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="metodo_mov" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Método de Pago *</label>
                                        <select name="metodo_pago" id="metodo_mov" onchange="toggleMetodoMovimientoManual()" required class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 font-medium text-slate-800">
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta">Tarjeta / POS</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Zelle">Zelle</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>

                                    <!-- Selector Tarjeta -->
                                    <div id="div_mov_tarjeta" class="hidden">
                                        <label for="tarjeta_id_mov" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tarjeta / Datáfono *</label>
                                        <select name="tarjeta_id" id="tarjeta_id_mov" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 font-medium text-slate-800">
                                            <option value="">Seleccione Tarjeta...</option>
                                            @foreach($tarjetas as $tar)
                                                <option value="{{ $tar->id }}">{{ $tar->nombre }} ({{ $tar->franquicia }} - {{ $tar->banco->nombre ?? 'Banco' }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Selector Banco -->
                                    <div id="div_mov_banco" class="hidden">
                                        <label for="banco_id_mov" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Banco Destino / Emisor *</label>
                                        <select name="banco_id" id="banco_id_mov" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 font-medium text-slate-800">
                                            <option value="">Seleccione Banco...</option>
                                            @foreach($bancos as $ban)
                                                <option value="{{ $ban->id }}">{{ $ban->nombre }} ({{ $ban->tipo_cuenta ?? 'General' }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="concepto_mov" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Concepto / Motivo *</label>
                                        <input type="text" name="concepto" id="concepto_mov" placeholder="Ej: Compra de insumos de oficina, Pago mensajería..." required
                                               class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 font-medium text-slate-800">
                                    </div>

                                    <div>
                                        <label for="ref_mov" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">N° Comprobante / Voucher / Factura / Ref (Opcional)</label>
                                        <input type="text" name="numero_referencia" id="ref_mov" placeholder="Ej: Voucher #00234, Factura #12, Ref Zelle #489"
                                               class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 font-medium text-slate-800">
                                    </div>
                                </div>

                                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2 border-t border-slate-100">
                                    <button type="button" onclick="closeModalMovimientoManual()" class="px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200">
                                        Guardar Movimiento
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function openModalMovimientoManual() {
                        document.getElementById('modalMovimientoManual').classList.remove('hidden');
                    }
                    function closeModalMovimientoManual() {
                        document.getElementById('modalMovimientoManual').classList.add('hidden');
                    }
                    function toggleMetodoMovimientoManual() {
                        const metodo = document.getElementById('metodo_mov').value;
                        const divTarjeta = document.getElementById('div_mov_tarjeta');
                        const divBanco = document.getElementById('div_mov_banco');

                        if (divTarjeta) divTarjeta.classList.toggle('hidden', metodo !== 'Tarjeta');
                        if (divBanco) divBanco.classList.toggle('hidden', metodo !== 'Transferencia' && metodo !== 'Cheque' && metodo !== 'Zelle');
                    }
                </script>

            @endif

        </div>
    </div>
</x-app-layout>
