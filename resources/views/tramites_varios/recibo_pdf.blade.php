<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprobante de Trámite Vario #{{ str_pad($tramite->id_tramite_varios, 5, '0', STR_PAD_LEFT) }}</title>
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
            font-size: 13px; /* Slightly larger base size */
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        /* Header Table to replace Flexbox */
        .header-table {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 3px solid #004080;
            padding-bottom: 15px;
        }
        .header-logo {
            max-width: 180px; /* Slightly larger logo */
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
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #004080;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 15px;
            color: #fff;
            background-color: #004080;
            border-radius: 4px;
            padding: 6px 12px;
            border-left: 4px solid #C0A16B;
        }

        /* Field Row replacing flexbox with tables */
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
            width: 30%;
            font-weight: bold;
            color: #444;
        }
        .value-cell {
            width: 70%;
            color: #222;
        }

        /* Checkbox List */
        .checkbox-list {
            margin-top: 5px;
            margin-left: 20px;
            margin-bottom: 15px;
        }
        .checkbox-item {
            margin-bottom: 5px;
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
        <!-- HEADER -->
        <table class="header-table">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <!-- Adjust logo path to be absolute or base64 if needed, public_path is standard for DomPDF -->
                    @php
                        $logoPath = public_path('img/logo_impre.png');
                        $logoBase64 = '';
                        if(file_exists($logoPath)) {
                            $logoData = file_get_contents($logoPath);
                            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                        }
                    @endphp
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo" class="header-logo">
                    @else
                        <h2>NE SISTEMA</h2>
                    @endif
                </td>
                <td class="header-right">
                    <p><strong>Fecha:</strong> {{ $tramite->tv_fecha }}</p>
                    <p><strong>Oficina:</strong> {{ $tramite->tv_oficina }}</p>
                    <p><strong>Firmar en:</strong> {{ $tramite->tv_firmar_en }}</p>
                    <p><strong>Atendido por:</strong> {{ App\Models\User::find($tramite->id_usuario)->name ?? 'N/A' }}</p>
                </td>
            </tr>
        </table>

        <div class="title">COMPROBANTE DE TRÁMITE VARIOS #{{ str_pad($tramite->id_tramite_varios, 5, '0', STR_PAD_LEFT) }}</div>

        <!-- INFORMACIÓN DEL CLIENTE -->
        <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
        <table class="info-table">
            <tr>
                <td class="label-cell">Nombres Completos:</td>
                <td class="value-cell">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</td>
            </tr>
            <tr>
                <td class="label-cell">Identificación/RUC:</td>
                <td class="value-cell">{{ $cliente->c_identificacion }}</td>
            </tr>
            <tr>
                <td class="label-cell">Teléfono:</td>
                <td class="value-cell">{{ $cliente->c_telefono }}</td>
            </tr>
            <tr>
                <td class="label-cell">Dirección:</td>
                <td class="value-cell">{{ $cliente->c_direccion }}, {{ $cliente->c_ciudad }}, {{ $cliente->c_estado }}</td>
            </tr>
            <tr>
                <td class="label-cell">N° Apartamento:</td>
                <td class="value-cell">{{ $cliente->c_napartamento }}</td>
            </tr>
            <tr>
                <td class="label-cell">Email:</td>
                <td class="value-cell">{{ $cliente->c_email }}</td>
            </tr>
            <tr>
                <td class="label-cell">Código Postal:</td>
                <td class="value-cell">{{ $cliente->c_codpostal }}</td>
            </tr>
        </table>

        <!-- DETALLES DEL TRÁMITE -->
        <div class="section-title">DETALLES DEL TRÁMITE</div>
        <table class="info-table">
            <tr>
                <td class="label-cell">Motivo del Trámite:</td>
                <td class="value-cell">{{ $tramite->tv_motivo }}</td>
            </tr>
            <tr>
                <td class="label-cell">Razón del Trámite:</td>
                <td class="value-cell">{{ $tramite->tv_razon_t }}</td>
            </tr>
            <tr>
                <td class="label-cell">Tipo de Documento:</td>
                <td class="value-cell">{{ $tramite->tv_tip_documento }}</td>
            </tr>
            <tr>
                <td class="label-cell">Observaciones:</td>
                <td class="value-cell">{{ $tramite->tv_observaciones }}</td>
            </tr>
        </table>

        <div class="section-title">SERVICIOS SOLICITADOS</div>
        <div class="checkbox-list">
            <div class="checkbox-item">
                <span>{{ $tramite->tv_traducciones ? '[ X ]' : '[   ]' }}</span> Traducciones
            </div>
            <div class="checkbox-item">
                <span>{{ $tramite->tv_notarizacion ? '[ X ]' : '[   ]' }}</span> Notarización
            </div>
            <div class="checkbox-item">
                <span>{{ $tramite->tv_certificacion ? '[ X ]' : '[   ]' }}</span> Certificación
            </div>
            <div class="checkbox-item">
                <span>{{ $tramite->tv_apostilla ? '[ X ]' : '[   ]' }}</span> Apostilla
            </div>
        </div>

        <!-- INFORMACIÓN DE ENVÍO -->
        @if (!empty($tramite->tv_oenvio) && $tramite->tv_oenvio != 'Pick up')
        <div class="section-title">INFORMACIÓN DE ENVÍO</div>
        <table class="info-table">
            <tr>
                <td class="label-cell">Opción de Envío:</td>
                <td class="value-cell">{{ $tramite->tv_oenvio }}</td>
            </tr>
            <tr>
                <td class="label-cell">Nombre de Remitente:</td>
                <td class="value-cell">{{ $tramite->tv_nom_envio }}</td>
            </tr>
            <tr>
                <td class="label-cell">Ciudad de Envío:</td>
                <td class="value-cell">{{ $tramite->tv_ciudad }}</td>
            </tr>
            <tr>
                <td class="label-cell">Provincia de Envío:</td>
                <td class="value-cell">{{ $tramite->tv_provincia }}</td>
            </tr>
            <tr>
                <td class="label-cell">Teléfono de Envío:</td>
                <td class="value-cell">{{ $tramite->tv_telefono }}</td>
            </tr>
        </table>
        @else
        <div class="section-title">INFORMACIÓN DE ENTREGA</div>
        <table class="info-table">
            <tr>
                <td class="label-cell">Opción de Entrega:</td>
                <td class="value-cell">Pick up (Recoger en oficina)</td>
            </tr>
        </table>
        @endif

        <!-- TOTALS -->
        <div class="total-amount">
            <table class="total-table">
                <tr>
                    <td style="color: #555;">Valor del Trámite:</td>
                    <td>${{ number_format($tramite->tv_valor_tramite, 2) }}</td>
                </tr>
                <tr>
                    <td style="color: #555;">Abono del Trámite:</td>
                    <td style="color: green;">${{ number_format($tramite->tv_abono_tramite, 2) }}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #ccc; color: #555;">Saldo Pendiente:</td>
                    <td style="border-top: 1px solid #ccc; color: red;">${{ number_format($tramite->tv_saldo, 2) }}</td>
                </tr>
            </table>
            <div class="clearfix"></div>
        </div>

        <!-- SIGNATURES -->
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-text">FIRMA DEL CLIENTE</div>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-text">FIRMA NOTARIO/RESPONSABLE</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
