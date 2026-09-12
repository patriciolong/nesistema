<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuario') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full max-w-[1700px] mx-auto sm:px-6 lg:px-10">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <x-input-label for="name" :value="__('Nombre Completo')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Usuario -->
                            <div>
                                <x-input-label for="username" :value="__('Nombre de Usuario (Login)')" />
                                <x-text-input id="username" class="block mt-1 w-full bg-gray-100" type="text" name="username" :value="old('username', $user->username)" required readonly title="No se puede cambiar el nombre de usuario de login"/>
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Correo Electrónico (Opcional)')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('Nueva Contraseña (Dejar en blanco para mantener actual)')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Rol -->
                            <div>
                                <x-input-label for="role" :value="__('Rol en el Sistema')" />
                                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="Administrador" {{ old('role', $user->role) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                    <option value="Supervisor" {{ old('role', $user->role) == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                                    <option value="Asesor" {{ old('role', $user->role) == 'Asesor' ? 'selected' : '' }}>Asesor</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <!-- Oficina -->
                            <div>
                                <x-input-label for="office" :value="__('Oficina Asignada')" />
                                <select id="office" name="office" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach($oficinas as $oficina)
                                        <option value="{{ $oficina->nombre }}" {{ old('office', $user->office) == $oficina->nombre ? 'selected' : '' }}>
                                            {{ $oficina->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('office')" class="mt-2" />
                            </div>

                            <!-- Estado -->
                            <div>
                                <x-input-label for="status" :value="__('Estado del Usuario')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="Activo" {{ old('status', $user->status) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="Inactivo" {{ old('status', $user->status) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('users.index') }}">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Actualizar Usuario') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
