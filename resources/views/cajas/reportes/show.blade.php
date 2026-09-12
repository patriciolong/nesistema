<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-sky-50 text-sky-600 rounded-xl border border-sky-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Auditoría Detallada de Caja') }} #{{ $caja->id }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Cajero: <strong class="text-slate-700">{{ $caja->user->name ?? 'N/A' }}</strong> • Oficina: <strong class="text-slate-700">{{ $caja->oficina }}</strong>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('reportes.cajas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-sm transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Reportes
                </a>
                <a href="{{ route('cajas.acta_pdf', $caja->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Imprimir Acta PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Ficha Resumen de la Sesión -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-xl font-black text-slate-800">Resumen Financiero del Turno</h3>
                        <p class="text-xs text-slate-400">
                            Apertura: {{ $caja->fecha_apertura->format('d/m/Y h:i A') }} • Cierre: {{ $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y h:i A') : 'En curso' }}
                        </p>
                    </div>
                    <div>
                        @if($caja->estado === 'abierta')
                            <span class="px-3 py-1 text-xs font-black text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">
                                SESIÓN ABIERTA
                            </span>
                        @elseif($caja->cuadrado)
                            <span class="px-3 py-1 text-xs font-black text-blue-700 bg-blue-50 border border-blue-200 rounded-full">
                                CUADRADA AL 100%
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-black text-rose-700 bg-rose-50 border border-rose-200 rounded-full">
                                DESCUADRE (${{ number_format($caja->diferencia_total, 2) }})
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Tabla Comparativa Arqueo Sistema vs Conteo -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-sm text-left text-slate-600 divide-y divide-slate-100">
                        <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold tracking-wider">
                            <tr>
                                <th class="py-3.5 px-6">Rubro / Medio de Pago</th>
                                <th class="py-3.5 px-6 text-right">Sistema Teórico ($)</th>
                                <th class="py-3.5 px-6 text-right">Arqueo Declarado ($)</th>
                                <th class="py-3.5 px-6 text-right">Diferencia ($)</th>
                                <th class="py-3.5 px-6 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr>
                                <td class="py-3.5 px-6 font-bold text-slate-800">
                                    Efectivo (Incluye Fondo Inicial de ${{ number_format($caja->monto_apertura, 2) }})
                                </td>
                                <td class="py-3.5 px-6 text-right font-black text-slate-800">${{ number_format($totales['efectivo'], 2) }}</td>
                                <td class="py-3.5 px-6 text-right font-black text-indigo-600">${{ number_format($caja->monto_cierre_efectivo ?? $totales['efectivo'], 2) }}</td>
                                <td class="py-3.5 px-6 text-right font-black">${{ number_format($caja->diferencia_efectivo ?? 0, 2) }}</td>
                                <td class="py-3.5 px-6 text-center font-bold text-xs" style="color: {{ ($caja->diferencia_efectivo ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                                    {{ ($caja->diferencia_efectivo ?? 0) == 0 ? 'CUADRADO' : 'DESCUADRADO' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-bold text-slate-800">Tarjetas / Vouchers Datáfono</td>
                                <td class="py-3.5 px-6 text-right font-black text-slate-800">${{ number_format($totales['tarjeta'], 2) }}</td>
                                <td class="py-3.5 px-6 text-right font-black text-indigo-600">${{ number_format($caja->monto_cierre_tarjeta ?? $totales['tarjeta'], 2) }}</td>
                                <td class="py-3.5 px-6 text-right font-black">${{ number_format($caja->diferencia_tarjeta ?? 0, 2) }}</td>
                                <td class="py-3.5 px-6 text-center font-bold text-xs" style="color: {{ ($caja->diferencia_tarjeta ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                                    {{ ($caja->diferencia_tarjeta ?? 0) == 0 ? 'CUADRADO' : 'DESCUADRADO' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-bold text-slate-800">Transferencias Bancarias</td>
                                <td class="py-3.5 px-6 text-right font-black text-slate-800">${{ number_format($totales['transferencia'], 2) }}</td>
                                <td class="py-3.5 px-6 text-right font-black text-indigo-600">${{ number_format($caja->monto_cierre_transferencia ?? $totales['transferencia'], 2) }}</td>
                                <td class="py-3.5 px-6 text-right font-black">${{ number_format($caja->diferencia_transferencia ?? 0, 2) }}</td>
                                <td class="py-3.5 px-6 text-center font-bold text-xs" style="color: {{ ($caja->diferencia_transferencia ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                                    {{ ($caja->diferencia_transferencia ?? 0) == 0 ? 'CUADRADO' : 'DESCUADRADO' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-6 font-bold text-slate-800">Cheques en Custodia</td>
                                <td class="py-3.5 px-6 text-right font-black text-slate-800">${{ number_format($totales['cheque'], 2) }}</td>
                                <td class="py-3.5 px-6 text-right font-black text-indigo-600">${{ number_format($caja->monto_cierre_cheque ?? $totales['cheque'], 2) }}</td>
                                <td class="py-3.5 px-6 text-right font-black">${{ number_format($caja->diferencia_cheque ?? 0, 2) }}</td>
                                <td class="py-3.5 px-6 text-center font-bold text-xs" style="color: {{ ($caja->diferencia_cheque ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                                    {{ ($caja->diferencia_cheque ?? 0) == 0 ? 'CUADRADO' : 'DESCUADRADO' }}
                                </td>
                            </tr>
                            <tr class="bg-slate-50 font-black text-base border-t-2 border-slate-200">
                                <td class="py-4 px-6 text-slate-900">TOTAL GLOBAL EN CAJA</td>
                                <td class="py-4 px-6 text-right text-slate-900">${{ number_format($totales['total_general'], 2) }}</td>
                                <td class="py-4 px-6 text-right text-indigo-600">${{ number_format($caja->monto_cierre_total ?? $totales['total_general'], 2) }}</td>
                                <td class="py-4 px-6 text-right">${{ number_format($caja->diferencia_total ?? 0, 2) }}</td>
                                <td class="py-4 px-6 text-center text-xs" style="color: {{ ($caja->diferencia_total ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                                    {{ ($caja->diferencia_total ?? 0) == 0 ? 'CUADRE EXACTO' : 'DESCUADRE TOTAL' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($caja->observaciones_cierre || $caja->observaciones_apertura)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs">
                        @if($caja->observaciones_apertura)
                            <div>
                                <span class="font-bold text-slate-500 uppercase block mb-0.5">Observaciones de Apertura:</span>
                                <p class="text-slate-800 font-medium">{{ $caja->observaciones_apertura }}</p>
                            </div>
                        @endif
                        @if($caja->observaciones_cierre)
                            <div>
                                <span class="font-bold text-slate-500 uppercase block mb-0.5">Observaciones de Cierre:</span>
                                <p class="text-slate-800 font-medium">{{ $caja->observaciones_cierre }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Listado Completo de Transacciones -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 pb-4 border-b border-slate-100">
                    <h3 class="text-base font-extrabold text-slate-800">Transacciones de la Sesión ({{ $caja->movimientos->count() }})</h3>
                    <p class="text-xs text-slate-400">Detalle cronológico de cada cobro, abono o gasto registrado en esta caja</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600 divide-y divide-slate-100">
                        <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-extrabold tracking-wider">
                            <tr>
                                <th class="py-3.5 px-6">Hora</th>
                                <th class="py-3.5 px-6">Concepto / Tipo</th>
                                <th class="py-3.5 px-6">Cliente</th>
                                <th class="py-3.5 px-6">Método de Pago</th>
                                <th class="py-3.5 px-6">Banco / Tarjeta / Ref</th>
                                <th class="py-3.5 px-6 text-right">Monto ($)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($caja->movimientos as $mov)
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
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                            {{ $mov->metodo_pago === 'Efectivo' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                            {{ $mov->metodo_pago === 'Tarjeta' ? 'bg-pink-50 text-pink-700 border border-pink-200' : '' }}
                                            {{ $mov->metodo_pago === 'Transferencia' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                            {{ $mov->metodo_pago === 'Cheque' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                            {{ $mov->metodo_pago === 'Crédito' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}">
                                            {{ $mov->metodo_pago }}
                                        </span>
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
                                        No hay transacciones registradas en esta sesión.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
