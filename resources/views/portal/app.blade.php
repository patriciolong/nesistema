<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="NESISTEMA">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="/img/logo.png">

    <title>NESISTEMA 2.0 - Portal Móvil de Clientes</title>

    <!-- Google Fonts Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN para máxima velocidad y portabilidad) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }
        /* Custom Mobile Scrollbar */
        ::-webkit-scrollbar {
            width: 3px;
            height: 3px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom, 16px);
        }
        .safe-top {
            padding-top: env(safe-area-inset-top, 16px);
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex justify-center antialiased select-none" x-data="clientPortalApp()">

    <!-- Contenedor Principal (Simula formato App Móvil centrado en pantallas grandes o 100% full en móvil) -->
    <div class="w-full max-w-md h-full min-h-screen bg-slate-900 flex flex-col justify-between shadow-2xl relative overflow-hidden border-x border-slate-800/60">

        <!-- ========================================== -->
        <!-- PANTALLA: LOGIN (Si no está autenticado)   -->
        <!-- ========================================== -->
        <div x-show="!isAuthenticated" x-cloak class="flex-1 flex flex-col justify-between p-6 safe-top safe-bottom overflow-y-auto">
            
            <div class="pt-8 pb-4 text-center">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-500 p-0.5 shadow-xl shadow-indigo-500/20 flex items-center justify-center mb-5">
                    <div class="w-full h-full bg-slate-900/90 rounded-[22px] flex items-center justify-center">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight">NESISTEMA</h1>
                <p class="text-xs font-semibold text-indigo-400 uppercase tracking-widest mt-1">Portal Móvil de Trámites</p>
                <p class="text-xs text-slate-400 mt-2 max-w-xs mx-auto">Consulta el avance de tus poderes, divorcios, impuestos y documentos notariales en tiempo real.</p>
            </div>

            <div class="space-y-4 my-auto py-4">
                <div x-show="loginError" x-text="loginError" class="p-3 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-xs font-semibold text-center"></div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Cédula / Identificación o Email</label>
                    <div class="relative">
                        <input type="text" x-model="loginForm.identificacion" placeholder="Ej: 1310539588" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-sm font-semibold text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Contraseña</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" x-model="loginForm.password" placeholder="Tu contraseña o número de cédula" 
                               class="w-full pl-11 pr-12 py-3.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-sm font-semibold text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400 py-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="rememberMe" class="rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-0">
                        <span>Recordar mis datos</span>
                    </label>
                    <span class="text-indigo-400 font-semibold">¿Necesitas ayuda?</span>
                </div>

                <button type="button" @click="handleLogin()" :disabled="isLoading" 
                        class="w-full py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 active:scale-[0.98] text-white rounded-2xl font-bold text-sm shadow-xl shadow-indigo-600/30 transition-all flex items-center justify-center gap-2">
                    <span x-show="!isLoading">Ingresar a mis Trámites</span>
                    <span x-show="isLoading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Iniciando sesión...
                    </span>
                </button>
            </div>

            <div class="pt-4 text-center">
                <p class="text-[11px] text-slate-500 font-medium">NESISTEMA 2.0 • Gestión Notarial & Legal</p>
            </div>
        </div>


        <!-- ========================================== -->
        <!-- APLICACIÓN PRINCIPAL (Si está autenticado) -->
        <!-- ========================================== -->
        <div x-show="isAuthenticated" x-cloak class="flex-1 flex flex-col h-full overflow-hidden">

            <!-- HEADER SUPERIOR -->
            <header class="bg-slate-900/95 backdrop-blur-md px-5 pt-5 pb-3.5 border-b border-slate-800/80 sticky top-0 z-30 flex items-center justify-between safe-top">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-indigo-600/20">
                        <span x-text="cliente.nombre ? cliente.nombre.charAt(0) : 'C'"></span>
                    </div>
                    <div>
                        <h2 class="text-sm font-extrabold text-white leading-tight" x-text="cliente.nombre || 'Mi Portal'"></h2>
                        <p class="text-[11px] text-indigo-400 font-semibold flex items-center gap-1 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span x-text="cliente.oficina ? 'Sede ' + cliente.oficina : 'Cliente Activo'"></span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Botón Refrescar Datos -->
                    <button type="button" @click="refreshAll()" :class="isRefreshing ? 'animate-spin text-indigo-400' : 'text-slate-400 hover:text-white'" class="p-2.5 bg-slate-800 hover:bg-slate-700 active:scale-95 rounded-2xl transition-all border border-slate-700/60" title="Actualizar datos">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>

                    <!-- Botón Campana Notificaciones -->
                    <button type="button" @click="activeTab = 'notificaciones'" class="relative p-2.5 bg-slate-800 hover:bg-slate-700 active:scale-95 rounded-2xl text-slate-300 transition-all border border-slate-700/60">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span x-show="unreadNotifCount > 0" x-cloak 
                              class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-black flex items-center justify-center animate-bounce shadow-md" 
                              x-text="unreadNotifCount"></span>
                    </button>
                </div>
            </header>

            <!-- CONTENIDO DE LAS PESTAÑAS (Scrollable) -->
            <main class="flex-1 overflow-y-auto px-5 py-4 space-y-5 pb-24">

                <!-- ============================== -->
                <!-- PESTAÑA 1: INICIO (DASHBOARD) -->
                <!-- ============================== -->
                <div x-show="activeTab === 'inicio'" x-cloak class="space-y-5">
                    
                    <!-- Tarjeta de Saldo y Resumen Financiero -->
                    <div class="rounded-3xl p-5 bg-gradient-to-br from-indigo-900 via-indigo-950 to-slate-900 border border-indigo-500/20 shadow-xl relative overflow-hidden">
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-300">Estado de Cuenta</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-indigo-500/20 text-indigo-300 border border-indigo-400/20">C.I: <span x-text="cliente.identificacion"></span></span>
                        </div>

                        <div class="space-y-1 mb-4">
                            <span class="text-xs text-slate-400">Saldo Pendiente por Pagar:</span>
                            <h3 class="text-3xl font-black text-white" x-text="'$' + formatNumber(cliente.saldo_pendiente || 0)"></h3>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-800/80">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Total Trámites</span>
                                <span class="text-xs font-black text-slate-200" x-text="'$' + formatNumber(cliente.total_deuda || 0)"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Total Abonado</span>
                                <span class="text-xs font-black text-emerald-400" x-text="'$' + formatNumber(cliente.total_abonado || 0)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Métricas Rápidas de Trámites -->
                    <div class="grid grid-cols-2 gap-3">
                        <div @click="activeTab = 'tramites'; statusFilter = 'en_proceso'" class="bg-slate-800/60 hover:bg-slate-800 active:scale-95 p-4 rounded-2xl border border-slate-700/60 cursor-pointer transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <span class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                                <span class="text-2xl font-black text-amber-400" x-text="stats.en_proceso || 0"></span>
                            </div>
                            <span class="text-xs font-bold text-slate-200 block">En Proceso</span>
                            <span class="text-[10px] text-slate-400">En redacción notarial</span>
                        </div>

                        <div @click="activeTab = 'tramites'; statusFilter = 'listo'" class="bg-slate-800/60 hover:bg-slate-800 active:scale-95 p-4 rounded-2xl border border-emerald-500/30 cursor-pointer transition-all bg-emerald-950/10">
                            <div class="flex items-center justify-between mb-2">
                                <span class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                                <span class="text-2xl font-black text-emerald-400" x-text="stats.listos || 0"></span>
                            </div>
                            <span class="text-xs font-bold text-emerald-300 block">¡Listos para Retiro!</span>
                            <span class="text-[10px] text-emerald-400/80 font-medium">Disponibles en sede</span>
                        </div>
                    </div>

                    <!-- Banner de Notificación Urgente si hay trámites listos -->
                    <template x-if="stats.listos > 0">
                        <div class="p-4 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-lg flex items-center justify-between gap-3 text-white">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-wider">¡Trámite Completado!</h4>
                                    <p class="text-[11px] text-emerald-100 mt-0.5">Tienes documentación lista para retirar o firmar.</p>
                                </div>
                            </div>
                            <button type="button" @click="activeTab = 'tramites'; statusFilter = 'listo'" class="px-3 py-1.5 bg-white text-emerald-900 rounded-xl text-xs font-bold shrink-0 shadow-sm">
                                Ver
                            </button>
                        </div>
                    </template>

                    <!-- Acceso Directo por Categorías -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Mis Categorías</h3>
                            <button type="button" @click="activeTab = 'tramites'; categoryFilter = 'todas'" class="text-xs text-indigo-400 font-bold">Ver Todos &rarr;</button>
                        </div>

                        <div class="grid grid-cols-3 gap-2.5">
                            <button type="button" @click="activeTab = 'tramites'; categoryFilter = 'poderes'" class="p-3 bg-slate-800/80 hover:bg-slate-800 active:scale-95 rounded-2xl border border-slate-700/60 flex flex-col items-center text-center gap-2">
                                <span class="w-9 h-9 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold">📜</span>
                                <span class="text-[11px] font-bold text-slate-200">Poderes</span>
                            </button>

                            <button type="button" @click="activeTab = 'tramites'; categoryFilter = 'divorcios'" class="p-3 bg-slate-800/80 hover:bg-slate-800 active:scale-95 rounded-2xl border border-slate-700/60 flex flex-col items-center text-center gap-2">
                                <span class="w-9 h-9 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold">⚖️</span>
                                <span class="text-[11px] font-bold text-slate-200">Divorcios</span>
                            </button>

                            <button type="button" @click="activeTab = 'tramites'; categoryFilter = 'impuestos'" class="p-3 bg-slate-800/80 hover:bg-slate-800 active:scale-95 rounded-2xl border border-slate-700/60 flex flex-col items-center text-center gap-2">
                                <span class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">💵</span>
                                <span class="text-[11px] font-bold text-slate-200">Impuestos</span>
                            </button>
                        </div>
                    </div>

                    <!-- Contacto / Ayuda Notarial -->
                    <div class="p-4 bg-slate-800/40 rounded-2xl border border-slate-700/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">
                                🏢
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-200 block" x-text="cliente.oficina ? 'Oficina ' + cliente.oficina : 'Atención al Cliente'"></span>
                                <span class="text-[10px] text-slate-400">¿Dudas sobre tus documentos?</span>
                            </div>
                        </div>
                        <a :href="'https://wa.me/?text=Hola%20tengo%20una%20consulta%20sobre%20mis%20tramites%20CI%20' + (cliente.identificacion || '')" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold flex items-center gap-1 shadow-sm">
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>


                <!-- ============================== -->
                <!-- PESTAÑA 2: LISTADO DE TRÁMITES -->
                <!-- ============================== -->
                <div x-show="activeTab === 'tramites'" x-cloak class="space-y-4">
                    
                    <!-- Buscador -->
                    <div class="relative">
                        <input type="text" x-model="searchQuery" placeholder="Buscar trámite por nombre o número..." 
                               class="w-full pl-10 pr-4 py-3 bg-slate-800/90 border border-slate-700/80 rounded-2xl text-xs font-semibold text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Filtros por Estado (Chips Horizontales) -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                        <button type="button" @click="statusFilter = 'todos'" 
                                :class="statusFilter === 'todos' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all">
                            Todos (<span x-text="stats.total || 0"></span>)
                        </button>
                        <button type="button" @click="statusFilter = 'en_proceso'" 
                                :class="statusFilter === 'en_proceso' ? 'bg-amber-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all">
                            🟡 En Proceso (<span x-text="stats.en_proceso || 0"></span>)
                        </button>
                        <button type="button" @click="statusFilter = 'listo'" 
                                :class="statusFilter === 'listo' ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all">
                            🟢 ¡Listos! (<span x-text="stats.listos || 0"></span>)
                        </button>
                        <button type="button" @click="statusFilter = 'en_revision'" 
                                :class="statusFilter === 'en_revision' ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all">
                            🔵 En Revisión
                        </button>
                    </div>

                    <!-- Filtros por Categoría -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                        <button type="button" @click="categoryFilter = 'todas'" 
                                :class="categoryFilter === 'todas' ? 'border-indigo-500 text-indigo-400 bg-indigo-500/10' : 'border-slate-700/60 text-slate-400 bg-slate-800/40'"
                                class="px-3 py-1 rounded-lg text-[11px] font-bold border whitespace-nowrap transition-all">
                            Todas
                        </button>
                        <button type="button" @click="categoryFilter = 'poderes'" 
                                :class="categoryFilter === 'poderes' ? 'border-indigo-500 text-indigo-400 bg-indigo-500/10' : 'border-slate-700/60 text-slate-400 bg-slate-800/40'"
                                class="px-3 py-1 rounded-lg text-[11px] font-bold border whitespace-nowrap transition-all">
                            Poderes
                        </button>
                        <button type="button" @click="categoryFilter = 'divorcios'" 
                                :class="categoryFilter === 'divorcios' ? 'border-indigo-500 text-indigo-400 bg-indigo-500/10' : 'border-slate-700/60 text-slate-400 bg-slate-800/40'"
                                class="px-3 py-1 rounded-lg text-[11px] font-bold border whitespace-nowrap transition-all">
                            Divorcios
                        </button>
                        <button type="button" @click="categoryFilter = 'impuestos'" 
                                :class="categoryFilter === 'impuestos' ? 'border-indigo-500 text-indigo-400 bg-indigo-500/10' : 'border-slate-700/60 text-slate-400 bg-slate-800/40'"
                                class="px-3 py-1 rounded-lg text-[11px] font-bold border whitespace-nowrap transition-all">
                            Impuestos
                        </button>
                        <button type="button" @click="categoryFilter = 'varios'" 
                                :class="categoryFilter === 'varios' ? 'border-indigo-500 text-indigo-400 bg-indigo-500/10' : 'border-slate-700/60 text-slate-400 bg-slate-800/40'"
                                class="px-3 py-1 rounded-lg text-[11px] font-bold border whitespace-nowrap transition-all">
                            Varios
                        </button>
                        <button type="button" @click="categoryFilter = 'formularios'" 
                                :class="categoryFilter === 'formularios' ? 'border-indigo-500 text-indigo-400 bg-indigo-500/10' : 'border-slate-700/60 text-slate-400 bg-slate-800/40'"
                                class="px-3 py-1 rounded-lg text-[11px] font-bold border whitespace-nowrap transition-all">
                            Formularios
                        </button>
                    </div>

                    <!-- Lista de Tarjetas de Trámites -->
                    <div class="space-y-3">
                        <template x-for="t in filteredTramites" :key="t.categoria + '_' + t.id">
                            <div @click="openTramiteDetail(t)" 
                                 class="bg-slate-800/80 hover:bg-slate-800 active:scale-[0.99] p-4 rounded-2xl border border-slate-700/60 cursor-pointer shadow-sm transition-all space-y-3">
                                
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md text-white shadow-xs inline-block mb-1" 
                                              :style="'background:' + t.color_badge" 
                                              x-text="t.categoria_label"></span>
                                        <h4 class="text-sm font-extrabold text-white leading-snug" x-text="t.nombre"></h4>
                                        <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5" x-text="t.descripcion"></p>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-black shrink-0 border"
                                          :class="t.estado === 'listo' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : (t.estado === 'en_revision' ? 'bg-blue-500/20 text-blue-300 border-blue-500/30' : 'bg-amber-500/20 text-amber-300 border-amber-500/30')"
                                          x-text="t.estado_label"></span>
                                </div>

                                <!-- Barra de Progreso Visual -->
                                <div class="space-y-1">
                                    <div class="flex justify-between text-[10px] font-bold text-slate-400">
                                        <span>Progreso del Trámite</span>
                                        <span :class="t.porcentaje_avance === 100 ? 'text-emerald-400' : 'text-indigo-400'" x-text="t.porcentaje_avance + '%'"></span>
                                    </div>
                                    <div class="w-full bg-slate-700/80 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full transition-all duration-500" 
                                             :class="t.porcentaje_avance === 100 ? 'bg-emerald-500' : 'bg-indigo-500'" 
                                             :style="'width:' + t.porcentaje_avance + '%'"></div>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-700/60 flex items-center justify-between text-[11px]">
                                    <span class="text-slate-400" x-text="'📅 ' + t.fecha_formateada"></span>
                                    <span class="font-bold" :class="t.saldo > 0 ? 'text-amber-400' : 'text-emerald-400'" x-text="t.saldo > 0 ? 'Saldo: $' + formatNumber(t.saldo) : '✓ Pagado'"></span>
                                </div>
                            </div>
                        </template>

                        <!-- Estado Vacío -->
                        <div x-show="filteredTramites.length === 0" x-cloak class="p-8 text-center bg-slate-800/40 rounded-3xl border border-dashed border-slate-700/60">
                            <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-500 flex items-center justify-center mx-auto mb-3">
                                📋
                            </div>
                            <h4 class="text-xs font-bold text-slate-300">No hay trámites que coincidan</h4>
                            <p class="text-[11px] text-slate-500 mt-1">Prueba cambiando los filtros de categoría o estado.</p>
                        </div>
                    </div>
                </div>


                <!-- ============================== -->
                <!-- PESTAÑA 3: NOTIFICACIONES      -->
                <!-- ============================== -->
                <div x-show="activeTab === 'notificaciones'" x-cloak class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Bandeja de Avisos</h3>
                        <button type="button" @click="markAllAsRead()" class="text-xs text-indigo-400 font-bold hover:underline">Marcar leídas</button>
                    </div>

                    <div class="space-y-2.5">
                        <template x-for="n in notificaciones" :key="n.id">
                            <div @click="handleNotifClick(n)" 
                                 class="p-4 rounded-2xl border transition-all cursor-pointer space-y-1" 
                                 :class="!n.leido ? 'bg-indigo-950/40 border-indigo-500/40 shadow-md shadow-indigo-950/20' : 'bg-slate-800/60 border-slate-700/60 opacity-80'">
                                
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-black text-white" x-text="n.titulo"></h4>
                                    <span x-show="!n.leido" class="px-2 py-0.5 bg-indigo-500 text-white text-[9px] font-black rounded-full">NUEVO</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed" x-text="n.mensaje"></p>
                                <span class="text-[10px] text-slate-500 font-medium block pt-1" x-text="formatDate(n.created_at)"></span>
                            </div>
                        </template>

                        <div x-show="notificaciones.length === 0" x-cloak class="p-8 text-center bg-slate-800/40 rounded-3xl border border-slate-700/60">
                            <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-500 flex items-center justify-center mx-auto mb-3">
                                🔔
                            </div>
                            <h4 class="text-xs font-bold text-slate-300">No tienes notificaciones pendientes</h4>
                            <p class="text-[11px] text-slate-500 mt-1">Aquí recibirás avisos inmediatos cuando tus trámites estén listos.</p>
                        </div>
                    </div>
                </div>


                <!-- ============================== -->
                <!-- PESTAÑA 4: MI CUENTA / PERFIL  -->
                <!-- ============================== -->
                <div x-show="activeTab === 'perfil'" x-cloak class="space-y-4">
                    
                    <div class="bg-slate-800/80 p-5 rounded-3xl border border-slate-700/60 text-center space-y-3">
                        <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-indigo-600 to-purple-600 mx-auto flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-indigo-600/30">
                            <span x-text="cliente.nombre ? cliente.nombre.charAt(0) : 'C'"></span>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white" x-text="cliente.nombre_completo"></h3>
                            <p class="text-xs text-slate-400 mt-0.5">Identificación: <span class="font-mono font-bold text-indigo-400" x-text="cliente.identificacion"></span></p>
                        </div>
                    </div>

                    <!-- Datos del Cliente -->
                    <div class="bg-slate-800/60 p-4 rounded-2xl border border-slate-700/60 space-y-2.5 text-xs">
                        <div class="flex justify-between py-1 border-b border-slate-700/50">
                            <span class="text-slate-400">Teléfono:</span>
                            <span class="font-bold text-white" x-text="cliente.telefono || 'No registrado'"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-700/50">
                            <span class="text-slate-400">Correo Electrónico:</span>
                            <span class="font-bold text-white" x-text="cliente.email || 'No registrado'"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-700/50">
                            <span class="text-slate-400">Ciudad / País:</span>
                            <span class="font-bold text-white" x-text="(cliente.ciudad || '') + ' ' + (cliente.pais || '')"></span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-400">Oficina Notarial:</span>
                            <span class="font-bold text-indigo-400" x-text="cliente.oficina || 'General'"></span>
                        </div>
                    </div>

                    <!-- Acciones de Cuenta -->
                    <div class="space-y-2 pt-2">
                        <button type="button" @click="showPasswordModal = true" class="w-full py-3.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl text-xs font-bold border border-slate-700/80 flex items-center justify-center gap-2">
                            <span>🔑 Cambiar Contraseña de Acceso</span>
                        </button>

                        <button type="button" @click="handleLogout()" class="w-full py-3.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-2xl text-xs font-bold border border-rose-500/20 flex items-center justify-center gap-2">
                            <span>Cerrar Sesión</span>
                        </button>
                    </div>
                </div>

            </main>


            <!-- ============================== -->
            <!-- MODAL: DETALLE DEL TRÁMITE     -->
            <!-- ============================== -->
            <div x-show="selectedTramite" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex justify-center items-end sm:items-center p-0 sm:p-4">
                <div @click.away="selectedTramite = null" 
                     class="w-full max-w-md bg-slate-900 border-t sm:border border-slate-700/80 rounded-t-3xl sm:rounded-3xl p-5 max-h-[85vh] overflow-y-auto space-y-4 shadow-2xl safe-bottom">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <span class="text-[11px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md text-white" 
                              :style="'background:' + (selectedTramite ? selectedTramite.color_badge : '#4f46e5')" 
                              x-text="selectedTramite ? selectedTramite.categoria_label : ''"></span>
                        <button type="button" @click="selectedTramite = null" class="p-1.5 bg-slate-800 rounded-xl text-slate-400 hover:text-white">✕</button>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-base font-black text-white" x-text="selectedTramite ? selectedTramite.nombre : ''"></h3>
                        <p class="text-xs text-slate-400" x-text="selectedTramite ? selectedTramite.descripcion : ''"></p>
                    </div>

                    <!-- Timeline de Avance -->
                    <div class="p-4 bg-slate-800/80 rounded-2xl border border-slate-700/60 space-y-3">
                        <h4 class="text-xs font-black uppercase tracking-wider text-indigo-300">Línea de Tiempo del Trámite</h4>
                        
                        <div class="space-y-3 relative pl-4 border-l-2 border-slate-700">
                            <template x-for="step in timeline" :key="step.fase">
                                <div class="relative">
                                    <div class="absolute -left-[23px] top-0.5 w-4 h-4 rounded-full border-2" 
                                         :class="step.completado ? 'bg-emerald-500 border-emerald-300' : (step.activo ? 'bg-indigo-500 border-indigo-300 animate-pulse' : 'bg-slate-800 border-slate-600')"></div>
                                    <h5 class="text-xs font-bold text-white" x-text="step.titulo"></h5>
                                    <p class="text-[11px] text-slate-400" x-text="step.descripcion"></p>
                                    <span x-show="step.fecha" class="text-[10px] text-indigo-400 font-bold block mt-0.5" x-text="step.fecha"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Detalles del Trámite -->
                    <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-700/60 space-y-2 text-xs" x-show="selectedTramite && Object.keys(selectedTramite.detalles || {}).length > 0">
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-300 mb-1">Detalles Registrados</h4>
                        <template x-for="(val, key) in (selectedTramite ? selectedTramite.detalles : {})" :key="key">
                            <div class="flex justify-between py-1 border-b border-slate-700/40">
                                <span class="text-slate-400" x-text="key + ':'"></span>
                                <span class="font-bold text-white text-right max-w-[60%] truncate" x-text="val"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Resumen Financiero -->
                    <div class="p-4 bg-slate-800/60 rounded-2xl border border-slate-700/60 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Valor Total:</span>
                            <span class="font-bold text-white" x-text="'$' + formatNumber(selectedTramite ? selectedTramite.costo_total : 0)"></span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Abonado:</span>
                            <span class="font-bold text-emerald-400" x-text="'$' + formatNumber(selectedTramite ? selectedTramite.abono : 0)"></span>
                        </div>
                        <div class="flex justify-between text-xs pt-1 border-t border-slate-700 font-black">
                            <span class="text-slate-300">Saldo Pendiente:</span>
                            <span :class="(selectedTramite && selectedTramite.saldo > 0) ? 'text-amber-400' : 'text-emerald-400'" x-text="'$' + formatNumber(selectedTramite ? selectedTramite.saldo : 0)"></span>
                        </div>
                    </div>

                    <button type="button" @click="selectedTramite = null" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-xs font-bold shadow-md">
                        Cerrar Detalle
                    </button>
                </div>
            </div>


            <!-- ============================== -->
            <!-- MODAL: CAMBIAR CONTRASEÑA      -->
            <!-- ============================== -->
            <div x-show="showPasswordModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex justify-center items-center p-4">
                <div @click.away="showPasswordModal = false" class="w-full max-w-sm bg-slate-900 border border-slate-700/80 rounded-3xl p-5 space-y-4 shadow-2xl">
                    <h3 class="text-sm font-black text-white">Cambiar Contraseña</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Contraseña Actual</label>
                            <input type="password" x-model="pwdForm.actual" placeholder="Tu contraseña actual" class="w-full px-3.5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nueva Contraseña</label>
                            <input type="password" x-model="pwdForm.nueva" placeholder="Mínimo 6 caracteres" class="w-full px-3.5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white">
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="showPasswordModal = false" class="flex-1 py-2.5 bg-slate-800 text-slate-300 rounded-xl text-xs font-bold">Cancelar</button>
                        <button type="button" @click="handleChangePassword()" class="flex-1 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold">Guardar</button>
                    </div>
                </div>
            </div>


            <!-- ============================== -->
            <!-- BOTTOM NAVIGATION BAR (Móvil)  -->
            <!-- ============================== -->
            <nav class="bg-slate-900/95 backdrop-blur-md border-t border-slate-800/80 px-6 py-2.5 fixed bottom-0 max-w-md w-full z-40 flex items-center justify-between safe-bottom">
                
                <!-- Tab 1: Inicio -->
                <button type="button" @click="activeTab = 'inicio'" 
                        :class="activeTab === 'inicio' ? 'text-indigo-400 font-extrabold' : 'text-slate-400 hover:text-slate-200'"
                        class="flex flex-col items-center gap-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-[10px]">Inicio</span>
                </button>

                <!-- Tab 2: Trámites -->
                <button type="button" @click="activeTab = 'tramites'" 
                        :class="activeTab === 'tramites' ? 'text-indigo-400 font-extrabold' : 'text-slate-400 hover:text-slate-200'"
                        class="flex flex-col items-center gap-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-[10px]">Trámites</span>
                </button>

                <!-- Tab 3: Notificaciones -->
                <button type="button" @click="activeTab = 'notificaciones'" 
                        :class="activeTab === 'notificaciones' ? 'text-indigo-400 font-extrabold' : 'text-slate-400 hover:text-slate-200'"
                        class="flex flex-col items-center gap-1 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="text-[10px]">Avisos</span>
                    <span x-show="unreadNotifCount > 0" class="absolute -top-1 right-2 w-2 h-2 rounded-full bg-rose-500"></span>
                </button>

                <!-- Tab 4: Perfil -->
                <button type="button" @click="activeTab = 'perfil'" 
                        :class="activeTab === 'perfil' ? 'text-indigo-400 font-extrabold' : 'text-slate-400 hover:text-slate-200'"
                        class="flex flex-col items-center gap-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-[10px]">Mi Cuenta</span>
                </button>

            </nav>

        </div>

    </div>

    <!-- SCRIPT DE LÓGICA REACTIVA DE LA APP -->
    <script>
        function clientPortalApp() {
            return {
                isAuthenticated: false,
                token: localStorage.getItem('nesistema_client_token') || '',
                cliente: {},
                activeTab: 'inicio', // inicio, tramites, notificaciones, perfil
                isLoading: false,
                loginError: '',
                showPassword: false,
                rememberMe: true,
                loginForm: {
                    identificacion: localStorage.getItem('nesistema_saved_user') || '',
                    password: '',
                },
                pwdForm: {
                    actual: '',
                    nueva: '',
                },
                showPasswordModal: false,
                isRefreshing: false,
                tramites: [],
                notificaciones: [],
                unreadNotifCount: 0,
                stats: {
                    total: 0,
                    en_proceso: 0,
                    listos: 0,
                    en_revision: 0,
                },
                statusFilter: 'todos',
                categoryFilter: 'todas',
                searchQuery: '',
                selectedTramite: null,
                timeline: [],

                init() {
                    if (this.token) {
                        this.isAuthenticated = true;
                        this.refreshAll();
                        // Polling cada 8 segundos para chequear si el admin completó un trámite
                        setInterval(() => {
                            if (this.isAuthenticated && !document.hidden) {
                                this.fetchTramites(false);
                                this.fetchNotificaciones(false);
                            }
                        }, 8000);
                    }
                },

                async refreshAll() {
                    if (!this.token) return;
                    this.isRefreshing = true;
                    try {
                        await Promise.all([
                            this.fetchProfile(),
                            this.fetchTramites(false),
                            this.fetchNotificaciones(false)
                        ]);
                    } catch (e) {
                        console.error('Error al actualizar datos:', e);
                    } finally {
                        setTimeout(() => { this.isRefreshing = false; }, 500);
                    }
                },

                async handleLogin() {
                    this.isLoading = true;
                    this.loginError = '';
                    try {
                        const res = await fetch('/api/v1/client/login', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(this.loginForm)
                        });

                        const data = await res.json();
                        if (data.success) {
                            this.token = data.token;
                            this.cliente = data.cliente;
                            this.isAuthenticated = true;
                            localStorage.setItem('nesistema_client_token', data.token);
                            if (this.rememberMe) {
                                localStorage.setItem('nesistema_saved_user', this.loginForm.identificacion);
                            }
                            await this.fetchTramites();
                            await this.fetchNotificaciones();
                        } else {
                            this.loginError = data.message || 'Credenciales incorrectas.';
                        }
                    } catch (e) {
                        this.loginError = 'Error de conexión con el servidor. Revisa tu red.';
                    } finally {
                        this.isLoading = false;
                    }
                },

                async fetchProfile() {
                    try {
                        const res = await fetch('/api/v1/client/profile', {
                            headers: {
                                'Authorization': 'Bearer ' + this.token,
                                'Accept': 'application/json',
                            }
                        });
                        if (res.status === 401) return this.handleLogout();
                        const data = await res.json();
                        if (data.success) {
                            this.cliente = data.cliente;
                        }
                    } catch (e) {}
                },

                async fetchTramites(showLoader = true) {
                    try {
                        const res = await fetch('/api/v1/client/tramites', {
                            headers: {
                                'Authorization': 'Bearer ' + this.token,
                                'Accept': 'application/json',
                            }
                        });
                        if (res.status === 401) return this.handleLogout();
                        const data = await res.json();
                        if (data.success) {
                            this.tramites = data.tramites;
                            this.stats = data.stats;
                        }
                    } catch (e) {
                        console.error('Error al obtener trámites:', e);
                    }
                },

                async fetchNotificaciones(showLoader = true) {
                    try {
                        const res = await fetch('/api/v1/client/notificaciones', {
                            headers: {
                                'Authorization': 'Bearer ' + this.token,
                                'Accept': 'application/json',
                            }
                        });
                        if (res.status === 401) return this.handleLogout();
                        const data = await res.json();
                        if (data.success) {
                            this.notificaciones = data.notificaciones;
                            this.unreadNotifCount = data.unread_count;
                        }
                    } catch (e) {}
                },

                get filteredTramites() {
                    return this.tramites.filter(t => {
                        const matchesStatus = this.statusFilter === 'todos' || t.estado === this.statusFilter;
                        const matchesCategory = this.categoryFilter === 'todas' || t.categoria === this.categoryFilter;
                        const q = this.searchQuery.toLowerCase().trim();
                        const matchesSearch = !q || 
                            t.nombre.toLowerCase().includes(q) || 
                            t.descripcion.toLowerCase().includes(q) ||
                            String(t.id).includes(q);

                        return matchesStatus && matchesCategory && matchesSearch;
                    });
                },

                async openTramiteDetail(t) {
                    this.selectedTramite = t;
                    try {
                        const res = await fetch(`/api/v1/client/tramites/${t.categoria}/${t.id}`, {
                            headers: {
                                'Authorization': 'Bearer ' + this.token,
                                'Accept': 'application/json',
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.timeline = data.timeline;
                        }
                    } catch (e) {}
                },

                async handleNotifClick(n) {
                    // Marcar como leída
                    if (!n.leido) {
                        n.leido = true;
                        this.unreadNotifCount = Math.max(0, this.unreadNotifCount - 1);
                        fetch(`/api/v1/client/notificaciones/${n.id}/read`, {
                            method: 'POST',
                            headers: { 'Authorization': 'Bearer ' + this.token, 'Accept': 'application/json' }
                        });
                    }

                    // Si la notificación pertenece a un trámite, abrirlo
                    if (n.tramite_tipo && n.tramite_id) {
                        const found = this.tramites.find(t => t.categoria === n.tramite_tipo && String(t.id) === String(n.tramite_id));
                        if (found) {
                            this.openTramiteDetail(found);
                        }
                    }
                },

                async markAllAsRead() {
                    this.notificaciones.forEach(n => n.leido = true);
                    this.unreadNotifCount = 0;
                    fetch('/api/v1/client/notificaciones/all/read', {
                        method: 'POST',
                        headers: { 'Authorization': 'Bearer ' + this.token, 'Accept': 'application/json' }
                    });
                },

                async handleChangePassword() {
                    if (!this.pwdForm.actual || !this.pwdForm.nueva) {
                        return alert('Por favor llena todos los campos.');
                    }
                    try {
                        const res = await fetch('/api/v1/client/update-password', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + this.token,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                password_actual: this.pwdForm.actual,
                                password_nuevo: this.pwdForm.nueva,
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            alert('¡Contraseña actualizada con éxito!');
                            this.showPasswordModal = false;
                            this.pwdForm.actual = '';
                            this.pwdForm.nueva = '';
                        } else {
                            alert(data.message || 'Error al cambiar contraseña.');
                        }
                    } catch (e) {
                        alert('Error de conexión.');
                    }
                },

                handleLogout() {
                    if (this.token) {
                        fetch('/api/v1/client/logout', {
                            method: 'POST',
                            headers: { 'Authorization': 'Bearer ' + this.token, 'Accept': 'application/json' }
                        }).catch(() => {});
                    }
                    this.token = '';
                    this.cliente = {};
                    this.tramites = [];
                    this.notificaciones = [];
                    this.unreadNotifCount = 0;
                    this.stats = { total: 0, en_proceso: 0, listos: 0, en_revision: 0 };
                    this.isAuthenticated = false;
                    this.loginForm.password = '';
                    localStorage.removeItem('nesistema_client_token');
                },

                formatNumber(val) {
                    return Number(val || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
                }
            }
        }
    </script>
</body>
</html>
