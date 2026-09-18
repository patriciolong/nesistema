<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Nuevo Trámite de Divorcio
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

            <form method="POST" action="{{ route('divorcios.store', $cliente->id_cliente) }}" class="space-y-8">
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
                                <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-1">Fecha del Trámite</label>
                                <input id="fecha" name="fecha" type="date" value="{{ old('fecha', date('Y-m-d')) }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                <x-input-error :messages="$errors->get('fecha')" class="mt-1" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: INFORMACIÓN DEL DIVORCIO -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-blue-50 border-b border-blue-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-blue-900 flex items-center gap-2">
                            <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                            Información del Divorcio
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Tipo de Divorcio -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Tipo de Divorcio <span class="text-red-500">*</span></label>
                                <div class="space-y-3">
                                    <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-4 transition-colors hover:bg-gray-50 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-200">
                                        <div class="flex items-center h-5">
                                            <input type="radio" name="tipo_divorcio" value="Controvertido" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500" required {{ old('tipo_divorcio') == 'Controvertido' ? 'checked' : '' }}>
                                        </div>
                                        <div class="flex flex-col">
                                            <strong class="font-medium text-gray-900 text-sm">Causal (Controvertido)</strong>
                                        </div>
                                    </label>
                                    <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-4 transition-colors hover:bg-gray-50 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-200">
                                        <div class="flex items-center h-5">
                                            <input type="radio" name="tipo_divorcio" value="Consensual" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500" required {{ old('tipo_divorcio') == 'Consensual' ? 'checked' : '' }}>
                                        </div>
                                        <div class="flex flex-col">
                                            <strong class="font-medium text-gray-900 text-sm">Consensual</strong>
                                        </div>
                                    </label>
                                    <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-4 transition-colors hover:bg-gray-50 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-200">
                                        <div class="flex items-center h-5">
                                            <input type="radio" name="tipo_divorcio" value="Notarial" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500" required {{ old('tipo_divorcio') == 'Notarial' ? 'checked' : '' }}>
                                        </div>
                                        <div class="flex flex-col">
                                            <strong class="font-medium text-gray-900 text-sm">Notarial</strong>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Separado -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">¿Está separado de su cónyuge? <span class="text-red-500">*</span></label>
                                    <div class="flex gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="esta_separado" value="1" class="text-blue-600 focus:ring-blue-500" {{ old('esta_separado') == '1' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Sí</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="esta_separado" value="0" class="text-blue-600 focus:ring-blue-500" {{ old('esta_separado', '0') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700 font-medium">No</span>
                                        </label>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="tiempo_separacion" class="block text-sm font-semibold text-gray-700 mb-1">Tiempo de Separación</label>
                                        <input id="tiempo_separacion" type="text" name="tiempo_separacion" value="{{ old('tiempo_separacion') }}"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors"
                                            placeholder="Ej: 2 años, 6 meses">
                                    </div>
                                </div>
                                
                                <!-- Hijos y Docs -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">¿Existen Hijos Menores de Edad? <span class="text-red-500">*</span></label>
                                    <div class="flex gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="hijos" value="1" class="text-blue-600 focus:ring-blue-500" {{ old('hijos') == '1' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Sí</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="hijos" value="0" class="text-blue-600 focus:ring-blue-500" {{ old('hijos', '0') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-700 font-medium">No</span>
                                        </label>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Documentos Físicos Entregados</label>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="posee_partida_matrimonio" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" {{ old('posee_partida_matrimonio') ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700 font-medium">Partida de Matrimonio</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" name="posee_partida_nacimiento_menores" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" {{ old('posee_partida_nacimiento_menores') ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700 font-medium">Partida de Nacimiento Hijos</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                            <div>
                                <label for="lugar_matrimonio" class="block text-sm font-semibold text-gray-700 mb-1">Lugar de Matrimonio</label>
                                <input id="lugar_matrimonio" type="text" name="lugar_matrimonio" value="{{ old('lugar_matrimonio') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                            <div>
                                <label for="fecha_matrimonio" class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Matrimonio</label>
                                <input id="fecha_matrimonio" type="date" name="fecha_matrimonio" value="{{ old('fecha_matrimonio') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                            <div class="md:col-span-2">
                                <label for="motivo" class="block text-sm font-semibold text-gray-700 mb-1">Motivo del Divorcio</label>
                                <textarea id="motivo" name="motivo" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors resize-none">{{ old('motivo') }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label for="con_quien_vive" class="block text-sm font-semibold text-gray-700 mb-1">Hijos a Cargo de:</label>
                                <textarea id="con_quien_vive" name="con_quien_vive" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors resize-none">{{ old('con_quien_vive') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: CÓNYUGE Y CONTACTOS -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-purple-50 border-b border-purple-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-purple-900 flex items-center gap-2">
                            <span class="bg-purple-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                            Información del Cónyuge y Contactos
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                            <label for="nombre_conyugue" class="block text-sm font-semibold text-gray-700 mb-1">Nombres del Cónyuge <span class="text-red-500">*</span></label>
                                <input id="nombre_conyugue" type="text" name="nombre_conyugue" value="{{ old('nombre_conyugue') }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                <x-input-error :messages="$errors->get('nombre_conyugue')" class="mt-1" />
                            </div>
                            <div>
                                <label for="identificacion_conyugue" class="block text-sm font-semibold text-gray-700 mb-1">Identificación del Cónyuge</label>
                                <input id="identificacion_conyugue" type="text" name="identificacion_conyugue" value="{{ old('identificacion_conyugue') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                            <div>
                                <label for="direccion_conyugue" class="block text-sm font-semibold text-gray-700 mb-1">Dirección</label>
                                <input id="direccion_conyugue" type="text" name="direccion_conyugue" value="{{ old('direccion_conyugue') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="apartamento_conyugue" class="block text-sm font-semibold text-gray-700 mb-1">Apartamento</label>
                                    <input id="apartamento_conyugue" type="text" name="apartamento_conyugue" value="{{ old('apartamento_conyugue') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                </div>
                                <div>
                                    <label for="ciudad_conyugue" class="block text-sm font-semibold text-gray-700 mb-1">Ciudad</label>
                                    <input id="ciudad_conyugue" type="text" name="ciudad_conyugue" value="{{ old('ciudad_conyugue') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="estado_conyugue" class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                                    <input id="estado_conyugue" type="text" name="estado_conyugue" value="{{ old('estado_conyugue') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                </div>
                                <div>
                                    <label for="postal_conyugue" class="block text-sm font-semibold text-gray-700 mb-1">C. Postal</label>
                                    <input id="postal_conyugue" type="text" name="postal_conyugue" value="{{ old('postal_conyugue') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>
                            <div>
                                <label for="telefono_conyugue" class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                                <input id="telefono_conyugue" type="text" name="telefono_conyugue" value="{{ old('telefono_conyugue') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                            <div>
                                <label for="contacto_ecuador" class="block text-sm font-semibold text-gray-700 mb-1">Contacto en Ecuador</label>
                                <input id="contacto_ecuador" type="text" name="contacto_ecuador" value="{{ old('contacto_ecuador') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
                            </div>
                            <div>
                                <label for="telefono_contacto_ecuador" class="block text-sm font-semibold text-gray-700 mb-1">Tel. Contacto Ecuador</label>
                                <input id="telefono_contacto_ecuador" type="text" name="telefono_contacto_ecuador" value="{{ old('telefono_contacto_ecuador') }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm bg-gray-50 focus:bg-white transition-colors">
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
                                <label for="honorarios" class="block text-sm font-bold text-gray-700 mb-1">Honorarios Totales ($) <span class="text-red-500">*</span></label>
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
                                        <select name="metodo_pago" id="metodo_pago" onchange="toggleDivorcioPaymentFields()"
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-xs bg-white">
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta">Tarjeta / POS</option>
                                            <option value="Transferencia">Transferencia Bancaria</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Zelle">Zelle</option>
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
                            <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">Observaciones Internas del Trámite</label>
                            <textarea id="observaciones" name="observaciones" rows="6"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm bg-gray-50 focus:bg-white resize-none h-full"
                                placeholder="Anota cualquier instrucción especial o detalles del servicio...">{{ old('observaciones') }}</textarea>
                            <x-input-error :messages="$errors->get('observaciones')" class="mt-1" />
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
            let total = parseFloat(document.getElementById('honorarios').value) || 0;
            let abono = parseFloat(document.getElementById('abono').value) || 0;
            let saldo = total - abono;
            if (saldo < 0) saldo = 0;
            document.getElementById('saldo').value = saldo.toFixed(2);
        }

        function toggleDivorcioPaymentFields() {
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
            } else if (metodo === 'Transferencia' || metodo === 'Cheque' || metodo === 'Zelle') {
                divBanco.classList.remove('hidden');
                divRef.classList.remove('hidden');
            }
        }
        
        // Initial calculation on load if values exist (e.g. on validation error back)
        document.addEventListener('DOMContentLoaded', () => {
            calcularSaldo();
            toggleDivorcioPaymentFields();
        });
    </script>
</x-app-layout>
