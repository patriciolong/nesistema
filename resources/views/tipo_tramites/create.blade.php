<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        Crear Nuevo Tipo de Trámite
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Diseña la tarjeta visual y construye el formulario dinámico</p>
                </div>
            </div>

            <a href="{{ route('tipo-tramites.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-wider shadow-sm hover:bg-slate-50 transition duration-150">
                &larr; Volver al Listado
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="formBuilder()">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-10">
            
            <form action="{{ route('tipo-tramites.store') }}" method="POST" @submit="prepareSubmission">
                @csrf
                <!-- Input oculto para JSON de campos -->
                <input type="hidden" name="campos" :value="JSON.stringify(campos)">
                <input type="hidden" name="color_gradient" :value="selectedGradient">
                <input type="hidden" name="icono" :value="selectedIcon">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- Columna Izquierda: Configuración y Campos (8 cols) -->
                    <div class="lg:col-span-8 space-y-6">
                        
                        <!-- Tarjeta 1: Información Básica del Trámite -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
                            <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2 pb-3 border-b border-slate-100">
                                <span class="w-2.5 h-5 bg-indigo-600 rounded-full inline-block"></span>
                                1. Información de la Tarjeta
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nombre del Trámite *</label>
                                    <input type="text" name="nombre" x-model="nombre" placeholder="Ej: Poder Especial, Visa, Traducción..." 
                                           class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-semibold" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Descripción Breve</label>
                                    <input type="text" name="descripcion" x-model="descripcion" placeholder="Ej: Trámites consulares, cartas notariales..." 
                                           class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                                </div>
                            </div>

                            <!-- Selector de Color / Gradiente -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Color de la Tarjeta (Gradiente)</label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <template x-for="(grad, index) in gradients" :key="index">
                                        <button type="button" @click="selectedGradient = grad.value" 
                                                class="h-12 rounded-xl border-2 flex items-center justify-center p-2 text-white text-xs font-bold transition-all shadow-xs"
                                                :style="'background: ' + grad.value"
                                                :class="selectedGradient === grad.value ? 'border-slate-900 ring-2 ring-indigo-500 scale-105 shadow-md' : 'border-transparent opacity-85 hover:opacity-100'">
                                            <span x-text="grad.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Selector de Icono -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Icono Temático</label>
                                <div class="flex flex-wrap gap-2.5">
                                    <template x-for="ico in icons" :key="ico.id">
                                        <button type="button" @click="selectedIcon = ico.id" 
                                                class="p-3 rounded-xl border flex items-center justify-center transition-all"
                                                :class="selectedIcon === ico.id ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'">
                                            <span x-html="ico.svg"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta 2: Constructor de Campos del Formulario -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
                            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                                        <span class="w-2.5 h-5 bg-indigo-600 rounded-full inline-block"></span>
                                        2. Campos del Formulario
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Agrega los campos específicos que el usuario llenará para este trámite</p>
                                </div>
                                <button type="button" @click="agregarCampo()" 
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition-all shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    + Agregar Campo
                                </button>
                            </div>

                            <!-- Lista Dinámica de Campos -->
                            <div class="space-y-4">
                                <template x-for="(campo, index) in campos" :key="index">
                                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 relative group transition-all hover:border-slate-300">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                                <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-[10px]" x-text="index + 1"></span>
                                                Campo Personalizado
                                            </span>
                                            <button type="button" @click="eliminarCampo(index)" class="text-rose-500 hover:text-rose-700 p-1 rounded hover:bg-rose-50 transition-colors" title="Eliminar campo">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                            <!-- Etiqueta -->
                                            <div class="sm:col-span-5">
                                                <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Nombre / Etiqueta del Campo *</label>
                                                <input type="text" x-model="campo.label" @input="campo.name = generateSlug(campo.label)" placeholder="Ej: País de Destino, Tipo Documento..." 
                                                       class="w-full text-xs bg-white border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 font-semibold" required>
                                            </div>

                                            <!-- Tipo de Campo -->
                                            <div class="sm:col-span-4">
                                                <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Tipo de Entrada</label>
                                                <select x-model="campo.type" class="w-full text-xs bg-white border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 font-medium">
                                                    <option value="text">Texto Corto (Línea simple)</option>
                                                    <option value="textarea">Texto Largo (Área de texto)</option>
                                                    <option value="number">Número</option>
                                                    <option value="date">Fecha</option>
                                                    <option value="select">Lista Desplegable (Selector)</option>
                                                    <option value="checkbox">Casilla de Verificación (Sí/No)</option>
                                                </select>
                                            </div>

                                            <!-- Requerido -->
                                            <div class="sm:col-span-3 flex items-center pt-5">
                                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" x-model="campo.required" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                    <span class="text-xs font-bold text-slate-700">Obligatorio</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Opciones si el tipo es Select -->
                                        <div x-show="campo.type === 'select'" class="mt-3 pt-3 border-t border-slate-200/60" x-cloak>
                                            <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Opciones de la Lista (separadas por coma)</label>
                                            <input type="text" x-model="campo.optionsString" @input="campo.options = campo.optionsString.split(',').map(s => s.trim()).filter(Boolean)" placeholder="Opción 1, Opción 2, Opción 3..." 
                                                   class="w-full text-xs bg-white border border-slate-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500">
                                        </div>
                                    </div>
                                </template>

                                <!-- Mensaje si no hay campos adicionales -->
                                <div x-show="campos.length === 0" class="p-6 text-center bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl text-slate-400">
                                    <p class="text-xs font-semibold">No has agregado campos adicionales aún.</p>
                                    <p class="text-[11px] mt-1 text-slate-400">Los campos financieros estándar (Costo, Abono y Saldo) ya se incluyen de forma automática.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end gap-3 pt-2">
                            <a href="{{ route('tipo-tramites.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 transition-all">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg shadow-indigo-200 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Guardar Trámite y Publicar Tarjeta
                            </button>
                        </div>

                    </div>

                    <!-- Columna Derecha: Vista Previa en Vivo de la Tarjeta (4 cols) -->
                    <div class="lg:col-span-4 space-y-6">
                        <div class="sticky top-24 space-y-4">
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Vista Previa en Vivo de la Tarjeta
                                </h3>

                                <!-- Tarjeta Renderizada en Tiempo Real -->
                                <div class="rounded-2xl p-6 shadow-xl transition-all duration-300 border border-white/20 text-white relative overflow-hidden"
                                     :style="'background: ' + selectedGradient">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white" style="background-color: rgba(255, 255, 255, 0.25);">
                                            <span x-html="getCurrentIconSvg()"></span>
                                        </div>
                                        <span class="text-white/80 font-bold text-lg">&rarr;</span>
                                    </div>
                                    <h4 class="text-xl font-black text-white mb-1 tracking-wide" x-text="nombre || 'Nombre del Trámite'"></h4>
                                    <p class="text-white/80 text-xs font-medium leading-relaxed" x-text="descripcion || 'Descripción del trámite...'"></p>
                                </div>

                                <div class="mt-4 p-3.5 bg-slate-50 rounded-xl border border-slate-200/80 text-[11px] text-slate-500 space-y-1">
                                    <p class="font-bold text-slate-700">📌 ¿Cómo funcionará?</p>
                                    <p>Esta tarjeta aparecerá de inmediato en el <strong>Hub de Trámites</strong> de todos los clientes con soporte para <strong>arrastrar y reordenar</strong>.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js Lógica para Form Builder -->
    <script>
        function formBuilder() {
            return {
                nombre: '',
                descripcion: '',
                selectedGradient: 'linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%)',
                selectedIcon: 'document',
                campos: [
                    {
                        name: 'tipo_solicitud',
                        label: 'Tipo de Solicitud',
                        type: 'text',
                        required: true,
                        options: [],
                        optionsString: ''
                    }
                ],

                gradients: [
                    { name: 'Púrpura / Índigo', value: 'linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%)' },
                    { name: 'Azul Océano', value: 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)' },
                    { name: 'Verde Esmeralda', value: 'linear-gradient(135deg, #059669 0%, #064e3b 100%)' },
                    { name: 'Ámbar Dorado', value: 'linear-gradient(135deg, #d97706 0%, #78350f 100%)' },
                    { name: 'Rosa Vibrante', value: 'linear-gradient(135deg, #e11d48 0%, #9f1239 100%)' },
                    { name: 'Cyan / Turquesa', value: 'linear-gradient(135deg, #06b6d4 0%, #0e7490 100%)' },
                    { name: 'Naranja Fuego', value: 'linear-gradient(135deg, #ea580c 0%, #9a3412 100%)' },
                    { name: 'Grafito Oscuro', value: 'linear-gradient(135deg, #334155 0%, #0f172a 100%)' }
                ],

                icons: [
                    { id: 'document', svg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' },
                    { id: 'scale', svg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>' },
                    { id: 'certificate', svg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>' },
                    { id: 'briefcase', svg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>' },
                    { id: 'passport', svg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>' },
                    { id: 'translate', svg: '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>' }
                ],

                getCurrentIconSvg() {
                    let ico = this.icons.find(i => i.id === this.selectedIcon);
                    return ico ? ico.svg : this.icons[0].svg;
                },

                agregarCampo() {
                    this.campos.push({
                        name: 'campo_' + (this.campos.length + 1),
                        label: '',
                        type: 'text',
                        required: false,
                        options: [],
                        optionsString: ''
                    });
                },

                eliminarCampo(index) {
                    this.campos.splice(index, 1);
                },

                generateSlug(text) {
                    if (!text) return 'campo';
                    return text.toString().toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                        .replace(/\s+/g, '_')
                        .replace(/[^\w\-]+/g, '')
                        .replace(/\-\-+/g, '_');
                },

                prepareSubmission(e) {
                    if (!this.nombre.trim()) {
                        alert('Por favor especifica un nombre para el trámite.');
                        e.preventDefault();
                        return;
                    }
                }
            }
        }
    </script>
</x-app-layout>
