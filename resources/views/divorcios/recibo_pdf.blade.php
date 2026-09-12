<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprobante de Trámite de Divorcio #{{ str_pad($tramite->id_tram_div, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* Brand Colors */
        :root {
            --primary-color: #004080;
            --accent-color: #C0A16B;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        
        /* Header */
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #004080;
            padding-bottom: 10px;
        }
        .header table {
            width: 100%;
            border: none;
        }
        .header td {
            vertical-align: middle;
            border: none;
        }
        .logo {
            max-width: 200px;
        }
        .header-info {
            text-align: right;
            font-size: 10px;
        }
        .header-info strong {
            color: #004080;
        }
        
        /* Title */
        .title-box {
            text-align: center;
            margin-bottom: 15px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            color: #004080;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        /* Sections */
        .section-title {
            background-color: #004080;
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            margin-top: 15px;
            margin-bottom: 8px;
            font-size: 11px;
            border-left: 4px solid #C0A16B;
            text-transform: uppercase;
        }

        /* Layout Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table th {
            text-align: left;
            font-weight: bold;
            color: #004080;
            padding: 4px 2px;
            width: 25%;
            font-size: 10px;
        }
        .info-table td {
            padding: 4px 2px;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        .checkbox-item {
            display: inline-block;
            width: 30%;
            margin-bottom: 5px;
        }
        .check-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #004080;
            text-align: center;
            line-height: 12px;
            font-weight: bold;
            color: #004080;
            margin-right: 4px;
        }

        /* Financial Block */
        .financial-box {
            width: 40%;
            float: right;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-top: 20px;
        }
        .financial-table {
            width: 100%;
            border-collapse: collapse;
        }
        .financial-table th {
            text-align: left;
            padding: 3px 0;
            color: #555;
            font-weight: normal;
        }
        .financial-table td {
            text-align: right;
            padding: 3px 0;
            font-weight: bold;
        }
        .financial-table tr.total th,
        .financial-table tr.total td {
            color: #004080;
            font-size: 13px;
            border-top: 1px solid #C0A16B;
            padding-top: 5px;
            margin-top: 2px;
        }

        /* Signature Block */
        .signature-section {
            width: 100%;
            margin-top: 60px;
            clear: both;
        }
        .signature-box {
            width: 45%;
            float: left;
            text-align: center;
        }
        .signature-box.right {
            float: right;
        }
        .signature-line {
            border-top: 1px solid #004080;
            width: 80%;
            margin: 0 auto 5px auto;
        }
        .signature-name {
            font-weight: bold;
            color: #333;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('img/logo_impre.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
    @endphp

    <div class="header">
        <table>
            <tr>
                <td style="width: 50%;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" class="logo" alt="Logo">
                    @else
                        <h2 style="color: #004080; margin: 0;">NESISTEMA</h2>
                    @endif
                </td>
                <td class="header-info">
                    <strong>Fecha:</strong> {{ date('d/m/Y', strtotime($tramite->td_fecha)) }}<br>
                    <strong>Nº Trámite:</strong> {{ str_pad($tramite->id_tram_div, 5, '0', STR_PAD_LEFT) }}<br>
                    <strong>Atendido por:</strong> {{ $tramite->usuario->name ?? 'Usuario Sistema' }}<br>
                    <strong>Firma en:</strong> {{ $tramite->td_firmar_en }}
                </td>
            </tr>
        </table>
    </div>

    <div class="title-box">
        <h1 class="title">REGISTRO DE DIVORCIO</h1>
        <p class="subtitle">Comprobante de Recepción de Trámite</p>
    </div>

    <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
    <table class="info-table">
        <tr>
            <th>Identificación:</th>
            <td>{{ $cliente->c_identificacion }}</td>
            <th>Nombres:</th>
            <td>{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</td>
        </tr>
        <tr>
            <th>Teléfono:</th>
            <td>{{ $cliente->c_telefono }}</td>
            <th>Dirección:</th>
            <td>{{ $cliente->c_direccion }} {{ $cliente->c_departamento ? 'Apt: '.$cliente->c_departamento : '' }}</td>
        </tr>
        <tr>
            <th>Ciudad/Estado:</th>
            <td>{{ $cliente->c_ciudad }} / {{ $cliente->c_estado }}</td>
            <th>Email:</th>
            <td>{{ $cliente->c_email }}</td>
        </tr>
    </table>

    <div class="section-title">DETALLES DEL TRÁMITE</div>
    <table style="width: 100%; margin-bottom: 15px; border-collapse: collapse;">
        <!-- TIPO DE DIVORCIO -->
        <tr>
            <td colspan="3" style="padding: 8px 0 2px 0; font-weight: bold; font-size: 10px; color: #666; border-bottom: 1px solid #eee;">
                TIPO DE DIVORCIO
            </td>
        </tr>
        <tr>
            <td style="width: 33%; padding: 6px 0;">
                <span class="check-box">{!! $tramite->td_controvertido ? 'X' : '&nbsp;' !!}</span> Causal (Controvertido)
            </td>
            <td style="width: 33%; padding: 6px 0;">
                <span class="check-box">{!! $tramite->td_consensual ? 'X' : '&nbsp;' !!}</span> Consensual
            </td>
            <td style="width: 33%; padding: 6px 0;">
                <span class="check-box">{!! $tramite->td_notarial ? 'X' : '&nbsp;' !!}</span> Notarial
            </td>
        </tr>

        <!-- SITUACIÓN FAMILIAR -->
        <tr>
            <td colspan="3" style="padding: 8px 0 2px 0; font-weight: bold; font-size: 10px; color: #666; border-bottom: 1px solid #eee;">
                SITUACIÓN CONYUGAL Y FAMILIAR
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 0;">
                <span class="check-box">{!! $tramite->td_separados ? 'X' : '&nbsp;' !!}</span> Separados
            </td>
            <td style="padding: 6px 0;">
                <span class="check-box">{!! $tramite->td_noseparados ? 'X' : '&nbsp;' !!}</span> No Separados
            </td>
            <td style="padding: 6px 0;">
                <span class="check-box">{!! $tramite->td_hijos ? 'X' : '&nbsp;' !!}</span> Hijos Menores
            </td>
        </tr>

        <!-- DOCUMENTOS ENTREGADOS -->
        <tr>
            <td colspan="3" style="padding: 8px 0 2px 0; font-weight: bold; font-size: 10px; color: #666; border-bottom: 1px solid #eee;">
                DOCUMENTOS FÍSICOS ENTREGADOS
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 6px 0;">
                <span class="check-box">{!! $tramite->td_ep_matrimonio ? 'X' : '&nbsp;' !!}</span> Partida de Matrimonio
            </td>
            <td style="padding: 6px 0;">
                <span class="check-box">{!! $tramite->td_ep_nacimiento ? 'X' : '&nbsp;' !!}</span> Partida de Nacimiento
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <th>Lugar Matrimonio:</th>
            <td>{{ $tramite->td_lugar_matrimonio }}</td>
            <th>Fecha Matrimonio:</th>
            <td>{{ $tramite->td_fecha_matrimonio ? date('d/m/Y', strtotime($tramite->td_fecha_matrimonio)) : '' }}</td>
        </tr>
        <tr>
            <th>Tiempo Separación:</th>
            <td>{{ $tramite->td_tiempo_separacion }}</td>
            <th>Hijos a cargo de:</th>
            <td>{{ $tramite->td_con_quien_vive }}</td>
        </tr>
        <tr>
            <th>Motivo Divorcio:</th>
            <td colspan="3">{{ $tramite->td_motivo_divorcio }}</td>
        </tr>
    </table>

    <div class="section-title">INFORMACIÓN DEL CÓNYUGE</div>
    <table class="info-table">
        <tr>
            <th>Nombres:</th>
            <td>{{ $tramite->td_nombre_c }}</td>
            <th>Identificación:</th>
            <td>{{ $tramite->td_identificacion_c }}</td>
        </tr>
        <tr>
            <th>Teléfono:</th>
            <td>{{ $tramite->td_telefono_c }}</td>
            <th>Dirección:</th>
            <td>{{ $tramite->td_direccion_c }} {{ $tramite->td_apt_c ? 'Apt: '.$tramite->td_apt_c : '' }}</td>
        </tr>
        <tr>
            <th>Ciudad/Estado:</th>
            <td>{{ $tramite->td_ciudad_c }} / {{ $tramite->td_estado_c }}</td>
            <th>C. Postal:</th>
            <td>{{ $tramite->td_cpostal_c }}</td>
        </tr>
    </table>

    <div class="section-title">CONTACTO EN ECUADOR & OBSERVACIONES</div>
    <table class="info-table">
        <tr>
            <th>Contacto Ecuador:</th>
            <td>{{ $tramite->td_estado_contac_ecuador }}</td>
            <th>Tel. Ecuador:</th>
            <td>{{ $tramite->td_tel_ecuador }}</td>
        </tr>
        <tr>
            <th>Observaciones:</th>
            <td colspan="3">{{ $tramite->td_observaciones }}</td>
        </tr>
    </table>

    <div class="clearfix">
        <div class="financial-box">
            <table class="financial-table">
                <tr>
                    <th>Valor del Trámite:</th>
                    <td>$ {{ number_format($tramite->td_valor, 2) }}</td>
                </tr>
                <tr>
                    <th>Abono Inicial:</th>
                    <td>$ {{ number_format($tramite->td_abono, 2) }}</td>
                </tr>
                <tr class="total">
                    <th>SALDO PENDIENTE:</th>
                    <td>$ {{ number_format($tramite->td_saldo, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">Firma del Cliente</div>
            <div style="font-size: 9px; color: #666;">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</div>
        </div>
        <div class="signature-box right">
            <div class="signature-line"></div>
            <div class="signature-name">Firma del Asesor</div>
            <div style="font-size: 9px; color: #666;">{{ $tramite->usuario->name ?? '' }}</div>
        </div>
    </div>

    <div class="footer">
        Este documento es un comprobante de recepción de información para iniciar su trámite de divorcio.<br>
        Generado el {{ date('d/m/Y H:i') }} - Sistema de Gestión
    </div>
</body>
</html>
