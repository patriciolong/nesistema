<!-- Vertical Sidebar Component (Sticky Viewport Height) -->
<aside style="width: 270px; min-width: 270px; height: 100vh; max-height: 100vh; position: sticky; top: 0; background-color: #0f172a; color: #ffffff; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 4px 0 20px rgba(0,0,0,0.15); z-index: 40; flex-shrink: 0; overflow: hidden; box-sizing: border-box;">
    
    <!-- Top Header Brand Logo -->
    <div style="padding: 20px; border-bottom: 1px solid #1e293b; flex-shrink: 0;">
        <a href="{{ route('dashboard') }}" style="text-decoration: none; display: block;">
            <div style="background-color: #ffffff; padding: 10px 14px; border-radius: 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); text-align: center;">
                <img src="{{ asset('img/logo_impre.png') }}" alt="Logo" style="height: 48px; width: auto; max-width: 100%; margin: 0 auto; display: block; object-fit: contain;" />
            </div>
        </a>
        <div style="margin-top: 12px; display: flex; align-items: center; justify-content: space-between;">
            <span style="font-size: 10px; font-weight: 800; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase;">Notaría & Trámites</span>
            @if(Auth::user() && Auth::user()->office)
                <span style="font-size: 10px; font-weight: 800; padding: 2px 8px; background-color: rgba(99, 102, 241, 0.2); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 9999px;">
                    {{ Auth::user()->office }}
                </span>
            @endif
        </div>
    </div>

    <!-- Navigation Menu Items (Scrollable if content exceeds height) -->
    <nav class="custom-sidebar-scroll" style="flex: 1; padding: 16px; overflow-y: auto; overflow-x: hidden; min-height: 0;">
        <div style="padding: 0 10px; margin-bottom: 8px; font-size: 10px; font-weight: 900; color: #64748b; letter-spacing: 1px; text-transform: uppercase;">
            Menú Principal
        </div>

        <!-- Dashboard Link -->
        @if(Auth::check() && Auth::user()->hasPermission('dashboard.view'))
            <a href="{{ route('dashboard') }}" 
               style="{{ request()->routeIs('dashboard') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
               class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('dashboard') ? 'color: #ffffff;' : 'color: #818cf8;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Dashboard</span>
            </a>
        @endif

        <!-- Clientes Link -->
        @if(Auth::check() && Auth::user()->hasPermission('clientes.view'))
            <a href="{{ route('clientes.index') }}" 
               style="{{ request()->routeIs('clientes.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
               class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('clientes.*') ? 'color: #ffffff;' : 'color: #34d399;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Clientes</span>
            </a>
        @endif

        <!-- Cartera de Clientes (Cuentas por Cobrar & Créditos) Link -->
        @if(Auth::check() && (Auth::user()->hasPermission('cartera.view') || Auth::user()->hasPermission('clientes.view')))
            @php
                $deudoresNavCount = \App\Models\Cliente::where('c_saldo', '>', 0)->count();
            @endphp
            <a href="{{ route('cartera.index') }}" 
               style="{{ request()->routeIs('cartera.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
               class="flex items-center justify-between px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                <div class="flex items-center min-w-0">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('cartera.*') ? 'color: #ffffff;' : 'color: #fb923c;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="truncate">Cartera & Créditos</span>
                </div>
                @if($deudoresNavCount > 0)
                    <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; background-color: #ea580c; color: #ffffff; border-radius: 9999px; letter-spacing: 0.5px;">
                        {{ $deudoresNavCount }}
                    </span>
                @endif
            </a>
        @endif

        <!-- Módulo de Caja (Apertura, Arqueo, Cierre) -->
        @if(Auth::check() && Auth::user()->hasPermission('cajas.operar'))
            @php
                $cajaNav = Auth::user() ? Auth::user()->cajaAbierta() : null;
            @endphp
            <a href="{{ route('cajas.index') }}" 
               style="{{ request()->routeIs('cajas.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
               class="flex items-center justify-between px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                <div class="flex items-center min-w-0">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('cajas.*') ? 'color: #ffffff;' : 'color: #10b981;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="truncate">Módulo de Caja</span>
                </div>
                @if($cajaNav)
                    <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; background-color: #10b981; color: #ffffff; border-radius: 9999px; letter-spacing: 0.5px;">
                        #{{ $cajaNav->id }}
                    </span>
                @else
                    <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; background-color: #ef4444; color: #ffffff; border-radius: 9999px; letter-spacing: 0.5px;">
                        Cerrada
                    </span>
                @endif
            </a>
        @endif

        <!-- Plantillas Link -->
        @if(Auth::check() && Auth::user()->hasPermission('plantillas.view'))
            <a href="{{ route('plantillas.index') }}" 
               style="{{ request()->routeIs('plantillas.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
               class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('plantillas.*') ? 'color: #ffffff;' : 'color: #f59e0b;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Plantillas</span>
            </a>
        @endif

        {{-- 2. SECCIÓN: REPORTES & AUDITORÍA --}}
        @if(Auth::check() && (Auth::user()->hasPermission('reportes.cajas') || Auth::user()->hasPermission('reportes.desempeno')))
            <div style="padding: 14px 10px 6px 10px; margin-bottom: 2px; font-size: 10px; font-weight: 900; color: #64748b; letter-spacing: 1px; text-transform: uppercase;">
                Reportes & Auditoría
            </div>

            <!-- Reportes de Caja Link -->
            @if(Auth::user()->hasPermission('reportes.cajas'))
                <a href="{{ route('reportes.cajas.index') }}" 
                   style="{{ request()->routeIs('reportes.cajas.index') || request()->routeIs('reportes.cajas.show') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
                   class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('reportes.cajas.*') ? 'color: #ffffff;' : 'color: #38bdf8;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Reportes de Caja</span>
                </a>
            @endif

            <!-- Desempeño Cajeros Link -->
            @if(Auth::user()->hasPermission('reportes.desempeno'))
                <a href="{{ route('reportes.cajas.desempeno') }}" 
                   style="{{ request()->routeIs('reportes.cajas.desempeno') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
                   class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('reportes.cajas.desempeno') ? 'color: #ffffff;' : 'color: #f43f5e;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span>Desempeño Cajeros</span>
                </a>
            @endif
        @endif

        {{-- 3. SECCIÓN: ADMINISTRACIÓN DEL SISTEMA --}}
        @if(Auth::check() && (Auth::user()->hasPermission('users.manage') || Auth::user()->hasPermission('permisos.manage') || Auth::user()->hasPermission('oficinas.manage') || Auth::user()->hasPermission('bancos.manage') || Auth::user()->hasPermission('tarjetas.manage') || Auth::user()->hasPermission('tipo_tramites.manage')))
            <div style="padding: 14px 10px 6px 10px; margin-bottom: 2px; font-size: 10px; font-weight: 900; color: #64748b; letter-spacing: 1px; text-transform: uppercase;">
                Administración
            </div>

            <!-- Usuarios Link -->
            @if(Auth::user()->hasPermission('users.manage'))
                <a href="{{ route('users.index') }}" 
                   style="{{ request()->routeIs('users.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
                   class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('users.*') ? 'color: #ffffff;' : 'color: #c084fc;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Usuarios</span>
                </a>
            @endif

            <!-- Permisos de Usuario Link -->
            @if(Auth::user()->hasPermission('permisos.manage'))
                <a href="{{ route('permisos.index') }}" 
                   style="{{ request()->routeIs('permisos.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
                   class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('permisos.*') ? 'color: #ffffff;' : 'color: #818cf8;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Permisos de Acceso</span>
                </a>
            @endif

            <!-- Oficinas Link -->
            @if(Auth::user()->hasPermission('oficinas.manage'))
                <a href="{{ route('oficinas.index') }}" 
                   style="{{ request()->routeIs('oficinas.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
                   class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('oficinas.*') ? 'color: #ffffff;' : 'color: #fbbf24;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Oficinas</span>
                </a>
            @endif

            <!-- Bancos Link -->
            @if(Auth::user()->hasPermission('bancos.manage'))
                <a href="{{ route('bancos.index') }}" 
                   style="{{ request()->routeIs('bancos.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
                   class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('bancos.*') ? 'color: #ffffff;' : 'color: #06b6d4;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                    <span>Bancos</span>
                </a>
            @endif

            <!-- Tarjetas Link -->
            @if(Auth::user()->hasPermission('tarjetas.manage'))
                <a href="{{ route('tarjetas.index') }}" 
                   style="{{ request()->routeIs('tarjetas.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
                   class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('tarjetas.*') ? 'color: #ffffff;' : 'color: #ec4899;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span>Tarjetas / POS</span>
                </a>
            @endif

            <!-- Tipos de Trámites Link -->
            @if(Auth::user()->hasPermission('tipo_tramites.manage'))
                <a href="{{ route('tipo-tramites.index') }}" 
                   style="{{ request()->routeIs('tipo-tramites.*') ? 'background-color: #4f46e5; color: #ffffff; box-shadow: 0 8px 15px -3px rgba(79,70,229,0.4); font-weight: 800;' : 'color: #cbd5e1; font-weight: 700;' }}"
                   class="flex items-center px-3.5 py-3 text-sm rounded-xl transition-all duration-200 hover:bg-slate-800 hover:text-white mb-1.5 text-decoration-none">
                    <svg class="w-5 h-5 mr-3 shrink-0" style="{{ request()->routeIs('tipo-tramites.*') ? 'color: #ffffff;' : 'color: #a855f7;' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span>Tipos de Trámites</span>
                </a>
            @endif
        @endif
    </nav>

    <!-- User Panel Footer (Always Fixed at Screen Bottom) -->
    @if(Auth::check())
    <div style="padding: 14px; margin: 14px; background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; flex-shrink: 0;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background-color: #4f46e5; color: #ffffff; font-weight: 900; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div style="min-width: 0; flex: 1;">
                <div style="font-weight: 800; font-size: 13px; color: #ffffff; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ Auth::user()->name }}</div>
                <div style="font-size: 11px; color: #94a3b8; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; padding-top: 10px; border-top: 1px solid #334155;">
            <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; justify-content: center; padding: 6px; font-size: 11px; font-weight: 700; color: #cbd5e1; background-color: #334155; border-radius: 8px; text-decoration: none;">
                Perfil
            </a>
            
            <form method="POST" action="{{ route('logout') }}" style="display: block; margin: 0;">
                @csrf
                <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; padding: 6px; font-size: 11px; font-weight: 700; color: #fca5a5; background-color: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; cursor: pointer;">
                    Salir
                </button>
            </form>
        </div>
    </div>
    @endif
</aside>
