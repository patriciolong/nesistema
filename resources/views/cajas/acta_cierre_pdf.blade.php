<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Cierre de Caja #{{ $caja->id }}</title>
    <style>
        @page {
            margin: 20px 25px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
        }
        body {
            font-size: 11px;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .subtitle {
            font-size: 10px;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 6px;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 4px 6px;
            font-size: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #475569;
            width: 18%;
        }
        .info-value {
            color: #0f172a;
            width: 32%;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            background-color: #f1f5f9;
            padding: 6px 8px;
            border-left: 4px solid #4f46e5;
            margin-top: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 8px;
            text-align: left;
        }
        .data-table td {
            padding: 5px 8px;
            font-size: 9.5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .total-row {
            background-color: #f8fafc;
            font-weight: bold;
            border-top: 2px solid #cbd5e1;
        }
        .signatures-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .signatures-table td {
            width: 50%;
            text-align: center;
            padding: 0 30px;
        }
        .signature-line {
            border-top: 1px solid #0f172a;
            padding-top: 5px;
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
        }
        .signature-role {
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="title">ACTA DE ARQUEO Y CIERRE DE CAJA</div>
                <div class="subtitle">SISTEMA NOTARIAL & SERVICIOS MÚLTIPLES</div>
                <div style="font-size: 9px; color: #94a3b8; margin-top: 3px;">
                    Impreso el: {{ $fecha_impresion }}
                </div>
            </td>
            <td style="width: 30%; text-align: right;">
                <div style="font-size: 14px; font-weight: bold; color: #4f46e5;">CAJA #{{ $caja->id }}</div>
                <div style="margin-top: 4px;">
                    @if($caja->cuadrado)
                        <span class="badge badge-success">CUADRADA (100%)</span>
                    @else
                        <span class="badge badge-danger">CON DESCUADRE</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Información General -->
    <table class="info-grid">
        <tr>
            <td class="info-label">Cajero Responsable:</td>
            <td class="info-value">{{ $caja->user->name ?? 'N/A' }}</td>
            <td class="info-label">Oficina / Sucursal:</td>
            <td class="info-value">{{ $caja->oficina ?? 'General' }}</td>
        </tr>
        <tr>
            <td class="info-label">Fecha Apertura:</td>
            <td class="info-value">{{ $caja->fecha_apertura ? $caja->fecha_apertura->format('d/m/Y h:i A') : 'N/A' }}</td>
            <td class="info-label">Fecha Cierre:</td>
            <td class="info-value">{{ $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y h:i A') : 'En curso' }}</td>
        </tr>
        <tr>
            <td class="info-label">Fondo Inicial:</td>
            <td class="info-value font-bold">${{ number_format($caja->monto_apertura, 2) }}</td>
            <td class="info-label">Total Declarado:</td>
            <td class="info-value font-bold">${{ number_format($caja->monto_cierre_total ?? $totales['total_general'], 2) }}</td>
        </tr>
    </table>

    <!-- Resumen de Cuadre por Método de Pago -->
    <div class="section-title">Resumen de Cuadre y Arqueo por Método de Pago</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Método de Pago</th>
                <th class="text-right">Monto Sistema ($)</th>
                <th class="text-right">Conteo Físico Real ($)</th>
                <th class="text-right">Diferencia ($)</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Efectivo</strong> (Incluye Fondo Inicial de ${{ number_format($caja->monto_apertura, 2) }})</td>
                <td class="text-right">${{ number_format($totales['efectivo'], 2) }}</td>
                <td class="text-right">${{ number_format($caja->monto_cierre_efectivo ?? $totales['efectivo'], 2) }}</td>
                <td class="text-right">${{ number_format($caja->diferencia_efectivo ?? 0, 2) }}</td>
                <td class="text-center font-bold" style="color: {{ ($caja->diferencia_efectivo ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                    {{ ($caja->diferencia_efectivo ?? 0) == 0 ? 'CUADRADO' : 'DESCUADRE' }}
                </td>
            </tr>
            <tr>
                <td><strong>Tarjetas / Vouchers Datáfono</strong></td>
                <td class="text-right">${{ number_format($totales['tarjeta'], 2) }}</td>
                <td class="text-right">${{ number_format($caja->monto_cierre_tarjeta ?? $totales['tarjeta'], 2) }}</td>
                <td class="text-right">${{ number_format($caja->diferencia_tarjeta ?? 0, 2) }}</td>
                <td class="text-center font-bold" style="color: {{ ($caja->diferencia_tarjeta ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                    {{ ($caja->diferencia_tarjeta ?? 0) == 0 ? 'CUADRADO' : 'DESCUADRE' }}
                </td>
            </tr>
            <tr>
                <td><strong>Transferencias Bancarias</strong></td>
                <td class="text-right">${{ number_format($totales['transferencia'], 2) }}</td>
                <td class="text-right">${{ number_format($caja->monto_cierre_transferencia ?? $totales['transferencia'], 2) }}</td>
                <td class="text-right">${{ number_format($caja->diferencia_transferencia ?? 0, 2) }}</td>
                <td class="text-center font-bold" style="color: {{ ($caja->diferencia_transferencia ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                    {{ ($caja->diferencia_transferencia ?? 0) == 0 ? 'CUADRADO' : 'DESCUADRE' }}
                </td>
            </tr>
            <tr>
                <td><strong>Cheques en Custodia</strong></td>
                <td class="text-right">${{ number_format($totales['cheque'], 2) }}</td>
                <td class="text-right">${{ number_format($caja->monto_cierre_cheque ?? $totales['cheque'], 2) }}</td>
                <td class="text-right">${{ number_format($caja->diferencia_cheque ?? 0, 2) }}</td>
                <td class="text-center font-bold" style="color: {{ ($caja->diferencia_cheque ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                    {{ ($caja->diferencia_cheque ?? 0) == 0 ? 'CUADRADO' : 'DESCUADRE' }}
                </td>
            </tr>
            <tr class="total-row">
                <td style="font-size: 10.5px;">TOTAL GENERAL EN CAJA</td>
                <td class="text-right" style="font-size: 10.5px;">${{ number_format($totales['total_general'], 2) }}</td>
                <td class="text-right" style="font-size: 10.5px;">${{ number_format($caja->monto_cierre_total ?? $totales['total_general'], 2) }}</td>
                <td class="text-right" style="font-size: 10.5px;">${{ number_format($caja->diferencia_total ?? 0, 2) }}</td>
                <td class="text-center font-bold" style="font-size: 10.5px; color: {{ ($caja->diferencia_total ?? 0) == 0 ? '#16a34a' : '#dc2626' }}">
                    {{ ($caja->diferencia_total ?? 0) == 0 ? 'CUADRE EXACTO' : 'DESCUADRE TOTAL' }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Detalle de Movimientos -->
    <div class="section-title">Detalle de Transacciones Registradas ({{ $movimientos->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Hora</th>
                <th>Concepto / Tipo</th>
                <th>Cliente / Trámite</th>
                <th>Método</th>
                <th>Banco / Tarjeta / Ref</th>
                <th class="text-right">Monto ($)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimientos as $mov)
                <tr>
                    <td style="width: 10%;">{{ $mov->created_at->format('h:i A') }}</td>
                    <td style="width: 28%;">
                        <strong>{{ $mov->concepto }}</strong>
                    </td>
                    <td style="width: 25%;">
                        {{ $mov->cliente ? $mov->cliente->c_nombre . ' ' . $mov->cliente->c_apellido : '-' }}
                        @if($mov->tramite_tipo) <br><small style="color: #64748b;">Trámite: {{ $mov->tramite_tipo }}</small> @endif
                    </td>
                    <td style="width: 12%;">{{ $mov->metodo_pago }}</td>
                    <td style="width: 15%;">
                        {{ $mov->banco->nombre ?? '' }} {{ $mov->tarjeta->nombre ?? '' }}
                        @if($mov->numero_referencia) <small style="display:block;">Ref: {{ $mov->numero_referencia }}</small> @endif
                    </td>
                    <td class="text-right font-bold" style="width: 10%; color: {{ in_array($mov->tipo, ['ingreso_tramite', 'ingreso_abono', 'ingreso_extra']) ? '#16a34a' : '#dc2626' }}">
                        {{ in_array($mov->tipo, ['ingreso_tramite', 'ingreso_abono', 'ingreso_extra']) ? '+' : '-' }}${{ number_format($mov->monto, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px; color: #94a3b8;">
                        No se registraron movimientos en esta sesión.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($caja->observaciones_cierre || $caja->observaciones_apertura)
        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px; margin-top: 10px; font-size: 9.5px;">
            @if($caja->observaciones_apertura)
                <p><strong>Obs. Apertura:</strong> {{ $caja->observaciones_apertura }}</p>
            @endif
            @if($caja->observaciones_cierre)
                <p><strong>Obs. Cierre:</strong> {{ $caja->observaciones_cierre }}</p>
            @endif
        </div>
    @endif

    <!-- Firmas de Responsabilidad -->
    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-line">
                    {{ $caja->user->name ?? 'Cajero Responsable' }}
                </div>
                <div class="signature-role">Cajero / Asesor de Turno</div>
            </td>
            <td>
                <div class="signature-line">
                    Administrador / Supervisor
                </div>
                <div class="signature-role">Control y Auditoría de Caja</div>
            </td>
        </tr>
    </table>

</body>
</html>
