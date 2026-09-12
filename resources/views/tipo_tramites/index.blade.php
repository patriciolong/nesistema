<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-2">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        Tipos de Trámites y Formularios
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Configura las tarjetas visuales y los formularios dinámicos del sistema</p>
                </div>
            </div>

            <a href="{{ route('tipo-tramites.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg shadow-indigo-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                + Crear Nuevo Trámite
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-10 space-y-6">
            
            @if (session('success'))
                <div class="flex items-center p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-3 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tarjetas Estadísticas -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Tipos de Trámite</span>
                        <h4 class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm flex items-center justify-between bg-emerald-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Trámites Activos</span>
                        <h4 class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['activos'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm flex items-center justify-between bg-rose-50/20">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Trámites Inactivos</span>
                        <h4 class="text-2xl font-black text-rose-600 mt-1">{{ $stats['inactivos'] }}</h4>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Barra de Búsqueda y Filtros -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                <form method="GET" action="{{ route('tipo-tramites.index') }}" class="flex flex-wrap items-center gap-3">
                    
                    <!-- Input Búsqueda -->
                    <div class="relative flex-1 min-w-[260px]">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="buscar" value="{{ request('buscar') }}" 
                               placeholder="Buscar por nombre del trámite o descripción..." 
                               class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-800 transition-all placeholder-slate-400">
                    </div>

                    <!-- Filtro Estado -->
                    <div class="min-w-[160px]">
                        <select name="estado" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="todos">⚡ Todos los estados</option>
                            <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>🟢 Activo en el Hub</option>
                            <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>🔴 Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-indigo-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span>Filtrar</span>
                        </button>
                        @if(request()->anyFilled(['buscar', 'estado']))
                            <a href="{{ route('tipo-tramites.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span>Limpiar</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Grid de Trámites Configurados -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tipoTramites as $tipo)
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition-all group">
                        
                        <div>
                            <!-- Header con el Gradiente Configurado -->
                            <div class="p-6 text-white relative overflow-hidden" style="background: {{ $tipo->color_gradient }};">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white bg-white/20 backdrop-blur-xs">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/20 text-white">
                                        {{ count($tipo->campos ?? []) }} campos
                                    </span>
                                </div>
                                <h3 class="text-xl font-black text-white mb-0.5 tracking-tight">{{ $tipo->nombre }}</h3>
                                <p class="text-white/80 text-xs truncate">{{ $tipo->descripcion ?? 'Sin descripción' }}</p>
                            </div>

                            <!-- Resumen de Campos -->
                            <div class="p-5 space-y-3">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Campos en el Formulario:</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($tipo->campos ?? [] as $campo)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            {{ $campo['label'] }}
                                            <span class="text-[10px] text-slate-400 font-normal">({{ $campo['type'] }})</span>
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">Solo campos financieros estándar</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Footer de Acciones -->
                        <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-semibold {{ $tipo->activo ? 'text-emerald-600' : 'text-slate-400' }} flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full {{ $tipo->activo ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $tipo->activo ? 'Activo en el Hub' : 'Inactivo' }}
                            </span>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('tipo-tramites.edit', $tipo->id) }}" class="p-1.5 bg-white text-slate-600 hover:text-indigo-600 hover:bg-slate-100 rounded-lg border border-slate-200 transition-all" title="Editar Trámite">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                <form action="{{ route('tipo-tramites.destroy', $tipo->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este tipo de trámite?')" class="inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-white text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg border border-slate-200 transition-colors" title="Eliminar Trámite">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200 p-8">
                        <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">No se encontraron tipos de trámite</h3>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 mb-6">No hay tipos de trámites que coincidan con los criterios de búsqueda o filtros seleccionados.</p>
                        <a href="{{ route('tipo-tramites.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md hover:bg-indigo-700 transition-all">
                            + Crear Nuevo Trámite
                        </a>
                    </div>
                @endforelse
            </div>

            @if($tipoTramites->hasPages())
                <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                    {{ $tipoTramites->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
