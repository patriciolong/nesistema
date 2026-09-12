<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Oficina') }}: {{ $oficina->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-10">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('oficinas.update', $oficina) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <x-input-label for="nombre" :value="__('Nombre de la Oficina')" />
                                <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre', $oficina->nombre)" required autofocus />
                                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <x-input-label for="telefono" :value="__('Teléfono (Opcional)')" />
                                <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono', $oficina->telefono)" />
                                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                            </div>

                            <!-- Dirección -->
                            <div class="md:col-span-2">
                                <x-input-label for="direccion" :value="__('Dirección (Opcional)')" />
                                <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion', $oficina->direccion)" />
                                <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                            </div>

                            <!-- Estado -->
                            <div>
                                <x-input-label for="status" :value="__('Estado')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="Activa" {{ old('status', $oficina->status) == 'Activa' ? 'selected' : '' }}>Activa</option>
                                    <option value="Inactiva" {{ old('status', $oficina->status) == 'Inactiva' ? 'selected' : '' }}>Inactiva</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('oficinas.index') }}">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Actualizar Oficina') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
