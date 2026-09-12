<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprobante de Trámite #{{ str_pad($tramite->id, 5, '0', STR_PAD_LEFT) }} - {{ $tipo->nombre ?? 'Trámite' }}</title>
    <style>
        /* Brand Colors */
        :root {
            --primary-color: #004080;
            --accent-color: #C0A16B;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 13px;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        /* Header Table */
        .header-table {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 3px solid #004080;
            padding-bottom: 15px;
        }
        .header-logo {
            max-width: 180px;
            height: auto;
        }
        .header-right {
            text-align: right;
            font-size: 11px;
            color: #555;
            vertical-align: bottom;
        }
        .header-right p {
            margin: 2px 0;
        }
        
        .title {
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #004080;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 15px;
            color: #fff;
            background-color: #004080;
            border-radius: 4px;
            padding: 6px 12px;
            border-left: 4px solid #C0A16B;
        }

        /* Field Row */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 6px 8px;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
        }
        .label-cell {
            width: 35%;
            font-weight: bold;
            color: #444;
        }
        .value-cell {
            width: 65%;
            color: #222;
        }

        .total-amount {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-top: 35px;
            border-top: 2px solid #004080;
            padding-top: 15px;
            width: 100%;
        }
        .total-table {
            width: 45%;
            float: right;
            border-collapse: collapse;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .total-table td {
            padding: 8px 12px;
            text-align: right;
        }
        .total-table tr:not(:last-child) td {
            border-bottom: 1px solid #dee2e6;
        }
        .clearfix {
            clear: both;
        }

        /* Signatures */
        .signature-section {
            margin-top: 70px;
            width: 100%;
            text-align: center;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto;
            margin-bottom: 5px;
        }
        .signature-text {
            font-size: 10px;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER OFICIAL CON LOGO E INFORMACIÓN NOTARIAL -->
        <table class="header-table">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    @php
                        $logoPath = public_path('img/logo_impre.png');
                        $logoBase64 = '';
                        if(file_exists($logoPath)) {
                            $logoData = file_get_contents($logoPath);
                            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                        }
                    @endphp
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo Notaría" class="header-logo">
                    @else
                        <h2 style="color: #004080; margin: 0;">NOTARÍA & TRÁMITES</h2>
                    @endif
                </td>
                <td class="header-right">
                    <p><strong>Fecha:</strong> {{ $fecha }}</p>
                    <p><strong>Oficina:</strong> {{ $tramite->oficina ?? 'General' }}</p>
                    <p><strong>Atendido por:</strong> {{ $tramite->usuario ?? 'Personal Autorizado' }}</p>
                </td>
            </tr>
        </table>

        <!-- TÍTULO DEL COMPROBANTE -->
        <div class="title">
            COMPROBANTE DE {{ strtoupper($tipo->nombre ?? 'TRÁMITE') }} #{{ str_pad($tramite->id, 5, '0', STR_PAD_LEFT) }}
        </div>

        <!-- INFORMACIÓN DEL CLIENTE -->
        <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
        <table class="info-table">
            <tr>
                <td class="label-cell">Nombres Completos:</td>
                <td class="value-cell" style="font-weight: bold;">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</td>
            </tr>
            <tr>
                <td class="label-cell">Identificación / ID:</td>
                <td class="value-cell">{{ $cliente->c_identificacion }}</td>
            </tr>
            <tr>
                <td class="label-cell">Teléfono:</td>
                <td class="value-cell">{{ $cliente->c_telefono }}</td>
            </tr>
            @if(!empty($cliente->c_direccion))
            <tr>
                <td class="label-cell">Dirección:</td>
                <td class="value-cell">{{ $cliente->c_direccion }}, {{ $cliente->c_ciudad }}, {{ $cliente->c_estado }}</td>
            </tr>
            @endif
            @if(!empty($cliente->c_email))
            <tr>
                <td class="label-cell">Email:</td>
                <td class="value-cell">{{ $cliente->c_email }}</td>
            </tr>
            @endif
        </table>

        <!-- DETALLES ESPECÍFICOS DEL TRÁMITE -->
        <div class="section-title">DETALLES DEL TRÁMITE</div>
        <table class="info-table">
            @php $campos = $tipo->campos ?? []; @endphp
            @forelse($campos as $campo)
                <tr>
                    <td class="label-cell">{{ $campo['label'] }}:</td>
                    <td class="value-cell" style="font-weight: 600;">
                        {{ $tramite->datos_formulario[$campo['name']] ?? 'N/A' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="label-cell">Tipo:</td>
                    <td class="value-cell">{{ $tipo->nombre }}</td>
                </tr>
            @endforelse

            @if(!empty($tramite->observaciones))
            <tr>
                <td class="label-cell">Observaciones:</td>
                <td class="value-cell">{{ $tramite->observaciones }}</td>
            </tr>
            @endif
        </table>

        <!-- TOTALES Y LIQUIDACIÓN FINANCIERA -->
        <div class="total-amount">
            <table class="total-table">
                <tr>
                    <td style="color: #555;">Valor del Trámite:</td>
                    <td>${{ number_format($tramite->valor_tramite, 2) }}</td>
                </tr>
                <tr>
                    <td style="color: #555;">Abono Realizado:</td>
                    <td style="color: green;">${{ number_format($tramite->abono_tramite, 2) }}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #ccc; color: #555;">Saldo Pendiente:</td>
                    <td style="border-top: 1px solid #ccc; color: red;">${{ number_format($tramite->saldo, 2) }}</td>
                </tr>
            </table>
            <div class="clearfix"></div>
        </div>

        <!-- FIRMAS AUTORIZADAS -->
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-text">FIRMA DEL CLIENTE<br>{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</div>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-text">FIRMA NOTARIO / RESPONSABLE<br>{{ $tramite->usuario ?? 'Oficina' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
