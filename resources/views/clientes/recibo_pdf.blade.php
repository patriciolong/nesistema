<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Abono</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #1f2937; }
        .header p { margin: 5px 0; color: #6b7280; }
        .receipt-info { display: table; width: 100%; margin-bottom: 30px; }
        .receipt-info > div { display: table-cell; width: 50%; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-table th, .details-table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        .details-table th { background-color: #f3f4f6; color: #374151; }
        .total-section { text-align: right; margin-top: 20px; font-size: 18px; }
        .total-section span { font-weight: bold; font-size: 24px; color: #059669; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #9ca3af; }
        .signatures { margin-top: 60px; display: table; width: 100%; text-align: center; }
        .signature { display: table-cell; width: 50%; }
        .signature-line { display: inline-block; width: 80%; border-top: 1px solid #333; margin-top: 40px; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RECIBO DE ABONO</h1>
        <p>Comprobante de Pago</p>
    </div>

    <div class="receipt-info">
        <div>
            <strong>Fecha:</strong> {{ $fecha }}<br>
            <strong>Atendido por:</strong> {{ $usuario }}<br>
            <strong>Oficina:</strong> {{ $oficina }}
        </div>
        <div style="text-align: right;">
            <strong>Recibo N°:</strong> {{ time() }}<br>
        </div>
    </div>

    <table class="details-table">
        <tr>
            <th colspan="2">Detalles del Cliente</th>
        </tr>
        <tr>
            <td width="30%"><strong>Nombre:</strong></td>
            <td>{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</td>
        </tr>
        <tr>
            <td><strong>Identificación:</strong></td>
            <td>{{ $cliente->c_identificacion }}</td>
        </tr>
        <tr>
            <td><strong>Teléfono:</strong></td>
            <td>{{ $cliente->c_telefono }}</td>
        </tr>
        <tr>
            <th colspan="2">Detalles del Pago</th>
        </tr>
        <tr>
            <td><strong>Deuda Total Inicial:</strong></td>
            <td>${{ number_format($cliente->c_deuda, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Total Abonado a la Fecha:</strong></td>
            <td>${{ number_format($cliente->c_abonado, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Saldo Pendiente Actual:</strong></td>
            <td><span style="color: #ea580c; font-weight: bold;">${{ number_format($cliente->c_saldo, 2) }}</span></td>
        </tr>
    </table>

    <div class="total-section">
        Valor Pagado en este Abono: <span>${{ number_format($monto, 2) }}</span>
    </div>

    <div class="signatures">
        <div class="signature">
            <span class="signature-line">Firma del Cliente</span>
        </div>
        <div class="signature">
            <span class="signature-line">Recibe ({{ $usuario }})</span>
        </div>
    </div>

    <div class="footer">
        Este documento es un comprobante de abono generado automáticamente por NESISTEMA.
    </div>
</body>
</html>
