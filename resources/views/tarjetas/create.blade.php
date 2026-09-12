<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-pink-50 text-pink-600 rounded-xl border border-pink-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        {{ __('Registrar Nueva Tarjeta / Datáfono') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Vincula una tarjeta o terminal de cobro a una entidad bancaria</p>
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
                
                <form action="{{ route('tarjetas.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="banco_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Banco al que pertenece *</label>
                        <select name="banco_id" id="banco_id" required
                                class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                            <option value="">Seleccione el Banco...</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}" {{ old('banco_id') == $banco->id ? 'selected' : '' }}>
                                    {{ $banco->nombre }} ({{ $banco->tipo_cuenta ?? 'General' }})
                                </option>
                            @endforeach
                        </select>
                        @error('banco_id') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nombre" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nombre de la Tarjeta / POS / Terminal *</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" placeholder="Ej: Visa Débito Chase, Datáfono Principal, Mastercard Corporativa" required
                               class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                        @error('nombre') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="tipo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tipo de Tarjeta *</label>
                            <select name="tipo" id="tipo" required
                                    class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                                <option value="Débito" {{ old('tipo') == 'Débito' ? 'selected' : '' }}>Débito</option>
                                <option value="Crédito" {{ old('tipo') == 'Crédito' ? 'selected' : '' }}>Crédito</option>
                            </select>
                            @error('tipo') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="franquicia" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Franquicia / Red *</label>
                            <select name="franquicia" id="franquicia" required
                                    class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                                <option value="Visa" {{ old('franquicia') == 'Visa' ? 'selected' : '' }}>Visa</option>
                                <option value="Mastercard" {{ old('franquicia') == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                                <option value="American Express" {{ old('franquicia') == 'American Express' ? 'selected' : '' }}>American Express</option>
                                <option value="Discover" {{ old('franquicia') == 'Discover' ? 'selected' : '' }}>Discover</option>
                                <option value="Otra" {{ old('franquicia') == 'Otra' ? 'selected' : '' }}>Otra</option>
                            </select>
                            @error('franquicia') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ultimos_digitos" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Últimos 4 Dígitos / ID</label>
                            <input type="text" name="ultimos_digitos" id="ultimos_digitos" value="{{ old('ultimos_digitos') }}" placeholder="Ej: 4589" maxlength="10"
                                   class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                            @error('ultimos_digitos') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="estado" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Estado *</label>
                        <select name="estado" id="estado" required
                                class="w-full text-sm bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 focus:bg-white focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium text-slate-800">
                            <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                        <a href="{{ route('tarjetas.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-md shadow-pink-200 transition-all">
                            Guardar Tarjeta
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
