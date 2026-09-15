<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Divorcio #{{ str_pad($tramite->id_tram_div, 5, '0', STR_PAD_LEFT) }}</title>
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
            min-height: 26px;
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
                <h1>Registro de Divorcio</h1>
                <p>Comprobante de Trámite #{{ str_pad($tramite->id_tram_div, 5, '0', STR_PAD_LEFT) }}</p>
            </td>
            <td class="info-cell">
                <strong>Fecha:</strong> {{ $tramite->td_fecha ? date('d/m/Y', strtotime($tramite->td_fecha)) : date('d/m/Y') }}<br>
                <strong>Atendido por:</strong> {{ $tramite->usuario->name ?? 'N/A' }}<br>
                <strong>Firmar en:</strong> {{ $tramite->td_firmar_en ?: 'Oficina' }}
            </td>
        </tr>
    </table>

    <!-- 1. DATOS DEL CLIENTE -->
    <div class="sec-title">1. Datos del Solicitante / Cliente</div>
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
                <span class="lbl">Ciudad / Estado</span>
                <div class="val">{{ $cliente->c_ciudad ?: 'N/E' }}{{ $cliente->c_estado ? ', '.$cliente->c_estado : '' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Correo Electrónico</span>
                <div class="val">{{ $cliente->c_email ?: 'S/E' }}</div>
            </td>
        </tr>
    </table>

    <!-- 2. DETALLES Y MODALIDAD DEL DIVORCIO -->
    <div class="sec-title">2. Modalidad & Situación Conyugal</div>
    <table class="chk-grid">
        <tr>
            <td style="width: 35%; border-right: 1px solid #e2e8f0;">
                <strong style="color: #64748b; font-size: 6.5pt; text-transform: uppercase; display: block; margin-bottom: 2px;">Tipo de Divorcio:</strong>
                <span class="chk-box">{!! $tramite->td_controvertido ? 'X' : '&nbsp;' !!}</span> Causal &nbsp;
                <span class="chk-box">{!! $tramite->td_consensual ? 'X' : '&nbsp;' !!}</span> Consensual &nbsp;
                <span class="chk-box">{!! $tramite->td_notarial ? 'X' : '&nbsp;' !!}</span> Notarial
            </td>
            <td style="width: 35%; border-right: 1px solid #e2e8f0;">
                <strong style="color: #64748b; font-size: 6.5pt; text-transform: uppercase; display: block; margin-bottom: 2px;">Situación Familiar:</strong>
                <span class="chk-box">{!! $tramite->td_separados ? 'X' : '&nbsp;' !!}</span> Separados &nbsp;
                <span class="chk-box">{!! $tramite->td_noseparados ? 'X' : '&nbsp;' !!}</span> No Sep. &nbsp;
                <span class="chk-box">{!! $tramite->td_hijos ? 'X' : '&nbsp;' !!}</span> Con Hijos
            </td>
            <td style="width: 30%;">
                <strong style="color: #64748b; font-size: 6.5pt; text-transform: uppercase; display: block; margin-bottom: 2px;">Documentos Físicos:</strong>
                <span class="chk-box">{!! $tramite->td_ep_matrimonio ? 'X' : '&nbsp;' !!}</span> P. Matrimonio &nbsp;
                <span class="chk-box">{!! $tramite->td_ep_nacimiento ? 'X' : '&nbsp;' !!}</span> P. Nacimiento
            </td>
        </tr>
    </table>

    <!-- 3. ANTECEDENTES MATRIMONIALES -->
    <div class="sec-title">3. Antecedentes Matrimoniales & Causa</div>
    <table class="data-table">
        <tr>
            <td style="width: 35%;">
                <span class="lbl">Lugar de Matrimonio</span>
                <div class="val">{{ $tramite->td_lugar_matrimonio ?: 'N/E' }}</div>
            </td>
            <td style="width: 20%;">
                <span class="lbl">Fecha Matrimonio</span>
                <div class="val">{{ $tramite->td_fecha_matrimonio ? date('d/m/Y', strtotime($tramite->td_fecha_matrimonio)) : 'N/E' }}</div>
            </td>
            <td style="width: 20%;">
                <span class="lbl">Tiempo Separación</span>
                <div class="val">{{ $tramite->td_tiempo_separacion ?: 'N/E' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Hijos a Cargo de</span>
                <div class="val">{{ $tramite->td_con_quien_vive ?: 'N/E' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span class="lbl">Motivo / Causal de Divorcio</span>
                <div class="val">{{ $tramite->td_motivo_divorcio ?: 'No especificado' }}</div>
            </td>
        </tr>
    </table>

    <!-- 4. DATOS DEL CÓNYUGE -->
    <div class="sec-title">4. Información del Cónyuge</div>
    <table class="data-table">
        <tr>
            <td style="width: 45%;">
                <span class="lbl">Nombres y Apellidos del Cónyuge</span>
                <div class="val">{{ $tramite->td_nombre_c ?: 'N/E' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Identificación / Cédula</span>
                <div class="val">{{ $tramite->td_identificacion_c ?: 'S/I' }}</div>
            </td>
            <td style="width: 30%;">
                <span class="lbl">Teléfono Cónyuge</span>
                <div class="val">{{ $tramite->td_telefono_c ?: 'S/T' }}</div>
            </td>
        </tr>
        <tr>
            <td style="width: 45%;">
                <span class="lbl">Dirección Cónyuge</span>
                <div class="val">{{ $tramite->td_direccion_c ?: 'N/E' }} {{ $tramite->td_apt_c ? 'Apt '.$tramite->td_apt_c : '' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Ciudad / Estado</span>
                <div class="val">{{ $tramite->td_ciudad_c ?: 'N/E' }}{{ $tramite->td_estado_c ? ', '.$tramite->td_estado_c : '' }}</div>
            </td>
            <td style="width: 30%;">
                <span class="lbl">Código Postal</span>
                <div class="val">{{ $tramite->td_cpostal_c ?: 'N/E' }}</div>
            </td>
        </tr>
    </table>

    <!-- 5. CONTACTO EN ECUADOR, OBSERVACIONES & FINANZAS -->
    <div class="sec-title">5. Contacto en Ecuador, Observaciones & Liquidación</div>
    <table style="width: 100%; border-collapse: separate; border-spacing: 4px 0;">
        <tr>
            <td style="width: 58%; vertical-align: top;">
                <table class="data-table" style="margin-bottom: 3px;">
                    <tr>
                        <td style="width: 50%;">
                            <span class="lbl">Contacto en Ecuador</span>
                            <div class="val">{{ $tramite->td_estado_contac_ecuador ?: 'N/E' }}</div>
                        </td>
                        <td style="width: 50%;">
                            <span class="lbl">Teléfono Ecuador</span>
                            <div class="val">{{ $tramite->td_tel_ecuador ?: 'S/T' }}</div>
                        </td>
                    </tr>
                </table>
                <div class="box-text" style="min-height: 38px;">
                    <strong style="color: #64748b; font-size: 6.5pt; text-transform: uppercase; display: block; margin-bottom: 2px;">Observaciones:</strong>
                    {!! nl2br(e($tramite->td_observaciones ?: 'Ninguna observación especial.')) !!}
                </div>
            </td>
            <td style="width: 42%; vertical-align: top;">
                <table class="fin-table">
                    <tr>
                        <th>Valor del Trámite:</th>
                        <td>${{ number_format($tramite->td_valor, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Abono Inicial:</th>
                        <td style="color: #059669;">${{ number_format($tramite->td_abono, 2) }}</td>
                    </tr>
                    <tr class="row-total">
                        <th>Saldo Pendiente:</th>
                        <td style="{{ $tramite->td_saldo > 0 ? 'color: #be123c;' : 'color: #059669;' }}">
                            ${{ number_format($tramite->td_saldo, 2) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- 6. FIRMAS -->
    <table class="sig-table">
        <tr>
            <td>
                <div class="sig-line">Firma del Solicitante<br><span style="font-size: 6pt; color: #64748b; text-transform: none;">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</span></div>
            </td>
            <td>
                <div class="sig-line">Firma del Asesor Jurídico<br><span style="font-size: 6pt; color: #64748b; text-transform: none;">{{ $tramite->usuario->name ?? 'Personal Autorizado' }}</span></div>
            </td>
        </tr>
    </table>

    <div class="footer-bar">
        Comprobante de Recepción de Información para Divorcio • NESISTEMA • Página 1 de 1
    </div>

</body>
</html>
