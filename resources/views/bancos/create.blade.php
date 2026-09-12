<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-cyan-50 text-cyan-600 rounded-xl border border-cyan-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Registrar Nuevo Banco') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Agrega una entidad bancaria para recepción de pagos</p>
                </div>
            </div>

            <a href="{{ route('bancos.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-sm transition-all">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Bancos
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-8">
                
                <form action="{{ route('bancos.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="nombre" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nombre del Banco *</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" placeholder="Ej: Chase Bank, Bank of America, Banco Pichincha" required
                               class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all font-medium text-slate-800">
                        @error('nombre') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="codigo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Código / N° Cuenta</label>
                            <input type="text" name="codigo" id="codigo" value="{{ old('codigo') }}" placeholder="Ej: CHASE-001 o N° de Cuenta"
                                   class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all font-medium text-slate-800">
                            @error('codigo') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="tipo_cuenta" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tipo de Cuenta</label>
                            <select name="tipo_cuenta" id="tipo_cuenta"
                                    class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all font-medium text-slate-800">
                                <option value="Corriente" {{ old('tipo_cuenta') == 'Corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                                <option value="Ahorros" {{ old('tipo_cuenta') == 'Ahorros' ? 'selected' : '' }}>Cuenta de Ahorros</option>
                                <option value="POS / Datáfono" {{ old('tipo_cuenta') == 'POS / Datáfono' ? 'selected' : '' }}>Terminal POS / Datáfono</option>
                            </select>
                            @error('tipo_cuenta') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="titular" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Titular de la Cuenta</label>
                            <input type="text" name="titular" id="titular" value="{{ old('titular') }}" placeholder="Nombre del titular o empresa"
                                   class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all font-medium text-slate-800">
                            @error('titular') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="estado" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Estado *</label>
                            <select name="estado" id="estado" required
                                    class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition-all font-medium text-slate-800">
                                <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                        <a href="{{ route('bancos.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-xs font-bold shadow-md shadow-cyan-200 transition-all">
                            Guardar Banco
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
