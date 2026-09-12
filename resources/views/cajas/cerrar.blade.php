<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-rose-50 text-rose-600 rounded-2xl border border-rose-100 shadow-sm shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Arqueo y Cierre de Caja') }} #{{ $caja->id }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Cajero: <strong class="text-slate-700">{{ $caja->user->name }}</strong> • Sucursal: <strong class="text-slate-700">{{ $caja->oficina }}</strong> • Inicio: {{ $caja->fecha_apertura->format('d/m/Y h:i A') }}
                    </p>
                </div>
            </div>

            <a href="{{ route('cajas.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-sm transition-all">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Panel de Caja
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Banner Informativo de Regla de Cuadre -->
            <div class="p-4 sm:p-5 bg-gradient-to-r from-indigo-900 to-slate-900 text-white rounded-3xl shadow-md flex items-start gap-4">
                <div class="p-2.5 bg-indigo-500/20 text-indigo-300 rounded-2xl border border-indigo-400/30 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-xs space-y-1">
                    <h4 class="font-extrabold text-sm text-white">Validación Estricta de Cuadre Contable ($0.00 Diferencia)</h4>
                    <p class="text-slate-300 leading-relaxed">
                        Ingresa el conteo físico real de dinero en efectivo y el valor total de los comprobantes recibidos por tarjeta, transferencia o cheque. El sistema comparará cada rubro contra las operaciones registradas y habilitará el cierre únicamente cuando el arqueo esté cuadrado al 100%.
                    </p>
                </div>
            </div>

            <!-- Resumen Rápido de Valores Teóricos de Sistema -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Fondo Inicial</span>
                    <span class="text-lg font-black text-slate-700 mt-1 block">${{ number_format($totales['monto_apertura'], 2) }}</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-emerald-200 shadow-sm bg-emerald-50/20">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 block">Efectivo Teórico</span>
                    <span class="text-lg font-black text-emerald-600 mt-1 block">${{ number_format($totales['efectivo'], 2) }}</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-pink-200 shadow-sm">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-pink-600 block">Tarjetas / POS</span>
                    <span class="text-lg font-black text-pink-600 mt-1 block">${{ number_format($totales['tarjeta'], 2) }}</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-blue-200 shadow-sm">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 block">Transferencias</span>
                    <span class="text-lg font-black text-blue-600 mt-1 block">${{ number_format($totales['transferencia'], 2) }}</span>
                </div>
                <div class="col-span-2 sm:col-span-1 bg-white p-4 rounded-2xl border border-purple-200 shadow-sm">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600 block">Cheques</span>
                    <span class="text-lg font-black text-purple-600 mt-1 block">${{ number_format($totales['cheque'], 2) }}</span>
                </div>
            </div>

            <!-- Formulario Principal de Arqueo -->
            <form action="{{ route('cajas.procesar_cierre') }}" method="POST" id="formCierreCaja" class="space-y-6">
                @csrf

                <div class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden">
                    <div class="p-6 bg-slate-50 border-b border-slate-200/80 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                                <span class="w-3 h-3 bg-emerald-500 rounded-full inline-block"></span>
                                Tabla de Arqueo y Conteo Físico Real
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Ingresa los valores contados en caja para cada método de cobro</p>
                        </div>

                        <button type="button" onclick="llenarValoresEsperados()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Auto-llenar con Totales del Sistema
                        </button>
                    </div>

                    <!-- Lista de Rubros de Arqueo -->
                    <div class="divide-y divide-slate-100 p-4 sm:p-6 space-y-4">
                        
                        <!-- 1. Efectivo -->
                        <div class="p-4 sm:p-5 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200/90 transition-colors">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <div class="md:col-span-4 space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                        <h4 class="font-extrabold text-sm text-slate-800">1. Efectivo en Mano</h4>
                                    </div>
                                    <p class="text-xs text-slate-500">Fondo Inicial (${{ number_format($totales['monto_apertura'], 2) }}) + Cobros en Efectivo</p>
                                </div>

                                <div class="md:col-span-3 text-left md:text-center">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Esperado por Sistema</span>
                                    <span class="text-base font-black text-slate-800">${{ number_format($totales['efectivo'], 2) }}</span>
                                </div>

                                <div class="md:col-span-3">
                                    <label for="monto_efectivo" class="text-[11px] font-bold uppercase tracking-wider text-slate-600 block mb-1">Conteo Físico Real ($) *</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">$</span>
                                        <input type="number" step="0.01" min="0" name="monto_efectivo" id="monto_efectivo" value="{{ old('monto_efectivo', '0.00') }}" required
                                               class="w-full pl-8 pr-4 py-2.5 text-base font-bold bg-white border border-slate-300 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-slate-800 shadow-sm"
                                               oninput="calcularCuadreEnVivo()">
                                    </div>
                                </div>

                                <div class="md:col-span-2 text-left md:text-right">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Diferencia</span>
                                    <span id="diff_efectivo_badge" class="inline-block text-xs font-black px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 mt-1">
                                        $0.00
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Tarjetas / POS -->
                        <div class="p-4 sm:p-5 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200/90 transition-colors">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <div class="md:col-span-4 space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-pink-500"></span>
                                        <h4 class="font-extrabold text-sm text-slate-800">2. Vouchers de Tarjetas / POS</h4>
                                    </div>
                                    <p class="text-xs text-slate-500">Suma total de tickets de cobro débito y crédito</p>
                                </div>

                                <div class="md:col-span-3 text-left md:text-center">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Esperado por Sistema</span>
                                    <span class="text-base font-black text-slate-800">${{ number_format($totales['tarjeta'], 2) }}</span>
                                </div>

                                <div class="md:col-span-3">
                                    <label for="monto_tarjeta" class="text-[11px] font-bold uppercase tracking-wider text-slate-600 block mb-1">Total Vouchers ($) *</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">$</span>
                                        <input type="number" step="0.01" min="0" name="monto_tarjeta" id="monto_tarjeta" value="{{ old('monto_tarjeta', '0.00') }}" required
                                               class="w-full pl-8 pr-4 py-2.5 text-base font-bold bg-white border border-slate-300 rounded-xl focus:border-pink-500 focus:ring-2 focus:ring-pink-200 text-slate-800 shadow-sm"
                                               oninput="calcularCuadreEnVivo()">
                                    </div>
                                </div>

                                <div class="md:col-span-2 text-left md:text-right">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Diferencia</span>
                                    <span id="diff_tarjeta_badge" class="inline-block text-xs font-black px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 mt-1">
                                        $0.00
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Transferencias -->
                        <div class="p-4 sm:p-5 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200/90 transition-colors">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <div class="md:col-span-4 space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                        <h4 class="font-extrabold text-sm text-slate-800">3. Transferencias Bancarias</h4>
                                    </div>
                                    <p class="text-xs text-slate-500">Comprobantes y depósitos confirmados en cuenta</p>
                                </div>

                                <div class="md:col-span-3 text-left md:text-center">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Esperado por Sistema</span>
                                    <span class="text-base font-black text-slate-800">${{ number_format($totales['transferencia'], 2) }}</span>
                                </div>

                                <div class="md:col-span-3">
                                    <label for="monto_transferencia" class="text-[11px] font-bold uppercase tracking-wider text-slate-600 block mb-1">Total Confirmado ($) *</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">$</span>
                                        <input type="number" step="0.01" min="0" name="monto_transferencia" id="monto_transferencia" value="{{ old('monto_transferencia', '0.00') }}" required
                                               class="w-full pl-8 pr-4 py-2.5 text-base font-bold bg-white border border-slate-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-slate-800 shadow-sm"
                                               oninput="calcularCuadreEnVivo()">
                                    </div>
                                </div>

                                <div class="md:col-span-2 text-left md:text-right">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Diferencia</span>
                                    <span id="diff_transferencia_badge" class="inline-block text-xs font-black px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 mt-1">
                                        $0.00
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Cheques -->
                        <div class="p-4 sm:p-5 bg-slate-50/70 hover:bg-slate-50 rounded-2xl border border-slate-200/90 transition-colors">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <div class="md:col-span-4 space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                                        <h4 class="font-extrabold text-sm text-slate-800">4. Cheques en Custodia</h4>
                                    </div>
                                    <p class="text-xs text-slate-500">Total en cheques recibidos físicamente</p>
                                </div>

                                <div class="md:col-span-3 text-left md:text-center">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Esperado por Sistema</span>
                                    <span class="text-base font-black text-slate-800">${{ number_format($totales['cheque'], 2) }}</span>
                                </div>

                                <div class="md:col-span-3">
                                    <label for="monto_cheque" class="text-[11px] font-bold uppercase tracking-wider text-slate-600 block mb-1">Total en Cheques ($) *</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">$</span>
                                        <input type="number" step="0.01" min="0" name="monto_cheque" id="monto_cheque" value="{{ old('monto_cheque', '0.00') }}" required
                                               class="w-full pl-8 pr-4 py-2.5 text-base font-bold bg-white border border-slate-300 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-slate-800 shadow-sm"
                                               oninput="calcularCuadreEnVivo()">
                                    </div>
                                </div>

                                <div class="md:col-span-2 text-left md:text-right">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Diferencia</span>
                                    <span id="diff_cheque_badge" class="inline-block text-xs font-black px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 mt-1">
                                        $0.00
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Observaciones de Cierre -->
                    <div class="p-6 bg-slate-50/50 border-t border-slate-100">
                        <label for="observaciones_cierre" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Observaciones / Novedades de Cierre (Opcional)
                        </label>
                        <textarea name="observaciones_cierre" id="observaciones_cierre" rows="2" placeholder="Detalles de arqueo, novedades del turno, etc..."
                                  class="w-full text-sm bg-white border border-slate-300 rounded-2xl p-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 font-medium text-slate-800 shadow-sm"></textarea>
                    </div>
                </div>

                <!-- Panel Resumen de Balance y Botón de Cierre -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 pb-6 border-b border-slate-100">
                        <div class="space-y-1">
                            <h3 class="text-xl font-black text-slate-900">Balance Global de Turno</h3>
                            <p class="text-xs text-slate-400">Total Esperado de Sistema vs Total Físico Declarado</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 sm:gap-6 w-full md:w-auto">
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                                <span class="text-[10px] font-bold uppercase text-slate-400 block">Esperado Sistema:</span>
                                <span class="text-lg font-black text-slate-800">${{ number_format($totales['total_general'], 2) }}</span>
                            </div>

                            <div class="p-3 bg-indigo-50 border border-indigo-200 rounded-2xl">
                                <span class="text-[10px] font-bold uppercase text-indigo-600 block">Total Declarado:</span>
                                <span class="text-lg font-black text-indigo-700" id="display_declarado_total">$0.00</span>
                            </div>

                            <div class="p-3 rounded-2xl border" id="box_diferencia_total">
                                <span class="text-[10px] font-bold uppercase block" id="label_diferencia">Diferencia Total:</span>
                                <span class="text-lg font-black" id="display_diferencia_total">$0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Banner de Estado de Cuadre en Vivo -->
                    <div id="banner_cuadre_estado" class="p-4 sm:p-5 rounded-2xl border text-center transition-all">
                        <!-- Actualizado por JS -->
                    </div>

                    <!-- Botón de Cierre Formal -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-2">
                        <p class="text-xs text-slate-400 italic text-center sm:text-left">
                            * Al confirmar el cierre se generará el Acta Formal en PDF y la sesión de caja quedará sellada.
                        </p>

                        <button type="submit" id="btnConfirmarCierre" disabled
                                class="w-full sm:w-auto min-w-[280px] py-4 px-8 rounded-2xl text-sm font-black tracking-wide shadow-lg transition-all flex items-center justify-center gap-2 cursor-not-allowed bg-slate-300 text-slate-500 shadow-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Confirmar y Cerrar Caja</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <!-- Script de Cuadre en Tiempo Real -->
    <script>
        const sistema = {
            efectivo: {{ (float)$totales['efectivo'] }},
            tarjeta: {{ (float)$totales['tarjeta'] }},
            transferencia: {{ (float)$totales['transferencia'] }},
            cheque: {{ (float)$totales['cheque'] }},
            total: {{ (float)$totales['total_general'] }}
        };

        function llenarValoresEsperados() {
            document.getElementById('monto_efectivo').value = sistema.efectivo.toFixed(2);
            document.getElementById('monto_tarjeta').value = sistema.tarjeta.toFixed(2);
            document.getElementById('monto_transferencia').value = sistema.transferencia.toFixed(2);
            document.getElementById('monto_cheque').value = sistema.cheque.toFixed(2);
            calcularCuadreEnVivo();
        }

        function calcularCuadreEnVivo() {
            const decEfectivo = parseFloat(document.getElementById('monto_efectivo').value) || 0;
            const decTarjeta = parseFloat(document.getElementById('monto_tarjeta').value) || 0;
            const decTransf = parseFloat(document.getElementById('monto_transferencia').value) || 0;
            const decCheque = parseFloat(document.getElementById('monto_cheque').value) || 0;

            const totalDeclarado = +(decEfectivo + decTarjeta + decTransf + decCheque).toFixed(2);

            const difEfectivo = +(decEfectivo - sistema.efectivo).toFixed(2);
            const difTarjeta = +(decTarjeta - sistema.tarjeta).toFixed(2);
            const difTransf = +(decTransf - sistema.transferencia).toFixed(2);
            const difCheque = +(decCheque - sistema.cheque).toFixed(2);
            const difTotal = +(totalDeclarado - sistema.total).toFixed(2);

            // Actualizar insignias individuales
            updateRubroBadge('diff_efectivo_badge', difEfectivo);
            updateRubroBadge('diff_tarjeta_badge', difTarjeta);
            updateRubroBadge('diff_transferencia_badge', difTransf);
            updateRubroBadge('diff_cheque_badge', difCheque);

            // Actualizar Totales
            document.getElementById('display_declarado_total').innerText = '$' + totalDeclarado.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('display_diferencia_total').innerText = (difTotal > 0 ? '+$' : (difTotal < 0 ? '-$' : '$')) + Math.abs(difTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            const boxDif = document.getElementById('box_diferencia_total');
            const banner = document.getElementById('banner_cuadre_estado');
            const btn = document.getElementById('btnConfirmarCierre');

            const esCuadrado = Math.abs(difTotal) < 0.009 && 
                               Math.abs(difEfectivo) < 0.009 && 
                               Math.abs(difTarjeta) < 0.009 && 
                               Math.abs(difTransf) < 0.009 && 
                               Math.abs(difCheque) < 0.009;

            if (esCuadrado) {
                // CAJA CUADRADA AL 100%
                boxDif.className = 'p-3 rounded-2xl border bg-emerald-50 border-emerald-300 text-emerald-800';
                banner.className = 'p-5 rounded-2xl border bg-emerald-50 border-emerald-300 text-emerald-900 space-y-1 shadow-sm';
                banner.innerHTML = `
                    <div class="flex items-center justify-center gap-2 font-black text-base text-emerald-700">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        ¡Arqueo Cuadrado al 100%! ($0.00 de diferencia)
                    </div>
                    <p class="text-xs text-emerald-700 font-medium">Todos los métodos de pago coinciden con precisión con los registros del sistema.</p>
                `;

                btn.disabled = false;
                btn.className = 'w-full sm:w-auto min-w-[280px] py-4 px-8 rounded-2xl text-sm font-black tracking-wide shadow-lg shadow-emerald-200 hover:shadow-xl transition-all flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white cursor-pointer';
            } else {
                // CAJA DESCUADRADA
                boxDif.className = 'p-3 rounded-2xl border bg-rose-50 border-rose-300 text-rose-800';
                banner.className = 'p-5 rounded-2xl border bg-rose-50 border-rose-300 text-rose-900 space-y-1 shadow-sm';
                banner.innerHTML = `
                    <div class="flex items-center justify-center gap-2 font-black text-base text-rose-700">
                        <svg class="w-6 h-6 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Descuadre Detectado: ${difTotal > 0 ? 'Sobrante' : 'Faltante'} de $${Math.abs(difTotal).toFixed(2)}
                    </div>
                    <p class="text-xs text-rose-700 font-medium">No se permite cerrar caja con descuadre. Por favor revise el conteo físico y los comprobantes.</p>
                `;

                btn.disabled = true;
                btn.className = 'w-full sm:w-auto min-w-[280px] py-4 px-8 rounded-2xl text-sm font-black tracking-wide transition-all flex items-center justify-center gap-2 cursor-not-allowed bg-slate-300 text-slate-500 shadow-none';
            }
        }

        function updateRubroBadge(elementId, diff) {
            const el = document.getElementById(elementId);
            if (!el) return;
            if (Math.abs(diff) < 0.009) {
                el.innerText = 'Cuadrado ($0.00)';
                el.className = 'inline-block text-xs font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200';
            } else if (diff > 0) {
                el.innerText = 'Sobrante: +$' + diff.toFixed(2);
                el.className = 'inline-block text-xs font-bold px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
            } else {
                el.innerText = 'Faltante: -$' + Math.abs(diff).toFixed(2);
                el.className = 'inline-block text-xs font-bold px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            calcularCuadreEnVivo();
        });
    </script>
</x-app-layout>
