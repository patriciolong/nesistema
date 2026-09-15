<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte Ejecutivo de Cartera</title>
    <style>
        @page {
            margin: 12mm 15mm 15mm 15mm;
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
            border-bottom: 2px solid #e11d48; /* Rose / Red highlight */
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 120px;
        }
        .logo-cell img {
            width: 100%;
            max-width: 100px;
        }
        .title-cell {
            text-align: center;
        }
        .title-cell h1 {
            color: #881337;
            font-size: 14pt;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .title-cell p {
            color: #64748b;
            margin: 0;
            font-size: 8.5pt;
            font-weight: bold;
        }
        .info-cell {
            width: 160px;
            text-align: right;
            font-size: 8pt;
            color: #475569;
            line-height: 1.4;
        }

        /* KPI Cards Table */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin-bottom: 15px;
        }
        .kpi-card {
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 8px 10px;
            border-radius: 4px;
            text-align: center;
        }
        .kpi-card.highlight {
            border-color: #fecdd3;
            background-color: #fff1f2;
        }
        .kpi-title {
            font-size: 7pt;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 3px;
        }
        .kpi-card.highlight .kpi-title {
            color: #e11d48;
        }
        .kpi-val {
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
        }
        .kpi-card.highlight .kpi-val {
            color: #be123c;
        }
        .kpi-sub {
            font-size: 7pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* Section Title */
        .section-header {
            background-color: #0f172a;
            color: white;
            padding: 4px 8px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 8px;
            border-radius: 3px;
        }

        /* Tables */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .report-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 6px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        .report-table th.text-right {
            text-align: right;
        }
        .report-table th.text-center {
            text-align: center;
        }
        .report-table td {
            padding: 5px 6px;
            border: 1px solid #e2e8f0;
            font-size: 8pt;
            color: #334155;
        }
        .report-table td.text-right {
            text-align: right;
        }
        .report-table td.text-center {
            text-align: center;
        }
        .report-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .report-table tfoot td {
            background-color: #f1f5f9;
            font-weight: bold;
            border-top: 2px solid #94a3b8;
        }

        .badge-saldo {
            font-weight: bold;
            color: #be123c;
        }

        /* Footer */
        .footer-note {
            margin-top: 20px;
            font-size: 7.5pt;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
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
                <h1>Reporte Ejecutivo de Cartera</h1>
                <p>Auditoría de Cuentas por Cobrar & Morosidad</p>
            </td>
            <td class="info-cell">
                <strong>Fecha:</strong> {{ $fecha }}<br>
                <strong>Emitido por:</strong> {{ $usuario }}<br>
                <strong>Filtro Estado:</strong> {{ ucfirst($filtros['filtro_estado'] ?? 'Deudores') }}<br>
                @if(!empty($filtros['oficina']))
                    <strong>Oficina:</strong> {{ $filtros['oficina'] }}
                @endif
            </td>
        </tr>
    </table>

    <!-- Resumen de Indicadores Clave (KPIs) -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card highlight" style="width: 25%;">
                <div class="kpi-title">Saldo Total en Cartera</div>
                <div class="kpi-val">${{ number_format($totalCartera, 2) }}</div>
                <div class="kpi-sub">{{ number_format($deudoresCount) }} deudores activos</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Total Facturado Histórico</div>
                <div class="kpi-val">${{ number_format($totalDeuda, 2) }}</div>
                <div class="kpi-sub">Volumen consolidado</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Total Recaudado (Abonos)</div>
                <div class="kpi-val" style="color: #059669;">${{ number_format($totalAbonado, 2) }}</div>
                <div class="kpi-sub">Ingresos cobrados</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Efectividad de Cobro</div>
                @php
                    $efectividad = $totalDeuda > 0 ? ($totalAbonado / $totalDeuda) * 100 : 100;
                @endphp
                <div class="kpi-val" style="color: #2563eb;">{{ number_format($efectividad, 1) }}%</div>
                <div class="kpi-sub">Tasa de recuperación</div>
            </td>
        </tr>
    </table>

    <!-- Resumen por Oficina -->
    @if(count($desgloseOficinas) > 1)
        <div class="section-header">1. Distribución de Cartera por Oficina / Sucursal</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Oficina de Registro</th>
                    <th class="text-center" style="width: 15%;">Deudores</th>
                    <th class="text-right" style="width: 20%;">Total Facturado</th>
                    <th class="text-right" style="width: 20%;">Saldo por Cobrar</th>
                    <th class="text-right" style="width: 15%;">% De Cartera</th>
                </tr>
            </thead>
            <tbody>
                @foreach($desgloseOficinas as $ofi)
                    @php
                        $porcOfi = $totalCartera > 0 ? ($ofi['total_saldo'] / $totalCartera) * 100 : 0;
                    @endphp
                    <tr>
                        <td><strong>{{ $ofi['oficina'] }}</strong></td>
                        <td class="text-center">{{ $ofi['deudores_count'] }}</td>
                        <td class="text-right">${{ number_format($ofi['total_deuda'], 2) }}</td>
                        <td class="text-right"><strong class="badge-saldo">${{ number_format($ofi['total_saldo'], 2) }}</strong></td>
                        <td class="text-right">{{ number_format($porcOfi, 1) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Listado Detallado de Clientes -->
    <div class="section-header">2. Detalle de Clientes & Cuentas por Cobrar ({{ $clientes->count() }} Registros)</div>
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 28%;">Cliente / Razón Social</th>
                <th style="width: 14%;">Identificación</th>
                <th style="width: 14%;">Contacto</th>
                <th style="width: 14%;">Oficina</th>
                <th class="text-right" style="width: 12%;">Facturado</th>
                <th class="text-right" style="width: 14%;">Saldo Pendiente</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clientes as $idx => $c)
                <tr>
                    <td class="text-center" style="color: #64748b;">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $c->c_nombre }} {{ $c->c_apellido }}</strong>
                    </td>
                    <td>{{ $c->c_identificacion ?: 'S/I' }}</td>
                    <td>{{ $c->c_telefono ?: 'S/T' }}</td>
                    <td>{{ $c->c_oficina_registro ?: 'Oficina General' }}</td>
                    <td class="text-right">${{ number_format($c->c_deuda, 2) }}</td>
                    <td class="text-right">
                        @if($c->c_saldo > 0)
                            <strong class="badge-saldo">${{ number_format($c->c_saldo, 2) }}</strong>
                        @else
                            <span style="color: #059669; font-weight: bold;">$0.00</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px; color: #64748b;">
                        No se encontraron registros de clientes bajo los criterios de búsqueda.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">TOTAL GENERAL:</td>
                <td class="text-right">${{ number_format($totalDeuda, 2) }}</td>
                <td class="text-right" style="color: #be123c;">${{ number_format($totalCartera, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-note">
        Documento generado confidencialmente por el Sistema Notarial & Jurídico &bull; Página 1 de 1 &bull; {{ date('Y') }}
    </div>

</body>
</html>
