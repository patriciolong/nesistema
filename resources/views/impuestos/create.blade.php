<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Nueva Declaración de Impuestos
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

            <form method="POST" action="{{ route('impuestos.store', $cliente->id_cliente) }}" class="space-y-8">
                @csrf
                <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

                <!-- STEP 1: INFORMACIÓN DE FIRMA -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-indigo-50 border-b border-indigo-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-indigo-900 flex items-center gap-2">
                            <span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                            Información de Firma
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Oficina Responsable</label>
                                <input type="text" class="block w-full rounded-lg border-gray-300 shadow-sm sm:text-sm bg-gray-100 text-gray-600 cursor-not-allowed" value="{{ Auth::user()->office }}" readonly>
                            </div>

                            <div>
                                <label for="ofifirmar" class="block text-sm font-semibold text-gray-700 mb-1">Firmar en <span class="text-red-500">*</span></label>
                                <select id="ofifirmar" name="ofifirmar" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                    <option value="Brooklyn" {{ old('ofifirmar') == 'Brooklyn' ? 'selected' : '' }}>Brooklyn</option>
                                    <option value="Spring Valley" {{ old('ofifirmar') == 'Spring Valley' ? 'selected' : '' }}>Spring Valley</option>
                                    <option value="New Jersey" {{ old('ofifirmar') == 'New Jersey' ? 'selected' : '' }}>New Jersey</option>
                                    <option value="Ossining" {{ old('ofifirmar') == 'Ossining' ? 'selected' : '' }}>Ossining</option>
                                    <option value="En Domicilio" {{ old('ofifirmar') == 'En Domicilio' ? 'selected' : '' }}>En su domicilio</option>
                                </select>
                                <x-input-error :messages="$errors->get('ofifirmar')" class="mt-1" />
                            </div>

                            <div>
                                <label for="fechaim" class="block text-sm font-semibold text-gray-700 mb-1">Fecha del Trámite</label>
                                <input id="fechaim" name="fechaim" type="date" value="{{ old('fechaim', date('Y-m-d')) }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                <x-input-error :messages="$errors->get('fechaim')" class="mt-1" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: DETALLES DE LA DECLARACIÓN -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-teal-50 border-b border-teal-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-teal-900 flex items-center gap-2">
                            <span class="bg-teal-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                            Detalles de la Declaración
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Checkbox ITIN -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="check1" value="1" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500 w-5 h-5" {{ old('check1') ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm text-gray-900 font-bold">Aplicación ITIN</span>
                                </label>
                            </div>

                            <div>
                                <label for="fechaeeuu" class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Ingreso a EEUU</label>
                                <input id="fechaeeuu" type="date" name="fechaeeuu" value="{{ old('fechaeeuu') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>

                            <div>
                                <label for="anio_reporte" class="block text-sm font-semibold text-gray-700 mb-1">Año de Reporte</label>
                                <select id="anio_reporte" name="anio_reporte" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                    <option value="">Elegir año</option>
                                    @for ($i = date("Y"); $i >= 1950; $i--)
                                        <option value="{{ $i }}" {{ old('anio_reporte') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="numitin" class="block text-sm font-semibold text-gray-700 mb-1">Número de ITIN o Social</label>
                                <input id="numitin" type="text" name="numitin" value="{{ old('numitin') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>

                            <div>
                                <label for="estcivil" class="block text-sm font-semibold text-gray-700 mb-1">Estado Civil</label>
                                <select id="estcivil" name="estcivil" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                    <option value="">Elegir</option>
                                    <option value="Soltero(a)" {{ old('estcivil') == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                                    <option value="Casados Juntos" {{ old('estcivil') == 'Casados Juntos' ? 'selected' : '' }}>Casados Juntos</option>
                                    <option value="Cabeza de Familia" {{ old('estcivil') == 'Cabeza de Familia' ? 'selected' : '' }}>Cabeza de Familia</option>
                                    <option value="Casados Separados" {{ old('estcivil') == 'Casados Separados' ? 'selected' : '' }}>Casados Separados</option>
                                    <option value="Casados entre si" {{ old('estcivil') == 'Casados entre si' ? 'selected' : '' }}>Casados entre si</option>
                                    <option value="Viudo(a)" {{ old('estcivil') == 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
                                </select>
                            </div>

                            <div>
                                <label for="profesion" class="block text-sm font-semibold text-gray-700 mb-1">Profesión</label>
                                <input id="profesion" type="text" name="profesion" value="{{ old('profesion') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>

                            <div>
                                <label for="dependentes" class="block text-sm font-semibold text-gray-700 mb-1">Número de Dependientes</label>
                                <input id="dependentes" type="number" name="dependentes" value="{{ old('dependentes', 0) }}" min="0"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>

                            <div>
                                <label for="metpago" class="block text-sm font-semibold text-gray-700 mb-1">Método de Pago</label>
                                <select id="metpago" name="metpago" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                    <option value="">Elegir</option>
                                    <option value="W2" {{ old('metpago') == 'W2' ? 'selected' : '' }}>W2</option>
                                    <option value="1099" {{ old('metpago') == '1099' ? 'selected' : '' }}>1099</option>
                                    <option value="CASH" {{ old('metpago') == 'CASH' ? 'selected' : '' }}>CASH</option>
                                </select>
                            </div>

                            <div>
                                <label for="banco" class="block text-sm font-semibold text-gray-700 mb-1">Banco</label>
                                <input id="banco" type="text" name="banco" value="{{ old('banco') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>

                            <div>
                                <label for="ncuenta" class="block text-sm font-semibold text-gray-700 mb-1">Número de Cuenta</label>
                                <input id="ncuenta" type="text" name="ncuenta" value="{{ old('ncuenta') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>

                            <div>
                                <label for="nruta" class="block text-sm font-semibold text-gray-700 mb-1">Número de Ruta</label>
                                <input id="nruta" type="text" name="nruta" value="{{ old('nruta') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- STEP 3: COBRO -->
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
                                <label for="vtramite" class="block text-sm font-bold text-gray-700 mb-1">Valor del Trámite ($) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input id="vtramite" type="number" step="0.01" name="vtramite" value="{{ old('vtramite', 0) }}" required oninput="calcularSaldo()"
                                        onfocus="if(this.value=='0') this.value=''" onblur="if(this.value=='') this.value='0'"
                                        class="block w-full pl-7 rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-bold text-lg text-gray-900">
                                </div>
                                <x-input-error :messages="$errors->get('vtramite')" class="mt-1" />
                            </div>

                            <div class="mb-5">
                                <label for="abono" class="block text-sm font-bold text-gray-700 mb-1">Abono Inicial Inmediato ($)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-emerald-500 sm:text-sm font-bold">$</span>
                                    </div>
                                    <input id="abono" type="number" step="0.01" name="abono" value="{{ old('abono', 0) }}" oninput="calcularSaldo()"
                                        onfocus="if(this.value=='0') this.value=''" onblur="if(this.value=='') this.value='0'"
                                        class="block w-full pl-7 rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-bold text-lg text-emerald-600">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    💡 Si el cliente pagará en ventanilla de caja, deja el abono en <strong>$0.00</strong>.
                                </p>
                                <x-input-error :messages="$errors->get('abono')" class="mt-1" />
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <label for="saldo" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Saldo a Cartera (Por Cobrar)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-orange-500 sm:text-sm font-bold">$</span>
                                    </div>
                                    <input id="saldo" type="text" name="saldo" value="0.00" readonly tabindex="-1"
                                        class="block w-full pl-7 rounded-lg border-0 bg-transparent text-xl font-black text-orange-500 focus:ring-0 px-0">
                                </div>
                            </div>

                            @if($cajaAbierta)
                                <!-- Métodos de Pago si tiene caja abierta -->
                                <div id="seccion_metodo_pago" class="mt-4 pt-4 border-t border-gray-200 space-y-3">
                                    <div class="flex items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 p-2 rounded-lg border border-emerald-200 mb-2">
                                        <span>🟢</span>
                                        <span>Caja <strong>#{{ $cajaAbierta->id }}</strong> activa para cobro</span>
                                    </div>

                                    <div>
                                        <label for="metodo_pago" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Método de Pago</label>
                                        <select name="metodo_pago" id="metodo_pago" onchange="toggleImpuestosPaymentFields()"
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
                            @else
                                <div class="mt-4 pt-3 border-t border-gray-200">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-800 leading-relaxed">
                                        <div class="font-bold flex items-center gap-1 mb-1">
                                            <span>ℹ️</span> Redacción sin Cobro en Caja
                                        </div>
                                        El trámite se registrará en la <strong>Cartera del Cliente</strong> y el cajero podrá cobrarlo en ventanilla.
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Observaciones -->
                        <div class="md:col-span-2 flex flex-col">
                            <label for="notas" class="block text-sm font-semibold text-gray-700 mb-2">Notas</label>
                            <textarea id="notas" name="notas" rows="6"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm bg-gray-50 focus:bg-white resize-none h-full"
                                placeholder="Anota cualquier instrucción especial o detalles de la declaración...">{{ old('notas') }}</textarea>
                            <x-input-error :messages="$errors->get('notas')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 gap-4 pb-12">
                    <a href="{{ route('clientes.tramites', $cliente->id_cliente) }}" class="text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-indigo-200">
                        Guardar Declaración
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcularSaldo() {
            let total = parseFloat(document.getElementById('vtramite').value) || 0;
            let abono = parseFloat(document.getElementById('abono').value) || 0;
            let saldo = total - abono;
            if (saldo < 0) saldo = 0;
            document.getElementById('saldo').value = saldo.toFixed(2);
        }

        function toggleImpuestosPaymentFields() {
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
            toggleImpuestosPaymentFields();
        });
    </script>
</x-app-layout>
