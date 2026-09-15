<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Impuestos #{{ str_pad($tramite->id_tram_impuestos, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            size: letter portrait;
            margin: 5mm 9mm 5mm 9mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #0f172a;
            font-size: 8.5pt;
            line-height: 1.25;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 4px;
            margin-bottom: 4px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 105px;
        }
        .logo-cell img {
            max-width: 95px;
            max-height: 40px;
        }
        .title-cell {
            text-align: center;
        }
        .title-cell h1 {
            color: #0369a1;
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .title-cell p {
            color: #475569;
            margin: 1px 0 0 0;
            font-size: 8pt;
            font-weight: bold;
        }
        .info-cell {
            width: 165px;
            text-align: right;
            font-size: 7.5pt;
            color: #334155;
            line-height: 1.35;
        }

        /* Section Headings */
        .sec-title {
            background-color: #0284c7;
            color: white;
            padding: 3px 6px;
            font-size: 8.5pt;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 3px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .data-table td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .lbl {
            font-size: 7pt;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            display: block;
            margin-bottom: 1px;
        }
        .val {
            font-size: 8.5pt;
            font-weight: 600;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 1px;
            min-height: 14px;
            word-wrap: break-word;
        }

        /* Text Boxes */
        .box-text {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 4px 6px;
            border-radius: 3px;
            font-size: 8.5pt;
            color: #0f172a;
            min-height: 52px;
            line-height: 1.25;
        }

        /* Financial Table */
        .fin-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
        }
        .fin-table th {
            background-color: #f1f5f9;
            color: #334155;
            padding: 4px 6px;
            text-align: right;
            font-size: 8pt;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
        }
        .fin-table td {
            padding: 4px 6px;
            text-align: right;
            font-size: 9pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
        }
        .fin-table .row-total th,
        .fin-table .row-total td {
            background-color: #e0f2fe;
            color: #0369a1;
            font-size: 10pt;
            font-weight: 900;
            border-bottom: none;
        }

        /* Signatures */
        .sig-table {
            width: 100%;
            margin-top: 15px;
            text-align: center;
        }
        .sig-table td {
            width: 50%;
            vertical-align: bottom;
            padding: 0 20px;
        }
        .sig-line {
            border-top: 1.5px solid #1e293b;
            padding-top: 2px;
            font-size: 8pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }

        .footer-bar {
            text-align: center;
            font-size: 7pt;
            color: #64748b;
            margin-top: 6px;
            border-top: 1px solid #cbd5e1;
            padding-top: 2px;
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
                <strong>Oficina:</strong> {{ $tramite->ti_oficina ?: 'General' }}<br>
                <strong>Atendido por:</strong> {{ $tramite->usuario->name ?? 'N/A' }}<br>
                <strong>Firmar en:</strong> {{ $tramite->ti_firmar_en ?: 'Oficina' }}
            </td>
        </tr>
    </table>

    <!-- 1. DATOS DEL CONTRIBUYENTE -->
    <div class="sec-title">1. Datos del Contribuyente / Cliente</div>
    <table class="data-table">
        <tr>
            <td style="width: 50%;">
                <span class="lbl">Nombres y Apellidos Completos</span>
                <div class="val">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Identificación / ID</span>
                <div class="val">{{ $cliente->c_identificacion ?: 'S/I' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Teléfono / Celular</span>
                <div class="val">{{ $cliente->c_telefono ?: 'S/T' }}</div>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">
                <span class="lbl">Dirección Residencial</span>
                <div class="val">{{ $cliente->c_direccion ?: 'N/E' }} {{ $cliente->c_departamento ? 'Apt '.$cliente->c_departamento : '' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Ciudad / Estado / C.P.</span>
                <div class="val">{{ $cliente->c_ciudad ?: 'N/E' }}{{ $cliente->c_estado ? ', '.$cliente->c_estado : '' }} {{ $cliente->c_codpostal }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Correo Electrónico</span>
                <div class="val">{{ $cliente->c_email ?: 'S/E' }}</div>
            </td>
        </tr>
    </table>

    <!-- 2. DETALLES DE LA DECLARACIÓN TRIBUTARIA -->
    <div class="sec-title">2. Información Tributaria & Fiscal</div>
    <table class="data-table">
        <tr>
            <td style="width: 25%;">
                <span class="lbl">Aplicación ITIN</span>
                <div class="val">{{ $tramite->ti_itin ? 'SÍ (Aplica)' : 'NO' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Nº ITIN / Social Security</span>
                <div class="val">{{ $tramite->ti_nitin ?: 'N/E' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Año Fiscal de Reporte</span>
                <div class="val">{{ $tramite->ti_anio_reporte ?: 'N/E' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Fecha Ingreso EE.UU.</span>
                <div class="val">{{ $tramite->ti_fechain ? date('d/m/Y', strtotime($tramite->ti_fechain)) : 'N/E' }}</div>
            </td>
        </tr>
        <tr>
            <td style="width: 25%;">
                <span class="lbl">Estado Civil Declarado</span>
                <div class="val">{{ $tramite->ti_ecivil ?: 'N/E' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Profesión / Ocupación</span>
                <div class="val">{{ $tramite->ti_profesion ?: 'N/E' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Nº Dependientes</span>
                <div class="val">{{ $tramite->ti_dependientes ?: '0' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Método de Pago Declaración</span>
                <div class="val">{{ $tramite->ti_mpago ?: 'N/E' }}</div>
            </td>
        </tr>
    </table>

    <!-- 3. DETALLES DE CUENTA BANCARIA -->
    <div class="sec-title">3. Información Bancaria (Depósito / Débito)</div>
    <table class="data-table">
        <tr>
            <td style="width: 40%;">
                <span class="lbl">Entidad Bancaria</span>
                <div class="val">{{ $tramite->ti_banco ?: 'No especificado / Cheque' }}</div>
            </td>
            <td style="width: 30%;">
                <span class="lbl">Número de Cuenta</span>
                <div class="val">{{ $tramite->ti_ncuenta ?: 'N/E' }}</div>
            </td>
            <td style="width: 30%;">
                <span class="lbl">Número de Ruta (Routing)</span>
                <div class="val">{{ $tramite->ti_nruta ?: 'N/E' }}</div>
            </td>
        </tr>
    </table>

    <!-- 4. OBSERVACIONES & RESUMEN FINANCIERO -->
    <div class="sec-title">4. Observaciones & Liquidación Financiera</div>
    <table style="width: 100%; border-collapse: separate; border-spacing: 4px 0;">
        <tr>
            <td style="width: 58%; vertical-align: top;">
                <div class="box-text">
                    <strong style="color: #475569; font-size: 7pt; text-transform: uppercase; display: block; margin-bottom: 2px;">Observaciones / Notas Tributarias:</strong>
                    {!! nl2br(e($tramite->ti_observacion ?: 'Ninguna observación adicional.')) !!}
                </div>
            </td>
            <td style="width: 42%; vertical-align: top;">
                <table class="fin-table">
                    <tr>
                        <th>Honorarios Totales:</th>
                        <td>${{ number_format($tramite->ti_costo_tramite, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Abono Inicial:</th>
                        <td style="color: #059669;">${{ number_format($tramite->ti_abono_tramite, 2) }}</td>
                    </tr>
                    <tr class="row-total">
                        <th>Saldo Pendiente:</th>
                        <td style="{{ $tramite->ti_saldo > 0 ? 'color: #be123c;' : 'color: #059669;' }}">
                            ${{ number_format($tramite->ti_saldo, 2) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- 5. FIRMAS -->
    <table class="sig-table">
        <tr>
            <td>
                <div class="sig-line">Firma del Contribuyente<br><span style="font-size: 7pt; color: #475569; text-transform: none; font-weight: normal;">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</span></div>
            </td>
            <td>
                <div class="sig-line">Firma del Preparador de Impuestos<br><span style="font-size: 7pt; color: #475569; text-transform: none; font-weight: normal;">{{ $tramite->usuario->name ?? 'Personal Autorizado' }}</span></div>
            </td>
        </tr>
    </table>

    <div class="footer-bar">
        Comprobante de Trámite Tributario • NESISTEMA • Página 1 de 1
    </div>

</body>
</html>
