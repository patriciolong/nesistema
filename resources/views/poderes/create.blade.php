<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Nuevo Trámite de Poder
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Cliente: <span class="font-semibold text-purple-600">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</span>
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

            <form method="POST" action="{{ route('poderes.store', $cliente->id_cliente) }}" class="space-y-8">
                @csrf
                <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">

                <!-- STEP 1: INFORMACIÓN DE FIRMA -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-purple-50 border-b border-purple-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-purple-900 flex items-center gap-2">
                            <span class="bg-purple-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                            Información de Firma (Otorgantes)
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Oficina Responsable</label>
                                <input type="text" class="block w-full rounded-lg border-gray-300 shadow-sm sm:text-sm bg-gray-100 text-gray-600 cursor-not-allowed" value="{{ Auth::user()->office }}" readonly>
                            </div>

                            <div class="md:col-span-1">
                                <label for="ofifirmar" class="block text-sm font-semibold text-gray-700 mb-1">Firmar en <span class="text-red-500">*</span></label>
                                <select id="ofifirmar" name="ofifirmar" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                    <option value="Brooklyn" {{ old('ofifirmar') == 'Brooklyn' ? 'selected' : '' }}>Brooklyn</option>
                                    <option value="Spring Valley" {{ old('ofifirmar') == 'Spring Valley' ? 'selected' : '' }}>Spring Valley</option>
                                    <option value="New Jersey" {{ old('ofifirmar') == 'New Jersey' ? 'selected' : '' }}>New Jersey</option>
                                    <option value="Ossining" {{ old('ofifirmar') == 'Ossining' ? 'selected' : '' }}>Ossining</option>
                                    <option value="En Domicilio" {{ old('ofifirmar') == 'En Domicilio' ? 'selected' : '' }}>En su domicilio</option>
                                </select>
                                <x-input-error :messages="$errors->get('ofifirmar')" class="mt-1" />
                            </div>

                            <div class="md:col-span-1">
                                <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-1">Fecha del Trámite</label>
                                <input id="fecha" name="fecha" type="date" value="{{ old('fecha', date('Y-m-d')) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                <x-input-error :messages="$errors->get('fecha')" class="mt-1" />
                            </div>

                            <div class="md:col-span-3">
                                <label for="estcivil" class="block text-sm font-semibold text-gray-700 mb-1">Estado Civil del Otorgante <span class="text-red-500">*</span></label>
                                <select class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors" name="estcivil" id="estcivil" required>
                                    <option value="" disabled selected>Elegir</option>
                                    <option value="Soltero(a)" {{ old('estcivil') == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                                    <option value="Casado(a)" {{ old('estcivil') == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                                    <option value="Divorciado(a)" {{ old('estcivil') == 'Divorciado(a)' ? 'selected' : '' }}>Divorciado(a)</option>
                                    <option value="Viudo(a)" {{ old('estcivil') == 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
                                    <option value="Union de Hecho" {{ old('estcivil') == 'Union de Hecho' ? 'selected' : '' }}>Unión de Hecho</option>
                                    <option value="Casados entre si" {{ old('estcivil') == 'Casados entre si' ? 'selected' : '' }}>Casados entre sí</option>
                                    <option value="Casado con disolución conyugal" {{ old('estcivil') == 'Casado con disolución conyugal' ? 'selected' : '' }}>Casado con disolución conyugal</option>
                                </select>
                            </div>
                        </div>

                        <!-- Segunda Persona que Otorga -->
                        <div class="pt-6 border-t border-gray-100">
                            <h4 class="text-md font-bold text-gray-700 mb-4">2da Persona que Otorga el Poder (Opcional)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="identificacion2" class="block text-sm font-semibold text-gray-700 mb-1">Identificación</label>
                                    <input id="identificacion2" name="identificacion2" type="text" value="{{ old('identificacion2') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                </div>
                                <div>
                                    <label for="nombre2" class="block text-sm font-semibold text-gray-700 mb-1">Nombres</label>
                                    <input id="nombre2" name="nombre2" type="text" value="{{ old('nombre2') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                </div>
                                <div>
                                    <label for="apellido2" class="block text-sm font-semibold text-gray-700 mb-1">Apellidos</label>
                                    <input id="apellido2" name="apellido2" type="text" value="{{ old('apellido2') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                </div>
                                <div>
                                    <label for="telefono2" class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                                    <input id="telefono2" name="telefono2" type="text" value="{{ old('telefono2') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: A FAVOR DE -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-fuchsia-50 border-b border-fuchsia-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-fuchsia-900 flex items-center gap-2">
                            <span class="bg-fuchsia-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                            Persona(s) a favor de quien se otorga el poder
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="md:col-span-2">
                                <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">1ra Persona <span class="text-red-500">*</span></h4>
                            </div>
                            <div>
                                <label for="nombres_otorga" class="block text-sm font-semibold text-gray-700 mb-1">Nombres y Apellidos Completos</label>
                                <input id="nombres_otorga" type="text" name="nombres_otorga" value="{{ old('nombres_otorga') }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                            <div>
                                <label for="cedula_otorga" class="block text-sm font-semibold text-gray-700 mb-1">Número de Cédula</label>
                                <input id="cedula_otorga" type="text" name="cedula_otorga" value="{{ old('cedula_otorga') }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                            <div class="md:col-span-2">
                                <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">2da Persona (Opcional)</h4>
                            </div>
                            <div>
                                <label for="nombres_otorga2" class="block text-sm font-semibold text-gray-700 mb-1">Nombres y Apellidos Completos</label>
                                <input id="nombres_otorga2" type="text" name="nombres_otorga2" value="{{ old('nombres_otorga2') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                            <div>
                                <label for="cedula_otorga2" class="block text-sm font-semibold text-gray-700 mb-1">Número de Cédula</label>
                                <input id="cedula_otorga2" type="text" name="cedula_otorga2" value="{{ old('cedula_otorga2') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-fuchsia-500 focus:ring-fuchsia-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: RAZON Y ENVIO -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-blue-50 border-b border-blue-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-blue-900 flex items-center gap-2">
                            <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                            Razón del Poder y Envío
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-8">
                            <label for="razon_poder" class="block text-sm font-semibold text-gray-700 mb-2">Razón del Poder <span class="text-red-500">*</span></label>
                            <textarea id="razon_poder" name="razon_poder" rows="3" required
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white resize-none"
                                placeholder="Escriba la razón o mandato específico...">{{ old('razon_poder') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                            <div class="md:col-span-2">
                                <label for="opcion_envio_poder" class="block text-sm font-semibold text-gray-700 mb-1">Método de Envío <span class="text-red-500">*</span></label>
                                <select class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors" name="opcion_envio_poder" id="opcion_envio_poder" required>
                                    <option value="" disabled selected>Elegir</option>
                                    <option value="Original al Ecuador" {{ old('opcion_envio_poder') == 'Original al Ecuador' ? 'selected' : '' }}>Original al Ecuador</option>
                                    <option value="Scan-Mail-Whatsapp" {{ old('opcion_envio_poder') == 'Scan-Mail-Whatsapp' ? 'selected' : '' }}>Scan-Mail-Whatsapp</option>
                                    <option value="Envio a domicilio USA" {{ old('opcion_envio_poder') == 'Envio a domicilio USA' ? 'selected' : '' }}>Envio a domicilio USA</option> 
                                    <option value="Retiro en oficina" {{ old('opcion_envio_poder') == 'Retiro en oficina' ? 'selected' : '' }}>Retiro en oficina</option>   
                                </select>
                            </div>

                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-xl border border-gray-200 mt-2">
                                <h4 class="text-sm font-bold text-gray-700 mb-4">Información de Envío a Ecuador (Si aplica)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label for="remitente" class="block text-sm font-semibold text-gray-700 mb-1">Enviar a nombre de:</label>
                                        <input id="remitente" type="text" name="remitente" value="{{ old('remitente') }}"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white transition-colors">
                                    </div>
                                    <div>
                                        <label for="ciudad_r" class="block text-sm font-semibold text-gray-700 mb-1">Ciudad</label>
                                        <input id="ciudad_r" type="text" name="ciudad_r" value="{{ old('ciudad_r') }}"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white transition-colors">
                                    </div>
                                    <div>
                                        <label for="provincia_r" class="block text-sm font-semibold text-gray-700 mb-1">Provincia</label>
                                        <input id="provincia_r" type="text" name="provincia_r" value="{{ old('provincia_r') }}"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white transition-colors">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="telefono_r" class="block text-sm font-semibold text-gray-700 mb-1">Teléfonos (011593)</label>
                                        <input id="telefono_r" type="text" name="telefono_r" value="{{ old('telefono_r') }}"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: COBRO -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-emerald-50 border-b border-emerald-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-emerald-900 flex items-center gap-2">
                            <span class="bg-emerald-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">4</span>
                            Cobro y Notas Adicionales
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Financial Box -->
                        <div class="md:col-span-1 bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <div class="mb-5">
                                <label for="honorarios" class="block text-sm font-bold text-gray-700 mb-1">Valor del Trámite ($) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input id="honorarios" type="number" step="0.01" name="honorarios" value="{{ old('honorarios', 0) }}" required oninput="calcularSaldo()"
                                        onfocus="if(this.value=='0') this.value=''" onblur="if(this.value=='') this.value='0'"
                                        class="block w-full pl-7 rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 font-bold text-lg text-gray-900">
                                </div>
                                <x-input-error :messages="$errors->get('honorarios')" class="mt-1" />
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
                                        <select name="metodo_pago" id="metodo_pago" onchange="togglePoderesPaymentFields()"
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
                            <label for="observaciones_p" class="block text-sm font-semibold text-gray-700 mb-2">Observaciones</label>
                            <textarea id="observaciones_p" name="observaciones_p" rows="6"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm bg-gray-50 focus:bg-white resize-none h-full"
                                placeholder="Anota cualquier instrucción especial o detalles...">{{ old('observaciones_p') }}</textarea>
                            <x-input-error :messages="$errors->get('observaciones_p')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 gap-4 pb-12">
                    <a href="{{ route('clientes.tramites', $cliente->id_cliente) }}" class="text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-indigo-200">
                        Guardar Trámite de Poder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcularSaldo() {
            let total = parseFloat(document.getElementById('honorarios').value) || 0;
            let abono = parseFloat(document.getElementById('abono').value) || 0;
            let saldo = total - abono;
            if (saldo < 0) saldo = 0;
            document.getElementById('saldo').value = saldo.toFixed(2);
        }

        function togglePoderesPaymentFields() {
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
            togglePoderesPaymentFields();
        });
    </script>
</x-app-layout>
