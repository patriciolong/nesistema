<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Trámite Vario #{{ str_pad($tramite->id_tramite_varios, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            size: letter portrait;
            margin: 7mm 10mm 7mm 10mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            font-size: 7.5pt;
            line-height: 1.2;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #004080;
            padding-bottom: 5px;
            margin-bottom: 6px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 100px;
        }
        .logo-cell img {
            max-width: 90px;
            max-height: 38px;
        }
        .title-cell {
            text-align: center;
        }
        .title-cell h1 {
            color: #004080;
            font-size: 13pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .title-cell p {
            color: #64748b;
            margin: 1px 0 0 0;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .info-cell {
            width: 155px;
            text-align: right;
            font-size: 7pt;
            color: #475569;
            line-height: 1.3;
        }

        /* Section Headings */
        .sec-title {
            background-color: #004080;
            color: white;
            padding: 2.5px 6px;
            font-size: 7.5pt;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 3px;
            border-radius: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-left: 3px solid #C0A16B;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }
        .data-table td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 7.5pt;
        }
        .lbl {
            font-size: 6.5pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            display: block;
            margin-bottom: 1px;
        }
        .val {
            font-size: 7.5pt;
            font-weight: 600;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 1px;
            min-height: 12px;
            word-wrap: break-word;
        }

        /* Checkbox badges */
        .chk-grid {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            margin-bottom: 4px;
        }
        .chk-grid td {
            padding: 3px 5px;
            font-size: 7pt;
        }
        .chk-box {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #004080;
            text-align: center;
            line-height: 10px;
            font-weight: bold;
            font-size: 8pt;
            color: #004080;
            margin-right: 3px;
            vertical-align: middle;
            background: #fff;
        }

        /* Text Boxes */
        .box-text {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 4px 6px;
            border-radius: 3px;
            font-size: 7.5pt;
            color: #1e293b;
            min-height: 44px;
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
            color: #475569;
            padding: 3.5px 5px;
            text-align: right;
            font-size: 7pt;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
        }
        .fin-table td {
            padding: 3.5px 5px;
            text-align: right;
            font-size: 8pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
        }
        .fin-table .row-total th,
        .fin-table .row-total td {
            background-color: #e6f2ff;
            color: #004080;
            font-size: 9pt;
            font-weight: 900;
            border-bottom: none;
        }

        /* Signatures */
        .sig-table {
            width: 100%;
            margin-top: 14px;
            text-align: center;
        }
        .sig-table td {
            width: 50%;
            vertical-align: bottom;
            padding: 0 20px;
        }
        .sig-line {
            border-top: 1px solid #334155;
            padding-top: 2px;
            font-size: 7pt;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
        }

        .footer-bar {
            text-align: center;
            font-size: 6.5pt;
            color: #94a3b8;
            margin-top: 8px;
            border-top: 1px solid #e2e8f0;
            padding-top: 3px;
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
                <h1>Trámite Varios</h1>
                <p>Comprobante de Trámite #{{ str_pad($tramite->id_tramite_varios, 5, '0', STR_PAD_LEFT) }}</p>
            </td>
            <td class="info-cell">
                <strong>Fecha:</strong> {{ $tramite->tv_fecha ? date('d/m/Y', strtotime($tramite->tv_fecha)) : date('d/m/Y') }}<br>
                <strong>Oficina:</strong> {{ $tramite->tv_oficina ?: 'General' }}<br>
                <strong>Atendido por:</strong> {{ App\Models\User::find($tramite->id_usuario)->name ?? 'N/A' }}<br>
                <strong>Firmar en:</strong> {{ $tramite->tv_firmar_en ?: 'Oficina' }}
            </td>
        </tr>
    </table>

    <!-- 1. DATOS DEL CLIENTE -->
    <div class="sec-title">1. Datos del Solicitante / Cliente</div>
    <table class="data-table">
        <tr>
            <td style="width: 50%;">
                <span class="lbl">Nombres y Apellidos Completos</span>
                <div class="val">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Identificación / Cédula / RUC</span>
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
                <div class="val">{{ $cliente->c_direccion ?: 'N/E' }} {{ $cliente->c_napartamento ? 'Apt '.$cliente->c_napartamento : '' }}</div>
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

    <!-- 2. DETALLES DEL TRÁMITE -->
    <div class="sec-title">2. Objeto & Naturaleza del Trámite</div>
    <table class="data-table">
        <tr>
            <td style="width: 35%;">
                <span class="lbl">Motivo del Trámite</span>
                <div class="val">{{ $tramite->tv_motivo ?: 'N/E' }}</div>
            </td>
            <td style="width: 35%;">
                <span class="lbl">Razón del Trámite</span>
                <div class="val">{{ $tramite->tv_razon_t ?: 'N/E' }}</div>
            </td>
            <td style="width: 30%;">
                <span class="lbl">Tipo de Documento</span>
                <div class="val">{{ $tramite->tv_tip_documento ?: 'N/E' }}</div>
            </td>
        </tr>
    </table>

    <!-- 3. SERVICIOS SOLICITADOS & ENVÍO -->
    <div class="sec-title">3. Servicios Solicitados & Modalidad de Entrega</div>
    <table class="chk-grid">
        <tr>
            <td style="width: 45%; border-right: 1px solid #e2e8f0;">
                <strong style="color: #64748b; font-size: 6.5pt; text-transform: uppercase; display: block; margin-bottom: 2px;">Servicios Incluidos:</strong>
                <span class="chk-box">{!! $tramite->tv_traducciones ? 'X' : '&nbsp;' !!}</span> Traducciones &nbsp;
                <span class="chk-box">{!! $tramite->tv_notarizacion ? 'X' : '&nbsp;' !!}</span> Notarización &nbsp;
                <span class="chk-box">{!! $tramite->tv_certificacion ? 'X' : '&nbsp;' !!}</span> Certificación &nbsp;
                <span class="chk-box">{!! $tramite->tv_apostilla ? 'X' : '&nbsp;' !!}</span> Apostilla
            </td>
            <td style="width: 55%;">
                <strong style="color: #64748b; font-size: 6.5pt; text-transform: uppercase; display: block; margin-bottom: 2px;">Entrega / Destino:</strong>
                @if(!empty($tramite->tv_oenvio) && $tramite->tv_oenvio != 'Pick up')
                    <span style="font-weight: bold; color: #004080;">{{ $tramite->tv_oenvio }}</span> &bull; 
                    A: {{ $tramite->tv_nom_envio ?: 'N/E' }} ({{ $tramite->tv_ciudad ?: '' }} - {{ $tramite->tv_provincia ?: '' }}) - Tel: {{ $tramite->tv_telefono ?: 'S/T' }}
                @else
                    <span style="font-weight: bold; color: #059669;">Pick up (Retiro directo en ventanilla de oficina)</span>
                @endif
            </td>
        </tr>
    </table>

    <!-- 4. OBSERVACIONES & RESUMEN FINANCIERO -->
    <div class="sec-title">4. Observaciones & Liquidación Financiera</div>
    <table style="width: 100%; border-collapse: separate; border-spacing: 4px 0;">
        <tr>
            <td style="width: 58%; vertical-align: top;">
                <div class="box-text">
                    <strong style="color: #64748b; font-size: 6.5pt; text-transform: uppercase; display: block; margin-bottom: 2px;">Observaciones Internas:</strong>
                    {!! nl2br(e($tramite->tv_observaciones ?: 'Ninguna observación especial registrada.')) !!}
                </div>
            </td>
            <td style="width: 42%; vertical-align: top;">
                <table class="fin-table">
                    <tr>
                        <th>Valor del Trámite:</th>
                        <td>${{ number_format($tramite->tv_valor_tramite, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Abono del Trámite:</th>
                        <td style="color: #059669;">${{ number_format($tramite->tv_abono_tramite, 2) }}</td>
                    </tr>
                    <tr class="row-total">
                        <th>Saldo Pendiente:</th>
                        <td style="{{ $tramite->tv_saldo > 0 ? 'color: #be123c;' : 'color: #059669;' }}">
                            ${{ number_format($tramite->tv_saldo, 2) }}
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
                <div class="sig-line">Firma del Cliente<br><span style="font-size: 6pt; color: #64748b; text-transform: none;">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</span></div>
            </td>
            <td>
                <div class="sig-line">Firma Notario / Responsable<br><span style="font-size: 6pt; color: #64748b; text-transform: none;">{{ App\Models\User::find($tramite->id_usuario)->name ?? 'Personal Autorizado' }}</span></div>
            </td>
        </tr>
    </table>

    <div class="footer-bar">
        Comprobante de Trámite Notarial • NESISTEMA • Página 1 de 1
    </div>

</body>
</html>
