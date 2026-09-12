<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-2">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        Editar Plantilla: {{ $plantilla->nombre_plantilla }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Edita hojas A4 continuas, repite encabezados/pies y personaliza variables</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('plantillas.generar', $plantilla->id_plantilla) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    ⚡ Probar con Cliente
                </a>

                <a href="{{ route('plantillas.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-wider shadow-sm hover:bg-slate-50 transition duration-150">
                    &larr; Volver
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Librería PDF.js para renderizado de PDFs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        }
    </script>

    <!-- Estilos específicos para Hojas Multi-Página A4, Marcas de Agua, Imágenes y Menú Flotante -->
    <style>
        .variable-badge {
            cursor: grab;
            user-select: none;
            transition: all 0.15s ease;
        }
        .variable-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
        }
        .variable-badge:active {
            cursor: grabbing;
        }

        /* Contenedor de Hoja A4 Individual (Estilo Word / Google Docs) */
        .a4-sheet-container {
            position: relative;
            background-color: #ffffff !important;
            background: #ffffff !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15), 0 2px 6px rgba(0, 0, 0, 0.06);
            width: 210mm;
            min-height: 297mm;
            max-width: 210mm;
            margin: 0 auto;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            overflow: visible;
        }

        .a4-sheet-header-render {
            flex-shrink: 0;
            padding: 8mm 18mm 2mm 18mm;
            background: #ffffff;
            user-select: none;
        }

        .a4-sheet-body {
            flex: 1;
            min-height: 195mm;
            padding: 4mm 18mm 4mm 18mm;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.45;
            color: #1e293b;
            outline: none;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }
        .a4-sheet-body:focus {
            box-shadow: none;
            outline: none;
        }
        .a4-sheet-body p {
            margin-bottom: 8pt;
            text-align: justify;
            line-height: 1.45;
        }
        .a4-sheet-body h1, .a4-sheet-body h2, .a4-sheet-body h3 {
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            margin-top: 6pt;
            margin-bottom: 6pt;
        }
        .a4-sheet-body h1 { font-size: 14pt; }
        .a4-sheet-body h2 { font-size: 12pt; }
        .a4-sheet-body h3 { font-size: 11pt; }
        .a4-sheet-body strong { font-weight: 700; color: #000; }
        .a4-sheet-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 8pt 0;
        }
        .a4-sheet-body td, .a4-sheet-body th {
            border: 1px solid #cbd5e1;
            padding: 5pt 8pt;
            vertical-align: top;
        }
        .a4-sheet-body ul {
            list-style-type: disc;
            padding-left: 20pt;
            margin-bottom: 8pt;
        }
        .a4-sheet-body ol {
            list-style-type: decimal;
            padding-left: 20pt;
            margin-bottom: 8pt;
        }
        .a4-sheet-body li {
            margin-bottom: 3pt;
        }
        .a4-sheet-body blockquote {
            border-left: 3px solid #6366f1;
            padding-left: 10pt;
            margin: 8pt 0;
            color: #475569;
            font-style: italic;
        }
        .a4-sheet-body hr {
            border: none;
            border-top: 1px solid #94a3b8;
            margin: 12pt 0;
        }
        .a4-sheet-body a {
            color: #4f46e5;
            text-decoration: underline;
        }

        .a4-sheet-footer-render {
            flex-shrink: 0;
            padding: 2mm 18mm 8mm 18mm;
            background: #ffffff;
            user-select: none;
        }
        
        .watermark-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 60pt;
            font-weight: 900;
            color: rgba(15, 23, 42, 0.06);
            letter-spacing: 10px;
            text-transform: uppercase;
            user-select: none;
            pointer-events: none;
            z-index: 1;
            white-space: nowrap;
        }

        .a4-sheet-body img {
            max-width: 100%;
            height: auto;
            cursor: pointer;
            display: inline-block;
            user-select: none;
            border: none !important;
            box-shadow: none !important;
            -webkit-box-shadow: none !important;
            filter: none !important;
            background: transparent !important;
        }
        .a4-sheet-body img.selected-img {
            outline: 2px dashed #4f46e5 !important;
            outline-offset: 2px !important;
        }
        .a4-sheet-body img.free-moving {
            position: absolute !important;
            cursor: move !important;
            z-index: 20;
        }
        
        /* Menú contextual flotante que acompaña a la imagen */
        .img-floating-toolbar {
            position: absolute;
            background: #0f172a;
            color: #ffffff;
            padding: 5px 8px;
            border-radius: 12px;
            box-shadow: 0 12px 28px -4px rgba(0, 0, 0, 0.35), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 100;
            white-space: nowrap;
            user-select: none;
            border: 1px solid #334155;
        }
        
        .img-resize-handle {
            position: absolute;
            width: 12px;
            height: 12px;
            background-color: #4f46e5;
            border: 2px solid #ffffff;
            border-radius: 50%;
            cursor: se-resize;
            z-index: 101;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .toolbar-btn {
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px;
            color: #475569;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.1s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .toolbar-btn:hover {
            background: #e0e7ff;
            color: #4338ca;
            border-color: #c7d2fe;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0mm !important;
            }
            html, body {
                background: #ffffff !important;
                background-color: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm !important;
                height: auto !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Ocultar absolutamente todo en la página */
            body * {
                visibility: hidden !important;
            }

            /* Ocultar elementos de UI y navegación de raíz sin ocupar espacio */
            aside, header, nav, .sticky, .sidebar, .no-print, .a4-sheet-header-badge, 
            .img-floating-toolbar, .img-resize-handle, .toolbar-container, .watermark-overlay, 
            [class*="no-print"], button, select, input, textarea, form > .bg-white, .modal {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                width: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
            }

            /* Hacer visible únicamente el workspace y las hojas A4 */
            [x-ref="workspaceContainer"],
            [x-ref="workspaceContainer"] *,
            .a4-sheet-container,
            .a4-sheet-container * {
                visibility: visible !important;
            }

            [x-ref="workspaceContainer"] {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 210mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: transparent !important;
                background-color: transparent !important;
                border: none !important;
                box-shadow: none !important;
                display: block !important;
                gap: 0 !important;
            }

            .a4-sheet-container {
                position: relative !important;
                width: 210mm !important;
                height: 297mm !important;
                min-height: 297mm !important;
                max-height: 297mm !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                page-break-after: always !important;
                break-after: page !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                box-sizing: border-box !important;
                background: #ffffff !important;
                background-color: #ffffff !important;
                overflow: hidden !important;
            }

            .a4-sheet-container:last-child,
            .a4-sheet-container:last-of-type {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            .a4-sheet-header-render {
                display: block !important;
                padding: 8mm 18mm 2mm 18mm !important;
                flex-shrink: 0 !important;
                background: #ffffff !important;
                background-color: #ffffff !important;
            }

            .a4-sheet-body {
                display: block !important;
                flex: 1 !important;
                padding: 4mm 18mm 4mm 18mm !important;
                font-family: 'Helvetica', 'Arial', sans-serif !important;
                font-size: 11pt !important;
                line-height: 1.45 !important;
                color: #000000 !important;
                overflow: hidden !important;
                box-sizing: border-box !important;
            }

            .a4-sheet-footer-render {
                display: block !important;
                padding: 2mm 18mm 8mm 18mm !important;
                flex-shrink: 0 !important;
                background: #ffffff !important;
                background-color: #ffffff !important;
            }
        }
    </style>

    <div class="py-6 bg-slate-100 min-h-screen" x-data="templateDesigner()" @membrete-saved.window="onMembreteSaved($event.detail)">
        <div class="w-full max-w-[1750px] mx-auto sm:px-6 lg:px-8 space-y-5">
            
            <form action="{{ route('plantillas.update', $plantilla->id_plantilla) }}" method="POST" @submit="syncContent">
                @csrf
                @method('PUT')
                <input type="hidden" name="contenido_html" x-model="contenidoHtml">
                <input type="hidden" name="tipo_documento" value="general">
                <input type="hidden" name="encabezado_id" x-model="encabezadoId">
                <input type="hidden" name="pie_id" x-model="pieId">

                <!-- Fila Superior: Metadatos de la Plantilla -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4 no-print">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nombre de la Plantilla *</label>
                            <input type="text" name="nombre_plantilla" x-model="nombre" placeholder="Nombre de la plantilla..." 
                                   class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 focus:bg-white focus:border-indigo-500 font-bold text-slate-800" required>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Categoría</label>
                            <input type="text" name="categoria" x-model="categoria" list="cat-list" placeholder="Categoría..." 
                                   class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 focus:bg-white focus:border-indigo-500 font-medium text-slate-800">
                            <datalist id="cat-list">
                                <option value="Poderes">
                                <option value="Declaraciones Juramentadas">
                                <option value="Cartas y Autorizaciones">
                                <option value="Contratos y Convenios">
                                <option value="Trámites Consulares">
                            </datalist>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Descripción Breve</label>
                            <input type="text" name="descripcion" x-model="descripcion" placeholder="Descripción..." 
                                   class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 focus:bg-white focus:border-indigo-500 text-slate-700">
                        </div>

                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg shadow-indigo-200 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Actualizar Plantilla
                            </button>
                        </div>

                    </div>

                    <!-- Fila de Selección de Membretes (Encabezado y Pie) -->
                    <div class="pt-3 border-t border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 bg-slate-50/70 p-3 rounded-xl">
                        <div class="flex flex-wrap items-center gap-4">
                            <!-- Encabezado -->
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-black uppercase text-amber-800 tracking-wider flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Encabezado:
                                </span>
                                <select x-model="encabezadoId" class="text-xs bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-slate-800 focus:border-amber-500">
                                    <option value="">(Sin encabezado)</option>
                                    <template x-for="enc in encabezadosList" :key="enc.id">
                                        <option :value="enc.id" x-text="enc.nombre + (enc.es_predeterminado ? ' ★' : '')"></option>
                                    </template>
                                </select>
                                <button type="button" @click="openCanvasDesigner('encabezado')" class="px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-lg text-[11px] shadow-xs flex items-center gap-1 transition">
                                    <span>🎨</span>
                                    <span>Diseñar / Acomodar</span>
                                </button>
                            </div>

                            <!-- Pie de Página -->
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-black uppercase text-indigo-800 tracking-wider flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span> Pie de Página:
                                </span>
                                <select x-model="pieId" class="text-xs bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-slate-800 focus:border-indigo-500">
                                    <option value="">(Sin pie de página)</option>
                                    <template x-for="pie in piesList" :key="pie.id">
                                        <option :value="pie.id" x-text="pie.nombre + (pie.es_predeterminado ? ' ★' : '')"></option>
                                    </template>
                                </select>
                                <button type="button" @click="openCanvasDesigner('pie')" class="px-2.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-lg text-[11px] shadow-xs flex items-center gap-1 transition">
                                    <span>🎨</span>
                                    <span>Diseñar / Acomodar</span>
                                </button>
                            </div>
                        </div>

                        <a href="{{ route('plantillas-membretes.index') }}" target="_blank" class="text-[11px] font-bold text-slate-600 hover:text-indigo-600 flex items-center gap-1.5 hover:underline">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                            Gestor de Membretes
                        </a>
                    </div>
                </div>

                <!-- Grid Principal: Sidebar de Variables + Hoja de Trabajo -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- SIDEBAR IZQUIERDA: Panel de Variables y Multimedia -->
                    <div class="lg:col-span-4 xl:col-span-3 space-y-4 sticky top-20 no-print">
                        
                        <!-- Panel 1: Variables de Cliente -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                            <div class="pb-3 border-b border-slate-100">
                                <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                                    <span class="w-2.5 h-4 bg-indigo-600 rounded-full inline-block"></span>
                                    Variables del Cliente
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    <strong>Arrastra</strong> o haz <strong>clic</strong> para insertar en el cursor.
                                </p>
                            </div>

                            <!-- 1. Datos Personales -->
                            <div class="space-y-2">
                                <h4 class="text-[10px] font-black uppercase tracking-wider text-indigo-700 flex items-center gap-1">
                                    👤 Datos Personales
                                </h4>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="v in variablesCliente" :key="v.tag">
                                        <div class="variable-badge inline-flex items-center gap-1 px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg text-[11px] font-bold shadow-2xs"
                                             draggable="true" 
                                             @dragstart="onDragStart($event, v.tag)"
                                             @click="insertTag(v.tag)"
                                             :title="'Clic o arrastrar para insertar: ' + v.tag">
                                            <span x-text="v.label"></span>
                                            <span class="text-[9px] opacity-60 font-mono">+</span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- 2. Ubicación -->
                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                <h4 class="text-[10px] font-black uppercase tracking-wider text-emerald-700 flex items-center gap-1">
                                    📍 Ubicación y Domicilio
                                </h4>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="v in variablesUbicacion" :key="v.tag">
                                        <div class="variable-badge inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-lg text-[11px] font-bold shadow-2xs"
                                             draggable="true" 
                                             @dragstart="onDragStart($event, v.tag)"
                                             @click="insertTag(v.tag)"
                                             :title="'Clic o arrastrar para insertar: ' + v.tag">
                                            <span x-text="v.label"></span>
                                            <span class="text-[9px] opacity-60 font-mono">+</span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- 3. Fechas y Notaría -->
                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                <h4 class="text-[10px] font-black uppercase tracking-wider text-amber-700 flex items-center gap-1">
                                    🏛️ Notaría y Fechas
                                </h4>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="v in variablesNotaria" :key="v.tag">
                                        <div class="variable-badge inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-lg text-[11px] font-bold shadow-2xs"
                                             draggable="true" 
                                             @dragstart="onDragStart($event, v.tag)"
                                             @click="insertTag(v.tag)"
                                             :title="'Clic o arrastrar para insertar: ' + v.tag">
                                            <span x-text="v.label"></span>
                                            <span class="text-[9px] opacity-60 font-mono">+</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Panel 2: Bloques Rápidos Notariales -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                📜 Bloques y Sellos Rápidos
                            </h3>
                            <div class="grid grid-cols-1 gap-2">
                                <button type="button" @click="insertSignatureBlock()" class="w-full text-left p-2.5 rounded-xl border border-slate-200 hover:border-indigo-300 bg-slate-50 hover:bg-indigo-50/50 text-xs font-bold text-slate-700 transition-all flex items-center justify-between">
                                    <span>✍️ Bloque de Doble Firma</span>
                                    <span class="text-[10px] text-indigo-600">+ Insertar</span>
                                </button>
                                <button type="button" @click="insertFingerprintBox()" class="w-full text-left p-2.5 rounded-xl border border-slate-200 hover:border-indigo-300 bg-slate-50 hover:bg-indigo-50/50 text-xs font-bold text-slate-700 transition-all flex items-center justify-between">
                                    <span>🖐️ Recuadro Pulgar Derecho</span>
                                    <span class="text-[10px] text-indigo-600">+ Insertar</span>
                                </button>
                                <button type="button" @click="insertNotaryApostilleBlock()" class="w-full text-left p-2.5 rounded-xl border border-slate-200 hover:border-indigo-300 bg-slate-50 hover:bg-indigo-50/50 text-xs font-bold text-slate-700 transition-all flex items-center justify-between">
                                    <span>⚖️ Certificación Notarial NY</span>
                                    <span class="text-[10px] text-indigo-600">+ Insertar</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- ÁREA CENTRAL: Barra de Herramientas y Hojas de Documento -->
                    <div class="lg:col-span-8 xl:col-span-9 space-y-4">
                        
                        <!-- Barra de Herramientas Principal de Word Completa -->
                        <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm space-y-2 no-print">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                            <!-- Fila de Edición de Texto y Formato -->
                            <div class="flex flex-wrap items-center gap-1.5">
                                
                                <!-- Deshacer / Rehacer -->
                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('undo')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Deshacer (Ctrl+Z)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                    </button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('redo')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Rehacer (Ctrl+Y)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"></path></svg>
                                    </button>
                                </div>

                                <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                                <!-- 1. Selector de Tipo de Letra / Tipografía -->
                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl px-2 py-1 shadow-2xs">
                                    <span class="text-[10px] text-slate-400 mr-1 font-bold">🔤</span>
                                    <select @mousedown.prevent="" @change="setFontFamily($event.target.value)" class="text-xs bg-transparent border-0 py-0.5 pl-0 pr-6 font-bold text-slate-800 focus:ring-0 cursor-pointer">
                                        <option value="Arial, sans-serif">Arial</option>
                                        <option value="'Calibri', sans-serif">Calibri</option>
                                        <option value="'Times New Roman', serif">Times New Roman</option>
                                        <option value="'Georgia', serif">Georgia</option>
                                        <option value="'Courier New', monospace">Courier New</option>
                                        <option value="'Verdana', sans-serif">Verdana</option>
                                        <option value="'Trebuchet MS', sans-serif">Trebuchet MS</option>
                                        <option value="'Garamond', serif">Garamond</option>
                                    </select>
                                </div>

                                <!-- 2. Selector de Tamaño de Letra con Stepper (+ / -) -->
                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                                    <select @mousedown.prevent="" @change="setFontSize($event.target.value)" class="text-xs bg-transparent border-0 py-1 px-1 font-extrabold text-slate-800 focus:ring-0 cursor-pointer">
                                        <option value="8pt">8 pt</option>
                                        <option value="9pt">9 pt</option>
                                        <option value="10pt">10 pt</option>
                                        <option value="11pt" selected>11 pt</option>
                                        <option value="12pt">12 pt</option>
                                        <option value="14pt">14 pt</option>
                                        <option value="16pt">16 pt</option>
                                        <option value="18pt">18 pt</option>
                                        <option value="20pt">20 pt</option>
                                        <option value="24pt">24 pt</option>
                                        <option value="28pt">28 pt</option>
                                        <option value="36pt">36 pt</option>
                                        <option value="48pt">48 pt</option>
                                    </select>
                                    <button type="button" @mousedown.prevent="" @click="stepFontSize(-1)" class="p-1 hover:bg-white rounded-lg text-slate-600 font-black text-xs transition" title="Reducir tamaño (A-)">A⁻</button>
                                    <button type="button" @mousedown.prevent="" @click="stepFontSize(1)" class="p-1 hover:bg-white rounded-lg text-slate-600 font-black text-xs transition" title="Aumentar tamaño (A+)">A⁺</button>
                                </div>

                                <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                                <!-- 3. Estilos de Carácter (Negrita, Cursiva, Subrayado, Tachado, Subíndice, Superíndice) -->
                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('bold')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 font-black transition" title="Negrita (Ctrl+B)">
                                        <strong class="text-xs">B</strong>
                                    </button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('italic')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 font-serif italic transition" title="Cursiva (Ctrl+I)">
                                        <span class="text-xs">I</span>
                                    </button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('underline')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 underline transition" title="Subrayado (Ctrl+U)">
                                        <span class="text-xs">U</span>
                                    </button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('strikeThrough')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 line-through transition" title="Tachado">
                                        <span class="text-xs">S</span>
                                    </button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('subscript')" class="p-1 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 text-[10px] font-bold transition" title="Subíndice">x₂</button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('superscript')" class="p-1 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 text-[10px] font-bold transition" title="Superíndice">x²</button>
                                </div>

                                <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                                <!-- 4. Color de Letra y Resaltado -->
                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 gap-1 shadow-2xs">
                                    <!-- Selector Color Texto -->
                                    <label class="p-1.5 hover:bg-white rounded-lg cursor-pointer flex items-center gap-0.5" title="Color de Fuente">
                                        <span class="text-xs font-black text-slate-800">A</span>
                                        <span class="w-2.5 h-2.5 rounded-full border border-slate-300 bg-black inline-block" id="color-preview-circle"></span>
                                        <input type="color" @change="setTextColor($event.target.value)" class="sr-only" value="#000000">
                                    </label>

                                    <!-- Selector Color Resaltador -->
                                    <div class="relative" x-data="{ openHilite: false }">
                                        <button type="button" @mousedown.prevent="" @click="openHilite = !openHilite" @click.away="openHilite = false" class="p-1.5 hover:bg-white rounded-lg flex items-center gap-0.5 text-xs text-amber-500 font-black" title="Resaltar Texto">
                                            <span>🖍️</span>
                                            <span class="text-[9px]">▼</span>
                                        </button>
                                        <div x-show="openHilite" x-transition class="absolute left-0 mt-2 p-2 bg-white rounded-xl shadow-xl border border-slate-200 z-50 flex gap-1.5" x-cloak>
                                            <button type="button" @mousedown.prevent="" @click="setTextHighlight('#fef08a'); openHilite = false;" class="w-6 h-6 rounded bg-yellow-200 border border-yellow-400 hover:scale-110 transition" title="Amarillo"></button>
                                            <button type="button" @mousedown.prevent="" @click="setTextHighlight('#bbf7d0'); openHilite = false;" class="w-6 h-6 rounded bg-green-200 border border-green-400 hover:scale-110 transition" title="Verde"></button>
                                            <button type="button" @mousedown.prevent="" @click="setTextHighlight('#bae6fd'); openHilite = false;" class="w-6 h-6 rounded bg-sky-200 border border-sky-400 hover:scale-110 transition" title="Celeste"></button>
                                            <button type="button" @mousedown.prevent="" @click="setTextHighlight('#fbcfe8'); openHilite = false;" class="w-6 h-6 rounded bg-pink-200 border border-pink-400 hover:scale-110 transition" title="Rosa"></button>
                                            <button type="button" @mousedown.prevent="" @click="setTextHighlight('transparent'); openHilite = false;" class="px-2 py-1 text-[10px] font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded">Sin color</button>
                                        </div>
                                    </div>

                                    <!-- Borrar Formato -->
                                    <button type="button" @mousedown.prevent="" @click="clearFormatting()" class="p-1.5 hover:bg-white hover:text-rose-600 rounded-lg text-slate-500 transition" title="Borrar todo el formato">
                                        <span class="text-xs font-bold">🧹</span>
                                    </button>
                                </div>

                                <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                                <!-- 5. Estilos de Párrafo (P, H1, H2, H3, Cita) -->
                                <select @mousedown.prevent="" @change="formatBlock($event.target.value)" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 font-bold text-slate-800 focus:bg-white focus:border-indigo-500 cursor-pointer shadow-2xs">
                                    <option value="P">Párrafo Normal</option>
                                    <option value="H1">Título Principal (H1)</option>
                                    <option value="H2">Subtítulo (H2)</option>
                                    <option value="H3">Sección (H3)</option>
                                    <option value="BLOCKQUOTE">Cita / Bloque Notarial</option>
                                </select>

                                <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                                <!-- 6. Alineación -->
                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('justifyLeft')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Alinear a la izquierda">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h14"/></svg>
                                    </button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('justifyCenter')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Centrar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M5 18h14"/></svg>
                                    </button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('justifyRight')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Alinear a la derecha">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M6 18h14"/></svg>
                                    </button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('justifyFull')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Justificar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                    </button>
                                </div>

                                <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                                <!-- 7. Interlineado y Sangrías -->
                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                                    <div class="relative" x-data="{ openLineH: false }">
                                        <button type="button" @mousedown.prevent="" @click="openLineH = !openLineH" @click.away="openLineH = false" class="px-2 py-1 hover:bg-white rounded-lg flex items-center gap-1 text-xs text-slate-700 font-bold" title="Interlineado">
                                            <span>↕️ 1.45</span>
                                            <span class="text-[9px]">▼</span>
                                        </button>
                                        <div x-show="openLineH" x-transition class="absolute left-0 mt-2 py-1 bg-white rounded-xl shadow-xl border border-slate-200 z-50 w-36 text-xs" x-cloak>
                                            <button type="button" @mousedown.prevent="" @click="setLineHeight('1.0'); openLineH = false;" class="w-full text-left px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-700 font-semibold">1.0 Sencillo</button>
                                            <button type="button" @mousedown.prevent="" @click="setLineHeight('1.15'); openLineH = false;" class="w-full text-left px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-700 font-semibold">1.15 Estándar</button>
                                            <button type="button" @mousedown.prevent="" @click="setLineHeight('1.45'); openLineH = false;" class="w-full text-left px-3 py-1.5 bg-indigo-50/60 font-bold text-indigo-700">1.45 Notarial ★</button>
                                            <button type="button" @mousedown.prevent="" @click="setLineHeight('1.5'); openLineH = false;" class="w-full text-left px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-700 font-semibold">1.5 Líneas</button>
                                            <button type="button" @mousedown.prevent="" @click="setLineHeight('2.0'); openLineH = false;" class="w-full text-left px-3 py-1.5 hover:bg-indigo-50 hover:text-indigo-700 font-semibold">2.0 Doble</button>
                                        </div>
                                    </div>

                                    <button type="button" @mousedown.prevent="" @click="formatDoc('outdent')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Disminuir sangría">⇤</button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('indent')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Aumentar sangría">⇥</button>
                                </div>

                                <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                                <!-- 8. Listas, Tablas e Inserciones -->
                                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('insertUnorderedList')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Viñetas">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16M2 6h.01M2 12h.01M2 18h.01"></path></svg>
                                    </button>
                                    <button type="button" @mousedown.prevent="" @click="formatDoc('insertOrderedList')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Numeración">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 6h11M9 12h11M9 18h11M4 6h1v4M4 14h2a1 1 0 011 1v1a1 1 0 01-1 1H4v2h3"></path></svg>
                                    </button>
                                    
                                    <!-- Insertar Tabla Dropdown -->
                                    <div class="relative" x-data="{ openTableMenu: false }">
                                        <button type="button" @mousedown.prevent="" @click="openTableMenu = !openTableMenu" @click.away="openTableMenu = false" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Insertar Tabla">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-10v16M4 4h16a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z"/></svg>
                                        </button>
                                        <div x-show="openTableMenu" x-transition class="absolute left-0 mt-2 p-2 bg-white rounded-xl shadow-xl border border-slate-200 z-50 w-44 text-xs space-y-1" x-cloak>
                                            <div class="px-2 py-1 font-black text-slate-400 uppercase text-[10px]">Insertar Tabla:</div>
                                            <button type="button" @mousedown.prevent="" @click="insertTable(2, 2); openTableMenu = false;" class="w-full text-left px-2.5 py-1.5 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg font-bold transition">📊 2 x 2 Columnas</button>
                                            <button type="button" @mousedown.prevent="" @click="insertTable(3, 3); openTableMenu = false;" class="w-full text-left px-2.5 py-1.5 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg font-bold transition">📊 3 x 3 Filas/Col</button>
                                            <button type="button" @mousedown.prevent="" @click="insertTable(4, 2); openTableMenu = false;" class="w-full text-left px-2.5 py-1.5 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg font-bold transition">📊 4 x 2 Tabla datos</button>
                                            <button type="button" @mousedown.prevent="" @click="insertTable(5, 4); openTableMenu = false;" class="w-full text-left px-2.5 py-1.5 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg font-bold transition">📊 5 x 4 Tabla grande</button>
                                        </div>
                                    </div>

                                    <button type="button" @mousedown.prevent="" @click="insertHorizontalRule()" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Línea divisoria horizontal">―</button>
                                    <button type="button" @mousedown.prevent="" @click="insertLink()" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Insertar Enlace URL">🔗</button>
                                </div>

                                <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                                <!-- 9. HERRAMIENTA Aa: CONVERSIÓN DE MAYÚSCULAS Y MINÚSCULAS -->
                                <div class="relative inline-block" x-data="{ openAa: false }">
                                    <button type="button" 
                                            @mousedown.prevent=""
                                            @click="openAa = !openAa" 
                                            @click.away="openAa = false" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl font-black text-xs transition shadow-2xs">
                                        <span>Aa</span>
                                        <span class="text-[10px]">▼</span>
                                        <span class="hidden sm:inline text-[11px] font-bold">Mayús / Minús</span>
                                    </button>

                                    <div x-show="openAa" 
                                         x-transition 
                                         class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50 space-y-1 text-xs"
                                         x-cloak>
                                        <div class="px-2.5 py-1 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                                            Cambiar Letras:
                                        </div>
                                        
                                        <button type="button" @mousedown.prevent="" @click="transformTextCase('sentence'); openAa = false;" class="w-full text-left px-3 py-2 rounded-xl hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 font-bold transition flex items-center justify-between">
                                            <div>
                                                <div>🔤 <strong>Tipo Oración (Normal)</strong></div>
                                                <div class="text-[10px] text-slate-400 font-normal">Mayúscula al inicio de cada frase</div>
                                            </div>
                                        </button>

                                        <button type="button" @mousedown.prevent="" @click="transformTextCase('lower'); openAa = false;" class="w-full text-left px-3 py-2 rounded-xl hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 font-bold transition flex items-center justify-between">
                                            <div>
                                                <div>abc <strong>todo minúsculas</strong></div>
                                            </div>
                                        </button>

                                        <button type="button" @mousedown.prevent="" @click="transformTextCase('upper'); openAa = false;" class="w-full text-left px-3 py-2 rounded-xl hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 font-bold transition flex items-center justify-between">
                                            <div>
                                                <div>ABC <strong>TODO MAYÚSCULAS</strong></div>
                                            </div>
                                        </button>

                                        <button type="button" @mousedown.prevent="" @click="transformTextCase('title'); openAa = false;" class="w-full text-left px-3 py-2 rounded-xl hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 font-bold transition flex items-center justify-between">
                                            <div>
                                                <div>Abc <strong>Tipo Título</strong></div>
                                            </div>
                                        </button>

                                        <div class="border-t border-slate-100 pt-1 mt-1">
                                            <button type="button" @mousedown.prevent="" @click="transformTextCase('sentence', true); openAa = false;" class="w-full text-left px-3 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-black transition flex items-center gap-1.5">
                                                <span>⚡</span>
                                                <span>Convertir TODO a Tipo Oración</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                                <!-- 10. Selector de Modo de Pegado (Paste Mode Switcher) -->
                                <div class="flex items-center bg-emerald-50 border border-emerald-200 rounded-xl px-2 py-1 gap-1 text-[11px] font-bold text-emerald-900 shadow-2xs">
                                    <span>📋 Pegado:</span>
                                    <select x-model="pasteMode" class="text-[11px] font-extrabold bg-transparent border-0 p-0 text-emerald-800 focus:ring-0 cursor-pointer">
                                        <option value="rich">✨ Mantener formato origen</option>
                                        <option value="plain">📄 Pegar solo texto plano</option>
                                    </select>
                                </div>

                            </div>

                            <!-- Controles de Hoja y Acciones Rápidas -->
                            <div class="flex items-center gap-2">
                                <!-- Selector de Marca de Agua -->
                                <select x-model="marcaAgua" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-slate-800 focus:bg-white shadow-2xs">
                                    <option value="">Sin marca de agua</option>
                                    <option value="ORIGINAL">ORIGINAL</option>
                                    <option value="COPIA CERTIFICADA">COPIA CERTIFICADA</option>
                                    <option value="BORRADOR">BORRADOR</option>
                                    <option value="DOCUMENTO NOTARIAL">DOCUMENTO NOTARIAL</option>
                                </select>

                                <!-- Botón Insertar Salto de Hoja A4 -->
                                <button type="button" @click="insertPageBreak()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition shadow-sm" title="Insertar división para nueva hoja A4 (Ctrl+Enter)">
                                    <span>📄</span>
                                    <span class="hidden sm:inline">+ Salto A4</span>
                                </button>
                            </div>

                        </div>
                            </div>

                            <!-- Grupo de Inserción Multimedia, Firmas y Documentos -->
                            <div class="flex items-center gap-2">
                                <!-- Botón Dibujar Firma Digital -->
                                <button type="button" @click="openSignatureModal()" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                                    ✍️ Dibujar Firma
                                </button>

                                <!-- Botón Subir Imagen / Sello -->
                                <button type="button" @click="$refs.imageUploader.click()" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    🖼️ Subir Imagen / Sello
                                </button>
                                <input type="file" x-ref="imageUploader" @change="handleImageUpload($event)" accept="image/*" class="hidden">

                                <!-- Botón Subir PDF / Doc -->
                                <button type="button" @click="modalImport = true" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    📄 Subir PDF / Doc
                                </button>
                            </div>

                        </div>

                        <!-- ESPACIO DE TRABAJO MULTI-HOJA A4 ESTILO MICROSOFT WORD -->
                        <div class="bg-slate-200/80 p-4 sm:p-8 rounded-2xl border border-slate-300 min-h-[700px] flex flex-col items-center gap-8 relative" x-ref="workspaceContainer">
                            
                            <!-- Marca de Agua Renderizada (Visual en pantalla) -->
                            <div class="watermark-overlay" x-show="marcaAgua" x-text="marcaAgua" x-cloak></div>

                            <!-- MENÚ CONTEXTUAL FLOTANTE ADOSADO DIRECTAMENTE A LA IMAGEN -->
                            <div x-show="selectedImage" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="img-floating-toolbar text-xs no-print" 
                                 :style="'top:' + menuTop + '; left:' + menuLeft + ';'" 
                                 x-cloak>
                                
                                <!-- Botón / Tirador de Arrastre Libre -->
                                <div class="flex items-center gap-1.5 px-2.5 py-1 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-white font-bold text-[11px] cursor-move shadow-xs"
                                     @mousedown="startDraggingImage($event)"
                                     title="Haz clic y arrastra para mover la imagen libremente">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 8h16M4 16h16M8 4v16M16 4v16"></path></svg>
                                    <span>🖐️ Arrastrar Libre</span>
                                </div>

                                <span class="w-px h-4 bg-slate-700 mx-0.5"></span>

                                <!-- Tamaños Rápidos -->
                                <button type="button" @click="resizeSelectedImage('120px')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 rounded text-[10px] font-bold">120px</button>
                                <button type="button" @click="resizeSelectedImage('250px')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 rounded text-[10px] font-bold">250px</button>
                                <button type="button" @click="resizeSelectedImage('450px')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 rounded text-[10px] font-bold">450px</button>

                                <span class="w-px h-4 bg-slate-700 mx-0.5"></span>

                                <!-- Alineaciones Rápidas -->
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="alignSelectedImage('left')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 rounded text-[10px] font-bold" title="Alinear a la izquierda">⬅️ Izq</button>
                                    <button type="button" @click="alignSelectedImage('center')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 rounded text-[10px] font-bold" title="Centrar en bloque">🎯 Centro</button>
                                    <button type="button" @click="alignSelectedImage('right')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 rounded text-[10px] font-bold" title="Alinear a la derecha">➡️ Der</button>
                                </div>

                                <span class="w-px h-4 bg-slate-700 mx-0.5"></span>

                                <!-- Alternar Modo Libre -->
                                <button type="button" @click="toggleFreePosition()" class="px-2.5 py-1 rounded text-[10px] font-bold transition-colors" :class="isFreePosition ? 'bg-amber-400 text-slate-950 shadow-xs' : 'bg-slate-800 text-slate-200 hover:bg-slate-700'">
                                    <span x-text="isFreePosition ? '📍 Modo Libre' : '📄 En Flujo'"></span>
                                </button>

                                <span class="w-px h-4 bg-slate-700 mx-0.5"></span>

                                <!-- Eliminar Imagen -->
                                <button type="button" @click="deleteSelectedImage()" class="px-2 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-[10px] font-bold" title="Eliminar imagen seleccionada">🗑️</button>
                                <button type="button" @click="deselectImage()" class="px-1.5 py-1 text-slate-400 hover:text-white text-[10px]">✕</button>
                            </div>

                            <!-- Manija de Redimensionamiento -->
                            <div x-show="selectedImage" 
                                 class="img-resize-handle no-print" 
                                 :style="'top:' + resizeTop + '; left:' + resizeLeft + ';'" 
                                 @mousedown="startResizingImage($event)"
                                 x-cloak></div>

                            <!-- RENDERIZADO DE HOJAS A4 (CADA HOJA TIENE SU ENCABEZADO Y PIE DE PÁGINA) -->
                            <template x-for="(page, pIndex) in pages" :key="page.id">
                                <div class="a4-sheet-container" :id="'page-card-' + page.id">
                                    
                                    <!-- Barra Superior de Hoja (Solo en pantalla) -->
                                    <div class="a4-sheet-header-badge no-print flex items-center justify-between px-6 py-2 bg-slate-100 border-b border-slate-200 text-xs text-slate-600 rounded-t select-none">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                            <span class="font-extrabold text-slate-800 tracking-tight" x-text="'Hoja A4 — Página ' + (pIndex + 1) + ' de ' + pages.length"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="addPageAfter(pIndex)" class="inline-flex items-center gap-1 px-3 py-1 bg-white hover:bg-indigo-50 text-indigo-700 border border-slate-200 rounded-lg text-xs font-bold shadow-2xs transition">
                                                <span>➕</span> Añadir Hoja Abajo
                                            </button>
                                            <template x-if="pages.length > 1">
                                                <button type="button" @click="removePage(pIndex)" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 rounded-lg text-xs font-bold shadow-2xs transition" title="Eliminar esta hoja">
                                                    <span>🗑️</span> Eliminar
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- 1. ENCABEZADO SUPERIOR (Se repite en CADA hoja A4) -->
                                    <div class="a4-sheet-header-render select-none bg-white transition-all rounded-t" 
                                         x-show="selectedEncabezadoHtml" 
                                         x-html="selectedEncabezadoHtml"
                                         x-cloak></div>

                                    <!-- 2. CUERPO EDITABLE DE LA HOJA A4 -->
                                    <div :id="'editor-sheet-' + page.id" 
                                         class="a4-sheet-body document-paper" 
                                         contenteditable="true" 
                                         @focus="activePageIndex = pIndex"
                                         @input="onPageInput(pIndex, $event)"
                                         @paste="handlePagePaste(pIndex, $event)"
                                         @keydown="handlePageKeydown(pIndex, $event)"
                                         @click="handlePaperClick($event)"
                                         @drop="onDropOnPage(pIndex, $event)" 
                                         @dragover.prevent
                                         x-html="page.content"></div>

                                    <!-- 3. PIE DE PÁGINA INFERIOR (Se repite en CADA hoja A4) -->
                                    <div class="a4-sheet-footer-render select-none bg-white transition-all rounded-b" 
                                         x-show="selectedPieHtml" 
                                         x-html="selectedPieHtml"
                                         x-cloak></div>

                                </div>
                            </template>

                            <!-- Botón Inferior Grande para Agregar Nueva Hoja -->
                            <div class="w-full max-w-[210mm] flex justify-center py-2 select-none no-print">
                                <button type="button" @click="addPageAfter(pages.length - 1)" class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-indigo-50 text-indigo-700 border-2 border-dashed border-indigo-300 hover:border-indigo-500 rounded-2xl font-bold text-xs shadow-sm hover:shadow transition-all transform active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    <span>+ Agregar Nueva Hoja A4 (Página Siguiente)</span>
                                </button>
                            </div>

                        </div>

                    </div>

            </form>

            <!-- Toast Flotante de Opciones de Pegado Inteligente -->
            <div x-show="showPasteToast" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-3.5 no-print"
                 x-cloak>
                <div class="flex items-center gap-2">
                    <span class="text-base">📋</span>
                    <span class="text-xs font-bold text-slate-200">Texto pegado:</span>
                </div>
                <div class="flex items-center gap-1.5 bg-slate-800 p-1 rounded-xl border border-slate-700">
                    <button type="button" @click="applyPastedAs('rich')" class="px-2.5 py-1 text-[11px] font-bold rounded-lg transition" :class="pasteMode === 'rich' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white'">
                        ✨ Formato Origen
                    </button>
                    <button type="button" @click="applyPastedAs('plain')" class="px-2.5 py-1 text-[11px] font-bold rounded-lg transition" :class="pasteMode === 'plain' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white'">
                        📄 Solo Texto (Destino)
                    </button>
                </div>
                <button type="button" @click="showPasteToast = false" class="text-slate-400 hover:text-white text-xs ml-1">✕</button>
            </div>

        </div>

        <!-- MODAL 1: LIENZO PARA DIBUJAR FIRMA DIGITAL (E-SIGNATURE) -->
        <div x-show="modalSignature" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalSignature" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="modalSignature = false"></div>

                <div x-show="modalSignature" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-200">
                    
                    <div class="bg-white p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                                    ✍️ Dibujar Firma en Pantalla
                                </h3>
                                <p class="text-xs text-slate-400 mt-0.5">Dibuja con el mouse, tableta o pantalla táctil</p>
                            </div>
                            <button type="button" @click="modalSignature = false" class="text-slate-400 hover:text-slate-600">✕</button>
                        </div>

                        <!-- Canvas de Firma -->
                        <div class="border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 relative overflow-hidden flex items-center justify-center">
                            <canvas id="signatureCanvas" width="400" height="180" class="cursor-crosshair bg-white touch-none"
                                    @mousedown="startDrawing($event)" 
                                    @mousemove="draw($event)" 
                                    @mouseup="stopDrawing()" 
                                    @mouseleave="stopDrawing()"
                                    @touchstart="startDrawingTouch($event)"
                                    @touchmove="drawTouch($event)"
                                    @touchend="stopDrawing()"></canvas>
                            <div class="absolute bottom-2 left-3 text-[10px] text-slate-400 pointer-events-none select-none">
                                Área de Firma Notarial
                            </div>
                        </div>

                        <!-- Paleta de Color de Tinta -->
                        <div class="flex items-center justify-between pt-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-600">Color de Tinta:</span>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" @click="sigColor = '#004080'" class="w-6 h-6 rounded-full bg-[#004080] border-2 transition" :class="sigColor === '#004080' ? 'border-slate-800 scale-110 shadow-xs' : 'border-transparent'"></button>
                                    <button type="button" @click="sigColor = '#000000'" class="w-6 h-6 rounded-full bg-black border-2 transition" :class="sigColor === '#000000' ? 'border-indigo-600 scale-110 shadow-xs' : 'border-transparent'"></button>
                                    <button type="button" @click="sigColor = '#0f172a'" class="w-6 h-6 rounded-full bg-slate-900 border-2 transition" :class="sigColor === '#0f172a' ? 'border-indigo-600 scale-110 shadow-xs' : 'border-transparent'"></button>
                                </div>
                            </div>
                            <button type="button" @click="clearSignature()" class="text-xs text-rose-600 hover:text-rose-700 font-bold px-2 py-1 rounded hover:bg-rose-50 transition">
                                🗑️ Borrar
                            </button>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="modalSignature = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-100 transition">
                            Cancelar
                        </button>
                        <button type="button" @click="saveAndInsertSignature()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-200 transition">
                            ✍️ Insertar Firma en Documento
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL 2: IMPORTAR Y PROCESAR PDF / WORD CON PDF.JS -->
        <div x-show="modalImport" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalImport" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="modalImport = false"></div>

                <div x-show="modalImport" x-transition class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-200">
                    <div class="bg-white p-6 space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl border border-amber-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-800">
                                        Importador de Documento PDF / Word
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Sube tu PDF para convertirlo automáticamente en plantilla editable</p>
                                </div>
                            </div>
                            <button type="button" @click="modalImport = false" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
                        </div>

                        <!-- Zona Drag & Drop de Archivos -->
                        <div class="border-2 border-dashed border-amber-300 bg-amber-50/40 hover:bg-amber-50/70 transition-all rounded-2xl p-8 text-center cursor-pointer relative"
                             @dragover.prevent
                             @drop.prevent="handleDroppedDoc($event)"
                             @click="$refs.docFileInput.click()">
                            
                            <input type="file" x-ref="docFileInput" @change="processDocumentFile($event)" accept=".pdf,.docx,.txt" class="hidden">

                            <div class="space-y-3">
                                <div class="w-14 h-14 mx-auto bg-white rounded-2xl shadow-sm border border-amber-200 flex items-center justify-center text-2xl text-amber-600">
                                    📄
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-800">
                                        Arrastra tu archivo PDF o Word aquí
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        o haz clic para explorar en tu computadora (.pdf, .docx)
                                    </p>
                                </div>
                                <span class="inline-block px-3 py-1 bg-amber-200/60 text-amber-900 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
                                    Fidelidad Visual Total
                                </span>
                            </div>
                        </div>

                        <!-- Loader / Estado de Procesamiento -->
                        <div x-show="processingDoc" class="p-4 bg-slate-900 text-white rounded-2xl flex items-center gap-3 animate-pulse" x-cloak>
                            <svg class="animate-spin h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div class="text-xs">
                                <p class="font-bold" x-text="processingStatus"></p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Detectando párrafos, firmas y saltos de página A4...</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-100 flex items-center justify-end">
                        <button type="button" @click="modalImport = false" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-100 transition">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Script de Interactividad Alpine y Lógica de Multi-Página -->
    <script>
        function templateDesigner() {
            return {
                nombre: '{{ addslashes($plantilla->nombre_plantilla) }}',
                categoria: '{{ addslashes($plantilla->categoria ?? "General") }}',
                descripcion: '{{ addslashes($plantilla->descripcion ?? "") }}',
                contenidoHtml: '',
                marcaAgua: '',
                modalImport: false,
                modalSignature: false,
                processingDoc: false,
                processingStatus: 'Extrayendo texto, marcas de agua e imágenes del PDF...',

                // Configuración de Pegado Inteligente
                pasteMode: 'rich',
                showPasteToast: false,
                lastPastedHtml: '',
                lastPastedText: '',
                lastPastePageIndex: 0,
                pasteToastTimer: null,

                encabezadosList: @json($encabezados),
                piesList: @json($pies),
                encabezadoId: @json($plantilla->encabezado_id ?? ''),
                pieId: @json($plantilla->pie_id ?? ''),

                get selectedEncabezadoHtml() {
                    if (!this.encabezadoId) return '';
                    const item = this.encabezadosList.find(e => e.id == this.encabezadoId);
                    return item ? item.contenido_html : '';
                },

                get selectedPieHtml() {
                    if (!this.pieId) return '';
                    const item = this.piesList.find(p => p.id == this.pieId);
                    return item ? item.contenido_html : '';
                },

                // Arquitectura Multi-Página A4 (Estilo Word)
                pages: [],
                activePageIndex: 0,

                init() {
                    let rawHtml = {!! json_encode($plantilla->contenido_html ?? '') !!};
                    let chunks = rawHtml ? rawHtml.split(/<!-- PAGE_BREAK -->|<div class="page-break-line"[^>]*>.*?<\/div>/gi) : [];
                    if (!chunks || chunks.length === 0 || (chunks.length === 1 && !chunks[0].trim())) {
                        this.pages = [{ id: 1, content: rawHtml || '<p>Escribe aquí el contenido del documento notarial...</p>' }];
                    } else {
                        this.pages = chunks.map((chunk, idx) => ({ id: idx + 1, content: chunk.trim() || '<p><br></p>' }));
                    }
                },

                getActiveEditor() {
                    const page = this.pages[this.activePageIndex] || this.pages[0];
                    return page ? document.getElementById('editor-sheet-' + page.id) : null;
                },

                addPageAfter(pIndex) {
                    const newId = Date.now() + Math.floor(Math.random() * 1000);
                    this.pages.splice(pIndex + 1, 0, { id: newId, content: '<p><br></p>' });
                    this.activePageIndex = pIndex + 1;
                    this.$nextTick(() => {
                        const ed = document.getElementById('editor-sheet-' + newId);
                        if (ed) {
                            ed.focus();
                            ed.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                },

                removePage(pIndex) {
                    if (this.pages.length <= 1) return;
                    if (confirm(`¿Deseas eliminar la Hoja ${pIndex + 1}?`)) {
                        this.pages.splice(pIndex, 1);
                        this.activePageIndex = Math.max(0, pIndex - 1);
                        this.$nextTick(() => {
                            const ed = this.getActiveEditor();
                            if (ed) ed.focus();
                        });
                    }
                },

                insertPageBreak() {
                    this.addPageAfter(this.activePageIndex);
                },

                onPageInput(pIndex, event) {
                    if (this.pages[pIndex]) {
                        this.pages[pIndex].content = event.target.innerHTML;
                    }
                },

                handlePageKeydown(pIndex, event) {
                    if (event.ctrlKey && event.key === 'Enter') {
                        event.preventDefault();
                        this.addPageAfter(pIndex);
                    }
                },

                // GESTIÓN AVANZADA DE PEGADO (CONSERVA FORMATO DE ORIGEN O PERMITE FORMATO DESTINO)
                handlePagePaste(pIndex, e) {
                    e.preventDefault();
                    this.activePageIndex = pIndex;
                    const clipboard = e.clipboardData || window.clipboardData;
                    const html = clipboard.getData('text/html');
                    const text = clipboard.getData('text/plain');

                    this.lastPastedHtml = html;
                    this.lastPastedText = text;
                    this.lastPastePageIndex = pIndex;

                    if (this.pasteMode === 'rich' && html && html.trim()) {
                        const clean = this.cleanPastedHtml(html);
                        document.execCommand('insertHTML', false, clean);
                    } else if (text) {
                        document.execCommand('insertText', false, text);
                    }

                    if (this.pages[pIndex]) {
                        this.pages[pIndex].content = e.target.innerHTML;
                    }

                    // Mostrar toast de cambio rápido tras pegar
                    if (html && text) {
                        this.showPasteToast = true;
                        clearTimeout(this.pasteToastTimer);
                        this.pasteToastTimer = setTimeout(() => {
                            this.showPasteToast = false;
                        }, 8000);
                    }
                },

                cleanPastedHtml(rawHtml) {
                    let cleaned = rawHtml
                        .replace(/<!--[\s\S]*?-->/g, '')
                        .replace(/<script[\s\S]*?<\/script>/gi, '')
                        .replace(/<style[\s\S]*?<\/style>/gi, '')
                        .replace(/<link[\s\S]*?>/gi, '')
                        .replace(/<meta[\s\S]*?>/gi, '')
                        .replace(/class="[^"]*mso[^"]*"/gi, '')
                        .replace(/style="[^"]*mso-[^"]*"/gi, (match) => match.replace(/mso-[^;"]+;?/gi, ''));
                    return cleaned;
                },

                applyPastedAs(type) {
                    const editor = this.getActiveEditor();
                    if (!editor) return;
                    editor.focus();
                    document.execCommand('undo', false, null);
                    if (type === 'rich' && this.lastPastedHtml) {
                        const clean = this.cleanPastedHtml(this.lastPastedHtml);
                        document.execCommand('insertHTML', false, clean);
                    } else if (this.lastPastedText) {
                        document.execCommand('insertText', false, this.lastPastedText);
                    }
                    this.onPageInput(this.activePageIndex, { target: editor });
                    this.showPasteToast = false;
                },

                onDropOnPage(pIndex, event) {
                    this.activePageIndex = pIndex;
                    this.onDrop(event);
                },

                selectedImage: null,
                isFreePosition: false,
                menuTop: '0px',
                menuLeft: '0px',
                resizeTop: '0px',
                resizeLeft: '0px',

                sigCanvas: null,
                sigCtx: null,
                isDrawing: false,
                sigColor: '#004080',

                variablesCliente: [
                    { label: 'Nombre Completo', tag: '[[cliente_nombre_completo]]' },
                    { label: 'Nombres', tag: '[[cliente_nombre]]' },
                    { label: 'Apellidos', tag: '[[cliente_apellido]]' },
                    { label: 'Identificación / ID', tag: '[[cliente_identificacion]]' },
                    { label: 'Teléfono', tag: '[[cliente_telefono]]' },
                    { label: 'Email', tag: '[[cliente_email]]' },
                    { label: 'Deuda Total', tag: '[[cliente_deuda]]' },
                    { label: 'Saldo Pendiente', tag: '[[cliente_saldo]]' },
                ],

                variablesUbicacion: [
                    { label: 'Dirección Completa', tag: '[[cliente_direccion_completa]]' },
                    { label: 'Calle / Dirección', tag: '[[cliente_direccion]]' },
                    { label: 'Ciudad', tag: '[[cliente_ciudad]]' },
                    { label: 'Estado', tag: '[[cliente_estado]]' },
                    { label: 'País', tag: '[[cliente_pais]]' },
                    { label: 'Código Postal', tag: '[[cliente_codpostal]]' },
                    { label: 'N° Apartamento', tag: '[[cliente_napartamento]]' },
                ],

                variablesNotaria: [
                    { label: 'Fecha Actual (Larga)', tag: '[[fecha_actual]]' },
                    { label: 'Fecha Corta (dd/mm/aaaa)', tag: '[[fecha_actual_corta]]' },
                    { label: 'Año Actual', tag: '[[anio_actual]]' },
                    { label: 'Nombre del Notario', tag: '[[notario_nombre]]' },
                    { label: 'Dirección Notaría', tag: '[[notary_address]]' },
                ],

                formatDoc(command, value = null) {
                    const editor = this.getActiveEditor();
                    if (editor) {
                        editor.focus();
                        document.execCommand(command, false, value);
                        this.onPageInput(this.activePageIndex, { target: editor });
                    }
                },

                formatBlock(tag) {
                    const editor = this.getActiveEditor();
                    if (editor) {
                        editor.focus();
                        document.execCommand('formatBlock', false, tag);
                        this.onPageInput(this.activePageIndex, { target: editor });
                    }
                },

                setFontFamily(family) {
                    if (!family) return;
                    const editor = this.getActiveEditor();
                    if (editor) {
                        editor.focus();
                        document.execCommand('fontName', false, family);
                        this.onPageInput(this.activePageIndex, { target: editor });
                    }
                },

                setFontSize(size) {
                    if (!size) return;
                    const editor = this.getActiveEditor();
                    if (!editor) return;
                    editor.focus();
                    const sel = window.getSelection();
                    if (!sel || sel.rangeCount === 0) return;
                    
                    document.execCommand('fontSize', false, '7');
                    const fontEls = editor.querySelectorAll('font[size="7"]');
                    fontEls.forEach(el => {
                        el.removeAttribute('size');
                        el.style.fontSize = size;
                    });
                    this.onPageInput(this.activePageIndex, { target: editor });
                },

                stepFontSize(delta) {
                    const editor = this.getActiveEditor();
                    if (!editor) return;
                    editor.focus();
                    const sel = window.getSelection();
                    if (!sel || sel.rangeCount === 0) return;
                    
                    let currentPt = 11;
                    const parent = sel.anchorNode?.parentElement;
                    if (parent) {
                        const styleVal = parent.style.fontSize;
                        if (styleVal && styleVal.includes('pt')) {
                            currentPt = parseInt(styleVal);
                        } else {
                            const comp = window.getComputedStyle(parent).fontSize;
                            const px = parseFloat(comp);
                            if (!isNaN(px)) currentPt = Math.round(px * 0.75);
                        }
                    }
                    const newPt = Math.max(7, Math.min(72, currentPt + delta));
                    this.setFontSize(newPt + 'pt');
                },

                setTextColor(color) {
                    if (!color) return;
                    const editor = this.getActiveEditor();
                    if (editor) {
                        editor.focus();
                        document.execCommand('foreColor', false, color);
                        this.onPageInput(this.activePageIndex, { target: editor });
                    }
                },

                setTextHighlight(color) {
                    const editor = this.getActiveEditor();
                    if (editor) {
                        editor.focus();
                        if (!color || color === 'transparent') {
                            document.execCommand('removeFormat', false, null);
                        } else {
                            document.execCommand('hiliteColor', false, color);
                        }
                        this.onPageInput(this.activePageIndex, { target: editor });
                    }
                },

                clearFormatting() {
                    const editor = this.getActiveEditor();
                    if (editor) {
                        editor.focus();
                        document.execCommand('removeFormat', false, null);
                        document.execCommand('formatBlock', false, 'p');
                        this.onPageInput(this.activePageIndex, { target: editor });
                    }
                },

                setLineHeight(val) {
                    const editor = this.getActiveEditor();
                    if (!editor) return;
                    editor.focus();
                    const sel = window.getSelection();
                    if (!sel || sel.rangeCount === 0) return;
                    let node = sel.anchorNode;
                    while (node && node !== editor) {
                        if (node.nodeType === 1 && (node.tagName === 'P' || node.tagName === 'DIV' || /^H[1-6]$/.test(node.tagName) || node.tagName === 'LI')) {
                            node.style.lineHeight = val;
                            break;
                        }
                        node = node.parentNode;
                    }
                    if (node === editor) {
                        document.execCommand('formatBlock', false, 'p');
                        const p = sel.anchorNode?.parentElement;
                        if (p) p.style.lineHeight = val;
                    }
                    this.onPageInput(this.activePageIndex, { target: editor });
                },

                insertTable(rows = 2, cols = 2) {
                    const editor = this.getActiveEditor();
                    if (!editor) return;
                    editor.focus();
                    let html = '<table style="width: 100%; border-collapse: collapse; margin: 10pt 0; border: 1.5px solid #64748b;">';
                    for (let r = 0; r < rows; r++) {
                        html += '<tr>';
                        for (let c = 0; c < cols; c++) {
                            const isH = (r === 0);
                            const bg = isH ? 'background-color: #f8fafc; font-weight: bold;' : 'background-color: #ffffff;';
                            html += `<td style="border: 1px solid #cbd5e1; padding: 6pt 10pt; min-width: 50px; vertical-align: top; ${bg}">&nbsp;</td>`;
                        }
                        html += '</tr>';
                    }
                    html += '</table><p><br></p>';
                    document.execCommand('insertHTML', false, html);
                    this.onPageInput(this.activePageIndex, { target: editor });
                },

                insertHorizontalRule() {
                    const editor = this.getActiveEditor();
                    if (editor) {
                        editor.focus();
                        document.execCommand('insertHorizontalRule', false, null);
                        this.onPageInput(this.activePageIndex, { target: editor });
                    }
                },

                insertLink() {
                    const url = prompt('Ingrese el enlace URL (ej: https://ejemplo.com):', 'https://');
                    if (url) {
                        this.formatDoc('createLink', url);
                    }
                },

                transformTextCase(mode, applyToAll = false) {
                    const convertText = (str) => {
                        if (!str) return '';
                        if (mode === 'upper') {
                            return str.toUpperCase();
                        } else if (mode === 'lower') {
                            return str.toLowerCase();
                        } else if (mode === 'title') {
                            return str.toLowerCase().replace(/(^|\s|[(\["'])([a-záéíóúñ])/gi, (m, p1, p2) => p1 + p2.toUpperCase());
                        } else if (mode === 'sentence') {
                            let res = str.toLowerCase();
                            res = res.replace(/(^\s*|[.?!;\n]\s+)([a-záéíóúñ])/gi, (match, prefix, char) => {
                                return prefix + char.toUpperCase();
                            });
                            res = res.replace(/^(\s*)([a-záéíóúñ])/i, (m, p, c) => p + c.toUpperCase());
                            return res;
                        }
                        return str;
                    };

                    if (applyToAll) {
                        this.pages.forEach((p) => {
                            const sheet = document.getElementById('editor-sheet-' + p.id);
                            if (sheet) {
                                const walker = document.createTreeWalker(sheet, NodeFilter.SHOW_TEXT, null, false);
                                let node;
                                while (node = walker.nextNode()) {
                                    if (node.nodeValue && node.nodeValue.trim().length > 0) {
                                        node.nodeValue = convertText(node.nodeValue);
                                    }
                                }
                                p.content = sheet.innerHTML;
                            }
                        });
                    } else {
                        const sel = window.getSelection();
                        if (sel && !sel.isCollapsed && sel.rangeCount > 0) {
                            const range = sel.getRangeAt(0);
                            const selectedText = range.toString();
                            if (selectedText) {
                                const transformed = convertText(selectedText);
                                document.execCommand('insertText', false, transformed);
                                const ed = this.getActiveEditor();
                                if (ed) this.pages[this.activePageIndex].content = ed.innerHTML;
                            }
                        } else {
                            const sheet = this.getActiveEditor();
                            if (sheet) {
                                const walker = document.createTreeWalker(sheet, NodeFilter.SHOW_TEXT, null, false);
                                let node;
                                while (node = walker.nextNode()) {
                                    if (node.nodeValue && node.nodeValue.trim().length > 0) {
                                        node.nodeValue = convertText(node.nodeValue);
                                    }
                                }
                                this.pages[this.activePageIndex].content = sheet.innerHTML;
                            }
                        }
                    }
                },

                onDragStart(event, tag) {
                    event.dataTransfer.setData('text/plain', tag);
                },

                onDrop(event) {
                    event.preventDefault();
                    if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                        const file = event.dataTransfer.files[0];
                        if (file.type.startsWith('image/')) {
                            this.readAndInsertImage(file);
                            return;
                        }
                    }

                    const tag = event.dataTransfer.getData('text/plain');
                    if (tag) {
                        this.insertHtmlAtCursor('<strong>' + tag + '</strong>');
                    }
                },

                insertTag(tag) {
                    this.insertHtmlAtCursor('<strong>' + tag + '</strong>');
                },

                insertHtmlAtCursor(html) {
                    const editor = this.getActiveEditor();
                    if (!editor) return;
                    editor.focus();

                    let sel = window.getSelection();
                    if (sel.getRangeAt && sel.rangeCount) {
                        let range = sel.getRangeAt(0);
                        range.deleteContents();
                        
                        let el = document.createElement('div');
                        el.innerHTML = html;
                        let frag = document.createDocumentFragment(), node, lastNode;
                        while ((node = el.firstChild)) {
                            lastNode = frag.appendChild(node);
                        }
                        range.insertNode(frag);
                        
                        if (lastNode) {
                            range = range.cloneRange();
                            range.setStartAfter(lastNode);
                            range.collapse(true);
                            sel.removeAllRanges();
                            sel.addRange(range);
                        }
                    } else {
                        editor.innerHTML += html;
                    }
                    this.pages[this.activePageIndex].content = editor.innerHTML;
                },

                openSignatureModal() {
                    this.modalSignature = true;
                    this.$nextTick(() => {
                        this.initSignatureCanvas();
                    });
                },

                initSignatureCanvas() {
                    this.sigCanvas = document.getElementById('signatureCanvas');
                    if (!this.sigCanvas) return;
                    this.sigCtx = this.sigCanvas.getContext('2d');
                    this.sigCtx.lineWidth = 2.5;
                    this.sigCtx.lineCap = 'round';
                    this.sigCtx.lineJoin = 'round';
                    this.clearSignature();
                },

                clearSignature() {
                    if (this.sigCtx && this.sigCanvas) {
                        this.sigCtx.clearRect(0, 0, this.sigCanvas.width, this.sigCanvas.height);
                    }
                },

                startDrawing(e) {
                    this.isDrawing = true;
                    this.sigCtx.beginPath();
                    this.sigCtx.strokeStyle = this.sigColor;
                    const rect = this.sigCanvas.getBoundingClientRect();
                    this.sigCtx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
                },

                draw(e) {
                    if (!this.isDrawing) return;
                    const rect = this.sigCanvas.getBoundingClientRect();
                    this.sigCtx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
                    this.sigCtx.stroke();
                },

                stopDrawing() {
                    this.isDrawing = false;
                },

                startDrawingTouch(e) {
                    e.preventDefault();
                    if (e.touches.length > 0) {
                        this.isDrawing = true;
                        this.sigCtx.beginPath();
                        this.sigCtx.strokeStyle = this.sigColor;
                        const rect = this.sigCanvas.getBoundingClientRect();
                        this.sigCtx.moveTo(e.touches[0].clientX - rect.left, e.touches[0].clientY - rect.top);
                    }
                },

                drawTouch(e) {
                    e.preventDefault();
                    if (!this.isDrawing || e.touches.length === 0) return;
                    const rect = this.sigCanvas.getBoundingClientRect();
                    this.sigCtx.lineTo(e.touches[0].clientX - rect.left, e.touches[0].clientY - rect.top);
                    this.sigCtx.stroke();
                },

                async saveAndInsertSignature() {
                    if (!this.sigCanvas) return;
                    const dataUrl = this.sigCanvas.toDataURL('image/png');
                    const serverUrl = await this.uploadImageToServer(dataUrl);
                    const finalSrc = serverUrl || dataUrl;

                    const html = `<img src="${finalSrc}" style="max-width: 140px; height: auto; display: block; margin: 10px 0; border: none; box-shadow: none;" alt="Firma Digital Notarial">`;
                    this.insertHtmlAtCursor(html);
                    this.modalSignature = false;
                },

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.readAndInsertImage(file);
                    }
                    event.target.value = '';
                },

                readAndInsertImage(file) {
                    const reader = new FileReader();
                    reader.onload = async (e) => {
                        const dataUrl = e.target.result;
                        const serverUrl = await this.uploadImageToServer(dataUrl);
                        const finalSrc = serverUrl || dataUrl;

                        const imgHtml = `<img src="${finalSrc}" style="max-width: 150px; height: auto; display: inline-block; margin: 8px; border: none; box-shadow: none;" alt="Imagen / Sello">`;
                        this.insertHtmlAtCursor(imgHtml);
                    };
                    reader.readAsDataURL(file);
                },

                async uploadImageToServer(base64Data) {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const res = await fetch('{{ route("plantillas.upload_image") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token || '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ image: base64Data })
                        });
                        const data = await res.json();
                        if (data.success && data.url) {
                            return data.url;
                        }
                    } catch (err) {
                        console.warn('No se pudo subir imagen al servidor, usando base64 local', err);
                    }
                    return null;
                },

                handlePaperClick(event) {
                    const target = event.target;
                    if (target.tagName === 'IMG' && !target.classList.contains('canvas-item-img')) {
                        this.selectImage(target);
                    } else {
                        if (this.selectedImage && !event.target.closest('.img-floating-toolbar') && !event.target.closest('.img-resize-handle')) {
                            this.deselectImage();
                        }
                    }
                },

                selectImage(img) {
                    if (this.selectedImage && this.selectedImage !== img) {
                        this.deselectImage();
                    }
                    this.selectedImage = img;
                    this.selectedImage.classList.add('selected-img');
                    this.isFreePosition = this.selectedImage.classList.contains('free-moving');
                    this.updateFloatingMenuPos();
                },

                deselectImage() {
                    if (this.selectedImage) {
                        this.selectedImage.classList.remove('selected-img');
                        this.selectedImage = null;
                    }
                },

                updateFloatingMenuPos() {
                    if (!this.selectedImage) return;
                    const workspace = this.$refs.workspaceContainer || document.body;
                    const containerRect = workspace.getBoundingClientRect();
                    const imgRect = this.selectedImage.getBoundingClientRect();

                    const top = imgRect.top - containerRect.top - 42;
                    const left = Math.max(10, imgRect.left - containerRect.left);

                    this.menuTop = top + 'px';
                    this.menuLeft = left + 'px';

                    this.resizeTop = (imgRect.bottom - containerRect.top - 6) + 'px';
                    this.resizeLeft = (imgRect.right - containerRect.left - 6) + 'px';
                },

                toggleFreePosition() {
                    if (!this.selectedImage) return;
                    const sheet = this.selectedImage.closest('.a4-sheet-container') || this.getActiveEditor();
                    if (!sheet) return;
                    
                    if (this.isFreePosition) {
                        this.selectedImage.classList.remove('free-moving');
                        this.selectedImage.style.position = 'relative';
                        this.selectedImage.style.left = 'auto';
                        this.selectedImage.style.top = 'auto';
                        this.selectedImage.style.zIndex = 'auto';
                        this.isFreePosition = false;
                    } else {
                        const containerRect = sheet.getBoundingClientRect();
                        const imgRect = this.selectedImage.getBoundingClientRect();
                        
                        this.selectedImage.classList.add('free-moving');
                        this.selectedImage.style.position = 'absolute';
                        this.selectedImage.style.left = (imgRect.left - containerRect.left) + 'px';
                        this.selectedImage.style.top = (imgRect.top - containerRect.top) + 'px';
                        this.selectedImage.style.zIndex = '20';
                        this.isFreePosition = true;
                    }
                    this.updateFloatingMenuPos();
                },

                startDraggingImage(e) {
                    if (!this.selectedImage) return;
                    e.preventDefault();
                    e.stopPropagation();

                    const container = this.selectedImage.closest('.a4-sheet-container') || this.getActiveEditor();
                    if (!container) return;

                    if (!this.isFreePosition) {
                        const containerRect = container.getBoundingClientRect();
                        const imgRect = this.selectedImage.getBoundingClientRect();

                        this.selectedImage.style.position = 'absolute';
                        this.selectedImage.style.left = (imgRect.left - containerRect.left) + 'px';
                        this.selectedImage.style.top = (imgRect.top - containerRect.top) + 'px';
                        this.selectedImage.style.zIndex = '20';
                        this.selectedImage.classList.add('free-moving');
                        this.isFreePosition = true;
                    }

                    const startLeft = parseFloat(this.selectedImage.style.left) || 0;
                    const startTop = parseFloat(this.selectedImage.style.top) || 0;
                    const startX = e.clientX;
                    const startY = e.clientY;

                    const onMouseMove = (moveEvent) => {
                        const dx = moveEvent.clientX - startX;
                        const dy = moveEvent.clientY - startY;

                        let newLeft = startLeft + dx;
                        let newTop = startTop + dy;

                        newLeft = Math.max(0, Math.min(container.clientWidth - this.selectedImage.offsetWidth, newLeft));
                        newTop = Math.max(0, newTop);

                        this.selectedImage.style.left = newLeft + 'px';
                        this.selectedImage.style.top = newTop + 'px';
                        this.updateFloatingMenuPos();
                    };

                    const onMouseUp = () => {
                        window.removeEventListener('mousemove', onMouseMove);
                        window.removeEventListener('mouseup', onMouseUp);
                        this.updateFloatingMenuPos();
                    };

                    window.addEventListener('mousemove', onMouseMove);
                    window.addEventListener('mouseup', onMouseUp);
                },

                startResizingImage(e) {
                    if (!this.selectedImage) return;
                    e.preventDefault();
                    e.stopPropagation();

                    const startX = e.clientX;
                    const startWidth = this.selectedImage.offsetWidth;

                    const onMouseMove = (moveEvent) => {
                        const dx = moveEvent.clientX - startX;
                        const newWidth = Math.max(40, startWidth + dx);
                        this.selectedImage.style.width = newWidth + 'px';
                        this.selectedImage.style.maxWidth = newWidth + 'px';
                        this.updateFloatingMenuPos();
                    };

                    const onMouseUp = () => {
                        window.removeEventListener('mousemove', onMouseMove);
                        window.removeEventListener('mouseup', onMouseUp);
                        this.updateFloatingMenuPos();
                    };

                    window.addEventListener('mousemove', onMouseMove);
                    window.addEventListener('mouseup', onMouseUp);
                },

                resizeSelectedImage(width) {
                    if (this.selectedImage) {
                        this.selectedImage.style.maxWidth = width;
                        this.selectedImage.style.width = width === '100%' ? '100%' : 'auto';
                        this.updateFloatingMenuPos();
                    }
                },

                alignSelectedImage(alignment) {
                    if (!this.selectedImage) return;
                    
                    this.selectedImage.style.position = 'relative';
                    this.selectedImage.style.left = 'auto';
                    this.selectedImage.style.top = 'auto';
                    this.selectedImage.style.zIndex = 'auto';
                    this.selectedImage.classList.remove('free-moving');
                    this.isFreePosition = false;

                    const parentP = this.selectedImage.closest('p');
                    if (alignment === 'center') {
                        this.selectedImage.style.float = 'none';
                        this.selectedImage.style.display = 'block';
                        this.selectedImage.style.margin = '0 auto 15px auto';
                        this.selectedImage.style.clear = 'both';
                        if (parentP) parentP.style.textAlign = 'center';
                    } else if (alignment === 'left') {
                        this.selectedImage.style.float = 'left';
                        this.selectedImage.style.display = 'inline-block';
                        this.selectedImage.style.margin = '0 18px 12px 0';
                        this.selectedImage.style.clear = 'none';
                        if (parentP) parentP.style.textAlign = 'left';
                    } else if (alignment === 'right') {
                        this.selectedImage.style.float = 'right';
                        this.selectedImage.style.display = 'inline-block';
                        this.selectedImage.style.margin = '0 0 12px 18px';
                        this.selectedImage.style.clear = 'none';
                        if (parentP) parentP.style.textAlign = 'right';
                    }
                    this.updateFloatingMenuPos();
                },

                deleteSelectedImage() {
                    if (this.selectedImage) {
                        const parent = this.selectedImage.parentElement;
                        this.selectedImage.remove();
                        this.selectedImage = null;
                        if (parent && parent.tagName === 'P' && !parent.innerHTML.trim()) {
                            parent.remove();
                        }
                    }
                },

                // Bloques rápidos notariales
                insertSignatureBlock() {
                    const html = `
                    <br><br>
                    <table style="width: 100%; border: none; margin-top: 30px; border-collapse: collapse;">
                        <tr>
                            <td style="width: 50%; text-align: center; vertical-align: top; padding: 10px;">
                                <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto; padding-top: 6px;">
                                    <strong>[[cliente_nombre_completo]]</strong><br>
                                    NUI: <strong>[[cliente_identificacion]]</strong><br>
                                    <span style="font-size: 9pt;">OTORGANTE / COMPARECIENTE</span>
                                </div>
                            </td>
                            <td style="width: 50%; text-align: center; vertical-align: top; padding: 10px;">
                                <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto; padding-top: 6px;">
                                    <strong>[[notario_nombre]]</strong><br>
                                    <span style="font-size: 9pt;">NOTARIO PÚBLICO AUTORIZADO</span>
                                </div>
                            </td>
                        </tr>
                    </table>`;
                    this.insertHtmlAtCursor(html);
                },

                insertFingerprintBox() {
                    const html = `
                    <div style="text-align: center; margin: 25px auto; width: 140px;">
                        <div style="width: 120px; height: 130px; border: 2px dashed #475569; border-radius: 8px; margin: 0 auto; display: flex; align-items: center; justify-content: center; background-color: #f8fafc;">
                            <span style="font-size: 8pt; color: #64748b; font-weight: bold; text-align: center;">HUELLA DACTILAR<br>PULGAR DERECHO</span>
                        </div>
                    </div>`;
                    this.insertHtmlAtCursor(html);
                },

                insertNotaryApostilleBlock() {
                    const html = `
                    <div style="margin-top: 30px; padding: 12px; border: 1px solid #94a3b8; border-radius: 6px; font-size: 9.5pt; background-color: #f8fafc;">
                        <p style="margin: 0; text-align: center;"><strong>STATE OF NEW YORK, COUNTY OF [[lugar_otorgamiento]]:</strong></p>
                        <p style="margin: 4px 0 0 0; text-align: justify;">On this <strong>[[fecha_actual]]</strong>, before me, the undersigned Notary Public, personally appeared <strong>[[cliente_nombre_completo]]</strong>, known to me to be the person described in and who executed the foregoing instrument, and acknowledged that they executed the same.</p>
                        <br>
                        <p style="margin: 0; text-align: right;">_____________________________________<br><strong>[[notario_nombre]]</strong><br>Notary Public, State of New York</p>
                    </div>`;
                    this.insertHtmlAtCursor(html);
                },

                // Extracción de Documento PDF con fidelidad visual total usando PDF.js
                handleDroppedDoc(event) {
                    if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                        this.extractTextFromFile(event.dataTransfer.files[0]);
                    }
                },

                processDocumentFile(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.extractTextFromFile(file);
                    }
                    event.target.value = '';
                },

                async extractTextFromFile(file) {
                    this.processingDoc = true;
                    this.processingStatus = 'Leyendo archivo ' + file.name + '...';
                    const fileName = file.name.replace(/\.[^/.]+$/, "");
                    if (!this.nombre) {
                        this.nombre = fileName;
                    }

                    function escapeHtml(str) {
                        return str.replace(/&/g, '&amp;')
                                  .replace(/</g, '&lt;')
                                  .replace(/>/g, '&gt;')
                                  .replace(/"/g, '&quot;');
                    }

                    try {
                        let htmlOutput = '';
                        const ext = file.name.toLowerCase().split('.').pop();

                        if (ext === 'docx') {
                            this.processingStatus = 'Procesando documento Word (.docx)...';
                            if (typeof mammoth !== 'undefined') {
                                const arrayBuffer = await file.arrayBuffer();
                                const result = await mammoth.convertToHtml({ arrayBuffer: arrayBuffer });
                                htmlOutput = result.value || '';
                            } else {
                                htmlOutput = await this.uploadAndParseServer(file);
                            }
                        } else if (ext === 'pdf') {
                            this.processingStatus = 'Extrayendo páginas y sellos del PDF...';
                            if (typeof pdfjsLib !== 'undefined') {
                                const arrayBuffer = await file.arrayBuffer();
                                const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
                                const numPages = pdf.numPages;

                                for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                                    this.processingStatus = `Analizando página ${pageNum} de ${numPages}...`;
                                    const page = await pdf.getPage(pageNum);
                                    const viewport = page.getViewport({ scale: 1.0 });
                                    const pageWidth = viewport.width;
                                    const textContent = await page.getTextContent();
                                    
                                    const operatorList = await page.getOperatorList();
                                    const pageImages = [];

                                    for (let i = 0; i < operatorList.fnArray.length; i++) {
                                        const fn = operatorList.fnArray[i];
                                        if (fn === pdfjsLib.OPS.paintImageXObject || fn === pdfjsLib.OPS.paintInlineImageXObject) {
                                            const imgObjName = operatorList.argsArray[i][0];
                                            try {
                                                const imgObj = await new Promise((resolve) => {
                                                    page.objs.get(imgObjName, (img) => resolve(img));
                                                });

                                                if (imgObj && (imgObj.data || imgObj.bitmap)) {
                                                    const canvas = document.createElement('canvas');
                                                    canvas.width = imgObj.width;
                                                    canvas.height = imgObj.height;
                                                    const ctx = canvas.getContext('2d');
                                                    
                                                    if (imgObj.bitmap) {
                                                        ctx.drawImage(imgObj.bitmap, 0, 0);
                                                    } else if (imgObj.data) {
                                                        const imgData = ctx.createImageData(imgObj.width, imgObj.height);
                                                        if (imgObj.data.length === imgObj.width * imgObj.height * 4) {
                                                            imgData.data.set(imgObj.data);
                                                        } else if (imgObj.data.length === imgObj.width * imgObj.height * 3) {
                                                            let j = 0;
                                                            for (let k = 0; k < imgObj.data.length; k += 3) {
                                                                imgData.data[j] = imgObj.data[k];
                                                                imgData.data[j+1] = imgObj.data[k+1];
                                                                imgData.data[j+2] = imgObj.data[k+2];
                                                                imgData.data[j+3] = 255;
                                                                j += 4;
                                                            }
                                                        } else if (imgObj.data.length === imgObj.width * imgObj.height) {
                                                            let j = 0;
                                                            for (let k = 0; k < imgObj.data.length; k++) {
                                                                const v = imgObj.data[k];
                                                                imgData.data[j] = v;
                                                                imgData.data[j+1] = v;
                                                                imgData.data[j+2] = v;
                                                                imgData.data[j+3] = 255;
                                                                j += 4;
                                                            }
                                                        }
                                                        ctx.putImageData(imgData, 0, 0);
                                                    }

                                                    if (imgObj.width >= 35 && imgObj.height >= 35) {
                                                        pageImages.push({
                                                            width: imgObj.width,
                                                            height: imgObj.height,
                                                            dataUrl: canvas.toDataURL('image/png')
                                                        });
                                                    }
                                                }
                                            } catch (e) {
                                                console.warn('No se pudo extraer imagen específica de página', e);
                                            }
                                        }
                                    }

                                    const rawItems = textContent.items.map(item => {
                                        const tx = item.transform;
                                        const fontName = (item.fontName || '').toLowerCase();
                                        const isBold = fontName.includes('bold') || fontName.includes('black') || fontName.includes('heavy') || fontName.includes('b');
                                        return {
                                            str: item.str,
                                            x: tx[4],
                                            y: tx[5],
                                            width: item.width,
                                            height: item.height,
                                            fontSize: Math.sqrt(tx[0]*tx[0] + tx[1]*tx[1]),
                                            isBold: isBold
                                        };
                                    }).filter(item => item.str.length > 0);

                                    const lines = [];
                                    const Y_TOLERANCE = 3.5;

                                    rawItems.forEach(item => {
                                        let foundLine = lines.find(l => Math.abs(l.y - item.y) <= Y_TOLERANCE);
                                        if (foundLine) {
                                            foundLine.items.push(item);
                                            foundLine.fontSize = Math.max(foundLine.fontSize, item.fontSize);
                                        } else {
                                            lines.push({
                                                y: item.y,
                                                fontSize: item.fontSize,
                                                items: [item]
                                            });
                                        }
                                    });

                                    lines.sort((a, b) => b.y - a.y);

                                    const processedLines = [];
                                    lines.forEach(line => {
                                        line.items.sort((a, b) => a.x - b.x);

                                        const spans = [];
                                        for (let i = 0; i < line.items.length; i++) {
                                            const cur = line.items[i];
                                            if (i === 0) {
                                                spans.push({ text: cur.str, isBold: cur.isBold });
                                                continue;
                                            }

                                            const prev = line.items[i - 1];
                                            const prevEndX = prev.x + prev.width;
                                            const gap = cur.x - prevEndX;
                                            const spaceThreshold = (cur.fontSize || 11) * 0.18;

                                            const needsSpace = (gap >= spaceThreshold) || (prev.str.endsWith(' ') || cur.str.startsWith(' '));
                                            const lastSpan = spans[spans.length - 1];

                                            if (lastSpan.isBold === cur.isBold) {
                                                if (needsSpace && !lastSpan.text.endsWith(' ') && !cur.str.startsWith(' ')) {
                                                    lastSpan.text += ' ' + cur.str.trimStart();
                                                } else {
                                                    lastSpan.text += cur.str;
                                                }
                                            } else {
                                                let textToAdd = cur.str;
                                                if (needsSpace && !lastSpan.text.endsWith(' ') && !cur.str.startsWith(' ')) {
                                                    textToAdd = ' ' + textToAdd.trimStart();
                                                }
                                                spans.push({ text: textToAdd, isBold: cur.isBold });
                                            }
                                        }

                                        const minX = line.items[0].x;
                                        const lastItem = line.items[line.items.length - 1];
                                        const maxX = lastItem.x + lastItem.width;
                                        const lineWidth = maxX - minX;

                                        let lineHtml = '';
                                        let plainText = '';
                                        spans.forEach(s => {
                                            let cleanText = s.text;
                                            plainText += cleanText;
                                            if (s.isBold && cleanText.trim().length > 0) {
                                                lineHtml += `<strong>${escapeHtml(cleanText)}</strong>`;
                                            } else {
                                                lineHtml += escapeHtml(cleanText);
                                            }
                                        });

                                        processedLines.push({
                                            y: line.y,
                                            fontSize: line.fontSize,
                                            minX: minX,
                                            maxX: maxX,
                                            lineWidth: lineWidth,
                                            plainText: plainText.trim(),
                                            html: lineHtml.trim()
                                        });
                                    });

                                    let pageHtml = '';
                                    let currentParagraph = '';
                                    let currentAlign = 'left';
                                    let prevLineY = null;
                                    let inBulletList = false;

                                    for (let i = 0; i < processedLines.length; i++) {
                                        const line = processedLines[i];
                                        if (!line.plainText) continue;

                                        let align = 'left';
                                        if (line.minX > pageWidth * 0.48 && line.lineWidth < pageWidth * 0.48) {
                                            align = 'right';
                                        } else if (Math.abs((line.minX + line.maxX)/2 - pageWidth/2) < 40 && line.lineWidth < pageWidth * 0.75 && line.minX > 60) {
                                            align = 'center';
                                        }

                                        const isBullet = line.plainText.startsWith('•') || line.plainText.startsWith('*') || line.plainText.startsWith('- ') || line.plainText.startsWith('-');
                                        const isHeaderBlock = line.plainText.startsWith('ASUNTO:') || line.plainText.startsWith('De mi consideración:') || line.plainText.startsWith('Atentamente,') || line.plainText.startsWith('Ciudad.');
                                        const isSignerBlock = line.plainText.startsWith('Anaís') || line.plainText.startsWith('Gerente') || line.plainText.startsWith('Estación de');

                                        const isLargeGap = prevLineY !== null && (prevLineY - line.y) > (line.fontSize * 1.55);

                                        if (currentParagraph && (isLargeGap || align !== currentAlign || align === 'right' || align === 'center' || isBullet || isHeaderBlock || isSignerBlock)) {
                                            if (inBulletList) {
                                                pageHtml += currentParagraph + '</div>\n';
                                                inBulletList = false;
                                            } else {
                                                const pStyle = currentAlign === 'right' 
                                                    ? 'text-align: right; margin-bottom: 15px;' 
                                                    : (currentAlign === 'center' ? 'text-align: center; margin-bottom: 12px;' : 'text-align: justify; margin-bottom: 8pt; line-height: 1.45;');
                                                pageHtml += `<p style="${pStyle}">${currentParagraph}</p>\n`;
                                            }
                                            currentParagraph = '';
                                        }

                                        currentAlign = align;

                                        if (isBullet) {
                                            const cleanBulletHtml = line.html.replace(/^[•\*\-]\s*/, '');
                                            const bulletSymbol = line.plainText.startsWith('-') ? '-' : '•';
                                            if (!inBulletList) {
                                                currentParagraph = `<div style="margin-left: 20px; margin-top: 6px; margin-bottom: 10px;">\n<p style="margin-bottom: 5px; text-align: justify;"><strong>${bulletSymbol}</strong> ${cleanBulletHtml}</p>`;
                                                inBulletList = true;
                                            } else {
                                                currentParagraph += `\n<p style="margin-bottom: 5px; text-align: justify;"><strong>${bulletSymbol}</strong> ${cleanBulletHtml}</p>`;
                                            }
                                        } else {
                                            if (currentParagraph) {
                                                if (currentParagraph.endsWith(':') || isHeaderBlock || isSignerBlock || currentParagraph.endsWith('<br>')) {
                                                    currentParagraph += `<br>${line.html}`;
                                                } else {
                                                    currentParagraph += ` ${line.html}`;
                                                }
                                            } else {
                                                currentParagraph = line.html;
                                            }
                                        }

                                        prevLineY = line.y;
                                    }

                                    if (currentParagraph) {
                                        if (inBulletList) {
                                            pageHtml += currentParagraph + '</div>\n';
                                        } else {
                                            const pStyle = currentAlign === 'right' 
                                                ? 'text-align: right; margin-bottom: 15px;' 
                                                : (currentAlign === 'center' ? 'text-align: center; margin-bottom: 12px;' : 'text-align: justify; margin-bottom: 8pt; line-height: 1.45;');
                                            pageHtml += `<p style="${pStyle}">${currentParagraph}</p>\n`;
                                        }
                                    }

                                    if (pageImages.length > 0) {
                                        let imgHtml = '<div style="margin: 15px 0;">';
                                        for (const img of pageImages) {
                                            const serverUrl = await this.uploadImageToServer(img.dataUrl);
                                            imgHtml += `<img src="${serverUrl || img.dataUrl}" style="max-width: 140px; height: auto; display: inline-block; margin-right: 15px; vertical-align: middle; border: none; box-shadow: none;" alt="Sello / Firma Digital">`;
                                        }
                                        imgHtml += '</div>';

                                        if (pageHtml.includes('Atentamente,')) {
                                            pageHtml = pageHtml.replace('Atentamente,</p>', `Atentamente,</p>\n${imgHtml}`);
                                        } else {
                                            pageHtml += imgHtml;
                                        }
                                    }

                                    if (pageNum > 1 && htmlOutput.trim().length > 0) {
                                        htmlOutput += '<div class="page-break-line" contenteditable="false"><span class="page-break-badge">📄 Salto de Página A4</span></div>\n';
                                    }
                                    htmlOutput += pageHtml;
                                }
                            } else {
                                htmlOutput = await this.uploadAndParseServer(file);
                            }
                        } else {
                            const text = await file.text();
                            const paragraphs = text.split(/\r?\n+/).map(p => p.trim()).filter(p => p.length > 0);
                            paragraphs.forEach(p => {
                                htmlOutput += `<p style="text-align: justify; margin-bottom: 8pt; line-height: 1.45;">${escapeHtml(p)}</p>\n`;
                            });
                        }

                        if (!htmlOutput || htmlOutput.trim().length === 0) {
                            htmlOutput = await this.uploadAndParseServer(file);
                        }

                        if (htmlOutput && htmlOutput.trim().length > 0) {
                            const chunks = htmlOutput.split(/<!-- PAGE_BREAK -->|<div class="page-break-line"[^>]*>.*?<\/div>/gi);
                            if (chunks && chunks.length > 1) {
                                this.pages = chunks.map((c, i) => ({ id: i + 1, content: c.trim() || '<p><br></p>' }));
                            } else {
                                this.pages = [{ id: 1, content: htmlOutput }];
                            }
                            this.modalImport = false;
                            this.processingDoc = false;
                        } else {
                            throw new Error('No se pudo extraer contenido del archivo');
                        }

                    } catch (err) {
                        console.warn('Fallo extracción cliente, intentando servidor...', err);
                        try {
                            const serverHtml = await this.uploadAndParseServer(file);
                            if (serverHtml) {
                                const chunks = serverHtml.split(/<!-- PAGE_BREAK -->|<div class="page-break-line"[^>]*>.*?<\/div>/gi);
                                if (chunks && chunks.length > 1) {
                                    this.pages = chunks.map((c, i) => ({ id: i + 1, content: c.trim() || '<p><br></p>' }));
                                } else {
                                    this.pages = [{ id: 1, content: serverHtml }];
                                }
                                this.modalImport = false;
                                this.processingDoc = false;
                                return;
                            }
                        } catch (serverErr) {
                            console.error('Error total importando archivo:', serverErr);
                        }
                        this.processingDoc = false;
                        alert('Ocurrió un error al procesar el archivo. Intenta de nuevo o copia y pega el texto directamente en el editor.');
                    }
                },

                async uploadAndParseServer(file) {
                    const formData = new FormData();
                    formData.append('documento', file);
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const res = await fetch('{{ route("plantillas.import_pdf") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token || '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    const data = await res.json();
                    if (data.success && data.html) {
                        if (!this.nombre && data.nombre) {
                            this.nombre = data.nombre;
                        }
                        return data.html;
                    }
                    return null;
                },

                syncContent(e) {
                    this.deselectImage();
                    const cleanPages = this.pages.map((p) => {
                        const sheetEl = document.getElementById('editor-sheet-' + p.id);
                        let html = sheetEl ? sheetEl.innerHTML : (p.content || '');
                        
                        const temp = document.createElement('div');
                        temp.innerHTML = html;
                        temp.querySelectorAll('.selected-img').forEach(el => el.classList.remove('selected-img'));
                        temp.querySelectorAll('img').forEach(img => {
                            img.style.outline = 'none';
                            img.style.boxShadow = 'none';
                            img.style.webkitBoxShadow = 'none';
                        });
                        return temp.innerHTML.trim();
                    });

                    this.contenidoHtml = cleanPages.join('\n<!-- PAGE_BREAK -->\n<div class="page-break-line" style="page-break-after: always; break-after: page;"></div>\n');

                    if (!this.nombre.trim()) {
                        alert('Por favor especifica un nombre para la plantilla.');
                        e.preventDefault();
                        return;
                    }
                },

                // ==========================================
                // MÉTODOS DEL DISEÑADOR DRAG & DROP (CANVA)
                // ==========================================
                openCanvasDesigner(tipo) {
                    const currentId = (tipo === 'encabezado') ? this.encabezadoId : this.pieId;
                    const list = (tipo === 'encabezado') ? this.encabezadosList : this.piesList;
                    const found = list.find(item => item.id == currentId);
                    window.openCanvasDesigner(tipo, found || null);
                },

                onMembreteSaved(m) {
                    if (!m) return;
                    if (m.tipo === 'encabezado') {
                        const idx = this.encabezadosList.findIndex(e => e.id == m.id);
                        if (idx >= 0) {
                            this.encabezadosList[idx] = m;
                        } else {
                            this.encabezadosList.push(m);
                        }
                        this.encabezadoId = m.id;
                    } else {
                        const idx = this.piesList.findIndex(p => p.id == m.id);
                        if (idx >= 0) {
                            this.piesList[idx] = m;
                        } else {
                            this.piesList.push(m);
                        }
                        this.pieId = m.id;
                    }
                }
            };
        }
    </script>

    @include('plantillas.partials.drag_drop_designer_modal')
</x-app-layout>
