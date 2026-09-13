<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-2">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-xl border border-white/20 shadow-sm text-white" :style="'background: {{ $tipoTramite->color_gradient }}'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        Nuevo Trámite: {{ $tipoTramite->nombre }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Cliente: <span class="font-bold text-indigo-700">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</span> (ID: <span class="font-mono font-bold">{{ $cliente->c_identificacion }}</span>)
                    </p>
                </div>
            </div>

            <a href="{{ route('clientes.tramites', $cliente->id_cliente) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-wider shadow-sm hover:bg-slate-50 transition duration-150">
                &larr; Volver al Expediente
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{
        valor: 0,
        abono: 0,
        metodoPago: 'Efectivo',
        get saldo() {
            let s = parseFloat(this.valor || 0) - parseFloat(this.abono || 0);
            return s > 0 ? s.toFixed(2) : '0.00';
        }
    }">
        <div class="w-full max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('tramites-personalizados.store', ['cliente' => $cliente->id_cliente, 'tipoTramite' => $tipoTramite->id]) }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Sección 1: Campos Específicos del Trámite -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                    <div class="pb-4 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                                <span class="w-2.5 h-5 bg-indigo-600 rounded-full inline-block"></span>
                                Datos del Trámite
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $tipoTramite->descripcion ?? 'Completa la información requerida' }}</p>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full text-white" style="background: {{ $tipoTramite->color_gradient }}">
                            {{ $tipoTramite->nombre }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @forelse($tipoTramite->campos ?? [] as $campo)
                            <div class="{{ $campo['type'] === 'textarea' ? 'sm:col-span-2' : '' }}">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    {{ $campo['label'] }}
                                    @if(!empty($campo['required']))
                                        <span class="text-rose-500">*</span>
                                    @endif
                                </label>

                                @if($campo['type'] === 'text')
                                    <input type="text" name="{{ $campo['name'] }}" 
                                           class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-medium text-slate-800"
                                           {{ !empty($campo['required']) ? 'required' : '' }}>

                                @elseif($campo['type'] === 'number')
                                    <input type="number" step="any" name="{{ $campo['name'] }}" 
                                           class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-medium text-slate-800"
                                           {{ !empty($campo['required']) ? 'required' : '' }}>

                                @elseif($campo['type'] === 'date')
                                    <input type="date" name="{{ $campo['name'] }}" value="{{ date('Y-m-d') }}"
                                           class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-medium text-slate-800"
                                           {{ !empty($campo['required']) ? 'required' : '' }}>

                                @elseif($campo['type'] === 'select')
                                    <select name="{{ $campo['name'] }}" 
                                            class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-medium text-slate-800"
                                            {{ !empty($campo['required']) ? 'required' : '' }}>
                                        <option value="">Seleccione una opción...</option>
                                        @foreach($campo['options'] ?? [] as $opcion)
                                            <option value="{{ $opcion }}">{{ $opcion }}</option>
                                        @endforeach
                                    </select>

                                @elseif($campo['type'] === 'textarea')
                                    <textarea name="{{ $campo['name'] }}" rows="3" 
                                              class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl p-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-medium text-slate-800"
                                              {{ !empty($campo['required']) ? 'required' : '' }}></textarea>

                                @elseif($campo['type'] === 'checkbox')
                                    <div class="pt-2">
                                        <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                            <input type="checkbox" name="{{ $campo['name'] }}" value="1" 
                                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm font-bold text-slate-700">Marcar como Sí / Aceptado</span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="sm:col-span-2 py-4 text-center text-slate-400 text-xs">
                                Este trámite no requiere campos adicionales.
                            </div>
                        @endforelse
                    </div>

                    <!-- Observaciones Adicionales -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Observaciones Generales</label>
                        <textarea name="observaciones" rows="2" placeholder="Notas o detalles especiales del trámite..." 
                                  class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl p-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all text-slate-800"></textarea>
                    </div>
                </div>

                <!-- Sección 2: Información Financiera y Pagos -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                    <div class="pb-4 border-b border-slate-100">
                        <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                            <span class="w-2.5 h-5 bg-emerald-600 rounded-full inline-block"></span>
                            Información Financiera del Trámite
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Define el costo del trámite y el abono recibido</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <!-- Valor Total -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Valor del Trámite ($) *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">$</span>
                                <input type="number" step="0.01" min="0" name="valor_tramite" x-model="valor" placeholder="0.00" 
                                       class="w-full pl-8 pr-4 py-2.5 text-base font-bold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-slate-800 transition-all" required>
                            </div>
                        </div>

                        <!-- Abono Inicial -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Abono Inicial ($)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">$</span>
                                <input type="number" step="0.01" min="0" name="abono_tramite" x-model="abono" placeholder="0.00" 
                                       class="w-full pl-8 pr-4 py-2.5 text-base font-bold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-slate-800 transition-all">
                            </div>
                        </div>

                        <!-- Saldo Pendiente (Calculado) -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Saldo Resultante</label>
                            <div class="p-2.5 bg-amber-50 border border-amber-200 rounded-xl text-center">
                                <span class="text-xl font-black text-amber-700">$<span x-text="saldo"></span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Caja status indicator -->
                    @if($cajaAbierta)
                        <div class="flex items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 p-2.5 rounded-xl border border-emerald-200 mb-4">
                            <span>🟢</span>
                            <span>Caja <strong>#{{ $cajaAbierta->id }}</strong> activa para cobro de abonos</span>
                        </div>
                    @else
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-2.5 text-xs text-blue-800 leading-relaxed mb-4">
                            <span>ℹ️</span> Si redactas este trámite sin caja abierta, deja el abono en <strong>$0.00</strong> para que se registre en la Cartera del Cliente y se cobre en ventanilla.
                        </div>
                    @endif

                    <!-- Métodos de Pago para el Abono -->
                    @if($cajaAbierta)
                    <div x-show="parseFloat(abono) > 0" class="pt-4 border-t border-slate-100 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="metodo_pago" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Método de Pago del Abono</label>
                                <select name="metodo_pago" id="metodo_pago" x-model="metodoPago"
                                        class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 font-medium text-slate-800">
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Tarjeta">Tarjeta / POS (Débito/Crédito)</option>
                                    <option value="Transferencia">Transferencia Bancaria</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>

                            <div x-show="metodoPago === 'Tarjeta'">
                                <label for="tarjeta_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tarjeta / Datáfono</label>
                                <select name="tarjeta_id" id="tarjeta_id" class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 font-medium text-slate-800">
                                    <option value="">Seleccione Tarjeta / POS...</option>
                                    @foreach($tarjetas as $tar)
                                        <option value="{{ $tar->id }}">{{ $tar->nombre }} ({{ $tar->franquicia }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="metodoPago === 'Transferencia' || metodoPago === 'Cheque'">
                                <label for="banco_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Banco</label>
                                <select name="banco_id" id="banco_id" class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 font-medium text-slate-800">
                                    <option value="">Seleccione Banco...</option>
                                    @foreach($bancos as $ban)
                                        <option value="{{ $ban->id }}">{{ $ban->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="metodoPago !== 'Efectivo'">
                                <label for="numero_referencia" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">N° Voucher / Cheque / Ref</label>
                                <input type="text" name="numero_referencia" id="numero_referencia" placeholder="Ej: Voucher #1234"
                                       class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 font-medium text-slate-800">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Botón de Envío -->
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('clientes.tramites', $cliente->id_cliente) }}" class="px-5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 transition-all">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-7 py-3 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-sm font-bold shadow-lg hover:shadow-xl shadow-emerald-200 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        Guardar Trámite y Generar Comprobante
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
