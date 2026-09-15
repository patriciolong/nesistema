<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Recibo de Abono #{{ $recibo_num ?? time() }}</title>
    <style>
        @page {
            size: letter portrait;
            margin: 8mm 12mm 8mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            font-size: 8.5pt;
            line-height: 1.3;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #059669; /* Emerald theme for payments */
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 100px;
        }
        .logo-cell img {
            max-width: 90px;
            max-height: 40px;
        }
        .title-cell {
            text-align: center;
        }
        .title-cell h1 {
            color: #065f46;
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .title-cell p {
            color: #64748b;
            margin: 2px 0 0 0;
            font-size: 8pt;
            font-weight: bold;
        }
        .info-cell {
            width: 160px;
            text-align: right;
            font-size: 7.5pt;
            color: #475569;
            line-height: 1.3;
        }

        /* Section Headings */
        .sec-title {
            background-color: #059669;
            color: white;
            padding: 3px 8px;
            font-size: 8pt;
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 4px;
            border-radius: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .data-table td {
            padding: 3px 5px;
            vertical-align: top;
            font-size: 8pt;
        }
        .lbl {
            font-size: 7pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            display: block;
            margin-bottom: 1px;
        }
        .val {
            font-size: 8pt;
            font-weight: 600;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 1px;
            min-height: 14px;
            word-wrap: break-word;
        }

        /* Payment Hero Box */
        .hero-box {
            background-color: #ecfdf5;
            border: 2px dashed #059669;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            margin: 10px 0;
        }
        .hero-lbl {
            font-size: 8pt;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .hero-amount {
            font-size: 20pt;
            font-weight: 900;
            color: #047857;
            margin: 2px 0;
        }
        .hero-sub {
            font-size: 7.5pt;
            color: #047857;
        }

        /* Financial Statement Table */
        .fin-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            margin-top: 6px;
        }
        .fin-table th {
            background-color: #f1f5f9;
            color: #475569;
            padding: 5px 8px;
            text-align: left;
            font-size: 7.5pt;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
        }
        .fin-table td {
            padding: 5px 8px;
            text-align: right;
            font-size: 8.5pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
        }
        .fin-table .row-total th,
        .fin-table .row-total td {
            background-color: #fef2f2;
            color: #b91c1c;
            font-size: 9.5pt;
            font-weight: 900;
            border-bottom: none;
        }

        /* Signatures */
        .sig-table {
            width: 100%;
            margin-top: 25px;
            text-align: center;
        }
        .sig-table td {
            width: 50%;
            vertical-align: bottom;
            padding: 0 25px;
        }
        .sig-line {
            border-top: 1px solid #334155;
            padding-top: 3px;
            font-size: 7.5pt;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
        }

        .footer-bar {
            text-align: center;
            font-size: 7pt;
            color: #94a3b8;
            margin-top: 15px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @php
                    $logoPath = public_path('img/logo_impre.png');
                    if(file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                        echo '<img src="data:image/png;base64,'.$logoData.'" alt="Logo">';
                    }
                @endphp
            </td>
            <td class="title-cell">
                <h1>Recibo de Abono</h1>
                <p>Comprobante de Pago a Cartera</p>
            </td>
            <td class="info-cell">
                <strong>Fecha:</strong> {{ $fecha }}<br>
                <strong>Oficina:</strong> {{ $oficina }}<br>
                <strong>Recaudado por:</strong> {{ $usuario }}<br>
                <strong>Comprobante Nº:</strong> {{ time() }}
            </td>
        </tr>
    </table>

    <!-- 1. DATOS DEL CLIENTE -->
    <div class="sec-title">1. Datos del Cliente / Titular de Cuenta</div>
    <table class="data-table">
        <tr>
            <td style="width: 50%;">
                <span class="lbl">Nombres y Apellidos</span>
                <div class="val">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Identificación / Cédula</span>
                <div class="val">{{ $cliente->c_identificacion ?: 'S/I' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Teléfono</span>
                <div class="val">{{ $cliente->c_telefono ?: 'S/T' }}</div>
            </td>
        </tr>
    </table>

    <!-- 2. DESTACADO DEL MONTO COBRADO -->
    <div class="hero-box">
        <div class="hero-lbl">Monto Recaudado en esta Transacción</div>
        <div class="hero-amount">${{ number_format($monto, 2) }}</div>
        <div class="hero-sub">Ingreso registrado y acreditado en caja exitosamente</div>
    </div>

    <!-- 3. ESTADO DE CUENTA ACTUALIZADO -->
    <div class="sec-title">2. Estado de Cuenta Consolidado del Cliente</div>
    <table class="fin-table">
        <tr>
            <th>Deuda Histórica Consolidada:</th>
            <td>${{ number_format($cliente->c_deuda, 2) }}</td>
        </tr>
        <tr>
            <th>Total Acumulado Abonado a la Fecha:</th>
            <td style="color: #059669;">${{ number_format($cliente->c_abonado, 2) }}</td>
        </tr>
        <tr class="row-total">
            <th>Saldo Pendiente Actual por Cobrar:</th>
            <td>${{ number_format($cliente->c_saldo, 2) }}</td>
        </tr>
    </table>

    <!-- 4. FIRMAS -->
    <table class="sig-table">
        <tr>
            <td>
                <div class="sig-line">Firma del Cliente<br><span style="font-size: 6.5pt; color: #64748b; text-transform: none;">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</span></div>
            </td>
            <td>
                <div class="sig-line">Recibido Conforme / Cajero<br><span style="font-size: 6.5pt; color: #64748b; text-transform: none;">{{ $usuario }}</span></div>
            </td>
        </tr>
    </table>

    <div class="footer-bar">
        Documento Electrónico Generado por NESISTEMA &bull; Validez Oficial de Recaudación &bull; Página 1 de 1
    </div>

</body>
</html>
