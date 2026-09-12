<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight leading-none">
                        Configurar Permisos de Usuario
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Personaliza los accesos a módulos y funciones para <strong class="text-slate-800">{{ $user->name }}</strong>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('permisos.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-sm transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Volver a Lista
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Estilos ultra confiables para interruptores switch -->
    <style>
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
            flex-shrink: 0;
        }
        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }
        .custom-slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #cbd5e1;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 9999px;
        }
        .custom-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: #ffffff;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .custom-switch input:checked + .custom-slider {
            background-color: #4f46e5;
        }
        .custom-switch input:checked + .custom-slider:before {
            transform: translateX(22px);
        }
        .perm-card-active {
            border-color: #818cf8 !important;
            background-color: #f8fafc !important;
        }
    </style>

    <div class="py-6 space-y-6">
        <div class="w-full max-w-[1500px] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Ficha del Usuario -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 p-6 rounded-3xl text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border border-slate-700">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600/40 border border-indigo-400/30 text-white font-black text-2xl flex items-center justify-center shadow-inner">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-xl font-black text-white">{{ $user->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold
                                {{ $user->role === 'Administrador' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : '' }}
                                {{ $user->role === 'Supervisor' ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : '' }}
                                {{ $user->role === 'Empleado' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : '' }}">
                                Rol: {{ $user->role }}
                            </span>
                            @if($isCustom)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                    ⚙️ Matriz Personalizada Activa
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-700/50 text-slate-300 border border-slate-600">
                                    📋 Valores Predeterminados por Rol
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-300 mt-1 flex items-center gap-3">
                            <span>✉️ {{ $user->email }}</span>
                            <span>📍 {{ $user->office ?? 'Sin Oficina Asignada' }}</span>
                        </p>
                    </div>
                </div>

                <!-- Botones de Presets Rápidos Globales -->
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="marcarTodos()" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold border border-indigo-400/40 shadow-sm transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Seleccionar Todos
                    </button>
                    <button type="button" onclick="desmarcarTodos()" class="px-3 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold border border-white/10 transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Desmarcar Todos
                    </button>
                    <button type="button" onclick="aplicarPresetCajero()" class="px-3 py-2 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 rounded-xl text-xs font-bold border border-emerald-500/30 transition-all">
                        Preset Cajero
                    </button>
                    <button type="button" onclick="aplicarPresetSupervisor()" class="px-3 py-2 bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 rounded-xl text-xs font-bold border border-sky-500/30 transition-all">
                        Preset Supervisor
                    </button>
                </div>
            </div>

            <!-- Formulario de Permisos -->
            <form action="{{ route('permisos.update', $user) }}" method="POST" id="formPermisos">
                @csrf
                @method('PUT')

                <!-- Grid de Categorías -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-24">
                    @foreach($availableGroups as $groupKey => $group)
                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between" id="card_grupo_{{ $groupKey }}">
                            
                            <!-- Header de Categoría -->
                            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/80 flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-xl shadow-xs">
                                        {{ $group['icono'] }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-800 leading-tight">
                                            {{ $group['nombre'] }}
                                        </h4>
                                        <p class="text-[11px] text-slate-400 font-medium">
                                            <span id="badge_count_{{ $groupKey }}" class="font-bold text-indigo-600">0</span> de {{ count($group['permisos']) }} activos
                                        </p>
                                    </div>
                                </div>

                                <!-- Botones de Control del Grupo -->
                                <div class="flex items-center gap-1.5">
                                    <button type="button" 
                                            onclick="alternarSeleccionGrupo('{{ $groupKey }}', event)" 
                                            id="btn_toggle_{{ $groupKey }}"
                                            class="inline-flex items-center gap-1 text-xs font-extrabold text-indigo-700 bg-indigo-50 hover:bg-indigo-600 hover:text-white border border-indigo-200 px-3 py-1.5 rounded-xl transition-all shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span>Alternar Grupo</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Lista de Permisos con Toggles -->
                            <div class="p-5 space-y-3 flex-1" id="grupo_{{ $groupKey }}">
                                @foreach($group['permisos'] as $permKey => $perm)
                                    @php
                                        $isChecked = in_array($permKey, $effectivePermissions, true);
                                    @endphp
                                    <div class="perm-item-card flex items-start justify-between gap-4 p-3.5 rounded-2xl border transition-all cursor-pointer {{ $isChecked ? 'perm-card-active border-indigo-200 bg-slate-50/80' : 'border-slate-100 bg-white hover:bg-slate-50/60' }}"
                                         onclick="clickCardItem(this, event)">
                                        
                                        <div class="flex-1 pr-2 pointer-events-none select-none">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-extrabold text-slate-800">
                                                    {{ $perm['nombre'] }}
                                                </span>
                                                <code class="text-[10px] font-mono font-bold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">
                                                    {{ $permKey }}
                                                </code>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                                {{ $perm['descripcion'] }}
                                            </p>
                                        </div>

                                        <!-- Toggle Switch Estilizado -->
                                        <label class="custom-switch" onclick="event.stopPropagation()">
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permKey }}" 
                                                   id="perm_{{ str_replace('.', '_', $permKey) }}"
                                                   data-group="{{ $groupKey }}"
                                                   data-key="{{ $permKey }}"
                                                   class="perm-checkbox"
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   onchange="handleCheckboxChange(this)">
                                            <span class="custom-slider"></span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Barra Flotante de Guardado -->
                <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 p-4 shadow-2xl">
                    <div class="max-w-[1500px] mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-3 text-xs font-bold text-slate-700">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Módulos autorizados: <strong id="contadorPermisos" class="text-indigo-600 text-sm font-black">0</strong></span>
                        </div>

                        <div class="flex items-center gap-2.5 w-full sm:w-auto">
                            <button type="submit" name="reset_to_role" value="1" onclick="return confirm('¿Restablecer permisos a los valores originales del rol {{ $user->role }}?');" class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                                Restablecer a Rol
                            </button>
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-black shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Guardar Cambios de Permisos</span>
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <!-- Scripts Interactivos para Alternar y Presets -->
    <script>
        const PRESET_CAJERO = @json(\App\Models\User::getRoleDefaultPermissions('Empleado'));
        const PRESET_SUPERVISOR = @json(\App\Models\User::getRoleDefaultPermissions('Supervisor'));

        function handleCheckboxChange(checkbox) {
            actualizarEstadoCard(checkbox);
            actualizarContador();
        }

        function clickCardItem(cardElement, event) {
            const checkbox = cardElement.querySelector('.perm-checkbox');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                handleCheckboxChange(checkbox);
            }
        }

        function actualizarEstadoCard(checkbox) {
            const card = checkbox.closest('.perm-item-card');
            if (!card) return;
            if (checkbox.checked) {
                card.classList.add('perm-card-active');
            } else {
                card.classList.remove('perm-card-active');
            }
        }

        function alternarSeleccionGrupo(groupKey, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const container = document.getElementById('grupo_' + groupKey);
            if (!container) return;

            const checkboxes = container.querySelectorAll('.perm-checkbox');
            if (!checkboxes.length) return;

            // Si hay alguno desmarcado, los marcamos todos. Si todos están marcados, los desmarcamos todos.
            const algunDesmarcado = Array.from(checkboxes).some(cb => !cb.checked);
            const nuevoEstado = algunDesmarcado;

            checkboxes.forEach(cb => {
                cb.checked = nuevoEstado;
                actualizarEstadoCard(cb);
            });

            actualizarContador();
        }

        function actualizarContador() {
            const allCheckboxes = document.querySelectorAll('.perm-checkbox');
            let activosTotal = 0;

            // Contar totales y actualizar badges por categoría
            const grupos = {};

            allCheckboxes.forEach(cb => {
                const group = cb.dataset.group;
                if (!grupos[group]) {
                    grupos[group] = { activos: 0, total: 0 };
                }
                grupos[group].total++;
                if (cb.checked) {
                    activosTotal++;
                    grupos[group].activos++;
                }
                actualizarEstadoCard(cb);
            });

            // Actualizar badges por grupo
            Object.keys(grupos).forEach(groupKey => {
                const badgeEl = document.getElementById('badge_count_' + groupKey);
                if (badgeEl) {
                    badgeEl.textContent = grupos[groupKey].activos;
                }
            });

            // Actualizar barra inferior
            const contadorEl = document.getElementById('contadorPermisos');
            if (contadorEl) {
                contadorEl.textContent = `${activosTotal} de ${allCheckboxes.length}`;
            }
        }

        function marcarTodos() {
            document.querySelectorAll('.perm-checkbox').forEach(cb => {
                cb.checked = true;
                actualizarEstadoCard(cb);
            });
            actualizarContador();
        }

        function desmarcarTodos() {
            document.querySelectorAll('.perm-checkbox').forEach(cb => {
                cb.checked = false;
                actualizarEstadoCard(cb);
            });
            actualizarContador();
        }

        function aplicarPresetCajero() {
            document.querySelectorAll('.perm-checkbox').forEach(cb => {
                cb.checked = PRESET_CAJERO.includes(cb.dataset.key);
                actualizarEstadoCard(cb);
            });
            actualizarContador();
        }

        function aplicarPresetSupervisor() {
            document.querySelectorAll('.perm-checkbox').forEach(cb => {
                cb.checked = PRESET_SUPERVISOR.includes(cb.dataset.key);
                actualizarEstadoCard(cb);
            });
            actualizarContador();
        }

        document.addEventListener('DOMContentLoaded', () => {
            actualizarContador();
        });
    </script>
</x-app-layout>
