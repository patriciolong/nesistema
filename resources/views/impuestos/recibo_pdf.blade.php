<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Declaración de Impuestos #{{ str_pad($tramite->id_tram_impuestos, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* PDF specific styles for dompdf compatibility */
        @page {
            margin: 15mm 20mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 10pt;
            line-height: 1.3;
        }
        /* Header Container */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #004080;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 120px;
        }
        .logo-cell img {
            width: 100%;
            max-width: 110px;
        }
        .title-cell {
            text-align: center;
        }
        .title-cell h1 {
            color: #004080;
            font-size: 16pt;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .title-cell p {
            color: #666;
            margin: 0;
            font-size: 9pt;
        }
        .info-cell {
            width: 150px;
            text-align: right;
            font-size: 8.5pt;
            color: #555;
            line-height: 1.4;
        }
        
        /* Sections */
        .section-title {
            background-color: #004080;
            color: white;
            padding: 5px 10px;
            font-size: 10pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        
        /* Grid System using Tables (Dompdf requires tables for robust layouts) */
        .grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-bottom: 10px;
        }
        .grid-table td {
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #444;
            font-size: 8.5pt;
            display: block;
            margin-bottom: 2px;
        }
        .value {
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
            font-size: 9.5pt;
            min-height: 14px;
            word-wrap: break-word;
        }
        
        /* Financial Summary Box */
        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border: 1px solid #ddd;
        }
        .financial-table th {
            background-color: #f5f5f5;
            color: #333;
            padding: 8px;
            text-align: right;
            font-size: 9pt;
            border-bottom: 1px solid #ddd;
        }
        .financial-table td {
            padding: 8px;
            text-align: right;
            font-size: 10pt;
            border-bottom: 1px solid #ddd;
        }
        .financial-table .total-row th,
        .financial-table .total-row td {
            background-color: #e6f2ff;
            font-weight: bold;
            color: #004080;
            border-bottom: none;
            font-size: 11pt;
        }
        
        /* Observaciones */
        .obs-box {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            min-height: 50px;
            font-size: 9pt;
            background-color: #fafafa;
        }
        
        /* Signatures */
        .signatures {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }
        .signatures td {
            width: 50%;
            vertical-align: bottom;
        }
        .sign-line {
            border-top: 1px solid #000;
            width: 70%;
            margin: 0 auto;
            padding-top: 5px;
            font-size: 9pt;
            font-weight: bold;
            color: #444;
        }
        
        /* Checkboxes */
        .checkbox-group {
            margin-bottom: 10px;
        }
        .checkbox-item {
            margin-bottom: 5px;
            font-size: 9pt;
        }
        .check-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #333;
            margin-right: 5px;
            text-align: center;
            line-height: 12px;
            font-size: 10pt;
            font-weight: bold;
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
                <h1>Declaración de Impuestos</h1>
                <p>Comprobante de Trámite #{{ str_pad($tramite->id_tram_impuestos, 5, '0', STR_PAD_LEFT) }}</p>
            </td>
            <td class="info-cell">
                <strong>Fecha:</strong> {{ $tramite->ti_fecha ? date('d/m/Y', strtotime($tramite->ti_fecha)) : date('d/m/Y') }}<br>
                <strong>Oficina:</strong> {{ $tramite->ti_oficina }}<br>
                <strong>Atendido por:</strong> {{ $tramite->usuario->name ?? 'N/A' }}<br>
                <strong>Firmar en:</strong> {{ $tramite->ti_firmar_en }}
            </td>
        </tr>
    </table>

    <!-- Datos del Cliente -->
    <div class="section-title">DATOS DEL CLIENTE</div>
    <table class="grid-table">
        <tr>
            <td style="width: 50%; padding-right: 10px;">
                <span class="label">NOMBRES COMPLETOS</span>
                <div class="value">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</div>
            </td>
            <td style="width: 50%;">
                <span class="label">IDENTIFICACIÓN</span>
                <div class="value">{{ $cliente->c_identificacion ?: '__________' }}</div>
            </td>
        </tr>
    </table>
    <table class="grid-table">
        <tr>
            <td style="width: 100%;">
                <span class="label">DIRECCIÓN</span>
                <div class="value">{{ $cliente->c_direccion ?: '__________' }} {{ $cliente->c_departamento ? 'Apt/Suite '.$cliente->c_departamento : '' }}</div>
            </td>
        </tr>
    </table>
    <table class="grid-table">
        <tr>
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">CIUDAD</span>
                <div class="value">{{ $cliente->c_ciudad ?: '__________' }}</div>
            </td>
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">ESTADO</span>
                <div class="value">{{ $cliente->c_estado ?: '__________' }}</div>
            </td>
            <td style="width: 34%;">
                <span class="label">CÓDIGO POSTAL</span>
                <div class="value">{{ $cliente->c_codpostal ?: '__________' }}</div>
            </td>
        </tr>
    </table>
    <table class="grid-table">
        <tr>
            <td style="width: 50%; padding-right: 10px;">
                <span class="label">TELÉFONO</span>
                <div class="value">{{ $cliente->c_telefono ?: '__________' }}</div>
            </td>
            <td style="width: 50%;">
                <span class="label">EMAIL</span>
                <div class="value">{{ $cliente->c_email ?: '__________' }}</div>
            </td>
        </tr>
    </table>

    <!-- Detalles del Trámite -->
    <div class="section-title">DETALLES DE LA DECLARACIÓN</div>
    <table class="grid-table">
        <tr>
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">APLICACIÓN ITIN</span>
                <div class="value">{{ $tramite->ti_itin ? 'SÍ' : 'NO' }}</div>
            </td>
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">NÚMERO ITIN O SOCIAL</span>
                <div class="value">{{ $tramite->ti_nitin ?: '__________' }}</div>
            </td>
            <td style="width: 34%;">
                <span class="label">AÑO DE REPORTE</span>
                <div class="value">{{ $tramite->ti_anio_reporte ?: '__________' }}</div>
            </td>
        </tr>
    </table>
    
    <table class="grid-table">
        <tr>
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">FECHA INGRESO EE.UU.</span>
                <div class="value">{{ $tramite->ti_fechain ? date('d/m/Y', strtotime($tramite->ti_fechain)) : '__________' }}</div>
            </td>
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">ESTADO CIVIL</span>
                <div class="value">{{ $tramite->ti_ecivil ?: '__________' }}</div>
            </td>
            <td style="width: 34%;">
                <span class="label">PROFESIÓN</span>
                <div class="value">{{ $tramite->ti_profesion ?: '__________' }}</div>
            </td>
        </tr>
    </table>
    
    <table class="grid-table">
        <tr>
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">DEPENDIENTES</span>
                <div class="value">{{ $tramite->ti_dependientes ?: '0' }}</div>
            </td>
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">MÉTODO DE PAGO</span>
                <div class="value">{{ $tramite->ti_mpago ?: '__________' }}</div>
            </td>
            <td style="width: 34%;">
                <span class="label">BANCO</span>
                <div class="value">{{ $tramite->ti_banco ?: '__________' }}</div>
            </td>
        </tr>
    </table>

    <table class="grid-table">
        <tr>
            <td style="width: 50%; padding-right: 10px;">
                <span class="label">NÚMERO DE CUENTA</span>
                <div class="value">{{ $tramite->ti_ncuenta ?: '__________' }}</div>
            </td>
            <td style="width: 50%;">
                <span class="label">NÚMERO DE RUTA</span>
                <div class="value">{{ $tramite->ti_nruta ?: '__________' }}</div>
            </td>
        </tr>
    </table>

    <!-- Financiero y Notas -->
    <div class="section-title">RESUMEN FINANCIERO Y NOTAS</div>
    
    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
        <tr>
            <td style="width: 55%; vertical-align: top; padding-right: 15px;">
                <span class="label">OBSERVACIONES INTERNAS</span>
                <div class="obs-box">
                    {!! nl2br(e($tramite->ti_observacion ?: 'Ninguna observación adicional.')) !!}
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;">
                <table class="financial-table">
                    <tr>
                        <th>Honorarios Totales:</th>
                        <td>${{ number_format($tramite->ti_costo_tramite, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Abono Inicial:</th>
                        <td>${{ number_format($tramite->ti_abono_tramite, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <th>Saldo a Pagar:</th>
                        <td>${{ number_format($tramite->ti_saldo, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Firmas -->
    <table class="signatures">
        <tr>
            <td>
                <div class="sign-line">FIRMA DEL CLIENTE</div>
            </td>
            <td>
                <div class="sign-line">FIRMA AUTORIZADA</div>
            </td>
        </tr>
    </table>

</body>
</html>
