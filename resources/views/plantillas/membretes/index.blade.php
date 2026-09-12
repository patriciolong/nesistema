<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-2">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-2xl shadow-md shadow-amber-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-800 tracking-tight leading-none">
                        Gestor Visual de Membretes
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Crea encabezados y pies de página arrastrando imágenes, logos y escribiendo directamente en la pantalla</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('plantillas.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-700 hover:bg-slate-50 shadow-sm transition">
                    &larr; Volver a Plantillas
                </a>
                <button type="button" onclick="window.openCanvasDesigner('encabezado')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-xl text-xs font-black shadow-md shadow-amber-200 hover:shadow-lg transition-all transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    + Diseñar Nuevo Encabezado
                </button>
                <button type="button" onclick="window.openCanvasDesigner('pie')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-black shadow-md hover:shadow-lg transition-all transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    + Diseñar Nuevo Pie de Página
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-100/70 min-h-screen" x-data="membreteIndexManager()" @membrete-saved.window="window.location.reload()">
        <div class="w-full max-w-[1600px] mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="flex items-center p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm">
                    <svg class="w-5 h-5 mr-3 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- SECCIÓN 1: ENCABEZADOS DISPONIBLES -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl border border-amber-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Encabezados Superiores (Headers)</h3>
                            <p class="text-xs text-slate-500">Aparecen en la parte superior de las hojas notariales</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-extrabold px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full">
                            {{ $encabezados->count() }} disponible(s)
                        </span>
                        <button type="button" @click="window.openCanvasDesigner('encabezado')" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                            <span>➕</span> Crear Otro
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @forelse($encabezados as $enc)
                        <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50/60 hover:bg-white hover:border-amber-300 hover:shadow-lg transition-all flex flex-col justify-between space-y-4 group">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-black text-slate-800 text-sm flex items-center gap-2">
                                        {{ $enc->nombre }}
                                        @if($enc->es_predeterminado)
                                            <span class="px-2 py-0.5 bg-amber-500 text-white rounded text-[10px] font-black uppercase tracking-wider">Oficial</span>
                                        @endif
                                    </h4>
                                    <span class="text-[11px] text-slate-400 font-medium">ID #{{ $enc->id }}</span>
                                </div>
                                
                                <!-- Caja de Previsualización Blanca -->
                                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs overflow-hidden min-h-[90px] flex items-center justify-center">
                                    <div class="w-full">
                                        {!! $enc->contenido_html !!}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 text-xs">
                                <button type="button" @click="openMembrete({{ json_encode($enc) }})" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl transition">
                                    ✏️ Abrir en Diseñador Visual
                                </button>
                                @if(!$enc->es_predeterminado)
                                    <button type="button" @click="deleteMembrete({{ $enc->id }})" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl transition">
                                        Eliminar
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-8 text-center text-slate-400 text-xs font-medium">
                            No hay encabezados registrados aún.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- SECCIÓN 2: PIES DE PÁGINA DISPONIBLES -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Pies de Página Inferiores (Footers)</h3>
                            <p class="text-xs text-slate-500">Aparecen al final de cada página con sedes, teléfonos y código QR</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-extrabold px-3 py-1 bg-indigo-50 text-indigo-800 border border-indigo-200 rounded-full">
                            {{ $pies->count() }} disponible(s)
                        </span>
                        <button type="button" @click="window.openCanvasDesigner('pie')" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                            <span>➕</span> Crear Otro
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @forelse($pies as $pie)
                        <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50/60 hover:bg-white hover:border-indigo-300 hover:shadow-lg transition-all flex flex-col justify-between space-y-4 group">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-black text-slate-800 text-sm flex items-center gap-2">
                                        {{ $pie->nombre }}
                                        @if($pie->es_predeterminado)
                                            <span class="px-2 py-0.5 bg-indigo-600 text-white rounded text-[10px] font-black uppercase tracking-wider">Oficial</span>
                                        @endif
                                    </h4>
                                    <span class="text-[11px] text-slate-400 font-medium">ID #{{ $pie->id }}</span>
                                </div>
                                
                                <!-- Caja de Previsualización Blanca -->
                                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs overflow-hidden min-h-[90px] flex items-center justify-center">
                                    <div class="w-full">
                                        {!! $pie->contenido_html !!}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 text-xs">
                                <button type="button" @click="openMembrete({{ json_encode($pie) }})" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl transition">
                                    ✏️ Abrir en Diseñador Visual
                                </button>
                                @if(!$pie->es_predeterminado)
                                    <button type="button" @click="deleteMembrete({{ $pie->id }})" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl transition">
                                        Eliminar
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-8 text-center text-slate-400 text-xs font-medium">
                            No hay pies de página registrados aún.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- INCLUIR EL MODAL CANVA COMPLETO -->
    @include('plantillas.partials.drag_drop_designer_modal')

    <!-- SCRIPT ALPINE DEL GESTOR DE MEMBRETES -->
    <script>
        function membreteIndexManager() {
            return {
                openMembrete(item) {
                    window.openCanvasDesigner(item.tipo, item);
                },

                async deleteMembrete(id) {
                    if (!confirm('¿Estás seguro de que deseas eliminar este membrete?')) return;

                    try {
                        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        const res = await fetch(`/plantillas-membretes/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'No se pudo eliminar.');
                        }
                    } catch (e) {
                        alert('Error de conexión.');
                    }
                }
            };
        }
    </script>
</x-app-layout>
