<!-- ========================================================================= -->
<!-- MODAL COMPLETO: ESTUDIO PROFESIONAL DRAG & DROP (TIPO CANVA / NOTARIAL)  -->
<!-- ========================================================================= -->
<div x-data="dragDropCanvasStudio()" 
     @open-canvas-designer.window="openDesigner($event.detail)"
     @keydown.escape.window="designerOpen = false">

    <template x-teleport="body">
        <div x-show="designerOpen" 
             x-cloak 
             class="fixed inset-0 flex items-center justify-center p-2 sm:p-4"
             style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100vw !important; height: 100vh !important; z-index: 9999999 !important; background-color: rgba(2, 6, 23, 0.95) !important; backdrop-filter: blur(14px) !important; margin: 0 !important; padding: 16px !important; box-sizing: border-box !important;">

            <div class="bg-slate-900 rounded-3xl w-full max-w-[1500px] h-[94vh] max-h-[940px] shadow-2xl flex flex-col overflow-hidden border border-slate-700 text-slate-100"
                 style="display: flex; flex-direction: column; width: 100%; max-width: 1500px; height: 94vh; max-height: 940px; background-color: #0f172a !important; border-radius: 1.5rem; overflow: hidden; box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.9); border: 1px solid #334155; position: relative;">
                
                <!-- ============================================================== -->
                <!-- 1. BARRA SUPERIOR DEL ESTUDIO: TÍTULO, PRESETS Y GUARDADO      -->
                <!-- ============================================================== -->
                <div class="px-6 py-3.5 bg-slate-900 border-b border-slate-800 flex items-center justify-between gap-4 shrink-0"
                     style="flex-shrink: 0; background-color: #0f172a; border-bottom: 1px solid #1e293b; padding: 14px 24px;">
                    
                    <!-- Identificador y Título -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-xl shadow-md shadow-amber-500/20 shrink-0">
                            <span x-text="designerTipo === 'encabezado' ? '🏛️' : '📄'"></span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm sm:text-base font-black text-white tracking-tight" 
                                    x-text="designerTipo === 'encabezado' ? 'Estudio Visual de Encabezado Notarial' : 'Estudio Visual de Pie de Página'"></h3>
                                <span class="text-[10px] bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                    100% Drag & Drop
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[11px] text-slate-400 font-semibold">Nombre:</span>
                                <input type="text" x-model="designerNombre" placeholder="Ej: Encabezado Oficial Notaría Ecuador..." 
                                       class="text-xs font-bold text-amber-300 bg-slate-800/90 border border-slate-700 rounded-lg px-2.5 py-1 focus:outline-none focus:border-amber-500 w-64 sm:w-80">
                            </div>
                        </div>
                    </div>

                    <!-- Botones Rápidos de Plantillas Pre-diseñadas (1 Clic) -->
                    <div class="hidden lg:flex items-center gap-1.5 bg-slate-800/90 p-1.5 rounded-xl border border-slate-700/60">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-2">Plantillas Rápidas:</span>
                
                <template x-if="designerTipo === 'encabezado'">
                    <div class="flex items-center gap-1">
                        <button type="button" @click="loadDefaultCanvasItems('encabezado')" class="px-2.5 py-1 bg-slate-700 hover:bg-slate-600 text-amber-300 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Cargar diseño oficial con escudo, notario y balanza">
                            <span>🌟</span> Oficial Notaría
                        </button>
                        <button type="button" @click="loadPresetCanvas('encabezado_centrado')" class="px-2.5 py-1 bg-slate-700/60 hover:bg-slate-600 text-slate-200 rounded-lg text-xs font-semibold transition" title="Escudo al centro con divisor">
                            ⚖️ Centrado
                        </button>
                        <button type="button" @click="loadPresetCanvas('encabezado_minimalista')" class="px-2.5 py-1 bg-slate-700/60 hover:bg-slate-600 text-slate-200 rounded-lg text-xs font-semibold transition" title="Diseño moderno y limpio">
                            📄 Minimalista
                        </button>
                    </div>
                </template>

                <template x-if="designerTipo === 'pie'">
                    <div class="flex items-center gap-1">
                        <button type="button" @click="loadDefaultCanvasItems('pie')" class="px-2.5 py-1 bg-slate-700 hover:bg-slate-600 text-indigo-300 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Cargar 4 sedes con QR y teléfonos">
                            <span>🌟</span> 4 Sedes + QR
                        </button>
                        <button type="button" @click="loadPresetCanvas('pie_simple')" class="px-2.5 py-1 bg-slate-700/60 hover:bg-slate-600 text-slate-200 rounded-lg text-xs font-semibold transition" title="Sede Principal + Contacto y QR">
                            🏢 2 Sedes + QR
                        </button>
                    </div>
                </template>
            </div>

            <!-- Botones de Acción (Guardar / Cancelar) -->
            <div class="flex items-center gap-2">
                <button type="button" @click="clearCanvas()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl text-xs transition border border-slate-700/60 flex items-center gap-1.5" title="Limpiar lienzo">
                    <span>🔄</span>
                    <span class="hidden sm:inline">Limpiar</span>
                </button>
                <button type="button" @click="designerOpen = false" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl text-xs transition border border-slate-700/60">
                    ✕ Cancelar
                </button>
                <button type="button" @click="saveCanvasToMembrete()" :disabled="savingCanvas" class="px-5 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black rounded-xl text-xs shadow-lg shadow-emerald-500/25 transition disabled:opacity-50 flex items-center gap-2 transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span x-text="savingCanvas ? 'Guardando...' : '💾 Aplicar y Guardar'"></span>
                </button>
            </div>

        </div>

        <!-- ============================================================== -->
        <!-- 2. CUERPO PRINCIPAL: BIBLIOTECA IZQUIERDA + LIENZO DERECHA     -->
        <!-- ============================================================== -->
        <div class="flex-1 flex flex-row overflow-hidden min-h-0 bg-slate-950"
             style="flex: 1; display: flex; flex-direction: row; overflow: hidden; min-height: 0; background-color: #020617;">
            
            <!-- ============================================================== -->
            <!-- PANEL IZQUIERDO: BIBLIOTECA DE ELEMENTOS (ANCHO FIJO w-80)     -->
            <!-- ============================================================== -->
            <div class="w-72 sm:w-80 shrink-0 bg-slate-900 border-r border-slate-800/80 p-4 overflow-y-auto space-y-5 select-none custom-scrollbar"
                 style="width: 320px; flex-shrink: 0; background-color: #0f172a; border-right: 1px solid #1e293b; padding: 16px; overflow-y: auto;">
                
                <!-- 1. Subir Imagen / Logo desde PC -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400">1. Subir Imagen o Logo</label>
                    <button type="button" @click="$refs.canvasImageUpload.click()" class="w-full py-2.5 px-3 bg-indigo-600/15 hover:bg-indigo-600/25 border border-indigo-500/40 hover:border-indigo-500 rounded-xl text-indigo-300 font-bold text-xs flex items-center justify-center gap-2 transition shadow-xs">
                        <span class="text-base">📁</span>
                        <span>Subir Imagen desde mi PC</span>
                    </button>
                    <input type="file" x-ref="canvasImageUpload" @change="uploadCanvasImage($event)" accept="image/*" class="hidden">
                </div>

                <!-- 2. Escudos y Logos Oficiales Notariales -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400">2. Logos Notariales Oficiales</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="addCanvasImage('/img/escudo_ecuador.jpg', 65, 65)" class="p-2.5 bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/80 hover:border-amber-500/50 rounded-xl flex flex-col items-center justify-center gap-1.5 transition text-center group">
                            <img src="/img/escudo_ecuador.jpg" class="h-10 object-contain rounded drop-shadow">
                            <span class="text-[10px] font-bold text-slate-300 group-hover:text-amber-300">Escudo Ecuador</span>
                        </button>
                        <button type="button" @click="addCanvasImage('/img/qr_notaria.jpg', 55, 55)" class="p-2.5 bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/80 hover:border-indigo-500/50 rounded-xl flex flex-col items-center justify-center gap-1.5 transition text-center group">
                            <img src="/img/qr_notaria.jpg" class="h-10 object-contain rounded drop-shadow">
                            <span class="text-[10px] font-bold text-slate-300 group-hover:text-indigo-300">Código QR</span>
                        </button>
                    </div>
                </div>

                <!-- 3. Textos Notariales (Haz Clic para Insertar y Escribir) -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400">3. Textos (Haz clic para agregar)</label>
                    
                    <button type="button" @click="addCanvasText('NOTARÍA ECUADOR', { fontSize: '18px', fontWeight: '900', letterSpacing: '1.5px', color: '#0f172a' })" class="w-full p-2.5 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl flex items-center gap-3 transition text-left group">
                        <span class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 font-black text-xs flex items-center justify-center shrink-0 border border-amber-500/30">H1</span>
                        <div>
                            <div class="font-bold text-xs text-white group-hover:text-amber-300">Título Principal</div>
                            <div class="text-[10px] text-slate-400">NOTARÍA ECUADOR</div>
                        </div>
                    </button>

                    <button type="button" @click="addCanvasText('Christian Moreno', { fontSize: '13px', fontWeight: '800', color: '#1e293b' })" class="w-full p-2.5 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl flex items-center gap-3 transition text-left group">
                        <span class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 font-bold text-xs flex items-center justify-center shrink-0 border border-indigo-500/30">H2</span>
                        <div>
                            <div class="font-bold text-xs text-white group-hover:text-indigo-300">Nombre del Notario</div>
                            <div class="text-[10px] text-slate-400">Christian Moreno</div>
                        </div>
                    </button>

                    <button type="button" @click="addCanvasText('New York – Notary Public', { fontSize: '11px', fontWeight: '600', color: '#475569' })" class="w-full p-2.5 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl flex items-center gap-3 transition text-left group">
                        <span class="w-8 h-8 rounded-lg bg-slate-700/60 text-slate-300 font-semibold text-xs flex items-center justify-center shrink-0 border border-slate-600">P</span>
                        <div>
                            <div class="font-bold text-xs text-white group-hover:text-slate-200">Jurisdicción / Cargo</div>
                            <div class="text-[10px] text-slate-400">New York – Notary Public</div>
                        </div>
                    </button>

                    <button type="button" @click="addCanvasText('WhatsApp: (212) 810-7721 | Tel: (718) 864-7908\nwww.NotariaEcuador.com', { fontSize: '9.5px', color: '#1e293b', fontWeight: 'bold' })" class="w-full p-2.5 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl flex items-center gap-3 transition text-left group">
                        <span class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 font-bold text-xs flex items-center justify-center shrink-0 border border-emerald-500/30">📞</span>
                        <div>
                            <div class="font-bold text-xs text-white group-hover:text-emerald-300">Teléfonos y Web</div>
                            <div class="text-[10px] text-slate-400">WhatsApp, Teléfono, Sitio</div>
                        </div>
                    </button>

                    <button type="button" @click="addCanvasText('Brooklyn – N.Y.\n190 Wyckoff Ave.\nBrooklyn, N.Y. 11237', { fontSize: '9.5px', color: '#334155', textAlign: 'center' })" class="w-full p-2.5 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl flex items-center gap-3 transition text-left group">
                        <span class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 font-bold text-xs flex items-center justify-center shrink-0 border border-blue-500/30">🏢</span>
                        <div>
                            <div class="font-bold text-xs text-white group-hover:text-blue-300">Sede / Sucursal</div>
                            <div class="text-[10px] text-slate-400">Dirección y Ciudad</div>
                        </div>
                    </button>
                </div>

                <!-- 4. Divisores y Separadores -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-black uppercase tracking-wider text-slate-400">4. Divisores y Separadores</label>
                    
                    <button type="button" @click="addCanvasImage('/img/divisor_balanza.jpg', 780, 24)" class="w-full p-2.5 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl flex items-center gap-3 transition text-left group">
                        <span class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 font-black text-xs flex items-center justify-center shrink-0 border border-amber-500/30">⚖️</span>
                        <div>
                            <div class="font-bold text-xs text-white group-hover:text-amber-300">Línea Dorada con Balanza</div>
                            <div class="text-[10px] text-slate-400">Divisor oficial centrado</div>
                        </div>
                    </button>

                    <button type="button" @click="addCanvasLine('#0f172a', '1.5px', 'solid')" class="w-full p-2.5 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl flex items-center gap-3 transition text-left group">
                        <span class="w-8 h-8 rounded-lg bg-slate-700/60 text-slate-200 font-black text-sm flex items-center justify-center shrink-0 border border-slate-600">⎯</span>
                        <div>
                            <div class="font-bold text-xs text-white group-hover:text-slate-200">Línea Divisoria Sólida</div>
                            <div class="text-[10px] text-slate-400">Ancho completo 1.5px</div>
                        </div>
                    </button>

                    <button type="button" @click="addCanvasLine('#cbd5e1', '1px', 'dashed')" class="w-full p-2.5 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl flex items-center gap-3 transition text-left group">
                        <span class="w-8 h-8 rounded-lg bg-slate-700/60 text-slate-300 font-black text-sm flex items-center justify-center shrink-0 border border-slate-600">╌</span>
                        <div>
                            <div class="font-bold text-xs text-white group-hover:text-slate-200">Línea Punteada para Pie</div>
                            <div class="text-[10px] text-slate-400">Separador sutil</div>
                        </div>
                    </button>

                    <button type="button" @click="addCanvasText('|', { fontSize: '32px', color: '#cbd5e1', fontWeight: '300' })" class="w-full p-2.5 bg-slate-800/70 hover:bg-slate-700/70 border border-slate-700/60 rounded-xl flex items-center gap-3 transition text-left group">
                        <span class="w-8 h-8 rounded-lg bg-slate-700/60 text-slate-300 font-light text-base flex items-center justify-center shrink-0 border border-slate-600">|</span>
                        <div>
                            <div class="font-bold text-xs text-white group-hover:text-slate-200">Separador Vertical</div>
                            <div class="text-[10px] text-slate-400">Barra fina entre escudo y texto</div>
                        </div>
                    </button>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- ÁREA CENTRAL: ESCENARIO DEL LIENZO (WORKSPACE CON SCROLL)      -->
            <!-- ============================================================== -->
            <div class="flex-1 bg-slate-950 flex flex-col overflow-hidden min-w-0" 
                 style="flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; background-color: #020617;"
                 @click="deselectCanvasAll($event)">
                
                <!-- BARRA DE HERRAMIENTAS FLOTANTE SUPERIOR -->
                <div class="w-full bg-slate-900/90 border-b border-slate-800 px-6 py-2.5 flex items-center justify-between gap-3 shrink-0 min-h-[52px]"
                     style="flex-shrink: 0; min-height: 52px; background-color: #0f172a; border-bottom: 1px solid #1e293b; padding: 10px 24px;">
                    
                    <template x-if="selectedCanvasItem">
                        <div class="flex items-center gap-3">
                            
                            <!-- CONTROLES PARA TEXTO -->
                            <template x-if="selectedCanvasItem.type === 'text'">
                                <div class="flex items-center gap-2 border-r border-slate-700 pr-3">
                                    <span class="text-[11px] font-bold text-slate-400">Texto:</span>
                                    <button type="button" @click="changeCanvasFontSize(-1)" class="w-7 h-7 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-black transition border border-slate-700 flex items-center justify-center" title="Reducir letra">A-</button>
                                    <span class="text-xs font-bold text-amber-300 min-w-[32px] text-center" x-text="selectedCanvasItem.style.fontSize"></span>
                                    <button type="button" @click="changeCanvasFontSize(1)" class="w-7 h-7 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-black transition border border-slate-700 flex items-center justify-center" title="Aumentar letra">A+</button>
                                    
                                    <span class="w-px h-4 bg-slate-700 mx-1"></span>

                                    <!-- Negrita, Cursiva, Mayúsculas -->
                                    <button type="button" @click="toggleCanvasBold()" class="w-7 h-7 rounded-lg text-xs font-bold transition flex items-center justify-center border" :class="selectedCanvasItem.style.fontWeight === 'bold' || selectedCanvasItem.style.fontWeight >= 700 ? 'bg-indigo-600 text-white border-indigo-500' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'"><strong>B</strong></button>
                                    <button type="button" @click="toggleCanvasItalic()" class="w-7 h-7 rounded-lg text-xs font-bold transition flex items-center justify-center border" :class="selectedCanvasItem.style.fontStyle === 'italic' ? 'bg-indigo-600 text-white border-indigo-500' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'"><em>I</em></button>
                                    <button type="button" @click="toggleCanvasUppercase()" class="px-2 h-7 rounded-lg text-[10px] font-black uppercase transition flex items-center justify-center border" :class="selectedCanvasItem.style.textTransform === 'uppercase' ? 'bg-indigo-600 text-white border-indigo-500' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'">MAYÚS</button>
                                    
                                    <span class="w-px h-4 bg-slate-700 mx-1"></span>

                                    <!-- Alineación -->
                                    <button type="button" @click="setCanvasTextAlign('left')" class="w-7 h-7 rounded-lg flex items-center justify-center border transition" :class="selectedCanvasItem.style.textAlign === 'left' ? 'bg-indigo-600 text-white border-indigo-500' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'" title="Izquierda">⬅️</button>
                                    <button type="button" @click="setCanvasTextAlign('center')" class="w-7 h-7 rounded-lg flex items-center justify-center border transition" :class="selectedCanvasItem.style.textAlign === 'center' ? 'bg-indigo-600 text-white border-indigo-500' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'" title="Centrado">🎯</button>
                                    <button type="button" @click="setCanvasTextAlign('right')" class="w-7 h-7 rounded-lg flex items-center justify-center border transition" :class="selectedCanvasItem.style.textAlign === 'right' ? 'bg-indigo-600 text-white border-indigo-500' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'" title="Derecha">➡️</button>

                                    <span class="w-px h-4 bg-slate-700 mx-1"></span>

                                    <!-- Paleta de Color Notarial -->
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" @click="setCanvasColor('#0f172a')" class="w-5 h-5 rounded-full bg-slate-900 border-2 border-slate-400" title="Negro"></button>
                                        <button type="button" @click="setCanvasColor('#1e3a8a')" class="w-5 h-5 rounded-full bg-blue-900 border-2 border-blue-400" title="Azul Notarial"></button>
                                        <button type="button" @click="setCanvasColor('#b45309')" class="w-5 h-5 rounded-full bg-amber-700 border-2 border-amber-400" title="Dorado"></button>
                                        <button type="button" @click="setCanvasColor('#475569')" class="w-5 h-5 rounded-full bg-slate-600 border-2 border-slate-400" title="Gris"></button>
                                    </div>
                                </div>
                            </template>

                            <!-- CONTROLES PARA IMAGEN -->
                            <template x-if="selectedCanvasItem.type === 'image'">
                                <div class="flex items-center gap-2 border-r border-slate-700 pr-3">
                                    <span class="text-[11px] font-bold text-slate-400">Tamaño:</span>
                                    <button type="button" @click="resizeCanvasImageTo(45)" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold">45px</button>
                                    <button type="button" @click="resizeCanvasImageTo(65)" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold">65px</button>
                                    <button type="button" @click="resizeCanvasImageTo(120)" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold">120px</button>
                                    <button type="button" @click="resizeCanvasImageTo(780)" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-amber-300 border border-slate-700 rounded-lg text-xs font-bold">Ancho Completo</button>
                                </div>
                            </template>

                            <!-- Acciones de Elemento: Duplicar / Eliminar -->
                            <button type="button" @click="duplicateCanvasItem()" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-bold flex items-center gap-1.5 transition">
                                <span>📋</span> Duplicar
                            </button>
                            <button type="button" @click="deleteSelectedCanvasItem()" class="px-2.5 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/40 rounded-xl text-xs font-bold flex items-center gap-1.5 transition">
                                <span>🗑️</span> Eliminar
                            </button>

                        </div>
                    </template>

                    <template x-if="!selectedCanvasItem">
                        <div class="text-xs text-slate-400 font-medium flex items-center gap-2">
                            <span>👆 <strong>Haz clic</strong> en cualquier logo o texto sobre la hoja para moverlo a donde quieras o cambiar su tamaño.</span>
                        </div>
                    </template>

                    <div class="text-[11px] font-mono text-slate-500 hidden sm:block">
                        Lienzo: <strong class="text-slate-300">820px</strong> (Escala Exacta A4)
                    </div>

                </div>

                <!-- ESCENARIO CON FONDO RETICULADO Y HOJA DE TRABAJO CENTRADA -->
                <div class="flex-1 overflow-auto p-6 sm:p-10 flex flex-col items-center justify-center bg-[radial-gradient(#334155_1.2px,transparent_1.2px)] [background-size:20px_20px] custom-scrollbar"
                     style="flex: 1; overflow: auto; padding: 30px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    
                    <!-- MARCO DE LA HOJA NOTARIAL BLANCA (NUNCA SE APLASTA CON min-w-[820px] Y flex-shrink: 0) -->
                    <div class="relative select-none shadow-2xl rounded-2xl bg-white border-2 border-slate-400/90"
                         style="width: 820px; min-width: 820px; max-width: 820px; flex-shrink: 0; position: relative; background-color: #ffffff !important; margin: 0 auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.7); border-radius: 12px; border: 2px solid #94a3b8; box-sizing: border-box; overflow: hidden;"
                         :style="'width: 820px; min-width: 820px; max-width: 820px; height: ' + (designerTipo === 'encabezado' ? '135px' : '115px') + '; min-height: ' + (designerTipo === 'encabezado' ? '135px' : '115px') + '; background-color: #ffffff !important;'"
                         x-ref="modalCanvasBox"
                         @mousedown="onCanvasBackgroundMouseDown($event)">
                        
                        <!-- RENDERIZADO DE ELEMENTOS DEL LIENZO -->
                        <template x-for="(item, index) in canvasItems" :key="item.id">
                            <div class="canvas-item" 
                                 :class="{ 'selected': selectedCanvasItem && selectedCanvasItem.id === item.id }"
                                 :style="getCanvasItemStyle(item)"
                                 @mousedown.stop="startDragCanvasItem($event, item)">
                                
                                <!-- CASO 1: TEXTO EDITABLE DIRECTAMENTE -->
                                <template x-if="item.type === 'text'">
                                    <div class="canvas-item-text" 
                                         contenteditable="true"
                                         @input="item.text = $event.target.innerText"
                                         @blur="item.text = $event.target.innerText"
                                         x-text="item.text"
                                         :style="getCanvasTextStyle(item)"></div>
                                </template>

                                <!-- CASO 2: IMAGEN / LOGO / ESCUDO / QR -->
                                <template x-if="item.type === 'image'">
                                    <img :src="item.src" 
                                         :style="'width: ' + item.width + (typeof item.width === 'number' ? 'px' : '') + '; height: ' + (item.height ? item.height + 'px' : 'auto') + '; display: block; object-fit: contain; pointer-events: none; border: none; box-shadow: none;'" 
                                         alt="Elemento Visual">
                                </template>

                                <!-- CASO 3: LÍNEA DIVISORIA -->
                                <template x-if="item.type === 'line'">
                                    <div :style="'width: ' + item.width + 'px; height: 0px; border-top: ' + item.thickness + ' ' + (item.lineStyle || 'solid') + ' ' + item.color + '; pointer-events: none;'"></div>
                                </template>

                                <!-- TIRADOR DE REDIMENSIONAMIENTO (ESQUINA INFERIOR DERECHA) -->
                                <template x-if="selectedCanvasItem && selectedCanvasItem.id === item.id">
                                    <div class="resize-handle se" @mousedown.stop="startResizeCanvasItem($event, item)"></div>
                                </template>

                            </div>
                        </template>

                        <!-- MARCA DE AGUA INFORMATIVA EN ESQUINA -->
                        <div class="absolute bottom-1.5 right-3 text-[9px] font-mono text-slate-400 uppercase tracking-widest pointer-events-none select-none"
                             style="position: absolute; bottom: 6px; right: 12px; font-size: 9px; font-family: monospace; color: #94a3b8; letter-spacing: 0.1em; pointer-events: none; user-select: none;">
                            <span x-text="designerTipo === 'encabezado' ? '▲ ENCABEZADO SUPERIOR' : '▼ PIE DE PÁGINA INFERIOR'"></span> &bull; 820px
                        </div>

                    </div>

                    <!-- TIPS DE AYUDA RÁPIDA -->
                    <div class="mt-4 text-xs text-slate-400 flex items-center gap-2 bg-slate-900/80 px-4 py-2 rounded-xl border border-slate-800"
                         style="margin-top: 16px; font-size: 12px; color: #94a3b8; background-color: rgba(15, 23, 42, 0.8); padding: 8px 16px; border-radius: 12px; border: 1px solid #1e293b;">
                        <span>💡 <strong>Tip:</strong> Puedes arrastrar cualquier logo o texto a la posición deseada. Haz <strong>doble clic sobre el texto</strong> para escribir o cambiar nombres.</span>
                    </div>

                </div>

            </div>

        </div>

        </div>
    </template>
</div>

<!-- ========================================================================= -->
<!-- ESTILOS Y COMPONENTE ALPINE EXCLUSIVO PARA EL DRAG & DROP STUDIO          -->
<!-- ========================================================================= -->
<style>
    .canvas-item {
        position: absolute;
        cursor: move;
        transition: outline 0.1s;
        touch-action: none;
        z-index: 10;
        user-select: none;
    }
    .canvas-item:hover {
        outline: 1.5px dashed #6366f1;
    }
    .canvas-item.selected {
        outline: 2px solid #4f46e5 !important;
        z-index: 50 !important;
    }

    .canvas-item-text {
        outline: none;
        cursor: text;
        user-select: text;
        min-width: 30px;
        min-height: 18px;
        white-space: pre-wrap;
    }

    .resize-handle {
        position: absolute;
        width: 12px;
        height: 12px;
        background-color: #4f46e5;
        border: 2px solid #ffffff;
        border-radius: 50%;
        z-index: 60;
        box-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }
    .resize-handle.se { right: -6px; bottom: -6px; cursor: se-resize; }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.6);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(100, 116, 139, 0.5);
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.8);
    }
</style>

<script>
    function dragDropCanvasStudio() {
        return {
            designerOpen: false,
            designerTipo: 'encabezado',
            designerNombre: '',
            designerId: null,
            savingCanvas: false,

            canvasItems: [],
            selectedCanvasItem: null,

            isDraggingCanvas: false,
            dragStartX: 0,
            dragStartY: 0,
            itemStartLeft: 0,
            itemStartTop: 0,

            isResizingCanvas: false,
            resizeStartX: 0,
            itemStartWidth: 0,
            itemStartHeight: 0,

            onSaveCallback: null,

            init() {
                // Escuchador global para mover elementos por el lienzo
                window.addEventListener('mousemove', (e) => {
                    if (this.isDraggingCanvas && this.selectedCanvasItem) {
                        const dx = e.clientX - this.dragStartX;
                        const dy = e.clientY - this.dragStartY;
                        
                        let newX = Math.max(0, Math.min(800, this.itemStartLeft + dx));
                        const maxH = this.designerTipo === 'encabezado' ? 145 : 105;
                        let newY = Math.max(0, Math.min(maxH, this.itemStartTop + dy));

                        this.selectedCanvasItem.x = Math.round(newX);
                        this.selectedCanvasItem.y = Math.round(newY);
                    }

                    if (this.isResizingCanvas && this.selectedCanvasItem) {
                        const dx = e.clientX - this.resizeStartX;
                        let newWidth = Math.max(20, Math.min(800, this.itemStartWidth + dx));
                        this.selectedCanvasItem.width = Math.round(newWidth);
                        
                        if (this.itemStartHeight && this.itemStartWidth) {
                            this.selectedCanvasItem.height = Math.round(newWidth * (this.itemStartHeight / this.itemStartWidth));
                        }
                    }
                });

                // Escuchador global para soltar el ratón
                window.addEventListener('mouseup', () => {
                    this.isDraggingCanvas = false;
                    this.isResizingCanvas = false;
                });
            },

            openDesigner(options = {}) {
                const tipo = options.tipo || 'encabezado';
                this.designerTipo = tipo;
                this.selectedCanvasItem = null;
                this.onSaveCallback = options.onSave || null;

                if (options.id || options.existingItem) {
                    const item = options.existingItem || options;
                    this.designerId = item.id || null;
                    this.designerNombre = item.nombre || '';
                    
                    let datos = item.datos_json;
                    if (typeof datos === 'string') {
                        try { datos = JSON.parse(datos); } catch(e) { datos = null; }
                    }
                    if (datos && datos.items && Array.isArray(datos.items) && datos.items.length > 0) {
                        this.canvasItems = JSON.parse(JSON.stringify(datos.items));
                    } else {
                        this.loadDefaultCanvasItems(tipo);
                    }
                } else {
                    this.designerId = null;
                    this.designerNombre = tipo === 'encabezado' ? 'Nuevo Encabezado Notarial' : 'Nuevo Pie de Página Notarial';
                    this.loadDefaultCanvasItems(tipo);
                }

                this.designerOpen = true;
            },

            loadDefaultCanvasItems(tipo) {
                if (tipo === 'encabezado') {
                    this.canvasItems = [
                        { id: 1, type: 'image', src: '/img/escudo_ecuador.jpg', x: 25, y: 12, width: 60, height: 60 },
                        { id: 2, type: 'text', text: '|', x: 100, y: 16, style: { fontSize: '32px', color: '#cbd5e1', fontWeight: '300' } },
                        { id: 3, type: 'text', text: 'NOTARÍA ECUADOR\nChristian Moreno\nNew York – Notary Public', x: 118, y: 12, style: { fontSize: '13px', fontWeight: 'bold', color: '#0f172a', lineHeight: '1.25' } },
                        { id: 4, type: 'image', src: '/img/divisor_balanza.jpg', x: 20, y: 92, width: 780, height: 22 }
                    ];
                } else {
                    this.canvasItems = [
                        { id: 1, type: 'line', x: 20, y: 8, width: 780, thickness: '1px', color: '#cbd5e1', lineStyle: 'dashed' },
                        { id: 2, type: 'text', text: 'Brooklyn – N.Y.\n190 Wyckoff Ave.\nBrooklyn, N.Y. 11237', x: 25, y: 20, style: { fontSize: '9.5px', color: '#334155', textAlign: 'center', lineHeight: '1.25' } },
                        { id: 3, type: 'text', text: 'Spring Valley – N.Y.\n144 E. Central Ave.\nSpring Valley, N.Y. 10977', x: 210, y: 20, style: { fontSize: '9.5px', color: '#334155', textAlign: 'center', lineHeight: '1.25' } },
                        { id: 4, type: 'text', text: 'Cuenca – Ecuador\nAv. Remigio Crespo Toral y\nAv. Ricardo Durán', x: 400, y: 20, style: { fontSize: '9.5px', color: '#334155', textAlign: 'center', lineHeight: '1.25' } },
                        { id: 5, type: 'text', text: 'WhatsApp: (212) 810-7721\nTel: (718) 864-7908\nwww.NotariaEcuador.com', x: 575, y: 20, style: { fontSize: '9px', color: '#1e293b', fontWeight: 'bold', lineHeight: '1.3' } },
                        { id: 6, type: 'image', src: '/img/qr_notaria.jpg', x: 745, y: 20, width: 55, height: 55 }
                    ];
                }
            },

            loadPresetCanvas(preset) {
                if (preset === 'encabezado_oficial' || preset === 'encabezado') {
                    this.loadDefaultCanvasItems('encabezado');
                } else if (preset === 'encabezado_centrado') {
                    this.canvasItems = [
                        { id: 1, type: 'image', src: '/img/escudo_ecuador.jpg', x: 380, y: 10, width: 60, height: 60 },
                        { id: 2, type: 'text', text: 'NOTARÍA ECUADOR', x: 310, y: 75, style: { fontSize: '16px', fontWeight: '900', letterSpacing: '2px', color: '#0f172a', textAlign: 'center' } },
                        { id: 3, type: 'text', text: 'Christian Moreno • Notario Público • State of New York', x: 220, y: 98, style: { fontSize: '11px', fontWeight: '600', color: '#475569', textAlign: 'center' } },
                        { id: 4, type: 'image', src: '/img/divisor_balanza.jpg', x: 20, y: 125, width: 780, height: 24 }
                    ];
                } else if (preset === 'encabezado_minimalista') {
                    this.canvasItems = [
                        { id: 1, type: 'text', text: 'NOTARÍA ECUADOR', x: 30, y: 25, style: { fontSize: '20px', fontWeight: '900', letterSpacing: '2px', color: '#1e3a8a' } },
                        { id: 2, type: 'text', text: 'Abg. Christian Moreno\nNotario Público del Estado de New York', x: 30, y: 55, style: { fontSize: '11px', fontWeight: '600', color: '#475569', lineHeight: '1.3' } },
                        { id: 3, type: 'image', src: '/img/escudo_ecuador.jpg', x: 725, y: 20, width: 65, height: 65 },
                        { id: 4, type: 'line', x: 30, y: 110, width: 760, thickness: '2px', color: '#1e3a8a', lineStyle: 'solid' }
                    ];
                } else if (preset === 'pie_4sedes' || preset === 'pie') {
                    this.loadDefaultCanvasItems('pie');
                } else if (preset === 'pie_simple') {
                    this.canvasItems = [
                        { id: 1, type: 'line', x: 20, y: 8, width: 780, thickness: '1.5px', color: '#0f172a', lineStyle: 'solid' },
                        { id: 2, type: 'text', text: 'NOTARÍA ECUADOR • SERVICIOS NOTARIALES Y CONSULARES\nSede Principal: 190 Wyckoff Ave., Brooklyn, N.Y. 11237 | Tel: (718) 864-7908\nwww.NotariaEcuador.com | WhatsApp: (212) 810-7721', x: 30, y: 25, style: { fontSize: '10px', color: '#334155', lineHeight: '1.4' } },
                        { id: 3, type: 'image', src: '/img/qr_notaria.jpg', x: 745, y: 20, width: 55, height: 55 }
                    ];
                }
            },

            clearCanvas() {
                if (confirm('¿Deseas limpiar todos los elementos del lienzo?')) {
                    this.canvasItems = [];
                    this.selectedCanvasItem = null;
                }
            },

            addCanvasImage(src, width = 60, height = null) {
                const newItem = {
                    id: Date.now() + Math.random(),
                    type: 'image',
                    src: src,
                    x: 50 + (this.canvasItems.length * 15),
                    y: 30,
                    width: width,
                    height: height
                };
                this.canvasItems.push(newItem);
                this.selectedCanvasItem = newItem;
            },

            addCanvasText(text, style = {}) {
                const newItem = {
                    id: Date.now() + Math.random(),
                    type: 'text',
                    text: text,
                    x: 100 + (this.canvasItems.length * 10),
                    y: 30,
                    style: Object.assign({
                        fontSize: '12px',
                        fontWeight: 'normal',
                        fontStyle: 'normal',
                        color: '#1e293b',
                        textAlign: 'left',
                        textTransform: 'none',
                        lineHeight: '1.3'
                    }, style)
                };
                this.canvasItems.push(newItem);
                this.selectedCanvasItem = newItem;
            },

            addCanvasLine(color = '#000000', thickness = '1px', lineStyle = 'solid') {
                const newItem = {
                    id: Date.now() + Math.random(),
                    type: 'line',
                    x: 20,
                    y: 50,
                    width: 780,
                    thickness: thickness,
                    color: color,
                    lineStyle: lineStyle
                };
                this.canvasItems.push(newItem);
                this.selectedCanvasItem = newItem;
            },

            async uploadCanvasImage(event) {
                const file = event.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('image', file);

                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const res = await fetch("{{ route('plantillas-membretes.upload_image') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf },
                        body: formData
                    });
                    const data = await res.json();
                    if (data.success && data.url) {
                        this.addCanvasImage(data.url, 80, null);
                    } else {
                        alert(data.error || 'No se pudo subir la imagen.');
                    }
                } catch (e) {
                    alert('Error al conectar con el servidor.');
                }
            },

            getCanvasItemStyle(item) {
                return `position: absolute; left: ${item.x}px; top: ${item.y}px; z-index: ${this.selectedCanvasItem && this.selectedCanvasItem.id === item.id ? 50 : 10};`;
            },

            getCanvasTextStyle(item) {
                const s = item.style || {};
                return `
                    font-size: ${s.fontSize || '12px'};
                    font-weight: ${s.fontWeight || 'normal'};
                    font-style: ${s.fontStyle || 'normal'};
                    color: ${s.color || '#1e293b'};
                    text-align: ${s.textAlign || 'left'};
                    text-transform: ${s.textTransform || 'none'};
                    letter-spacing: ${s.letterSpacing || 'normal'};
                    line-height: ${s.lineHeight || '1.3'};
                    white-space: pre-wrap;
                    font-family: Arial, Helvetica, sans-serif;
                `;
            },

            onCanvasBackgroundMouseDown(event) {
                if (event.target === this.$refs.modalCanvasBox) {
                    this.selectedCanvasItem = null;
                }
            },

            deselectCanvasAll(event) {
                if (event.target === this.$refs.modalCanvasBox) {
                    this.selectedCanvasItem = null;
                }
            },

            startDragCanvasItem(event, item) {
                this.selectedCanvasItem = item;
                this.isDraggingCanvas = true;
                this.dragStartX = event.clientX;
                this.dragStartY = event.clientY;
                this.itemStartLeft = item.x;
                this.itemStartTop = item.y;
            },

            startResizeCanvasItem(event, item) {
                this.selectedCanvasItem = item;
                this.isResizingCanvas = true;
                this.resizeStartX = event.clientX;
                this.itemStartWidth = typeof item.width === 'number' ? item.width : 60;
                this.itemStartHeight = item.height || null;
            },

            deleteSelectedCanvasItem() {
                if (!this.selectedCanvasItem) return;
                this.canvasItems = this.canvasItems.filter(i => i.id !== this.selectedCanvasItem.id);
                this.selectedCanvasItem = null;
            },

            duplicateCanvasItem() {
                if (!this.selectedCanvasItem) return;
                const clone = JSON.parse(JSON.stringify(this.selectedCanvasItem));
                clone.id = Date.now() + Math.random();
                clone.x = Math.min(760, clone.x + 20);
                clone.y = Math.min(120, clone.y + 15);
                this.canvasItems.push(clone);
                this.selectedCanvasItem = clone;
            },

            changeCanvasFontSize(delta) {
                if (!this.selectedCanvasItem || this.selectedCanvasItem.type !== 'text') return;
                let current = parseInt(this.selectedCanvasItem.style.fontSize) || 12;
                let next = Math.max(8, Math.min(48, current + delta));
                this.selectedCanvasItem.style.fontSize = next + 'px';
            },

            toggleCanvasBold() {
                if (!this.selectedCanvasItem || this.selectedCanvasItem.type !== 'text') return;
                const current = this.selectedCanvasItem.style.fontWeight;
                this.selectedCanvasItem.style.fontWeight = (current === 'bold' || current >= 700) ? 'normal' : 'bold';
            },

            toggleCanvasItalic() {
                if (!this.selectedCanvasItem || this.selectedCanvasItem.type !== 'text') return;
                const current = this.selectedCanvasItem.style.fontStyle;
                this.selectedCanvasItem.style.fontStyle = (current === 'italic') ? 'normal' : 'italic';
            },

            toggleCanvasUppercase() {
                if (!this.selectedCanvasItem || this.selectedCanvasItem.type !== 'text') return;
                const current = this.selectedCanvasItem.style.textTransform;
                this.selectedCanvasItem.style.textTransform = (current === 'uppercase') ? 'none' : 'uppercase';
            },

            setCanvasTextAlign(align) {
                if (!this.selectedCanvasItem || this.selectedCanvasItem.type !== 'text') return;
                this.selectedCanvasItem.style.textAlign = align;
            },

            setCanvasColor(color) {
                if (!this.selectedCanvasItem) return;
                if (this.selectedCanvasItem.type === 'text') {
                    this.selectedCanvasItem.style.color = color;
                } else if (this.selectedCanvasItem.type === 'line') {
                    this.selectedCanvasItem.color = color;
                }
            },

            resizeCanvasImageTo(widthPx) {
                if (!this.selectedCanvasItem || this.selectedCanvasItem.type !== 'image') return;
                this.selectedCanvasItem.width = widthPx;
                this.selectedCanvasItem.height = null;
            },

            generateHtmlFromItems() {
                let height = (this.designerTipo === 'encabezado') ? 126 : 110;
                if (this.canvasItems && this.canvasItems.length > 0) {
                    let maxY = 0;
                    for (const item of this.canvasItems) {
                        let itemH = item.height || (item.type === 'text' ? 35 : (item.type === 'image' ? (item.width ? Math.min(item.width, 80) : 40) : 2));
                        let b = (item.y || 0) + itemH;
                        if (b > maxY) maxY = b;
                    }
                    height = Math.max(height, Math.round(maxY + 4));
                }

                let html = `<div style="position: relative; width: 100%; max-width: 820px; height: ${height}px; margin: 0 auto; overflow: hidden; box-sizing: border-box; font-family: Arial, Helvetica, sans-serif;">\n`;
                for (const item of this.canvasItems) {
                    if (item.type === 'image') {
                        const w = item.width ? (typeof item.width === 'number' ? item.width + 'px' : item.width) : 'auto';
                        const h = item.height ? item.height + 'px' : 'auto';
                        html += `  <div style="position: absolute; left: ${item.x}px; top: ${item.y}px; z-index: 10;">\n`;
                        html += `    <img src="${item.src}" style="width: ${w}; height: ${h}; display: block; object-fit: contain;" alt="Elemento Visual">\n`;
                        html += `  </div>\n`;
                    } else if (item.type === 'text') {
                        const s = item.style || {};
                        const fontSize = s.fontSize || '12px';
                        const fontWeight = s.fontWeight || 'normal';
                        const fontStyle = s.fontStyle || 'normal';
                        const color = s.color || '#1e293b';
                        const textAlign = s.textAlign || 'left';
                        const textTransform = s.textTransform || 'none';
                        const letterSpacing = s.letterSpacing || 'normal';
                        const lineHeight = s.lineHeight || '1.3';
                        const formattedText = (item.text || '').replace(/\n/g, '<br>');
                        html += `  <div style="position: absolute; left: ${item.x}px; top: ${item.y}px; font-size: ${fontSize}; font-weight: ${fontWeight}; font-style: ${fontStyle}; color: ${color}; text-align: ${textAlign}; text-transform: ${textTransform}; letter-spacing: ${letterSpacing}; line-height: ${lineHeight}; font-family: Arial, Helvetica, sans-serif; z-index: 15;">\n`;
                        html += `    ${formattedText}\n`;
                        html += `  </div>\n`;
                    } else if (item.type === 'line') {
                        const w = item.width || 780;
                        const th = item.thickness || '1px';
                        const ls = item.lineStyle || 'solid';
                        const clr = item.color || '#000000';
                        html += `  <div style="position: absolute; left: ${item.x}px; top: ${item.y}px; width: ${w}px; height: 0px; border-top: ${th} ${ls} ${clr}; z-index: 5;"></div>\n`;
                    }
                }
                html += `</div>`;
                return html;
            },

            async saveCanvasToMembrete() {
                if (!this.designerNombre || !this.designerNombre.trim()) {
                    alert('Por favor ingresa un nombre para el membrete.');
                    return;
                }

                this.savingCanvas = true;
                const html = this.generateHtmlFromItems();
                
                let height = (this.designerTipo === 'encabezado') ? 126 : 110;
                if (this.canvasItems && this.canvasItems.length > 0) {
                    let maxY = 0;
                    for (const item of this.canvasItems) {
                        let itemH = item.height || (item.type === 'text' ? 35 : (item.type === 'image' ? (item.width ? Math.min(item.width, 80) : 40) : 2));
                        let b = (item.y || 0) + itemH;
                        if (b > maxY) maxY = b;
                    }
                    height = Math.max(height, Math.round(maxY + 4));
                }

                const payload = {
                    nombre: this.designerNombre,
                    tipo: this.designerTipo,
                    contenido_html: html,
                    datos_json: {
                        items: this.canvasItems,
                        canvas_width: 820,
                        canvas_height: height
                    }
                };

                const isUpdate = !!this.designerId;
                const url = isUpdate ? `/plantillas-membretes/${this.designerId}` : '/plantillas-membretes';
                const method = isUpdate ? 'PUT' : 'POST';

                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const res = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (data.success && data.membrete) {
                        this.designerOpen = false;
                        
                        // Notificar globalmente a las vistas
                        window.dispatchEvent(new CustomEvent('membrete-saved', { detail: data.membrete }));
                        
                        if (this.onSaveCallback && typeof this.onSaveCallback === 'function') {
                            this.onSaveCallback(data.membrete);
                        }
                    } else {
                        alert(data.message || 'Error al guardar membrete.');
                    }
                } catch (e) {
                    alert('Error al conectar con el servidor.');
                } finally {
                    this.savingCanvas = false;
                }
            }
        };
    }

    // Helper global para abrir el diseñador desde cualquier parte
    window.openCanvasDesigner = function(tipo, existingItem = null, onSave = null) {
        window.dispatchEvent(new CustomEvent('open-canvas-designer', {
            detail: {
                tipo: tipo,
                existingItem: existingItem,
                id: existingItem ? existingItem.id : null,
                nombre: existingItem ? existingItem.nombre : null,
                datos_json: existingItem ? existingItem.datos_json : null,
                onSave: onSave
            }
        }));
    };
</script>
