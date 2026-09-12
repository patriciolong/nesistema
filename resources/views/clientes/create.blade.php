<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('clientes.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <x-input-label for="c_identificacion" :value="__('Identificación')" />
                                <x-text-input id="c_identificacion" class="block mt-1 w-full" type="text" name="c_identificacion" :value="old('c_identificacion')" required autofocus />
                                <x-input-error :messages="$errors->get('c_identificacion')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_nombre" :value="__('Nombres')" />
                                <x-text-input id="c_nombre" class="block mt-1 w-full" type="text" name="c_nombre" :value="old('c_nombre')" required />
                                <x-input-error :messages="$errors->get('c_nombre')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_apellido" :value="__('Apellidos')" />
                                <x-text-input id="c_apellido" class="block mt-1 w-full" type="text" name="c_apellido" :value="old('c_apellido')" required />
                                <x-input-error :messages="$errors->get('c_apellido')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_telefono" :value="__('Teléfono')" />
                                <x-text-input id="c_telefono" class="block mt-1 w-full" type="text" name="c_telefono" :value="old('c_telefono')" required />
                                <x-input-error :messages="$errors->get('c_telefono')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_edad" :value="__('Edad')" />
                                <x-text-input id="c_edad" class="block mt-1 w-full" type="number" name="c_edad" :value="old('c_edad')" required />
                                <x-input-error :messages="$errors->get('c_edad')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_email" :value="__('Email')" />
                                <x-text-input id="c_email" class="block mt-1 w-full" type="email" name="c_email" :value="old('c_email')" required />
                                <x-input-error :messages="$errors->get('c_email')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="c_direccion" :value="__('Dirección')" />
                                <x-text-input id="c_direccion" class="block mt-1 w-full" type="text" name="c_direccion" :value="old('c_direccion')" required />
                                <x-input-error :messages="$errors->get('c_direccion')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_pais" :value="__('País')" />
                                <x-text-input id="c_pais" class="block mt-1 w-full" type="text" name="c_pais" :value="old('c_pais', 'Ecuador')" required />
                                <x-input-error :messages="$errors->get('c_pais')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_estado" :value="__('Estado/Provincia')" />
                                <x-text-input id="c_estado" class="block mt-1 w-full" type="text" name="c_estado" :value="old('c_estado')" required />
                                <x-input-error :messages="$errors->get('c_estado')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_ciudad" :value="__('Ciudad')" />
                                <x-text-input id="c_ciudad" class="block mt-1 w-full" type="text" name="c_ciudad" :value="old('c_ciudad')" required />
                                <x-input-error :messages="$errors->get('c_ciudad')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_codpostal" :value="__('Código Postal')" />
                                <x-text-input id="c_codpostal" class="block mt-1 w-full" type="text" name="c_codpostal" :value="old('c_codpostal', '000000')" required />
                                <x-input-error :messages="$errors->get('c_codpostal')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="c_napartamento" :value="__('Nro. Apartamento / Casa')" />
                                <x-text-input id="c_napartamento" class="block mt-1 w-full" type="text" name="c_napartamento" :value="old('c_napartamento', 'N/A')" required />
                                <x-input-error :messages="$errors->get('c_napartamento')" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('clientes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Guardar Cliente') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
