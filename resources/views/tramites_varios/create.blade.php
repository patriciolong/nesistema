<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Nuevo Trámite Vario
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Cliente: <span class="font-semibold text-indigo-600">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</span>
                </p>
            </div>
            <a href="{{ route('clientes.tramites', $cliente->id_cliente) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                &larr; Cancelar
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm" role="alert">
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('tramites-varios.store') }}" class="space-y-8">
                @csrf
                <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

                <!-- STEP 1: DETALLES PRINCIPALES -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-indigo-50 border-b border-indigo-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-indigo-900 flex items-center gap-2">
                            <span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                            Información del Trámite
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tv_motivo" class="block text-sm font-semibold text-gray-700 mb-1">Motivo / Tipo de Servicio <span class="text-red-500">*</span></label>
                                <input id="tv_motivo" type="text" name="tv_motivo" value="{{ old('tv_motivo') }}" required autofocus
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors"
                                    placeholder="Ej: Traducción de partida">
                                <x-input-error :messages="$errors->get('tv_motivo')" class="mt-1" />
                            </div>

                            <div>
                                <label for="tv_tip_documento" class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Documento <span class="text-red-500">*</span></label>
                                <input id="tv_tip_documento" type="text" name="tv_tip_documento" value="{{ old('tv_tip_documento') }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors"
                                    placeholder="Ej: Pasaporte, Partida de Nacimiento">
                                <x-input-error :messages="$errors->get('tv_tip_documento')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Opciones Múltiples -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Servicios Adicionales Requeridos</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-4 transition-colors hover:bg-gray-50 has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-200">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="tv_traducciones" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('tv_traducciones') ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex flex-col">
                                        <strong class="font-medium text-gray-900 text-sm">Traducción</strong>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-4 transition-colors hover:bg-gray-50 has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-200">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="tv_notarizacion" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('tv_notarizacion') ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex flex-col">
                                        <strong class="font-medium text-gray-900 text-sm">Notarización</strong>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-4 transition-colors hover:bg-gray-50 has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-200">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="tv_certificacion" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('tv_certificacion') ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex flex-col">
                                        <strong class="font-medium text-gray-900 text-sm">Certificación</strong>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-4 transition-colors hover:bg-gray-50 has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-200">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="tv_apostilla" value="1" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ old('tv_apostilla') ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex flex-col">
                                        <strong class="font-medium text-gray-900 text-sm">Apostilla</strong>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="tv_razon_t" class="block text-sm font-semibold text-gray-700 mb-1">Razón Específica</label>
                            <input id="tv_razon_t" type="text" name="tv_razon_t" value="{{ old('tv_razon_t') }}"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            <x-input-error :messages="$errors->get('tv_razon_t')" class="mt-1" />
                        </div>

                        <div>
                            <label for="tv_firmar_en" class="block text-sm font-semibold text-gray-700 mb-1">Lugar de Firma</label>
                            <input id="tv_firmar_en" type="text" name="tv_firmar_en" value="{{ old('tv_firmar_en') }}"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors"
                                placeholder="Ej: Notaría Pública, Consulado">
                            <x-input-error :messages="$errors->get('tv_firmar_en')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- STEP 2: ENVIO -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-blue-50 border-b border-blue-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-blue-900 flex items-center gap-2">
                            <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                            Detalles de Envío y Recepción
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label for="tv_oenvio" class="block text-sm font-semibold text-gray-700 mb-1">Opción de Entrega <span class="text-red-500">*</span></label>
                            <select id="tv_oenvio" name="tv_oenvio" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white">
                                <option value="A su domicilio en EE.UU." {{ old('tv_oenvio') == 'A su domicilio en EE.UU.' ? 'selected' : '' }}>A su domicilio en EE.UU.</option>
                                <option value="Ofrecemos envios express al Ecuador en 3 dias laborables." {{ old('tv_oenvio') == 'Ofrecemos envios express al Ecuador en 3 dias laborables.' ? 'selected' : '' }}>Ofrecemos envios express al Ecuador en 3 dias laborables.</option>
                                <option value="Venirlo a retirar personalmente en la oficina." {{ old('tv_oenvio') == 'Venirlo a retirar personalmente en la oficina.' ? 'selected' : '' }}>Venirlo a retirar personalmente en la oficina.</option>
                            </select>
                            <x-input-error :messages="$errors->get('tv_oenvio')" class="mt-1" />
                        </div>



                        <div class="md:col-span-2 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-500 mb-4 font-medium uppercase tracking-wider">Datos del Destinatario (Solo si aplica envío)</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tv_nom_envio" class="block text-sm font-semibold text-gray-700 mb-1">Enviar a Nombre de</label>
                                    <input id="tv_nom_envio" type="text" name="tv_nom_envio" value="{{ old('tv_nom_envio') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white">
                                </div>
                                <div>
                                    <label for="tv_telefono" class="block text-sm font-semibold text-gray-700 mb-1">Teléfono Destino</label>
                                    <input id="tv_telefono" type="text" name="tv_telefono" value="{{ old('tv_telefono') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white">
                                </div>
                                <div>
                                    <label for="tv_ciudad" class="block text-sm font-semibold text-gray-700 mb-1">Ciudad Destino</label>
                                    <input id="tv_ciudad" type="text" name="tv_ciudad" value="{{ old('tv_ciudad') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white">
                                </div>
                                <div>
                                    <label for="tv_provincia" class="block text-sm font-semibold text-gray-700 mb-1">Estado / Provincia Destino</label>
                                    <input id="tv_provincia" type="text" name="tv_provincia" value="{{ old('tv_provincia') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: FINANZAS Y OBSERVACIONES -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-emerald-50 border-b border-emerald-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-emerald-900 flex items-center gap-2">
                            <span class="bg-emerald-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                            Cobro y Notas Adicionales
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Financial Box -->
                        <div class="md:col-span-1 bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <div class="mb-5">
                                <label for="tv_valor_tramite" class="block text-sm font-bold text-gray-700 mb-1">Valor Total del Servicio ($) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input id="tv_valor_tramite" type="number" step="0.01" name="tv_valor_tramite" value="{{ old('tv_valor_tramite') }}" required oninput="calcularSaldo()"
                                        class="block w-full pl-7 rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-bold text-lg text-gray-900">
                                </div>
                                <x-input-error :messages="$errors->get('tv_valor_tramite')" class="mt-1" />
                            </div>

                            <div class="mb-5">
                                <label for="tv_abono_tramite" class="block text-sm font-bold text-gray-700 mb-1">Abono Inicial del Cliente ($) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-emerald-500 sm:text-sm font-bold">$</span>
                                    </div>
                                    <input id="tv_abono_tramite" type="number" step="0.01" name="tv_abono_tramite" value="{{ old('tv_abono_tramite', 0) }}" required oninput="calcularSaldo()"
                                        class="block w-full pl-7 rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-bold text-lg text-emerald-600">
                                </div>
                                <x-input-error :messages="$errors->get('tv_abono_tramite')" class="mt-1" />
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <label for="tv_saldo" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Saldo a Sumar a Deuda</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-orange-500 sm:text-sm font-bold">$</span>
                                    </div>
                                    <input id="tv_saldo" type="text" name="tv_saldo" value="0.00" readonly tabindex="-1"
                                        class="block w-full pl-7 rounded-lg border-0 bg-transparent text-xl font-black text-orange-500 focus:ring-0 px-0">
                                </div>
                            </div>

                            <!-- Métodos de Pago -->
                            <div id="seccion_metodo_pago" class="mt-4 pt-4 border-t border-gray-200 space-y-3">
                                <div>
                                    <label for="metodo_pago" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Método de Pago</label>
                                    <select name="metodo_pago" id="metodo_pago" onchange="toggleTramitesVariosPaymentFields()"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-xs bg-white">
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Tarjeta">Tarjeta / POS</option>
                                        <option value="Transferencia">Transferencia Bancaria</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                </div>

                                <div id="div_tarjeta" class="hidden">
                                    <label for="tarjeta_id" class="block text-xs font-bold text-gray-700 mb-1">Tarjeta / POS</label>
                                    <select name="tarjeta_id" id="tarjeta_id" class="block w-full rounded-lg border-gray-300 shadow-sm sm:text-xs">
                                        <option value="">Seleccione Tarjeta...</option>
                                        @foreach($tarjetas as $tar)
                                            <option value="{{ $tar->id }}">{{ $tar->nombre }} ({{ $tar->franquicia }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="div_banco" class="hidden">
                                    <label for="banco_id" class="block text-xs font-bold text-gray-700 mb-1">Banco</label>
                                    <select name="banco_id" id="banco_id" class="block w-full rounded-lg border-gray-300 shadow-sm sm:text-xs">
                                        <option value="">Seleccione Banco...</option>
                                        @foreach($bancos as $ban)
                                            <option value="{{ $ban->id }}">{{ $ban->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="div_ref" class="hidden">
                                    <label for="numero_referencia" class="block text-xs font-bold text-gray-700 mb-1">N° Voucher / Ref</label>
                                    <input type="text" name="numero_referencia" id="numero_referencia" placeholder="Ej: Voucher #1234"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm sm:text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="md:col-span-2 flex flex-col">
                            <label for="tv_observaciones" class="block text-sm font-semibold text-gray-700 mb-2">Observaciones Internas del Trámite</label>
                            <textarea id="tv_observaciones" name="tv_observaciones" rows="6"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm bg-gray-50 focus:bg-white resize-none h-full"
                                placeholder="Anota cualquier instrucción especial, documentos faltantes o detalles del servicio...">{{ old('tv_observaciones') }}</textarea>
                            <x-input-error :messages="$errors->get('tv_observaciones')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 gap-4 pb-12">
                    <a href="{{ route('clientes.tramites', $cliente->id_cliente) }}" class="text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-indigo-200">
                        Generar e Imprimir Trámite
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcularSaldo() {
            let total = parseFloat(document.getElementById('tv_valor_tramite').value) || 0;
            let abono = parseFloat(document.getElementById('tv_abono_tramite').value) || 0;
            let saldo = total - abono;
            if (saldo < 0) saldo = 0;
            document.getElementById('tv_saldo').value = saldo.toFixed(2);
        }

        function toggleTramitesVariosPaymentFields() {
            const metodo = document.getElementById('metodo_pago').value;
            const divTarjeta = document.getElementById('div_tarjeta');
            const divBanco = document.getElementById('div_banco');
            const divRef = document.getElementById('div_ref');

            divTarjeta.classList.add('hidden');
            divBanco.classList.add('hidden');
            divRef.classList.add('hidden');

            if (metodo === 'Tarjeta') {
                divTarjeta.classList.remove('hidden');
                divRef.classList.remove('hidden');
            } else if (metodo === 'Transferencia' || metodo === 'Cheque') {
                divBanco.classList.remove('hidden');
                divRef.classList.remove('hidden');
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            calcularSaldo();
            toggleTramitesVariosPaymentFields();
        });
    </script>
</x-app-layout>
