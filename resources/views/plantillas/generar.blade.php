<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-2">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        Generador de Documento Notarial
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Plantilla: <strong>{{ $plantilla->nombre_plantilla }}</strong> 
                        @if($cliente) &bull; Cliente: <strong class="text-indigo-600">{{ $cliente->c_nombre }} {{ $cliente->c_apellido }}</strong> @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('plantillas.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-wider shadow-sm hover:bg-slate-50 transition duration-150">
                    &larr; Volver a Plantillas
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Estilos de Documento Multi-Página A4, Marca de Agua e Impresión -->
    <style>
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
            display: inline-block;
            cursor: pointer;
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
            [class*="no-print"], button, select, input, textarea, form, .modal {
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

    <div class="py-6 bg-slate-100 min-h-screen" x-data="documentGenerator()" @membrete-saved.window="onMembreteSaved($event.detail)">
        <div class="w-full max-w-[1750px] mx-auto sm:px-6 lg:px-8 space-y-5">

            <!-- Fila Superior: Selector de Cliente y Acciones Principales -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4 no-print">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    
                    <!-- Selector de Cliente -->
                    <div class="flex-1 w-full md:w-auto">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Seleccionar Cliente para Completar Datos Automáticamente:
                        </label>
                        <div class="flex items-center gap-2">
                            <select @change="changeClient($event.target.value)" class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 focus:bg-white focus:border-indigo-500 font-bold text-slate-800">
                                <option value="">-- Sin Cliente (Plantilla en blanco) --</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->id_cliente }}" {{ ($cliente && $cliente->id_cliente == $c->id_cliente) ? 'selected' : '' }}>
                                        {{ $c->c_apellido }} {{ $c->c_nombre }} &bull; C.I./ID: {{ $c->c_identificacion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Botonera de Acciones de Exportación y Guardado -->
                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto justify-end">
                        
                        <!-- Botón Descargar PDF Oficial -->
                        <button type="button" @click="descargarPdfServer()" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg shadow-rose-200 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            📄 Descargar PDF
                        </button>

                        <!-- Botón Descargar Word (.doc) -->
                        <button type="button" @click="descargarDocxServer()" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg shadow-blue-200 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            📝 Descargar Word
                        </button>

                        <!-- Botón Dibujar Firma Digital -->
                        <button type="button" @click="openSignatureModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold transition-all shadow-xs">
                            ✍️ Firma
                        </button>

                        <!-- Botón Guardar en Expediente del Cliente -->
                        @if($cliente)
                            <button type="button" @click="guardarEnExpediente()" :disabled="savingExpediente" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 rounded-xl text-xs font-bold transition-all shadow-xs">
                                <span x-text="savingExpediente ? 'Guardando...' : '💾 Guardar'"></span>
                            </button>
                        @endif

                        <!-- Botón Imprimir / Guardar como PDF Directo -->
                        <button type="button" @click="printDocument()" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>🖨️ Imprimir</span>
                        </button>

                    </div>

                </div>

                <!-- Sub-barra de Membretes Aplicados -->
                <div class="pt-3 border-t border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 bg-slate-50/70 p-3 rounded-xl">
                    <div class="flex flex-wrap items-center gap-4">
                        <!-- Encabezado -->
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-black uppercase text-amber-800 tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Encabezado:
                            </span>
                            <select x-model="encabezadoId" @change="encabezadoId = $event.target.value" class="text-xs bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-slate-800 focus:border-amber-500">
                                <option value="">(Sin encabezado)</option>
                                @foreach($encabezados as $enc)
                                    <option value="{{ $enc->id }}">{{ $enc->nombre }}{{ $enc->es_predeterminado ? ' ★' : '' }}</option>
                                @endforeach
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
                            <select x-model="pieId" @change="pieId = $event.target.value" class="text-xs bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-slate-800 focus:border-indigo-500">
                                <option value="">(Sin pie de página)</option>
                                @foreach($pies as $pie)
                                    <option value="{{ $pie->id }}">{{ $pie->nombre }}{{ $pie->es_predeterminado ? ' ★' : '' }}</option>
                                @endforeach
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

            <!-- FORMULARIO PREVIO: VARIABLES DINÁMICAS ADICIONALES -->
            @if(count($customVars) > 0)
                <div class="bg-amber-50/70 border border-amber-200 p-5 rounded-2xl shadow-xs no-print" x-data="{ openCustomVars: true }">
                    <div class="flex items-center justify-between cursor-pointer" @click="openCustomVars = !openCustomVars">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">⚡</span>
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-wider text-amber-900">
                                    Formulario de Variables Adicionales del Documento
                                </h3>
                                <p class="text-[11px] text-amber-700">Rellena estos campos para insertarlos instantáneamente en el documento en vivo</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-amber-800" x-text="openCustomVars ? '▲ Ocultar' : '▼ Mostrar'"></span>
                    </div>

                    <div x-show="openCustomVars" x-transition class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-4 pt-3 border-t border-amber-200/60">
                        @foreach($customVars as $cv)
                            <div>
                                <label class="block text-[11px] font-bold text-amber-900 uppercase tracking-tight mb-1">
                                    {{ $cv['label'] }}
                                </label>
                                <input type="text" 
                                       @input="replaceCustomVar('{{ $cv['tag'] }}', $event.target.value)"
                                       placeholder="Escribir {{ strtolower($cv['label']) }}..."
                                       class="w-full text-xs bg-white border border-amber-300 rounded-xl px-3 py-2 text-slate-800 font-semibold focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- BARRA DE HERRAMIENTAS PROFESIONAL DE EDICIÓN Y FORMATO DE TEXTO -->
            <div class="bg-white/95 backdrop-blur-md p-2.5 rounded-2xl border border-slate-200 shadow-md flex flex-wrap items-center justify-between gap-2 sticky top-3 z-40 no-print">
                
                <div class="flex flex-wrap items-center gap-1.5 text-xs">
                    
                    <!-- 1. Deshacer / Rehacer -->
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                        <button type="button" @mousedown.prevent="" @click="formatDoc('undo')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Deshacer (Ctrl+Z)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-4-4m4 4l4-4M3 10l4-4m-4 4l4 4"/></svg>
                        </button>
                        <button type="button" @mousedown.prevent="" @click="formatDoc('redo')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Rehacer (Ctrl+Y)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m0 0l4-4m-4 4l-4-4m20-2l-4-4m4 4l-4 4"/></svg>
                        </button>
                    </div>

                    <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                    <!-- 2. Fuente Tipográfica -->
                    <div class="relative inline-block">
                        <select @mousedown.prevent="" @change="setFontFamily($event.target.value)" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 font-bold text-slate-800 focus:bg-white focus:border-indigo-500 cursor-pointer shadow-2xs">
                            <option value="Helvetica, Arial, sans-serif" style="font-family: Helvetica, Arial, sans-serif;">Helvetica / Arial</option>
                            <option value="'Times New Roman', Times, serif" style="font-family: 'Times New Roman', Times, serif;">Times New Roman</option>
                            <option value="Calibri, Candara, Segoe, sans-serif" style="font-family: Calibri, sans-serif;">Calibri</option>
                            <option value="Georgia, serif" style="font-family: Georgia, serif;">Georgia</option>
                            <option value="'Courier New', Courier, monospace" style="font-family: 'Courier New', monospace;">Courier New</option>
                            <option value="Verdana, Geneva, sans-serif" style="font-family: Verdana, sans-serif;">Verdana</option>
                            <option value="Garamond, serif" style="font-family: Garamond, serif;">Garamond</option>
                            <option value="'Trebuchet MS', sans-serif" style="font-family: 'Trebuchet MS', sans-serif;">Trebuchet MS</option>
                            <option value="Tahoma, sans-serif" style="font-family: Tahoma, sans-serif;">Tahoma</option>
                        </select>
                    </div>

                    <!-- 3. Tamaño de Fuente -->
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                        <button type="button" @mousedown.prevent="" @click="stepFontSize(-1)" class="px-1.5 py-1 hover:bg-white text-slate-700 font-black rounded-lg text-xs transition" title="Disminuir tamaño (A-)">A-</button>
                        <select @mousedown.prevent="" @change="setFontSize($event.target.value)" class="text-xs bg-transparent border-0 py-1 px-1 font-extrabold text-slate-800 focus:ring-0 cursor-pointer">
                            <option value="8pt">8 pt</option>
                            <option value="9pt">9 pt</option>
                            <option value="10pt">10 pt</option>
                            <option value="11pt" selected>11 pt</option>
                            <option value="12pt">12 pt</option>
                            <option value="13pt">13 pt</option>
                            <option value="14pt">14 pt</option>
                            <option value="16pt">16 pt</option>
                            <option value="18pt">18 pt</option>
                            <option value="20pt">20 pt</option>
                            <option value="24pt">24 pt</option>
                            <option value="28pt">28 pt</option>
                            <option value="36pt">36 pt</option>
                        </select>
                        <button type="button" @mousedown.prevent="" @click="stepFontSize(1)" class="px-1.5 py-1 hover:bg-white text-slate-700 font-black rounded-lg text-xs transition" title="Aumentar tamaño (A+)">A+</button>
                    </div>

                    <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                    <!-- 4. Estilos de Carácter (B, I, U, S, Sub, Sup) -->
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                        <button type="button" @mousedown.prevent="" @click="formatDoc('bold')" class="px-2 py-1 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-800 font-black text-xs transition" title="Negrita (Ctrl+B)"><strong>B</strong></button>
                        <button type="button" @mousedown.prevent="" @click="formatDoc('italic')" class="px-2 py-1 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-800 italic font-bold text-xs transition" title="Cursiva (Ctrl+I)"><em>I</em></button>
                        <button type="button" @mousedown.prevent="" @click="formatDoc('underline')" class="px-2 py-1 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-800 underline font-bold text-xs transition" title="Subrayado (Ctrl+U)"><u>U</u></button>
                        <button type="button" @mousedown.prevent="" @click="formatDoc('strikeThrough')" class="px-2 py-1 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-600 line-through font-bold text-xs transition" title="Tachado"><s>S</s></button>
                        <button type="button" @mousedown.prevent="" @click="formatDoc('subscript')" class="px-1.5 py-1 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-600 font-bold text-[11px] transition" title="Subíndice">X<sub>2</sub></button>
                        <button type="button" @mousedown.prevent="" @click="formatDoc('superscript')" class="px-1.5 py-1 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-600 font-bold text-[11px] transition" title="Superíndice">X<sup>2</sup></button>
                    </div>

                    <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                    <!-- 5. Color de Texto, Resaltador y Limpiar Formato -->
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 gap-0.5 shadow-2xs">
                        <!-- Color de Texto -->
                        <div class="relative flex items-center px-1.5 py-1 hover:bg-white rounded-lg cursor-pointer" title="Color de Texto">
                            <label class="cursor-pointer flex items-center gap-1">
                                <span class="font-black text-xs text-slate-900 border-b-2 border-indigo-600 leading-none">A</span>
                                <input type="color" @change="setTextColor($event.target.value)" class="opacity-0 w-0 h-0 absolute cursor-pointer">
                            </label>
                        </div>

                        <!-- Color de Resaltado -->
                        <div class="relative" x-data="{ openHilite: false }">
                            <button type="button" @mousedown.prevent="" @click="openHilite = !openHilite" @click.away="openHilite = false" class="px-1.5 py-1 hover:bg-white rounded-lg flex items-center gap-0.5 text-xs text-slate-700" title="Resaltar Fondo de Texto">
                                <span>🖍️</span>
                                <span class="text-[9px]">▼</span>
                            </button>
                            <div x-show="openHilite" x-transition class="absolute left-0 mt-2 p-2 bg-white rounded-xl shadow-xl border border-slate-200 grid grid-cols-3 gap-1.5 z-50 w-36" x-cloak>
                                <button type="button" @mousedown.prevent="" @click="setTextHighlight('#fef08a'); openHilite = false;" class="w-8 h-8 rounded-lg bg-yellow-200 hover:scale-105 border border-yellow-300 transition" title="Amarillo"></button>
                                <button type="button" @mousedown.prevent="" @click="setTextHighlight('#bbf7d0'); openHilite = false;" class="w-8 h-8 rounded-lg bg-emerald-200 hover:scale-105 border border-emerald-300 transition" title="Verde"></button>
                                <button type="button" @mousedown.prevent="" @click="setTextHighlight('#a5f3fc'); openHilite = false;" class="w-8 h-8 rounded-lg bg-cyan-200 hover:scale-105 border border-cyan-300 transition" title="Cian"></button>
                                <button type="button" @mousedown.prevent="" @click="setTextHighlight('#fbcfe8'); openHilite = false;" class="w-8 h-8 rounded-lg bg-pink-200 hover:scale-105 border border-pink-300 transition" title="Rosa"></button>
                                <button type="button" @mousedown.prevent="" @click="setTextHighlight('#fed7aa'); openHilite = false;" class="w-8 h-8 rounded-lg bg-orange-200 hover:scale-105 border border-orange-300 transition" title="Naranja"></button>
                                <button type="button" @mousedown.prevent="" @click="setTextHighlight('transparent'); openHilite = false;" class="w-8 h-8 rounded-lg bg-slate-100 hover:scale-105 border border-slate-300 text-xs flex items-center justify-center font-bold text-slate-500" title="Sin color">✕</button>
                            </div>
                        </div>

                        <!-- Limpiar Formato -->
                        <button type="button" @mousedown.prevent="" @click="clearFormatting()" class="px-1.5 py-1 hover:bg-white hover:text-rose-600 rounded-lg text-slate-500 transition text-xs font-bold" title="Borrar Formato">
                            🧹
                        </button>
                    </div>

                    <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                    <!-- 6. Párrafos & Encabezados -->
                    <select @mousedown.prevent="" @change="formatBlock($event.target.value)" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 font-bold text-slate-800 focus:bg-white focus:border-indigo-500 cursor-pointer shadow-2xs">
                        <option value="P">Párrafo Normal</option>
                        <option value="H1">Título Principal (H1)</option>
                        <option value="H2">Subtítulo (H2)</option>
                        <option value="H3">Sección (H3)</option>
                        <option value="BLOCKQUOTE">Cita / Bloque Notarial</option>
                    </select>

                    <span class="w-px h-5 bg-slate-200 mx-0.5"></span>

                    <!-- 7. Alineación -->
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

                    <!-- 8. Interlineado y Sangrías -->
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

                    <!-- 9. Listas, Tablas e Inserciones -->
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-0.5 shadow-2xs">
                        <button type="button" @mousedown.prevent="" @click="formatDoc('insertUnorderedList')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Viñetas">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16M4 6a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0zm0 6a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                        </button>
                        <button type="button" @mousedown.prevent="" @click="formatDoc('insertOrderedList')" class="p-1.5 hover:bg-white hover:text-indigo-600 rounded-lg text-slate-700 transition" title="Lista numerada">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 5h1v3H3zm0 6h2v1H4v1h1v1H3zm0 6h2v3H3z"/></svg>
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

                    <!-- 10. HERRAMIENTA Aa: CONVERSIÓN DE MAYÚSCULAS Y MINÚSCULAS -->
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

                    <!-- 11. Selector de Modo de Pegado (Paste Mode Switcher) -->
                    <div class="flex items-center bg-emerald-50 border border-emerald-200 rounded-xl px-2 py-1 gap-1 text-[11px] font-bold text-emerald-900 shadow-2xs">
                        <span>📋 Pegado:</span>
                        <select x-model="pasteMode" class="text-[11px] font-extrabold bg-transparent border-0 p-0 text-emerald-800 focus:ring-0 cursor-pointer">
                            <option value="rich">✨ Mantener formato origen</option>
                            <option value="plain">📄 Pegar solo texto plano</option>
                        </select>
                    </div>

                </div>

                <!-- Controles de Hoja y Marca de Agua a la Derecha -->
                <div class="flex items-center gap-2">
                    <!-- Selector de Marca de Agua -->
                    <select x-model="marcaAgua" class="text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-slate-800 focus:bg-white shadow-2xs">
                        <option value="">Sin marca de agua</option>
                        <option value="ORIGINAL">ORIGINAL</option>
                        <option value="COPIA CERTIFICADA">COPIA CERTIFICADA</option>
                        <option value="BORRADOR">BORRADOR</option>
                        <option value="DOCUMENTO NOTARIAL">DOCUMENTO NOTARIAL</option>
                    </select>

                    <!-- Botón Insertar Salto de Página A4 -->
                    <button type="button" @click="insertPageBreak()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition shadow-sm" title="Insertar división para nueva hoja A4 (Ctrl+Enter)">
                        <span>📄</span>
                        <span class="hidden sm:inline">+ Salto A4</span>
                    </button>
                </div>

            </div>

            <!-- ESPACIO DE TRABAJO MULTI-HOJA A4 (ESTILO WORD CON ENCABEZADO Y PIE REPETIDOS) -->
            <div class="bg-slate-200/80 p-4 sm:p-8 rounded-2xl border border-slate-300 min-h-[700px] flex flex-col items-center gap-8 relative" x-ref="workspaceContainer">
                
                <!-- Marca de Agua Visual -->
                <div class="watermark-overlay" x-show="marcaAgua" x-text="marcaAgua" x-cloak></div>

                <!-- MENÚ CONTEXTUAL FLOTANTE ADOSADO A LA IMAGEN -->
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

                    <!-- Alternar Modo Libre -->
                    <button type="button" @click="toggleFreePosition()" class="px-2.5 py-1 rounded text-[10px] font-bold transition-colors" :class="isFreePosition ? 'bg-amber-400 text-slate-950 shadow-xs' : 'bg-slate-800 text-slate-200 hover:bg-slate-700'">
                        <span x-text="isFreePosition ? '📍 Modo Libre' : '📄 En Flujo'"></span>
                    </button>

                    <span class="w-px h-4 bg-slate-700 mx-0.5"></span>

                    <!-- Eliminar Imagen -->
                    <button type="button" @click="deleteSelectedImage()" class="px-2 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-[10px] font-bold" title="Eliminar imagen">
                        🗑️
                    </button>
                    <button type="button" @click="deselectImage()" class="px-1.5 py-1 text-slate-400 hover:text-white text-[10px]" title="Cerrar menú">✕</button>
                </div>

                <!-- Manija de Redimensionamiento de Esquina -->
                <div x-show="selectedImage" 
                     class="img-resize-handle no-print" 
                     :style="'top:' + resizeTop + '; left:' + resizeLeft + ';'"
                     @mousedown="startResizingImage($event)"
                     x-cloak>
                </div>

                <!-- RENDERIZADO DE HOJAS A4 (CADA HOJA TIENE SU ENCABEZADO Y PIE DE PÁGINA) -->
                <template x-for="(page, pIndex) in pages" :key="page.id">
                    <div class="a4-sheet-container" :id="'page-card-' + page.id">
                        
                        <!-- Barra Superior de Hoja (Solo en pantalla) -->
                        <div class="a4-sheet-header-badge no-print flex items-center justify-between px-6 py-2 bg-slate-100 border-b border-slate-200 text-xs text-slate-600 rounded-t select-none">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                                <span class="font-extrabold text-slate-800 tracking-tight" x-text="'Hoja A4 — Página ' + (pIndex + 1) + ' de ' + pages.length"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="addPageAfter(pIndex)" class="inline-flex items-center gap-1 px-3 py-1 bg-white hover:bg-emerald-50 text-emerald-700 border border-slate-200 rounded-lg text-xs font-bold shadow-2xs transition">
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
                             x-show="getEncabezadoHtml()" 
                             x-html="getEncabezadoHtml()"
                             x-cloak></div>

                        <!-- 2. CUERPO EDITABLE DE LA HOJA A4 -->
                        <div :id="'generador-sheet-' + page.id" 
                             class="a4-sheet-body document-paper" 
                             contenteditable="true" 
                             @focus="activePageIndex = pIndex"
                             @input="onPageInput(pIndex, $event)"
                             @paste="handlePagePaste(pIndex, $event)"
                             @keydown="handlePageKeydown(pIndex, $event)"
                             @click="handlePaperClick($event)"
                             x-html="page.content"></div>

                        <!-- 3. PIE DE PÁGINA INFERIOR (Se repite en CADA hoja A4) -->
                        <div class="a4-sheet-footer-render select-none bg-white transition-all rounded-b" 
                             x-show="getPieHtml()" 
                             x-html="getPieHtml()"
                             x-cloak></div>

                    </div>
                </template>

                <!-- Botón Inferior Grande para Agregar Nueva Hoja -->
                <div class="w-full max-w-[210mm] flex justify-center py-2 select-none no-print">
                    <button type="button" @click="addPageAfter(pages.length - 1)" class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-emerald-50 text-emerald-700 border-2 border-dashed border-emerald-300 hover:border-emerald-500 rounded-2xl font-bold text-xs shadow-sm hover:shadow transition-all transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>+ Agregar Nueva Hoja A4 (Página Siguiente)</span>
                    </button>
                </div>

            </div>

        </div>

        <!-- Toast Flotante de Opciones de Pegado Inteligente -->
        <div x-show="showPasteToast" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-3 text-xs no-print"
             x-cloak>
            <div class="flex items-center gap-2">
                <span class="text-base">📋</span>
                <span class="font-bold">Texto pegado:</span>
            </div>
            <div class="flex items-center gap-1 bg-slate-800 p-1 rounded-xl border border-slate-700">
                <button type="button" @click="applyPastedAs('rich')" class="px-2.5 py-1 rounded-lg font-bold transition text-xs" :class="pasteMode === 'rich' ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300'">
                    ✨ Mantener Origen
                </button>
                <button type="button" @click="applyPastedAs('plain')" class="px-2.5 py-1 rounded-lg font-bold transition text-xs" :class="pasteMode === 'plain' ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300'">
                    📄 Solo Texto
                </button>
            </div>
            <button type="button" @click="showPasteToast = false" class="text-slate-400 hover:text-white text-xs px-1">✕</button>
        </div>

        <!-- MODAL DE FIRMA DIGITAL EN PANTALLA -->
        <div x-show="modalSignature" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalSignature" x-transition class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="modalSignature = false"></div>

                <div x-show="modalSignature" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-200">
                    
                    <div class="bg-white p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                                    ✍️ Dibujar Firma en Pantalla
                                </h3>
                                <p class="text-xs text-slate-400 mt-0.5">Dibuja la firma del otorgante o notario</p>
                            </div>
                            <button type="button" @click="modalSignature = false" class="text-slate-400 hover:text-slate-600">✕</button>
                        </div>

                        <!-- Lienzo de Firma -->
                        <div class="border-2 border-indigo-200 rounded-2xl p-2 bg-slate-50 relative">
                            <canvas id="genSignatureCanvas" width="400" height="180" class="w-full bg-white rounded-xl cursor-crosshair shadow-inner"></canvas>
                        </div>

                        <!-- Opciones y Botones -->
                        <div class="flex items-center justify-between pt-2">
                            <button type="button" @click="clearSignature()" class="text-xs text-rose-600 hover:text-rose-700 font-bold px-3 py-1.5 rounded-lg hover:bg-rose-50 transition">
                                🗑️ Limpiar Firma
                            </button>
                            <button type="button" @click="insertSignature()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition">
                                ✍️ Insertar en Documento
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Script de Interactividad y Exportación Alpine -->
    <script>
        function documentGenerator() {
            return {
                marcaAgua: '',
                modalSignature: false,
                savingExpediente: false,
                currentDocHtml: '',

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

                getEncabezadoHtml() {
                    if (!this.encabezadoId) return '';
                    const item = this.encabezadosList.find(e => String(e.id) === String(this.encabezadoId));
                    return item ? item.contenido_html : '';
                },

                getPieHtml() {
                    if (!this.pieId) return '';
                    const item = this.piesList.find(p => String(p.id) === String(this.pieId));
                    return item ? item.contenido_html : '';
                },

                get selectedEncabezadoHtml() {
                    return this.getEncabezadoHtml();
                },

                get selectedPieHtml() {
                    return this.getPieHtml();
                },

                // Arquitectura Multi-Página A4 (Estilo Word)
                pages: [],
                activePageIndex: 0,

                selectedImage: null,
                isFreePosition: false,
                menuTop: '0px',
                menuLeft: '0px',
                resizeTop: '0px',
                resizeLeft: '0px',

                init() {
                    let rawHtml = {!! json_encode($contenidoGenerado ?? '') !!};
                    let chunks = rawHtml ? rawHtml.split(/<!-- PAGE_BREAK -->|<div class="page-break-line"[^>]*>.*?<\/div>/gi) : [];
                    if (!chunks || chunks.length === 0 || (chunks.length === 1 && !chunks[0].trim())) {
                        this.pages = [{ id: 1, content: rawHtml || '<p>Documento notarial...</p>' }];
                    } else {
                        this.pages = chunks.map((chunk, idx) => ({ id: idx + 1, content: chunk.trim() || '<p><br></p>' }));
                    }
                    this.syncExportHtml();
                },

                getActiveEditor() {
                    const page = this.pages[this.activePageIndex] || this.pages[0];
                    return page ? document.getElementById('generador-sheet-' + page.id) : null;
                },

                addPageAfter(pIndex) {
                    const newId = Date.now() + Math.floor(Math.random() * 1000);
                    this.pages.splice(pIndex + 1, 0, { id: newId, content: '<p><br></p>' });
                    this.activePageIndex = pIndex + 1;
                    this.$nextTick(() => {
                        const ed = document.getElementById('generador-sheet-' + newId);
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
                    this.syncExportHtml();
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
                    this.syncExportHtml();

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

                // FORMATO DE TEXTO Y HERRAMIENTAS COMPLETAS DE WORD
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

                // Transformación Inteligente de Mayúsculas / Minúsculas (Aa)
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
                            const sheet = document.getElementById('generador-sheet-' + p.id);
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
                    this.syncExportHtml();
                },

                descargarPdfServer() {
                    this.syncExportHtml();
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('plantillas.descargar_pdf', $plantilla->id_plantilla) }}";
                    form.target = '_blank';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    form.appendChild(csrf);

                    const htmlInput = document.createElement('input');
                    htmlInput.type = 'hidden';
                    htmlInput.name = 'contenido_html';
                    htmlInput.value = this.currentDocHtml;
                    form.appendChild(htmlInput);

                    if (this.encabezadoId) {
                        const encInput = document.createElement('input');
                        encInput.type = 'hidden';
                        encInput.name = 'encabezado_id';
                        encInput.value = this.encabezadoId;
                        form.appendChild(encInput);
                    }

                    if (this.pieId) {
                        const pieInput = document.createElement('input');
                        pieInput.type = 'hidden';
                        pieInput.name = 'pie_id';
                        pieInput.value = this.pieId;
                        form.appendChild(pieInput);
                    }

                    if (this.marcaAgua) {
                        const maInput = document.createElement('input');
                        maInput.type = 'hidden';
                        maInput.name = 'marca_agua';
                        maInput.value = this.marcaAgua;
                        form.appendChild(maInput);
                    }

                    @if($cliente)
                        const clienteInput = document.createElement('input');
                        clienteInput.type = 'hidden';
                        clienteInput.name = 'id_cliente';
                        clienteInput.value = "{{ $cliente->id_cliente }}";
                        form.appendChild(clienteInput);
                    @endif

                    document.body.appendChild(form);
                    form.submit();
                    form.remove();
                },

                descargarDocxServer() {
                    this.syncExportHtml();
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('plantillas.descargar_docx', $plantilla->id_plantilla) }}";
                    form.target = '_blank';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    form.appendChild(csrf);

                    const htmlInput = document.createElement('input');
                    htmlInput.type = 'hidden';
                    htmlInput.name = 'contenido_html';
                    htmlInput.value = this.currentDocHtml;
                    form.appendChild(htmlInput);

                    if (this.encabezadoId) {
                        const encInput = document.createElement('input');
                        encInput.type = 'hidden';
                        encInput.name = 'encabezado_id';
                        encInput.value = this.encabezadoId;
                        form.appendChild(encInput);
                    }

                    if (this.pieId) {
                        const pieInput = document.createElement('input');
                        pieInput.type = 'hidden';
                        pieInput.name = 'pie_id';
                        pieInput.value = this.pieId;
                        form.appendChild(pieInput);
                    }

                    @if($cliente)
                        const clienteInput = document.createElement('input');
                        clienteInput.type = 'hidden';
                        clienteInput.name = 'id_cliente';
                        clienteInput.value = "{{ $cliente->id_cliente }}";
                        form.appendChild(clienteInput);
                    @endif

                    document.body.appendChild(form);
                    form.submit();
                    form.remove();
                },

                changeClient(clienteId) {
                    let url = "{{ route('plantillas.generar', $plantilla->id_plantilla) }}";
                    if (clienteId) {
                        url += "?cliente=" + clienteId;
                    }
                    window.location.href = url;
                },

                replaceCustomVar(tag, value) {
                    const val = value.trim() ? value : tag;
                    const regex = new RegExp(tag.replace(/\[/g, '\\[').replace(/\]/g, '\\]'), 'g');
                    
                    this.pages.forEach(p => {
                        const sheet = document.getElementById('generador-sheet-' + p.id);
                        if (sheet) {
                            sheet.innerHTML = sheet.innerHTML.replace(regex, `<strong>${val}</strong>`);
                            p.content = sheet.innerHTML;
                        }
                    });
                    this.syncExportHtml();
                },

                syncExportHtml() {
                    this.deselectImage();
                    const cleanPages = this.pages.map((p) => {
                        const sheetEl = document.getElementById('generador-sheet-' + p.id);
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

                    this.currentDocHtml = cleanPages.join('\n<!-- PAGE_BREAK -->\n<div class="page-break-line" style="page-break-after: always; break-after: page;"></div>\n');
                },

                // Manejo de Clics en Imágenes
                handlePaperClick(event) {
                    if (event.target && event.target.tagName === 'IMG') {
                        this.selectImage(event.target);
                    } else if (!event.target.closest('.img-floating-toolbar') && !event.target.closest('.img-resize-handle')) {
                        this.deselectImage();
                    }
                },

                selectImage(imgEl) {
                    if (this.selectedImage && this.selectedImage !== imgEl) {
                        this.selectedImage.classList.remove('selected-img');
                    }
                    this.selectedImage = imgEl;
                    imgEl.classList.add('selected-img');
                    this.isFreePosition = imgEl.classList.contains('free-moving');
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

                printDocument() {
                    this.syncExportHtml();
                    window.print();
                },

                async guardarEnExpediente() {
                    this.syncExportHtml();
                    this.savingExpediente = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const res = await fetch("{{ route('plantillas.guardar_expediente', $plantilla->id_plantilla) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": token || "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                id_cliente: "{{ $cliente ? $cliente->id_cliente : '' }}",
                                contenido_html: this.currentDocHtml,
                                encabezado_id: this.encabezadoId,
                                pie_id: this.pieId,
                                marca_agua: this.marcaAgua
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            alert('✅ Documento guardado exitosamente en el expediente del cliente.');
                        } else {
                            alert(data.message || 'Error al guardar el documento.');
                        }
                    } catch (e) {
                        alert('Error al conectar con el servidor.');
                    } finally {
                        this.savingExpediente = false;
                    }
                },

                // Modal y Firma
                openSignatureModal() {
                    this.modalSignature = true;
                    this.$nextTick(() => {
                        const canvas = document.getElementById('genSignatureCanvas');
                        if (canvas) {
                            this.sigCanvas = canvas;
                            this.sigCtx = canvas.getContext('2d');
                            this.sigCtx.lineWidth = 2.5;
                            this.sigCtx.lineCap = 'round';
                            this.sigCtx.strokeStyle = '#004080';
                            
                            let drawing = false;
                            canvas.onmousedown = (e) => {
                                drawing = true;
                                this.sigCtx.beginPath();
                                const r = canvas.getBoundingClientRect();
                                this.sigCtx.moveTo(e.clientX - r.left, e.clientY - r.top);
                            };
                            canvas.onmousemove = (e) => {
                                if (!drawing) return;
                                const r = canvas.getBoundingClientRect();
                                this.sigCtx.lineTo(e.clientX - r.left, e.clientY - r.top);
                                this.sigCtx.stroke();
                            };
                            window.onmouseup = () => { drawing = false; };
                        }
                    });
                },

                clearSignature() {
                    if (this.sigCtx && this.sigCanvas) {
                        this.sigCtx.clearRect(0, 0, this.sigCanvas.width, this.sigCanvas.height);
                    }
                },

                async uploadImageToServer(fileOrBase64) {
                    const formData = new FormData();
                    if (fileOrBase64 instanceof File || fileOrBase64 instanceof Blob) {
                        formData.append('image', fileOrBase64);
                    } else if (typeof fileOrBase64 === 'string') {
                        formData.append('base64', fileOrBase64);
                    }
                    try {
                        const res = await fetch("{{ route('plantillas.upload_image') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: formData
                        });
                        const data = await res.json();
                        if (data.success && data.url) {
                            return data.url;
                        }
                    } catch (e) {
                        console.error('Error subiendo imagen:', e);
                    }
                    return typeof fileOrBase64 === 'string' ? fileOrBase64 : null;
                },

                async insertSignature() {
                    if (!this.sigCanvas) return;
                    const dataUrl = this.sigCanvas.toDataURL('image/png');
                    this.modalSignature = false;
                    const url = await this.uploadImageToServer(dataUrl);
                    if (!url) return;
                    
                    const imgHtml = `<img src="${url}" alt="Firma Digital" style="max-width: 160px; height: auto; display: inline-block; vertical-align: middle; margin: 5px 0;">`;
                    
                    let sel = window.getSelection();
                    const editor = this.getActiveEditor();
                    if (sel.getRangeAt && sel.rangeCount) {
                        let range = sel.getRangeAt(0);
                        range.deleteContents();
                        let el = document.createElement('div');
                        el.innerHTML = imgHtml;
                        range.insertNode(el.firstChild);
                    } else if (editor) {
                        editor.innerHTML += `<p style="text-align: center;">${imgHtml}</p>`;
                    }
                    this.syncExportHtml();
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
                        this.$nextTick(() => {
                            const selectEl = document.querySelector('select[x-model="encabezadoId"]');
                            if (selectEl) {
                                let opt = Array.from(selectEl.options).find(o => o.value == m.id);
                                if (!opt) {
                                    opt = document.createElement('option');
                                    opt.value = m.id;
                                    selectEl.appendChild(opt);
                                }
                                opt.textContent = m.nombre + (m.es_predeterminado ? ' ★' : '');
                                selectEl.value = m.id;
                            }
                        });
                    } else {
                        const idx = this.piesList.findIndex(p => p.id == m.id);
                        if (idx >= 0) {
                            this.piesList[idx] = m;
                        } else {
                            this.piesList.push(m);
                        }
                        this.pieId = m.id;
                        this.$nextTick(() => {
                            const selectEl = document.querySelector('select[x-model="pieId"]');
                            if (selectEl) {
                                let opt = Array.from(selectEl.options).find(o => o.value == m.id);
                                if (!opt) {
                                    opt = document.createElement('option');
                                    opt.value = m.id;
                                    selectEl.appendChild(opt);
                                }
                                opt.textContent = m.nombre + (m.es_predeterminado ? ' ★' : '');
                                selectEl.value = m.id;
                            }
                        });
                    }
                }
            };
        }
    </script>

    @include('plantillas.partials.drag_drop_designer_modal')
</x-app-layout>
