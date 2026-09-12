<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Poder #{{ str_pad($tramite->id_tram_poderes, 5, '0', STR_PAD_LEFT) }}</title>
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
            border-bottom: 2px solid #5a189a; /* Purple theme */
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
            color: #5a189a;
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
            background-color: #5a189a;
            color: white;
            padding: 5px 10px;
            font-size: 10pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
            border-radius: 3px;
            text-transform: uppercase;
        }
        
        /* Grid System using Tables (Dompdf requires tables for robust layouts) */
        .grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-bottom: 5px;
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
            background-color: #f3e8ff; /* Light purple */
            font-weight: bold;
            color: #5a189a;
            border-bottom: none;
            font-size: 11pt;
        }
        
        /* Observaciones */
        .obs-box {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            min-height: 40px;
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
                <h1>Trámite de Poder</h1>
                <p>Comprobante de Trámite #{{ str_pad($tramite->id_tram_poderes, 5, '0', STR_PAD_LEFT) }}</p>
            </td>
            <td class="info-cell">
                <strong>Fecha:</strong> {{ $tramite->tp_fecha ? date('d/m/Y', strtotime($tramite->tp_fecha)) : date('d/m/Y') }}<br>
                <strong>Oficina:</strong> {{ $tramite->tp_oficina }}<br>
                <strong>Atendido por:</strong> {{ $tramite->usuario->name ?? 'N/A' }}<br>
                <strong>Firmar en:</strong> {{ $tramite->tp_firmar_en }}
            </td>
        </tr>
    </table>

    <!-- Datos de Otorgantes -->
    <div class="section-title">1. PERSONA QUE OTORGA EL PODER (USTED)</div>
    <table class="grid-table">
        <tr>
            <td style="width: 50%; padding-right: 10px;">
                <span class="label">NOMBRES Y APELLIDOS (COMPLETOS)</span>
                <div class="value">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</div>
            </td>
            <td style="width: 50%;">
                <span class="label">NÚMERO DE IDENTIFICACIÓN</span>
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
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">ESTADO CIVIL</span>
                <div class="value">{{ mb_strtoupper($tramite->tp_estado_civil) ?: '__________' }}</div>
            </td>
            <td style="width: 33%; padding-right: 10px;">
                <span class="label">TELÉFONO</span>
                <div class="value">{{ $cliente->c_telefono ?: '__________' }}</div>
            </td>
            <td style="width: 34%;">
                <span class="label">CORREO ELECTRÓNICO</span>
                <div class="value">{{ $cliente->c_email ?: '__________' }}</div>
            </td>
        </tr>
    </table>

    @if($tramite->tp_nombre2 || $tramite->tp_identificacion2)
        <table class="grid-table" style="margin-top: 5px; border-top: 1px dotted #ccc; padding-top: 5px;">
            <tr>
                <td style="width: 100%;" colspan="2">
                    <span class="label" style="color: #666; font-style: italic;">PERSONA QUE OTORGA EL PODER (2da Persona)</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; padding-right: 10px;">
                    <span class="label">NOMBRES Y APELLIDOS COMPLETOS</span>
                    <div class="value">{{ $tramite->tp_nombre2 }} {{ $tramite->tp_apellido2 }}</div>
                </td>
                <td style="width: 50%;">
                    <span class="label">NÚMERO DE IDENTIFICACIÓN</span>
                    <div class="value">{{ $tramite->tp_identificacion2 ?: '__________' }}</div>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; padding-right: 10px;">
                    <span class="label">TELÉFONO</span>
                    <div class="value">{{ $tramite->tp_telefono2 ?: '__________' }}</div>
                </td>
                <td style="width: 50%;"></td>
            </tr>
        </table>
    @endif

    <!-- Datos a favor de -->
    <div class="section-title">2. PERSONA A FAVOR DE QUIEN OTORGA EL PODER</div>
    <table class="grid-table">
        <tr>
            <td style="width: 100%;" colspan="2">
                <span class="label" style="color: #666; font-style: italic;">1ra Persona</span>
            </td>
        </tr>
        <tr>
            <td style="width: 60%; padding-right: 10px;">
                <span class="label">NOMBRE Y APELLIDOS COMPLETOS</span>
                <div class="value">{{ $tramite->tp_nombres_otorga_poder ?: '__________' }}</div>
            </td>
            <td style="width: 40%;">
                <span class="label">NO. DE CÉDULA</span>
                <div class="value">{{ $tramite->tp_cedulla_otorga_poder ?: '__________' }}</div>
            </td>
        </tr>
    </table>

    @if($tramite->tp_nombres_otorga_poder2 || $tramite->tp_cedulla_otorga_poder2)
        <table class="grid-table" style="margin-top: 5px; border-top: 1px dotted #ccc; padding-top: 5px;">
            <tr>
                <td style="width: 100%;" colspan="2">
                    <span class="label" style="color: #666; font-style: italic;">2da Persona</span>
                </td>
            </tr>
            <tr>
                <td style="width: 60%; padding-right: 10px;">
                    <span class="label">NOMBRE Y APELLIDOS COMPLETOS</span>
                    <div class="value">{{ $tramite->tp_nombres_otorga_poder2 ?: '__________' }}</div>
                </td>
                <td style="width: 40%;">
                    <span class="label">NO. DE CÉDULA</span>
                    <div class="value">{{ $tramite->tp_cedulla_otorga_poder2 ?: '__________' }}</div>
                </td>
            </tr>
        </table>
    @endif

    <!-- Razón del Poder -->
    <div class="section-title">3. RAZÓN DEL PODER</div>
    <table class="grid-table">
        <tr>
            <td style="width: 100%;">
                <div class="obs-box" style="min-height: 50px;">
                    {!! nl2br(e($tramite->tp_razon_otorga_poder ?: '__________')) !!}
                </div>
            </td>
        </tr>
    </table>

    <!-- Envío y Pago -->
    <div class="section-title">4. DETALLES DE ENVÍO Y COBRO</div>
    
    <table class="grid-table" style="margin-bottom: 10px;">
        <tr>
            <td style="width: 100%;">
                <span class="label">MÉTODO DE ENVÍO SELECCIONADO</span>
                <div class="value" style="font-weight: bold; color: #5a189a;">{{ mb_strtoupper($tramite->tp_opcion_envio_poder) ?: '__________' }}</div>
            </td>
        </tr>
    </table>

    @if($tramite->tp_enviar_nombrede || $tramite->tp_ciudad_enviar)
        <table class="grid-table">
            <tr>
                <td style="width: 100%;" colspan="2">
                    <span class="label">ENVIAR A ECUADOR A NOMBRE DE:</span>
                    <div class="value">{{ $tramite->tp_enviar_nombrede ?: '__________' }}</div>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; padding-right: 10px;">
                    <span class="label">CIUDAD - PROVINCIA:</span>
                    <div class="value">{{ $tramite->tp_ciudad_enviar ?: '____' }} - {{ $tramite->tp_provincia ?: '____' }}</div>
                </td>
                <td style="width: 50%;">
                    <span class="label">TELÉFONOS (011593):</span>
                    <div class="value">{{ $tramite->tp_telefonos_enviar ?: '__________' }}</div>
                </td>
            </tr>
        </table>
    @endif

    <table style="width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 10px;">
        <tr>
            <td style="width: 55%; vertical-align: top; padding-right: 15px;">
                <span class="label">OBSERVACIONES INTERNAS</span>
                <div class="obs-box">
                    {!! nl2br(e($tramite->tp_observaciones ?: 'Ninguna observación adicional.')) !!}
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;">
                <table class="financial-table">
                    <tr>
                        <th>Valor del Trámite:</th>
                        <td>${{ number_format($tramite->tp_costo_tramite, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Monto Abonado:</th>
                        <td>${{ number_format($tramite->tp_abono_tramite, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <th>Saldo:</th>
                        <td>${{ number_format($tramite->tp_saldo, 2) }}</td>
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
                <!-- Reserved for Authorised Signature if needed, or leave empty -->
            </td>
        </tr>
    </table>

</body>
</html>
