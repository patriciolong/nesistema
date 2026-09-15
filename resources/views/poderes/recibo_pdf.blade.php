<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Poder #{{ str_pad($tramite->id_tram_poderes, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            size: letter portrait;
            margin: 4mm 8mm 4mm 8mm;
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
            border-bottom: 2.5px solid #5a189a;
            padding-bottom: 5px;
            margin-bottom: 4px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 180px;
            text-align: left;
        }
        .logo-cell img {
            width: 170px;
            max-height: 60px;
            height: auto;
        }
        .title-cell {
            text-align: center;
        }
        .title-cell h1 {
            color: #5a189a;
            font-size: 15pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .title-cell p {
            color: #475569;
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            font-weight: bold;
        }
        .info-cell {
            width: 165px;
            text-align: right;
            font-size: 8pt;
            color: #334155;
            line-height: 1.35;
        }

        /* Section Headings */
        .sec-title {
            background-color: #5a189a;
            color: white;
            padding: 3px 6px;
            font-size: 8.5pt;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 2px;
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
            min-height: 28px;
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
            background-color: #f3e8ff;
            color: #5a189a;
            font-size: 10pt;
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
            margin-top: 5px;
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
                <h1>Trámite de Poder</h1>
                <p>Comprobante de Trámite #{{ str_pad($tramite->id_tram_poderes, 5, '0', STR_PAD_LEFT) }}</p>
            </td>
            <td class="info-cell">
                <strong>Fecha:</strong> {{ $tramite->tp_fecha ? date('d/m/Y', strtotime($tramite->tp_fecha)) : date('d/m/Y') }}<br>
                <strong>Oficina:</strong> {{ $tramite->tp_oficina ?: 'General' }}<br>
                <strong>Atendido por:</strong> {{ $tramite->usuario->name ?? 'N/A' }}<br>
                <strong>Firmar en:</strong> {{ $tramite->tp_firmar_en ?: 'Oficina' }}
            </td>
        </tr>
    </table>

    <!-- 1. OTORGANTE(S) DEL PODER -->
    <div class="sec-title">1. Persona que Otorga el Poder (Poderdante)</div>
    <table class="data-table">
        <tr>
            <td style="width: 45%;">
                <span class="lbl">Nombres y Apellidos Completos</span>
                <div class="val">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Identificación / Cédula / Pasaporte</span>
                <div class="val">{{ $cliente->c_identificacion ?: 'S/I' }}</div>
            </td>
            <td style="width: 15%;">
                <span class="lbl">Estado Civil</span>
                <div class="val">{{ mb_strtoupper($tramite->tp_estado_civil) ?: 'N/E' }}</div>
            </td>
            <td style="width: 15%;">
                <span class="lbl">Teléfono</span>
                <div class="val">{{ $cliente->c_telefono ?: 'S/T' }}</div>
            </td>
        </tr>
        <tr>
            <td style="width: 45%;">
                <span class="lbl">Dirección Residencial</span>
                <div class="val">{{ $cliente->c_direccion ?: 'N/E' }} {{ $cliente->c_departamento ? 'Apt '.$cliente->c_departamento : '' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Ciudad / Estado / C.P.</span>
                <div class="val">{{ $cliente->c_ciudad ?: 'N/E' }}{{ $cliente->c_estado ? ', '.$cliente->c_estado : '' }} {{ $cliente->c_codpostal }}</div>
            </td>
            <td colspan="2" style="width: 30%;">
                <span class="lbl">Correo Electrónico</span>
                <div class="val">{{ $cliente->c_email ?: 'S/E' }}</div>
            </td>
        </tr>
    </table>

    @if($tramite->tp_nombre2 || $tramite->tp_identificacion2)
    <table class="data-table" style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 2px;">
        <tr>
            <td style="width: 50%;">
                <span class="lbl">2do Otorgante - Nombres y Apellidos</span>
                <div class="val">{{ $tramite->tp_nombre2 }} {{ $tramite->tp_apellido2 }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Identificación</span>
                <div class="val">{{ $tramite->tp_identificacion2 ?: 'S/I' }}</div>
            </td>
            <td style="width: 25%;">
                <span class="lbl">Teléfono</span>
                <div class="val">{{ $tramite->tp_telefono2 ?: 'S/T' }}</div>
            </td>
        </tr>
    </table>
    @endif

    <!-- 2. A FAVOR DE QUIEN OTORGA EL PODER -->
    <div class="sec-title">2. Persona a Favor de Quien Otorga el Poder (Apoderado)</div>
    <table class="data-table">
        <tr>
            <td style="width: 65%;">
                <span class="lbl">Nombres y Apellidos Completos (1ra Persona)</span>
                <div class="val">{{ $tramite->tp_nombres_otorga_poder ?: 'N/E' }}</div>
            </td>
            <td style="width: 35%;">
                <span class="lbl">Nº Cédula / Identificación</span>
                <div class="val">{{ $tramite->tp_cedulla_otorga_poder ?: 'S/I' }}</div>
            </td>
        </tr>
        @if($tramite->tp_nombres_otorga_poder2 || $tramite->tp_cedulla_otorga_poder2)
        <tr>
            <td style="width: 65%;">
                <span class="lbl">Nombres y Apellidos Completos (2da Persona)</span>
                <div class="val">{{ $tramite->tp_nombres_otorga_poder2 }}</div>
            </td>
            <td style="width: 35%;">
                <span class="lbl">Nº Cédula / Identificación</span>
                <div class="val">{{ $tramite->tp_cedulla_otorga_poder2 ?: 'S/I' }}</div>
            </td>
        </tr>
        @endif
    </table>

    <!-- 3. RAZÓN DEL PODER -->
    <div class="sec-title">3. Razón / Objeto del Poder</div>
    <div class="box-text">
        {!! nl2br(e($tramite->tp_razon_otorga_poder ?: 'Sin especificación adicional.')) !!}
    </div>

    <!-- 4. ENVÍO Y ENTREGA -->
    <div class="sec-title">4. Envío y Entrega</div>
    <table class="data-table">
        <tr>
            <td style="width: 30%;">
                <span class="lbl">Método de Envío</span>
                <div class="val" style="color: #5a189a; font-weight: bold;">{{ mb_strtoupper($tramite->tp_opcion_envio_poder) ?: 'OFICINA' }}</div>
            </td>
            <td style="width: 70%;">
                <span class="lbl">Destinatario en Destino / Ciudad / Teléfono</span>
                <div class="val">
                    @if($tramite->tp_enviar_nombrede)
                        {{ $tramite->tp_enviar_nombrede }} ({{ $tramite->tp_ciudad_enviar ?: '' }} - {{ $tramite->tp_provincia ?: '' }}) - Tel: {{ $tramite->tp_telefonos_enviar ?: 'S/T' }}
                    @else
                        Entrega directa en oficina / Según instrucción
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- 5. OBSERVACIONES & RESUMEN FINANCIERO -->
    <div class="sec-title">5. Observaciones & Liquidación Financiera</div>
    <table style="width: 100%; border-collapse: separate; border-spacing: 4px 0;">
        <tr>
            <td style="width: 58%; vertical-align: top;">
                <div class="box-text" style="min-height: 50px;">
                    <strong style="color: #475569; font-size: 7pt; text-transform: uppercase; display: block; margin-bottom: 2px;">Notas / Observaciones:</strong>
                    {!! nl2br(e($tramite->tp_observaciones ?: 'Ninguna observación especial registrada.')) !!}
                </div>
            </td>
            <td style="width: 42%; vertical-align: top;">
                <table class="fin-table">
                    <tr>
                        <th>Costo del Trámite:</th>
                        <td>${{ number_format($tramite->tp_costo_tramite, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Monto Abonado:</th>
                        <td style="color: #059669;">${{ number_format($tramite->tp_abono_tramite, 2) }}</td>
                    </tr>
                    <tr class="row-total">
                        <th>Saldo Pendiente:</th>
                        <td style="{{ $tramite->tp_saldo > 0 ? 'color: #be123c;' : 'color: #059669;' }}">
                            ${{ number_format($tramite->tp_saldo, 2) }}
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
                <div class="sig-line">Firma del Cliente / Otorgante<br><span style="font-size: 7pt; color: #475569; text-transform: none; font-weight: normal;">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</span></div>
            </td>
            <td>
                <div class="sig-line">Firma y Sello Notaría / Asesor<br><span style="font-size: 7pt; color: #475569; text-transform: none; font-weight: normal;">{{ $tramite->usuario->name ?? 'Personal Autorizado' }}</span></div>
            </td>
        </tr>
    </table>

    <div class="footer-bar">
        Comprobante de Recepción de Trámite • Sistema Notarial & Jurídico NESISTEMA • Página 1 de 1
    </div>

</body>
</html>
