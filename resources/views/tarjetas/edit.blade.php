<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-pink-50 text-pink-600 rounded-xl border border-pink-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Editar Tarjeta / Datáfono') }}: {{ $tarjeta->nombre }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Modifica los datos del medio de pago</p>
                </div>
            </div>

            <a href="{{ route('tarjetas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-sm transition-all">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Tarjetas
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-8">
                
                <form action="{{ route('tarjetas.update', $tarjeta->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="banco_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Banco al que pertenece *</label>
                        <select name="banco_id" id="banco_id" required
                                class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                            <option value="">Seleccione el Banco...</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}" {{ old('banco_id', $tarjeta->banco_id) == $banco->id ? 'selected' : '' }}>
                                    {{ $banco->nombre }} ({{ $banco->tipo_cuenta ?? 'General' }})
                                </option>
                            @endforeach
                        </select>
                        @error('banco_id') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nombre" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nombre de la Tarjeta / POS / Terminal *</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $tarjeta->nombre) }}" required
                               class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                        @error('nombre') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="tipo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tipo de Tarjeta *</label>
                            <select name="tipo" id="tipo" required
                                    class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                                <option value="Débito" {{ old('tipo', $tarjeta->tipo) == 'Débito' ? 'selected' : '' }}>Débito</option>
                                <option value="Crédito" {{ old('tipo', $tarjeta->tipo) == 'Crédito' ? 'selected' : '' }}>Crédito</option>
                            </select>
                            @error('tipo') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="franquicia" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Franquicia / Red *</label>
                            <select name="franquicia" id="franquicia" required
                                    class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                                <option value="Visa" {{ old('franquicia', $tarjeta->franquicia) == 'Visa' ? 'selected' : '' }}>Visa</option>
                                <option value="Mastercard" {{ old('franquicia', $tarjeta->franquicia) == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                                <option value="American Express" {{ old('franquicia', $tarjeta->franquicia) == 'American Express' ? 'selected' : '' }}>American Express</option>
                                <option value="Discover" {{ old('franquicia', $tarjeta->franquicia) == 'Discover' ? 'selected' : '' }}>Discover</option>
                                <option value="Otra" {{ old('franquicia', $tarjeta->franquicia) == 'Otra' ? 'selected' : '' }}>Otra</option>
                            </select>
                            @error('franquicia') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ultimos_digitos" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Últimos 4 Dígitos / ID</label>
                            <input type="text" name="ultimos_digitos" id="ultimos_digitos" value="{{ old('ultimos_digitos', $tarjeta->ultimos_digitos) }}" maxlength="10"
                                   class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                            @error('ultimos_digitos') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="estado" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Estado *</label>
                        <select name="estado" id="estado" required
                                class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                            <option value="Activo" {{ old('estado', $tarjeta->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ old('estado', $tarjeta->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                        <a href="{{ route('tarjetas.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-md shadow-pink-200 transition-all">
                            Actualizar Tarjeta
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
