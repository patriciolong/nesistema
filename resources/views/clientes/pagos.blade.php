<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Historial de Pagos y Abonos') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Cliente: <span class="font-semibold text-slate-700">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</span> (ID: <span class="font-mono text-indigo-600 font-bold">{{ $cliente->c_identificacion }}</span>)
                    </p>
                </div>
            </div>

            <a href="{{ route('clientes.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-sm transition-all">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Clientes
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Resumen Financiero del Cliente -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Facturado</p>
                        <h3 class="text-2xl font-black text-slate-800 mt-1">${{ number_format($cliente->c_deuda, 2) }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Deuda inicial acumulada</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Abonado</p>
                        <h3 class="text-2xl font-black text-emerald-600 mt-1">${{ number_format($cliente->c_abonado, 2) }}</h3>
                        <p class="text-xs text-emerald-600 font-semibold mt-0.5">Pagos confirmados</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Saldo Pendiente</p>
                        <h3 class="text-2xl font-black text-amber-600 mt-1">${{ number_format($cliente->c_saldo, 2) }}</h3>
                        <p class="text-xs text-amber-600 font-semibold mt-0.5">Por cobrar</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabla de Historial -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Transacciones y Recibos</h3>
                            <p class="text-xs text-slate-400">Registro detallado de todos los pagos registrados para este cliente</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-sm text-left text-slate-600 divide-y divide-slate-200">
                            <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold tracking-wider">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4">Fecha y Hora</th>
                                    <th scope="col" class="py-3.5 px-4">Concepto</th>
                                    <th scope="col" class="py-3.5 px-4">Método de Pago</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">Monto</th>
                                    <th scope="col" class="py-3.5 px-4">Atendido Por / Oficina</th>
                                    <th scope="col" class="py-3.5 px-4 text-center">Comprobante</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($pagos as $pago)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3.5 px-4 whitespace-nowrap font-medium text-slate-800">
                                            {{ $pago->created_at->format('d/m/Y h:i A') }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                                {{ $pago->concepto ?? 'Abono a deuda' }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <div class="text-xs">
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                                    {{ $pago->metodo_pago === 'Efectivo' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                                    {{ $pago->metodo_pago === 'Tarjeta' ? 'bg-pink-50 text-pink-700 border border-pink-200' : '' }}
                                                    {{ $pago->metodo_pago === 'Transferencia' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                                    {{ $pago->metodo_pago === 'Cheque' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                                    {{ !$pago->metodo_pago || $pago->metodo_pago === 'Crédito' ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}">
                                                    {{ $pago->metodo_pago ?? 'Efectivo' }}
                                                </span>
                                                @if($pago->banco || $pago->tarjeta || $pago->numero_referencia)
                                                    <p class="text-[11px] text-slate-500 mt-1">
                                                        {{ $pago->banco->nombre ?? '' }} {{ $pago->tarjeta->nombre ?? '' }}
                                                        @if($pago->numero_referencia) <span class="font-mono text-indigo-600 font-bold">({{ $pago->numero_referencia }})</span> @endif
                                                    </p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                            <span class="text-sm font-black text-emerald-600">
                                                +${{ number_format($pago->monto, 2) }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <div class="text-xs">
                                                <p class="font-bold text-slate-700">{{ $pago->usuario }}</p>
                                                <p class="text-slate-400 font-medium">Oficina: {{ $pago->oficina }}</p>
                                                @if($pago->caja_sesion_id)
                                                    <p class="text-[10px] text-indigo-600 font-bold">Caja #{{ $pago->caja_sesion_id }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <a href="{{ route('clientes.recibo_abono', ['cliente' => $cliente->id_cliente, 'monto' => $pago->monto]) }}" 
                                               target="_blank" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 hover:border-indigo-600 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                                Imprimir Recibo PDF
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p class="text-sm font-semibold">Este cliente no tiene pagos registrados aún.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
