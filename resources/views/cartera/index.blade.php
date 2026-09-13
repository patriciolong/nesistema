<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-orange-50 text-orange-600 rounded-xl border border-orange-200 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Cartera de Clientes & Cuentas por Cobrar (Créditos)') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Control centralizado de créditos, trámites por cobrar y recaudación en ventanilla de caja</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if($tieneCajaAbierta && $cajaAbierta)
                    <div class="flex items-center gap-2 bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200 shadow-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs text-emerald-800 font-bold">Caja #{{ $cajaAbierta->id }} Activa</span>
                    </div>
                @else
                    <a href="{{ route('cajas.index') }}" class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-3.5 py-1.5 rounded-full shadow-xs transition-all">
                        <span>⚠️ Abrir Caja para Cobrar</span> &rarr;
                    </a>
                @endif
                <a href="{{ route('clientes.index') }}" class="px-3.5 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition-all">
                    &larr; Ir a Directorio de Clientes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Notificaciones de Sistema -->
            @if (session('success'))
                <div class="flex items-center p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-xs transition-all" role="alert">
                    <svg class="w-5 h-5 mr-3 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-2xl shadow-xs transition-all" role="alert">
                    <svg class="w-5 h-5 mr-3 shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Modal de Impresión de Recibo si aplica -->
            @if (session('imprimir_recibo'))
                <div x-data="{ open: true }" @keydown.window.escape="open = false" x-show="open" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
                    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="open = false"></div>
                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md p-6 border border-emerald-200">
                                <div class="text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 mb-3 ring-8 ring-emerald-50">
                                        <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-black text-slate-900">¡Cobro Registrado con Éxito!</h3>
                                    <p class="text-xs text-slate-500 mt-1">El ingreso se registró en tu sesión de caja y la deuda fue actualizada.</p>
                                </div>
                                <div class="mt-6 flex flex-col gap-2.5">
                                    <a href="{{ session('imprimir_recibo') }}" target="_blank" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-xs font-black text-white shadow-md shadow-emerald-200 hover:bg-emerald-700 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        <span>Imprimir Recibo Oficial PDF</span>
                                    </a>
                                    <button type="button" @click="open = false" class="w-full inline-flex justify-center rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-all">
                                        Continuar en Cartera
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 4 Tarjetas de Estadísticas Principales de Cartera -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- 1. Total Saldo en Cartera (Por Cobrar) -->
                <div class="bg-gradient-to-br from-rose-500 to-rose-600 p-5 rounded-3xl shadow-lg shadow-rose-200/50 text-white flex items-center justify-between relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-[11px] font-extrabold uppercase tracking-wider text-rose-100">Saldo por Cobrar (Créditos)</p>
                        <p class="text-3xl font-black text-white mt-1">${{ number_format($totalCarteraPorCobrar, 2) }}</p>
                        <p class="text-[11px] font-medium text-rose-100 mt-1 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-rose-200 animate-pulse"></span>
                            {{ number_format($clientesDeudoresCount) }} clientes con saldo
                        </p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-xs flex items-center justify-center text-white text-2xl shrink-0 border border-white/30">
                        💳
                    </div>
                </div>

                <!-- 2. Clientes Deudores Activos -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Clientes con Saldo Pendiente</p>
                        <p class="text-3xl font-black text-slate-900 mt-1">{{ number_format($clientesDeudoresCount) }}</p>
                        <p class="text-[11px] font-semibold text-amber-600 mt-1">Requieren gestión o cobro</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-2xl shrink-0">
                        👥
                    </div>
                </div>

                <!-- 3. Total Facturado Histórico -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Honorarios (Facturado)</p>
                        <p class="text-3xl font-black text-slate-900 mt-1">${{ number_format($totalDeudaHistorica, 2) }}</p>
                        <p class="text-[11px] font-semibold text-indigo-600 mt-1">Acumulado total de trámites</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-2xl shrink-0">
                        📊
                    </div>
                </div>

                <!-- 4. Total Recaudado / Cobrado -->
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Cobrado (Abonado)</p>
                        <p class="text-3xl font-black text-emerald-600 mt-1">${{ number_format($totalAbonado, 2) }}</p>
                        <p class="text-[11px] font-semibold text-emerald-600 mt-1">Ingresado en caja / bancos</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-2xl shrink-0">
                        💵
                    </div>
                </div>

            </div>

            <!-- Barra de Búsqueda y Filtros de Cartera -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-xs space-y-4">
                <form method="GET" action="{{ route('cartera.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    
                    <!-- Buscador -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Buscar Cliente</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, apellido, cédula, teléfono..." class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-800">
                        </div>
                    </div>

                    <!-- Filtro Estado Deuda -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Filtro de Cartera</label>
                        <select name="filtro_estado" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-bold text-slate-800 focus:bg-white">
                            <option value="deudores" {{ request('filtro_estado', 'deudores') === 'deudores' ? 'selected' : '' }}>🔴 Solo con Deuda Pendiente</option>
                            <option value="al_dia" {{ request('filtro_estado') === 'al_dia' ? 'selected' : '' }}>🟢 Clientes al Día ($0.00)</option>
                            <option value="todos" {{ request('filtro_estado') === 'todos' ? 'selected' : '' }}>👥 Todos los Clientes</option>
                        </select>
                    </div>

                    <!-- Filtro Oficina -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Oficina de Registro</label>
                        <select name="oficina" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-medium text-slate-800 focus:bg-white">
                            <option value="">Todas las Oficinas</option>
                            @foreach($oficinas as $ofi)
                                <option value="{{ $ofi }}" {{ request('oficina') == $ofi ? 'selected' : '' }}>{{ $ofi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Orden y Botón Filtrar -->
                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Ordenar</label>
                            <select name="orden" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 font-medium text-slate-800 focus:bg-white">
                                <option value="mayor_saldo" {{ request('orden', 'mayor_saldo') === 'mayor_saldo' ? 'selected' : '' }}>Mayor Saldo</option>
                                <option value="menor_saldo" {{ request('orden') === 'menor_saldo' ? 'selected' : '' }}>Menor Saldo</option>
                                <option value="nombre" {{ request('orden') === 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all h-[38px] flex items-center justify-center shrink-0">
                            Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'oficina', 'filtro_estado', 'orden']))
                            <a href="{{ route('cartera.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all h-[38px] flex items-center justify-center shrink-0" title="Limpiar Filtros">
                                ✕
                            </a>
                        @endif
                    </div>

                </form>
            </div>

            <!-- Tabla de Clientes en Cartera -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                        Clientes y Estados de Cuenta
                    </h3>
                    <span class="text-xs font-bold text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-2xs">
                        Mostrando {{ $clientes->count() }} de {{ $clientes->total() }} clientes
                    </span>
                </div>

                @if($clientes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-700">
                            <thead class="bg-slate-50 uppercase text-[11px] font-bold text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="py-3.5 px-6">Cliente</th>
                                    <th class="py-3.5 px-6">Contacto / Oficina</th>
                                    <th class="py-3.5 px-6 text-right">Total Facturado</th>
                                    <th class="py-3.5 px-6 text-right">Total Abonado</th>
                                    <th class="py-3.5 px-6 text-right">Saldo Pendiente (Crédito)</th>
                                    <th class="py-3.5 px-6 text-center">Estado</th>
                                    <th class="py-3.5 px-6 text-center">Acciones de Cobro</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($clientes as $c)
                                    <tr class="hover:bg-slate-50/80 transition-colors {{ $c->c_saldo > 0 ? 'bg-rose-50/20' : '' }}">
                                        
                                        <!-- Cliente -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-sm shrink-0 {{ $c->c_saldo > 0 ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                                                    {{ strtoupper(substr($c->c_nombre, 0, 1)) }}{{ strtoupper(substr($c->c_apellido, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('clientes.tramites', $c->id_cliente) }}" class="font-extrabold text-slate-900 hover:text-indigo-600 text-sm transition-colors block">
                                                        {{ $c->c_nombre }} {{ $c->c_apellido }}
                                                    </a>
                                                    <span class="text-[11px] font-semibold text-slate-500">ID / C.I.: {{ $c->c_identificacion ?: 'Sin C.I.' }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Contacto y Oficina -->
                                        <td class="py-4 px-6">
                                            <div class="space-y-1">
                                                @if($c->c_telefono)
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c->c_telefono) }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-slate-700 hover:text-emerald-600 transition-colors">
                                                        <span>📱 {{ $c->c_telefono }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-slate-400">Sin teléfono</span>
                                                @endif
                                                <div>
                                                    @if($c->c_oficina_registro)
                                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                            {{ $c->c_oficina_registro }}
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] text-slate-400">Oficina General</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Facturado -->
                                        <td class="py-4 px-6 text-right font-bold text-slate-700">
                                            ${{ number_format($c->c_deuda, 2) }}
                                        </td>

                                        <!-- Abonado -->
                                        <td class="py-4 px-6 text-right font-bold text-emerald-600">
                                            ${{ number_format($c->c_abonado, 2) }}
                                        </td>

                                        <!-- Saldo Pendiente -->
                                        <td class="py-4 px-6 text-right">
                                            @if($c->c_saldo > 0)
                                                <span class="inline-block px-3 py-1 rounded-xl text-xs font-black bg-rose-100 text-rose-700 border border-rose-200 shadow-2xs">
                                                    ${{ number_format($c->c_saldo, 2) }}
                                                </span>
                                            @else
                                                <span class="inline-block px-3 py-1 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    $0.00
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Estado -->
                                        <td class="py-4 px-6 text-center">
                                            @if($c->c_saldo > 0)
                                                <span class="inline-flex items-center gap-1 text-[11px] font-extrabold px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                                    A Crédito
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[11px] font-extrabold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    ✓ Al Día
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Acciones -->
                                        <td class="py-4 px-6 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if($c->c_saldo > 0)
                                                    <button type="button" onclick="cargarDetalleCliente({{ $c->id_cliente }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-xs shadow-sm shadow-emerald-200 transition-all cursor-pointer">
                                                        <span>⚡ Ver Deuda & Cobrar</span>
                                                    </button>
                                                @else
                                                    <button type="button" onclick="cargarDetalleCliente({{ $c->id_cliente }})" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors cursor-pointer">
                                                        <span>🔍 Consultar</span>
                                                    </button>
                                                @endif

                                                <a href="{{ route('clientes.tramites', $c->id_cliente) }}" class="p-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 transition-colors" title="Ver Hub de Trámites">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                                </a>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                        {{ $clientes->links() }}
                    </div>
                @else
                    <div class="text-center py-16 px-4">
                        <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 border border-emerald-100 shadow-xs">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-800">¡Cartera Completamente al Día!</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">No hay registros de clientes con deudas pendientes bajo los filtros aplicados.</p>
                        <a href="{{ route('cartera.index', ['filtro_estado' => 'todos']) }}" class="inline-block mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                            Ver Todos los Clientes
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL SLIDE-OVER: GESTIÓN DE CARTERA, DETALLE DE TRÁMITES Y COBRO EN CAJA -->
    <div id="modalGestionCartera" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-cartera-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="cerrarModalCartera()"></div>

            <div class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200">
                
                <!-- Loading State -->
                <div id="carteraLoadingState" class="p-12 text-center">
                    <div class="inline-block w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-xs font-bold text-slate-600 mt-3">Consultando trámites y estado de deuda...</p>
                </div>

                <!-- Cartera Content Form -->
                <form method="POST" action="{{ route('cartera.cobrar') }}" id="formCarteraCobro" class="hidden">
                    @csrf
                    <input type="hidden" name="cliente_id" id="cartera_cliente_id" value="">
                    <input type="hidden" name="modo_cobro" id="cartera_modo_cobro" value="individual">
                    <input type="hidden" name="tramite_tipo" id="cartera_tramite_tipo" value="">
                    <input type="hidden" name="tramite_id" id="cartera_tramite_id" value="">

                    <!-- Header Modal -->
                    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 px-6 py-5 text-white flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black tracking-tight" id="modal-cartera-title">
                                    Estado de Cartera & Cobro
                                </h3>
                                <p class="text-xs text-indigo-200 font-medium" id="modal_cliente_nombre">Cargando...</p>
                            </div>
                        </div>
                        <button type="button" onclick="cerrarModalCartera()" class="text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-xl transition-all cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                        
                        <!-- Caja Status Badge -->
                        @if($cajaAbierta)
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-emerald-50 border border-emerald-200">
                                <div class="flex items-center gap-2 text-xs font-bold text-emerald-800">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Caja Activa #{{ $cajaAbierta->id }} para Recaudación</span>
                                </div>
                                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">
                                    Cajero: {{ Auth::user()->name }}
                                </span>
                            </div>
                        @else
                            <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-800 font-semibold flex items-center gap-2">
                                <span>⚠️</span>
                                <span>No tienes una caja abierta. Podrás ver los trámites, pero para registrar cobros requieres abrir caja.</span>
                            </div>
                        @endif

                        <!-- Resumen Financiero del Cliente -->
                        <div class="grid grid-cols-3 gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 block">Total Facturado</span>
                                <span class="text-sm font-black text-slate-800" id="modal_cliente_deuda">$0.00</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-600 block">Total Abonado</span>
                                <span class="text-sm font-black text-emerald-600" id="modal_cliente_abonado">$0.00</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-600 block">Saldo Pendiente</span>
                                <span class="text-lg font-black text-rose-600" id="modal_cliente_saldo">$0.00</span>
                            </div>
                        </div>

                        <!-- Sección: Listado de Trámites con Deuda -->
                        <div>
                            <div class="flex justify-between items-center mb-2.5">
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <span>📑</span> Trámites a Crédito / Pendientes de Pago
                                </h4>
                                <span class="text-[11px] font-bold text-slate-500" id="modal_count_tramites">0 trámites</span>
                            </div>

                            <div id="listaTramitesContainer" class="space-y-2.5">
                                <!-- Rellenado dinámicamente con JS -->
                            </div>
                        </div>

                        <!-- Panel de Configuración del Cobro -->
                        <div id="panelFormularioPago" class="p-5 bg-gradient-to-br from-indigo-50/50 to-slate-50 rounded-2xl border border-indigo-100 space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-indigo-100">
                                <div>
                                    <h5 class="text-xs font-black text-indigo-950 uppercase tracking-wider" id="label_destino_pago">Cobro de Trámite</h5>
                                    <p class="text-[11px] text-slate-500 font-medium" id="desc_destino_pago">Selecciona un trámite o la deuda completa</p>
                                </div>
                                <button type="button" onclick="seleccionarCobroTotal()" class="text-xs font-bold text-indigo-700 hover:text-indigo-900 bg-indigo-100/80 hover:bg-indigo-200 px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                                    Cancelar Toda la Deuda
                                </button>
                            </div>

                            <!-- Monto a Cobrar -->
                            <div>
                                <div class="flex justify-between items-center mb-1.5">
                                    <label for="cartera_monto_pago" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Monto a Cobrar ($) <span class="text-red-500">*</span></label>
                                    <div class="flex gap-1.5">
                                        <button type="button" onclick="ajustarMontoPorcentaje(1)" class="text-[10px] font-bold text-slate-600 bg-white border border-slate-200 px-2 py-0.5 rounded-lg hover:bg-slate-100">100%</button>
                                        <button type="button" onclick="ajustarMontoPorcentaje(0.5)" class="text-[10px] font-bold text-slate-600 bg-white border border-slate-200 px-2 py-0.5 rounded-lg hover:bg-slate-100">50%</button>
                                    </div>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">$</div>
                                    <input type="number" step="0.01" min="0.01" name="monto_pago" id="cartera_monto_pago" required
                                           class="w-full pl-8 pr-4 py-3 text-lg font-black text-emerald-700 bg-white border border-slate-300 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                                </div>
                            </div>

                            <!-- Método de Pago -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="cartera_metodo_pago" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Método de Pago</label>
                                    <select name="metodo_pago" id="cartera_metodo_pago" onchange="toggleCarteraPaymentFields()"
                                            class="w-full text-xs bg-white border border-slate-300 rounded-xl px-3 py-2.5 font-bold text-slate-800 focus:border-emerald-500">
                                        <option value="Efectivo">💵 Efectivo</option>
                                        <option value="Tarjeta">💳 Tarjeta (POS / Débito / Crédito)</option>
                                        <option value="Transferencia">🏦 Transferencia Bancaria</option>
                                        <option value="Cheque">📜 Cheque</option>
                                    </select>
                                </div>

                                <!-- Selector de Tarjeta (Condicional) -->
                                <div id="cartera_campo_tarjeta" class="hidden">
                                    <label for="cartera_tarjeta_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tarjeta / Terminal POS</label>
                                    <select name="tarjeta_id" id="cartera_tarjeta_id" class="w-full text-xs bg-white border border-slate-300 rounded-xl px-3 py-2.5 font-medium text-slate-800">
                                        <option value="">Seleccione Tarjeta / POS...</option>
                                        @foreach($tarjetas as $tar)
                                            <option value="{{ $tar->id }}">{{ $tar->nombre }} ({{ $tar->franquicia }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Selector de Banco (Condicional) -->
                                <div id="cartera_campo_banco" class="hidden">
                                    <label for="cartera_banco_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Banco Destino</label>
                                    <select name="banco_id" id="cartera_banco_id" class="w-full text-xs bg-white border border-slate-300 rounded-xl px-3 py-2.5 font-medium text-slate-800">
                                        <option value="">Seleccione Banco...</option>
                                        @foreach($bancos as $ban)
                                            <option value="{{ $ban->id }}">{{ $ban->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Nro de Referencia -->
                            <div id="cartera_campo_referencia" class="hidden">
                                <label for="cartera_numero_referencia" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">N° Voucher / Cheque / Autorización</label>
                                <input type="text" name="numero_referencia" id="cartera_numero_referencia" placeholder="Ej: Voucher #12345 / Lote #09"
                                       class="w-full text-xs bg-white border border-slate-300 rounded-xl px-3 py-2.5 font-medium text-slate-800">
                            </div>

                        </div>

                    </div>

                    <!-- Footer Modal -->
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3">
                        <button type="button" onclick="cerrarModalCartera()" class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                            Cerrar
                        </button>
                        @if($tieneCajaAbierta)
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-xs font-black shadow-md shadow-emerald-200 transition-all cursor-pointer">
                                <span>💵 Procesar Cobro e Imprimir Recibo</span>
                            </button>
                        @else
                            <a href="{{ route('cajas.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-black shadow-md transition-all">
                                <span>⚠️ Abrir Caja para Cobrar</span>
                            </a>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        let currentClienteData = null;
        let selectedTramiteSaldoMax = 0;

        async function cargarDetalleCliente(clienteId) {
            const modal = document.getElementById('modalGestionCartera');
            const loading = document.getElementById('carteraLoadingState');
            const form = document.getElementById('formCarteraCobro');
            
            modal.classList.remove('hidden');
            loading.classList.remove('hidden');
            form.classList.add('hidden');

            try {
                const response = await fetch(`/cartera/cliente/${clienteId}/detalle`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    currentClienteData = data;
                    renderizarDetalleCliente(data);
                    loading.classList.add('hidden');
                    form.classList.remove('hidden');
                } else {
                    alert('No se pudo cargar el detalle de cartera del cliente.');
                    cerrarModalCartera();
                }
            } catch (e) {
                console.error('Error al cargar detalle de cartera:', e);
                alert('Error al consultar el servidor.');
                cerrarModalCartera();
            }
        }

        function renderizarDetalleCliente(data) {
            const cli = data.cliente;
            document.getElementById('cartera_cliente_id').value = cli.id_cliente;
            document.getElementById('modal_cliente_nombre').innerText = `${cli.nombre_completo} (${cli.identificacion || 'Sin C.I.'})`;
            document.getElementById('modal_cliente_deuda').innerText = `$${parseFloat(cli.deuda).toFixed(2)}`;
            document.getElementById('modal_cliente_abonado').innerText = `$${parseFloat(cli.abonado).toFixed(2)}`;
            document.getElementById('modal_cliente_saldo').innerText = `$${parseFloat(cli.saldo).toFixed(2)}`;
            document.getElementById('modal_count_tramites').innerText = `${data.tramites.length} trámites con deuda`;

            const container = document.getElementById('listaTramitesContainer');
            container.innerHTML = '';

            if (data.tramites.length === 0) {
                container.innerHTML = `
                    <div class="p-6 text-center bg-white rounded-2xl border border-slate-200 text-slate-500">
                        <span class="text-2xl block mb-1">🎉</span>
                        <p class="text-xs font-bold">Este cliente no tiene trámites individuales pendientes de pago.</p>
                    </div>
                `;
                seleccionarCobroTotal();
                return;
            }

            data.tramites.forEach((t, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-indigo-300 hover:shadow-xs transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3';
                
                itemDiv.innerHTML = `
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black border ${t.color_badge}">
                                ${t.icon} ${t.tipo_label} #${t.id}
                            </span>
                            <span class="text-[11px] font-semibold text-slate-400">• ${t.fecha}</span>
                        </div>
                        <p class="text-xs font-bold text-slate-800 truncate">${t.descripcion}</p>
                        <div class="flex items-center gap-3 text-[11px] mt-1">
                            <span class="text-slate-500 font-medium">Costo: <strong>$${t.costo.toFixed(2)}</strong></span>
                            <span class="text-emerald-600 font-medium">Abonado: <strong>$${t.abono.toFixed(2)}</strong></span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Saldo Pendiente</span>
                            <span class="text-sm font-black text-rose-600">$${t.saldo.toFixed(2)}</span>
                        </div>
                        <button type="button" onclick="seleccionarTramiteIndividual('${t.tipo}', ${t.id}, '${t.tipo_label} #${t.id}', ${t.saldo})" class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white font-extrabold text-xs transition-all border border-indigo-200 cursor-pointer">
                            Cobrar este &rarr;
                        </button>
                    </div>
                `;
                container.appendChild(itemDiv);
            });

            // Por defecto seleccionar el primer trámite o la deuda total
            if (data.tramites.length > 0) {
                const first = data.tramites[0];
                seleccionarTramiteIndividual(first.tipo, first.id, `${first.tipo_label} #${first.id}`, first.saldo);
            } else {
                seleccionarCobroTotal();
            }
        }

        function seleccionarTramiteIndividual(tipo, id, label, saldo) {
            document.getElementById('cartera_modo_cobro').value = 'individual';
            document.getElementById('cartera_tramite_tipo').value = tipo;
            document.getElementById('cartera_tramite_id').value = id;
            document.getElementById('label_destino_pago').innerText = `Cobrar: ${label}`;
            document.getElementById('desc_destino_pago').innerText = `Saldo pendiente de este trámite: $${saldo.toFixed(2)}`;
            
            selectedTramiteSaldoMax = parseFloat(saldo);
            const inputMonto = document.getElementById('cartera_monto_pago');
            inputMonto.value = selectedTramiteSaldoMax.toFixed(2);
            inputMonto.max = selectedTramiteSaldoMax.toFixed(2);
        }

        function seleccionarCobroTotal() {
            if (!currentClienteData) return;
            const totalSaldo = currentClienteData.cliente.saldo;
            document.getElementById('cartera_modo_cobro').value = 'total';
            document.getElementById('cartera_tramite_tipo').value = '';
            document.getElementById('cartera_tramite_id').value = '';
            document.getElementById('label_destino_pago').innerText = 'Cancelar / Abonar a Deuda Total del Cliente';
            document.getElementById('desc_destino_pago').innerText = `Se aplicará a la deuda global de $${totalSaldo.toFixed(2)}`;

            selectedTramiteSaldoMax = parseFloat(totalSaldo);
            const inputMonto = document.getElementById('cartera_monto_pago');
            inputMonto.value = selectedTramiteSaldoMax.toFixed(2);
            inputMonto.max = selectedTramiteSaldoMax.toFixed(2);
        }

        function ajustarMontoPorcentaje(factor) {
            const monto = selectedTramiteSaldoMax * factor;
            document.getElementById('cartera_monto_pago').value = monto.toFixed(2);
        }

        function toggleCarteraPaymentFields() {
            const metodo = document.getElementById('cartera_metodo_pago').value;
            const campoTarjeta = document.getElementById('cartera_campo_tarjeta');
            const campoBanco = document.getElementById('cartera_campo_banco');
            const campoRef = document.getElementById('cartera_campo_referencia');

            campoTarjeta.classList.add('hidden');
            campoBanco.classList.add('hidden');
            campoRef.classList.add('hidden');

            if (metodo === 'Tarjeta') {
                campoTarjeta.classList.remove('hidden');
                campoRef.classList.remove('hidden');
            } else if (metodo === 'Transferencia' || metodo === 'Cheque') {
                campoBanco.classList.remove('hidden');
                campoRef.classList.remove('hidden');
            }
        }

        function cerrarModalCartera() {
            document.getElementById('modalGestionCartera').classList.add('hidden');
        }
    </script>
</x-app-layout>
