<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $plantilla->nombre_plantilla }} - {{ $cliente ? $cliente->c_nombre . ' ' . $cliente->c_apellido : 'Documento' }}</title>
    <style>
        @page {
            margin-top: {{ !empty($encabezadoHtml) ? '35mm' : '15mm' }};
            margin-bottom: {{ !empty($pieHtml) ? '28mm' : '15mm' }};
            margin-left: 16mm;
            margin-right: 16mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10.5pt;
            line-height: 1.45;
            color: #000000;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        header {
            position: fixed;
            top: -28mm;
            left: 0;
            right: 0;
            height: 26mm;
            z-index: 1000;
        }
        footer {
            position: fixed;
            bottom: -22mm;
            left: 0;
            right: 0;
            height: 20mm;
            z-index: 1000;
        }
        .content-area {
            width: 100%;
            position: relative;
        }
        .content-area:after {
            content: "";
            display: table;
            clear: both;
        }
        .content-area p {
            text-align: justify;
            margin-bottom: 9pt;
            line-height: 1.45;
        }
        .content-area h1, .content-area h2, .content-area h3 {
            color: #000000;
            text-align: center;
            margin-top: 12pt;
            margin-bottom: 6pt;
        }
        .content-area h1 { font-size: 13.5pt; font-weight: bold; }
        .content-area h2 { font-size: 11.5pt; font-weight: bold; }
        .content-area h3 { font-size: 10.5pt; font-weight: bold; }
        .content-area table {
            width: 100%;
            border-collapse: collapse;
            margin: 8pt 0;
            clear: both;
        }
        .content-area img {
            max-width: 100%;
            height: auto;
            border: none;
            outline: none;
            box-shadow: none;
        }
        .watermark {
            position: fixed;
            top: 40%;
            left: 5%;
            width: 90%;
            text-align: center;
            transform: rotate(-35deg);
            font-size: 45pt;
            font-weight: bold;
            color: rgba(0, 64, 128, 0.08);
            letter-spacing: 8px;
            text-transform: uppercase;
            z-index: -1000;
        }
    </style>
</head>
<body>
    @if(!empty($marcaAgua))
        <div class="watermark">{{ $marcaAgua }}</div>
    @endif

    @if(!empty($encabezadoHtml))
        <header>
            {!! $encabezadoHtml !!}
        </header>
    @endif

    @if(!empty($pieHtml))
        <footer>
            {!! $pieHtml !!}
        </footer>
    @endif

    <!-- Contenido 100% Personalizado de la Plantilla -->
    <div class="content-area">
        {!! $htmlContenido !!}
    </div>
</body>
</html>
