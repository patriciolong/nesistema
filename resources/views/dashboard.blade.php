<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="relative">
                    <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);" class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20 ring-4 ring-indigo-50">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
                    </span>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 style="color: #0f172a; font-weight: 900;" class="text-2xl tracking-tight">Centro Analítico & Diagnóstico IA</h1>
                        <span style="background-color: #ede9fe; color: #5b21b6; border: 1px solid #c4b5fd;" class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-black">
                            <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                            Motor IA 2.0
                        </span>
                    </div>
                    <p style="color: #475569; font-weight: 600;" class="text-xs mt-0.5">
                        Auditoría en tiempo real de operaciones, rendimiento del personal, cobros y horarios
                    </p>
                </div>
            </div>

            <!-- Header Quick Controls -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="scrollToAiSection()" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%); color: #ffffff;" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-black rounded-xl shadow-md shadow-indigo-500/25 transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer">
                    <svg class="w-4 h-4 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>Auditoría Inteligente IA</span>
                </button>

                <button type="button" onclick="exportExcel()" style="background-color: #059669; color: #ffffff;" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-black hover:bg-emerald-700 rounded-xl shadow-md shadow-emerald-600/25 transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer">
                    <svg class="w-4 h-4 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Descargar Excel</span>
                </button>
            </div>
        </div>
    </x-slot>

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="space-y-6 pb-16" style="color: #0f172a;">

        <!-- ========================================== -->
        <!-- 1. GLOBAL FILTER & CONTROL BAR             -->
        <!-- ========================================== -->
        <div style="background-color: #ffffff; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" class="rounded-2xl p-4">
            <form id="filterForm" onsubmit="event.preventDefault(); applyFilters();" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                @csrf
                
                <!-- Period Segmented Button Pill Group -->
                <div class="flex items-center gap-2 flex-wrap">
                    <div style="background-color: #f1f5f9; border: 1px solid #cbd5e1;" class="flex items-center gap-1.5 p-1 rounded-xl">
                        <button type="button" onclick="setPeriod('hoy')" data-period="hoy" style="color: #334155;" class="period-btn px-3 py-1.5 text-xs font-bold rounded-lg transition-all hover:bg-white">Hoy</button>
                        <button type="button" onclick="setPeriod('7d')" data-period="7d" style="color: #334155;" class="period-btn px-3 py-1.5 text-xs font-bold rounded-lg transition-all hover:bg-white">7 Días</button>
                        <button type="button" onclick="setPeriod('30d')" data-period="30d" style="color: #334155;" class="period-btn px-3 py-1.5 text-xs font-bold rounded-lg transition-all hover:bg-white">30 Días</button>
                        <button type="button" onclick="setPeriod('mes')" data-period="mes" style="color: #334155;" class="period-btn px-3 py-1.5 text-xs font-bold rounded-lg transition-all hover:bg-white">Este Mes</button>
                        <button type="button" onclick="setPeriod('anio')" data-period="anio" style="color: #334155;" class="period-btn px-3 py-1.5 text-xs font-bold rounded-lg transition-all hover:bg-white">Este Año</button>
                        <button type="button" onclick="setPeriod('todo')" data-period="todo" style="background-color: #4f46e5; color: #ffffff;" class="period-btn px-3 py-1.5 text-xs font-black rounded-lg shadow-sm">Histórico Total</button>
                    </div>
                    <input type="hidden" name="periodo" id="periodoInput" value="todo">
                </div>

                <!-- Office Selector & Date Range Pickers -->
                <div class="flex items-center gap-3 flex-wrap">
                    <!-- Office Select -->
                    <div class="flex items-center gap-2">
                        <span style="color: #334155; font-weight: 800;" class="text-xs whitespace-nowrap">Sede:</span>
                        <div class="relative">
                            <select name="oficina" id="oficinaSelect" onchange="applyFilters()" style="background-color: #f8fafc; color: #0f172a; border: 1.5px solid #cbd5e1; font-weight: 800;" class="text-xs rounded-xl pl-3 pr-8 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                                <option value="todas">🏢 Todas las Sedes</option>
                                @foreach($analytics['oficinas_disponibles'] as $ofi)
                                    <option value="{{ $ofi }}" {{ ($analytics['filters']['oficina'] ?? '') === $ofi ? 'selected' : '' }}>📍 {{ $ofi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div style="background-color: #f8fafc; border: 1.5px solid #cbd5e1;" class="flex items-center gap-1.5 rounded-xl px-2.5 py-1 text-xs">
                        <input type="date" name="fecha_desde" id="fechaDesde" onchange="onCustomDateChange()" value="{{ $analytics['filters']['fecha_desde'] ?? '' }}" style="color: #0f172a; font-weight: 700;" class="text-xs bg-transparent border-none focus:ring-0 p-1">
                        <span style="color: #64748b; font-weight: 900;">→</span>
                        <input type="date" name="fecha_hasta" id="fechaHasta" onchange="onCustomDateChange()" value="{{ $analytics['filters']['fecha_hasta'] ?? '' }}" style="color: #0f172a; font-weight: 700;" class="text-xs bg-transparent border-none focus:ring-0 p-1">
                    </div>

                    <button type="button" onclick="applyFilters()" style="background-color: #4f46e5; color: #ffffff;" class="px-3.5 py-2 text-xs font-black hover:bg-indigo-700 rounded-xl transition-all cursor-pointer flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Actualizar</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- 2. EXECUTIVE FINTECH KPI STATS CARDS       -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <!-- KPI 1: Total Ventas / Ingresos -->
            <div style="background-color: #ffffff; border: 1.5px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.03);" class="relative rounded-2xl p-5 hover:shadow-md transition-all group overflow-hidden">
                <div style="background: linear-gradient(90deg, #10b981, #14b8a6);" class="absolute top-0 left-0 right-0 h-1.5"></div>
                <div class="flex items-center justify-between mb-2">
                    <span style="color: #475569; font-weight: 900;" class="text-[11px] uppercase tracking-wider">Total Facturación</span>
                    <div style="background-color: #ecfdf5; color: #047857;" class="w-8 h-8 rounded-xl flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div style="color: #0f172a; font-weight: 900;" class="text-2xl tracking-tight" id="kpiTotalRecaudado">
                    ${{ number_format($analytics['kpis']['total_recaudado'], 2) }}
                </div>
                <div style="border-top: 1px solid #f1f5f9;" class="flex items-center justify-between mt-2.5 pt-2 text-xs font-semibold">
                    <span style="background-color: #ecfdf5; color: #065f46; font-weight: 900; border: 1px solid #a7f3d0;" class="inline-flex items-center px-2 py-0.5 rounded text-[11px]">
                        {{ $analytics['kpis']['total_pagos_count'] }} recibos
                    </span>
                    <span style="color: #475569; font-weight: 700;" class="text-[11px]">Prom: ${{ number_format($analytics['kpis']['ticket_promedio'], 2) }}</span>
                </div>
            </div>

            <!-- KPI 2: Total Trámites Procesados -->
            <div style="background-color: #ffffff; border: 1.5px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.03);" class="relative rounded-2xl p-5 hover:shadow-md transition-all group overflow-hidden">
                <div style="background: linear-gradient(90deg, #6366f1, #8b5cf6);" class="absolute top-0 left-0 right-0 h-1.5"></div>
                <div class="flex items-center justify-between mb-2">
                    <span style="color: #475569; font-weight: 900;" class="text-[11px] uppercase tracking-wider">Trámites Atendidos</span>
                    <div style="background-color: #eef2ff; color: #4338ca;" class="w-8 h-8 rounded-xl flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div style="color: #0f172a; font-weight: 900;" class="text-2xl tracking-tight" id="kpiTotalTramites">
                    {{ $analytics['kpis']['total_tramites'] }}
                </div>
                <div style="border-top: 1px solid #f1f5f9;" class="flex items-center justify-between mt-2.5 pt-2 text-xs font-semibold">
                    <span style="color: #4338ca; font-weight: 800;" class="text-[11px] truncate">Líder: {{ $analytics['tramites_categorias'][0]['categoria'] ?? 'Poderes' }}</span>
                </div>
            </div>

            <!-- KPI 3: Clientes Registrados -->
            <div style="background-color: #ffffff; border: 1.5px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.03);" class="relative rounded-2xl p-5 hover:shadow-md transition-all group overflow-hidden">
                <div style="background: linear-gradient(90deg, #06b6d4, #3b82f6);" class="absolute top-0 left-0 right-0 h-1.5"></div>
                <div class="flex items-center justify-between mb-2">
                    <span style="color: #475569; font-weight: 900;" class="text-[11px] uppercase tracking-wider">Cartera Clientes</span>
                    <div style="background-color: #ecfeff; color: #0e7490;" class="w-8 h-8 rounded-xl flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div style="color: #0f172a; font-weight: 900;" class="text-2xl tracking-tight" id="kpiTotalClientes">
                    {{ $analytics['kpis']['total_clientes'] }}
                </div>
                <div style="border-top: 1px solid #f1f5f9;" class="flex items-center justify-between mt-2.5 pt-2 text-xs font-semibold">
                    <span style="color: #0e7490; font-weight: 900;" class="text-[11px]">100% Clientes Activos</span>
                </div>
            </div>

            <!-- KPI 4: Cuadre y Disciplina de Cajas -->
            <div style="background-color: #ffffff; border: 1.5px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.03);" class="relative rounded-2xl p-5 hover:shadow-md transition-all group overflow-hidden">
                <div style="background: linear-gradient(90deg, #f59e0b, #ea580c);" class="absolute top-0 left-0 right-0 h-1.5"></div>
                <div class="flex items-center justify-between mb-2">
                    <span style="color: #475569; font-weight: 900;" class="text-[11px] uppercase tracking-wider">Cuadre de Cajas</span>
                    <div style="background-color: #fffbeb; color: #b45309;" class="w-8 h-8 rounded-xl flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div style="color: #0f172a; font-weight: 900;" class="text-2xl tracking-tight" id="kpiCuadreRate">
                    {{ $analytics['kpis']['porcentaje_cuadre'] }}%
                </div>
                <div style="border-top: 1px solid #f1f5f9;" class="flex items-center justify-between mt-2.5 pt-2 text-xs font-semibold">
                    <span style="color: #065f46; font-weight: 900;" class="text-[11px]" id="kpiCajasCuadradas">{{ $analytics['kpis']['cajas_cuadradas'] }} OK</span>
                    <span style="color: #b91c1c; font-weight: 900;" class="text-[11px]" id="kpiCajasDescuadradas">{{ $analytics['kpis']['cajas_descuadradas'] }} Descuadre</span>
                </div>
            </div>

            <!-- KPI 5: Sede Líder & Concurrencia -->
            <div style="background-color: #ffffff; border: 1.5px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.03);" class="relative rounded-2xl p-5 hover:shadow-md transition-all group overflow-hidden">
                <div style="background: linear-gradient(90deg, #a855f7, #ec4899);" class="absolute top-0 left-0 right-0 h-1.5"></div>
                <div class="flex items-center justify-between mb-2">
                    <span style="color: #475569; font-weight: 900;" class="text-[11px] uppercase tracking-wider">Sede & Concurrencia</span>
                    <div style="background-color: #fdf4ff; color: #a21caf;" class="w-8 h-8 rounded-xl flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div style="color: #0f172a; font-weight: 900;" class="text-lg tracking-tight truncate" id="kpiTopOficina">
                    🏢 {{ $analytics['kpis']['top_oficina'] }}
                </div>
                <div style="border-top: 1px solid #f1f5f9;" class="flex items-center justify-between mt-2.5 pt-2 text-xs font-semibold">
                    <span style="color: #7e22ce; font-weight: 800;" class="text-[11px] truncate" id="kpiHoraPico">⚡ {{ $analytics['kpis']['hora_pico'] }}</span>
                </div>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- 3. ADVANCED AI COMMAND CENTER (HIGH CONTRAST EXECUTIVE)   -->
        <!-- ========================================================= -->
        <div id="aiSection" style="background-color: #ffffff; border: 2px solid #6366f1; box-shadow: 0 10px 30px -5px rgba(99, 102, 241, 0.15);" class="rounded-3xl p-6 lg:p-8">
            
            <!-- AI Header -->
            <div style="border-bottom: 2px solid #e2e8f0;" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6">
                <div class="flex items-center gap-4">
                    <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%);" class="w-14 h-14 rounded-2xl flex items-center justify-center text-white shadow-md shadow-indigo-500/25 ring-4 ring-indigo-50">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h2 style="color: #0f172a; font-weight: 900;" class="text-xl lg:text-2xl tracking-tight">Comando de Diagnóstico & Inteligencia Artificial</h2>
                            <span style="background-color: #dcfce7; color: #15803d; border: 1.5px solid #86efac;" class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider">
                                EN VIVO
                            </span>
                        </div>
                        <p style="color: #475569; font-weight: 700;" class="text-xs mt-0.5" id="aiEngineLabel">
                            {{ $aiReport['engine'] ?? 'Motor de IA Analítica NESISTEMA' }} • Actualizado: {{ $aiReport['generated_at'] ?? now()->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="copyAiSummary()" style="background-color: #f8fafc; color: #1e293b; border: 1.5px solid #cbd5e1;" class="px-3.5 py-2 text-xs font-black hover:bg-slate-100 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        <span id="copyBtnText">Copiar Diagnóstico</span>
                    </button>

                    <button type="button" onclick="regenerateAiAnalysis()" id="btnRegenerateAi" style="background-color: #4f46e5; color: #ffffff;" class="px-4 py-2 text-xs font-black hover:bg-indigo-700 active:scale-95 rounded-xl transition-all shadow-md shadow-indigo-500/25 flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-white" id="aiSpinnerIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Regenerar IA</span>
                    </button>
                </div>
            </div>

            <!-- Executive Summary Callout (High Contrast Light Charcoal on Soft Blue) -->
            <div style="background-color: #f8fafc; border: 1.5px solid #c7d2fe;" class="my-6 p-5 rounded-2xl shadow-xs">
                <div class="flex items-start gap-3.5">
                    <div style="background: linear-gradient(135deg, #f59e0b, #d97706); color: #ffffff;" class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 mt-0.5 font-black text-lg shadow-sm">
                        💡
                    </div>
                    <div style="color: #0f172a; font-size: 13px; font-weight: 600; line-height: 1.65;" id="aiExecutiveSummary">
                        {!! Str::markdown($aiReport['executive_summary'] ?? '') !!}
                    </div>
                </div>
            </div>

            <!-- Strategic Pillars: 3 Clean High-Contrast Grid Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                
                <!-- Col 1: Strategic Highlights -->
                <div style="background-color: #f0fdf4; border: 2px solid #86efac;" class="p-5 rounded-2xl shadow-sm">
                    <div style="border-bottom: 1.5px solid #bbf7d0;" class="flex items-center justify-between gap-2 mb-4 pb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                            <h3 style="color: #14532d; font-weight: 900;" class="text-xs uppercase tracking-wider">PATRONES POSITIVOS & TRACCIÓN</h3>
                        </div>
                        <span style="background-color: #dcfce7; color: #166534; font-weight: 900; border: 1px solid #86efac;" class="text-[10px] px-2 py-0.5 rounded-full">Auditoría OK</span>
                    </div>
                    <ul class="space-y-3 text-xs" id="aiHighlightsList">
                        @foreach($aiReport['strategic_highlights'] ?? [] as $high)
                            <li style="color: #14532d; font-weight: 600;" class="flex items-start gap-2.5 leading-relaxed">
                                <span style="color: #15803d; font-weight: 900;" class="shrink-0 mt-0.5 text-sm">✓</span>
                                <span style="color: #14532d;">{!! Str::markdown($high) !!}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Col 2: Operational Bottlenecks & Warnings -->
                <div style="background-color: #fffbeb; border: 2px solid #fde68a;" class="p-5 rounded-2xl shadow-sm">
                    <div style="border-bottom: 1.5px solid #fef08a;" class="flex items-center justify-between gap-2 mb-4 pb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <h3 style="color: #78350f; font-weight: 900;" class="text-xs uppercase tracking-wider">RIESGOS & DESCUADRES</h3>
                        </div>
                        <span style="background-color: #fef3c7; color: #92400e; font-weight: 900; border: 1px solid #fde68a;" class="text-[10px] px-2 py-0.5 rounded-full">Monitoreo</span>
                    </div>
                    <ul class="space-y-3 text-xs" id="aiBottlenecksList">
                        @foreach($aiReport['operational_bottlenecks'] ?? [] as $bot)
                            <li style="color: #78350f; font-weight: 600;" class="flex items-start gap-2.5 leading-relaxed">
                                <span style="color: #b45309; font-weight: 900;" class="shrink-0 mt-0.5 text-sm">⚠️</span>
                                <span style="color: #78350f;">{!! Str::markdown($bot) !!}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Col 3: Actionable Recommendations -->
                <div style="background-color: #fdf2f8; border: 2px solid #fbcfe8;" class="p-5 rounded-2xl shadow-sm">
                    <div style="border-bottom: 1.5px solid #fce7f3;" class="flex items-center justify-between gap-2 mb-4 pb-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-pink-600"></span>
                            <h3 style="color: #831843; font-weight: 900;" class="text-xs uppercase tracking-wider">PLAN DE ACCIÓN SUGERIDO</h3>
                        </div>
                        <span style="background-color: #fce7f3; color: #9d174d; font-weight: 900; border: 1px solid #fbcfe8;" class="text-[10px] px-2 py-0.5 rounded-full">Priorizado</span>
                    </div>
                    <div class="space-y-3" id="aiRecommendationsList">
                        @foreach($aiReport['actionable_recommendations'] ?? [] as $rec)
                            <div style="background-color: #ffffff; border: 1.5px solid #f472b6;" class="p-3 rounded-xl shadow-2xs">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span style="color: #0f172a; font-weight: 900;" class="text-xs">{{ $rec['titulo'] ?? 'Acción Sugerida' }}</span>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider" style="background-color: {{ $rec['color'] ?? '#dc2626' }}20; color: {{ $rec['color'] ?? '#dc2626' }}; border: 1px solid {{ $rec['color'] ?? '#dc2626' }};">
                                        {{ $rec['prioridad'] ?? 'Media' }}
                                    </span>
                                </div>
                                <p style="color: #334155; font-weight: 600;" class="text-[11px] leading-relaxed">
                                    {{ $rec['descripcion'] ?? '' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

        <!-- ========================================== -->
        <!-- 4. VISUAL ANALYTICS CHARTS GRID (6 CARDS) -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- CUADRO 1: Rendimiento de Ventas & Facturación -->
            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.04);" class="rounded-3xl p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div style="background-color: #ecfdf5; color: #047857;" class="w-10 h-10 rounded-2xl flex items-center justify-center font-black shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        </div>
                        <div>
                            <h3 style="color: #0f172a; font-weight: 900;" class="text-sm">Rendimiento de Ventas & Facturación</h3>
                            <p style="color: #64748b; font-weight: 700;" class="text-[11px]">Evolución monetaria de cobros en el tiempo</p>
                        </div>
                    </div>
                    <span style="color: #047857; background-color: #ecfdf5; border: 1px solid #a7f3d0;" class="text-xs font-black px-3 py-1 rounded-xl shadow-xs">
                        ${{ number_format($analytics['kpis']['total_recaudado'], 2) }} USD
                    </span>
                </div>
                <div id="chartVentas" class="w-full h-72"></div>
            </div>

            <!-- CUADRO 2: Trámites más Usados -->
            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.04);" class="rounded-3xl p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div style="background-color: #eef2ff; color: #4338ca;" class="w-10 h-10 rounded-2xl flex items-center justify-center font-black shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        </div>
                        <div>
                            <h3 style="color: #0f172a; font-weight: 900;" class="text-sm">Trámites más Usados</h3>
                            <p style="color: #64748b; font-weight: 700;" class="text-[11px]">Distribución por categoría y motivos más recurrentes</p>
                        </div>
                    </div>
                    <span style="color: #4338ca; background-color: #eef2ff; border: 1px solid #c7d2fe;" class="text-xs font-black px-3 py-1 rounded-xl shadow-xs">
                        {{ $analytics['kpis']['total_tramites'] }} casos
                    </span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                    <div id="chartTramites" class="h-64 flex items-center justify-center"></div>
                    <div class="space-y-2">
                        <span style="color: #334155; font-weight: 900;" class="text-[10px] uppercase tracking-wider">Top Motivos Frecuentes:</span>
                        <div class="space-y-1.5" id="topMotivosContainer">
                            @forelse($analytics['top_motivos'] as $tm)
                                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0;" class="flex items-center justify-between p-2.5 rounded-xl text-xs hover:bg-slate-100 transition-all">
                                    <div class="truncate mr-2">
                                        <span style="color: #0f172a; font-weight: 800;" class="block truncate">{{ $tm['nombre'] }}</span>
                                        <span style="color: #64748b; font-weight: 700;" class="text-[10px]">{{ $tm['tipo'] }}</span>
                                    </div>
                                    <span style="background-color: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe;" class="px-2 py-0.5 rounded-lg font-black text-[10px] shrink-0">
                                        {{ $tm['cantidad'] }}
                                    </span>
                                </div>
                            @empty
                                <p style="color: #64748b;" class="text-xs">Sin trámites registrados en este periodo.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- CUADRO 3: Métodos de Pago & Ingresos por Método -->
            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.04);" class="rounded-3xl p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div style="background-color: #ecfeff; color: #0e7490;" class="w-10 h-10 rounded-2xl flex items-center justify-center font-black shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <h3 style="color: #0f172a; font-weight: 900;" class="text-sm">Métodos de Pago & Montos Recaudados</h3>
                            <p style="color: #64748b; font-weight: 700;" class="text-[11px]">Volumen ($ USD) vs Cantidad de Operaciones</p>
                        </div>
                    </div>
                </div>
                <div id="chartMetodosPago" class="w-full h-72"></div>
            </div>

            <!-- CUADRO 4: Rendimiento y Productividad de Usuarios -->
            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.04);" class="rounded-3xl p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div style="background-color: #fdf4ff; color: #7e22ce;" class="w-10 h-10 rounded-2xl flex items-center justify-center font-black shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 style="color: #0f172a; font-weight: 900;" class="text-sm">Rendimiento de los Usuarios</h3>
                            <p style="color: #64748b; font-weight: 700;" class="text-[11px]">Ranking por Score de Eficiencia e Impacto IA</p>
                        </div>
                    </div>
                    <span style="color: #7e22ce; background-color: #fdf4ff; border: 1px solid #f0abfc;" class="text-xs font-black px-3 py-1 rounded-xl shadow-xs">
                        👑 Top: {{ $analytics['kpis']['top_usuario'] }}
                    </span>
                </div>
                <div id="chartUsuarios" class="w-full h-72"></div>
            </div>

            <!-- CUADRO 5: Horas de Inicio de Sesión & Concurrencia -->
            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.04);" class="rounded-3xl p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div style="background-color: #fffbeb; color: #b45309;" class="w-10 h-10 rounded-2xl flex items-center justify-center font-black shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 style="color: #0f172a; font-weight: 900;" class="text-sm">Horas de Inicio de Sesión de cada Usuario</h3>
                            <p style="color: #64748b; font-weight: 700;" class="text-[11px]">Distribución de 00:00 a 23:00 con horarios de mayor afluencia</p>
                        </div>
                    </div>
                    <span style="color: #92400e; background-color: #fef3c7; border: 1px solid #fde68a;" class="text-xs font-black px-3 py-1 rounded-xl shadow-xs">
                        🔥 {{ $analytics['kpis']['hora_pico'] }}
                    </span>
                </div>
                <div id="chartHorasSesion" class="w-full h-72"></div>
            </div>

            <!-- CUADRO 6: Oficinas que más se Mueven -->
            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.04);" class="rounded-3xl p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div style="background-color: #fff1f2; color: #be123c;" class="w-10 h-10 rounded-2xl flex items-center justify-center font-black shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 style="color: #0f172a; font-weight: 900;" class="text-sm">Oficinas que más se Mueven</h3>
                            <p style="color: #64748b; font-weight: 700;" class="text-[11px]">Comparativa de volumen, recaudación y clientes por sede</p>
                        </div>
                    </div>
                    <span style="color: #be123c; background-color: #fff1f2; border: 1px solid #fecdd3;" class="text-xs font-black px-3 py-1 rounded-xl shadow-xs">
                        📍 Sede Top: {{ $analytics['kpis']['top_oficina'] }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                    <div id="chartOficinas" class="h-64 flex items-center justify-center"></div>
                    <div class="space-y-2.5" id="oficinasCardsContainer">
                        @foreach($analytics['oficinas_rendimiento'] as $ofi)
                            <div style="background-color: #f8fafc; border: 1.5px solid #e2e8f0;" class="p-3.5 rounded-2xl hover:bg-slate-100 transition-all">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span style="color: #0f172a; font-weight: 900;" class="text-xs">🏢 {{ $ofi['nombre'] }}</span>
                                    <span style="color: #3730a3; background-color: #eef2ff; border: 1px solid #c7d2fe; font-weight: 900;" class="text-xs px-2 py-0.5 rounded-lg">{{ $ofi['porcentaje'] }}%</span>
                                </div>
                                <div style="border-top: 1px solid #cbd5e1;" class="grid grid-cols-3 gap-2 text-[10px] font-semibold pt-1.5">
                                    <div style="color: #475569;">Trámites: <strong style="color: #0f172a; font-weight: 900;" class="block text-xs">{{ $ofi['total_tramites'] }}</strong></div>
                                    <div style="color: #475569;">Recaudado: <strong style="color: #047857; font-weight: 900;" class="block text-xs">${{ number_format($ofi['total_recaudado'], 0) }}</strong></div>
                                    <div style="color: #475569;">Clientes: <strong style="color: #0f172a; font-weight: 900;" class="block text-xs">{{ $ofi['total_clientes'] }}</strong></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- 5. DETAILED AUDIT & PERFORMANCE TABLES     -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- TABLA 1: Escalafón y Rendimiento de Colaboradores -->
            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.04);" class="rounded-3xl overflow-hidden">
                <div style="background-color: #f8fafc; border-bottom: 1.5px solid #e2e8f0;" class="p-5 flex items-center justify-between">
                    <div>
                        <h3 style="color: #0f172a; font-weight: 900;" class="text-sm">Escalafón de Productividad por Colaborador</h3>
                        <p style="color: #64748b; font-weight: 600;" class="text-xs">Trámites procesados, recaudación directa y efectividad de caja</p>
                    </div>
                    <span style="background-color: #eef2ff; color: #3730a3; border: 1px solid #c7d2fe; font-weight: 800;" class="text-xs px-2.5 py-1 rounded-xl">
                        {{ count($analytics['usuarios_rendimiento']) }} Colaboradores
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr style="background-color: #f1f5f9; color: #334155; font-weight: 900; border-bottom: 1.5px solid #cbd5e1;" class="text-[10px] uppercase tracking-wider">
                                <th class="p-3.5">#</th>
                                <th class="p-3.5">Colaborador</th>
                                <th class="p-3.5 text-center">Trámites</th>
                                <th class="p-3.5 text-right">Recaudación</th>
                                <th class="p-3.5 text-center">Cajas (OK)</th>
                                <th class="p-3.5 text-center">Score IA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-xs" id="usuariosTableBody">
                            @foreach($analytics['usuarios_rendimiento'] as $idx => $usr)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-3.5 font-black">
                                        @if($idx === 0) 
                                            <span style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;" class="w-6 h-6 rounded-full inline-flex items-center justify-center font-black text-xs">🥇</span>
                                        @elseif($idx === 1) 
                                            <span style="background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1;" class="w-6 h-6 rounded-full inline-flex items-center justify-center font-black text-xs">🥈</span>
                                        @elseif($idx === 2) 
                                            <span style="background-color: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;" class="w-6 h-6 rounded-full inline-flex items-center justify-center font-black text-xs">🥉</span>
                                        @else 
                                            <span style="color: #64748b; font-weight: 800;" class="ml-1.5">{{ $idx + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3.5">
                                        <div style="color: #0f172a; font-weight: 900;">{{ $usr['name'] }}</div>
                                        <div style="color: #64748b; font-weight: 600;" class="text-[10px]">{{ $usr['role'] }} • {{ $usr['office'] }}</div>
                                    </td>
                                    <td style="color: #3730a3; font-weight: 900;" class="p-3.5 text-center">
                                        {{ $usr['total_tramites'] }}
                                    </td>
                                    <td style="color: #047857; font-weight: 900;" class="p-3.5 text-right">
                                        ${{ number_format($usr['total_recaudado'], 2) }}
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black" style="{{ $usr['efectividad_caja'] >= 80 ? 'background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;' : 'background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a;' }}">
                                            {{ $usr['efectividad_caja'] }}%
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <div style="background-color: #e2e8f0;" class="w-12 rounded-full h-2 overflow-hidden">
                                                <div style="background-color: #4f46e5; width: {{ $usr['score'] }}%;" class="h-2 rounded-full"></div>
                                            </div>
                                            <span style="color: #3730a3; font-weight: 900;" class="text-[11px]">{{ $usr['score'] }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABLA 2: Registro de Inicios de Sesión y Horarios de Usuarios -->
            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.04);" class="rounded-3xl overflow-hidden">
                <div style="background-color: #f8fafc; border-bottom: 1.5px solid #e2e8f0;" class="p-5 flex items-center justify-between">
                    <div>
                        <h3 style="color: #0f172a; font-weight: 900;" class="text-sm">Auditoría de Inicios de Sesión & Conexiones</h3>
                        <p style="color: #64748b; font-weight: 600;" class="text-xs">Horarios frecuentes de conexión y última actividad registrada</p>
                    </div>
                    <span style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-weight: 800;" class="text-xs px-2.5 py-1 rounded-xl">
                        Accesos Registrados
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr style="background-color: #f1f5f9; color: #334155; font-weight: 900; border-bottom: 1.5px solid #cbd5e1;" class="text-[10px] uppercase tracking-wider">
                                <th class="p-3.5">Usuario</th>
                                <th class="p-3.5 text-center">Hora Frecuente</th>
                                <th class="p-3.5 text-center">Total Inicios</th>
                                <th class="p-3.5 text-right">Última Conexión</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-xs" id="loginsTableBody">
                            @foreach($analytics['usuarios_login_stats'] as $uls)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-3.5">
                                        <div style="color: #0f172a; font-weight: 900;">{{ $uls['name'] }}</div>
                                        <div style="color: #64748b; font-weight: 600;" class="text-[10px]">{{ $uls['role'] }} • {{ $uls['office'] }}</div>
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <span style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-weight: 900;" class="px-2.5 py-1 rounded-xl text-[11px] shadow-2xs">
                                            ⏰ {{ $uls['hora_mas_frecuente'] }}
                                        </span>
                                    </td>
                                    <td style="color: #0f172a; font-weight: 900;" class="p-3.5 text-center">
                                        {{ $uls['total_logins'] }}
                                    </td>
                                    <td style="color: #334155; font-weight: 700;" class="p-3.5 text-right text-[11px]">
                                        {{ $uls['ultimo_login'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- ========================================== -->
    <!-- 6. DASHBOARD & APEXCHARTS ENGINE SCRIPT    -->
    <!-- ========================================== -->
    <script>
        let analyticsData = @json($analytics);
        let aiReportData = @json($aiReport);

        let chartVentasInstance = null;
        let chartTramitesInstance = null;
        let chartMetodosPagoInstance = null;
        let chartUsuariosInstance = null;
        let chartHorasSesionInstance = null;
        let chartOficinasInstance = null;

        document.addEventListener('DOMContentLoaded', function () {
            renderAllCharts(analyticsData);
        });

        function scrollToAiSection() {
            const el = document.getElementById('aiSection');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function setPeriod(period) {
            document.getElementById('periodoInput').value = period;
            document.querySelectorAll('.period-btn').forEach(btn => {
                if (btn.getAttribute('data-period') === period) {
                    btn.style.backgroundColor = "#4f46e5";
                    btn.style.color = "#ffffff";
                    btn.style.fontWeight = "900";
                } else {
                    btn.style.backgroundColor = "transparent";
                    btn.style.color = "#334155";
                    btn.style.fontWeight = "700";
                }
            });
            applyFilters();
        }

        function onCustomDateChange() {
            document.getElementById('periodoInput').value = 'custom';
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.style.backgroundColor = "transparent";
                btn.style.color = "#334155";
                btn.style.fontWeight = "700";
            });
            applyFilters();
        }

        function applyFilters() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);

            fetch("{{ route('dashboard.filter') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    analyticsData = data.analytics;
                    aiReportData = data.ai_report;
                    updateDashboardDom(analyticsData, aiReportData);
                    renderAllCharts(analyticsData);
                }
            })
            .catch(err => console.error('Error actualizando dashboard:', err));
        }

        function updateDashboardDom(analytics, aiReport) {
            // Update KPIs
            document.getElementById('kpiTotalRecaudado').innerText = '$' + Number(analytics.kpis.total_recaudado).toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('kpiTotalTramites').innerText = analytics.kpis.total_tramites;
            document.getElementById('kpiTotalClientes').innerText = analytics.kpis.total_clientes;
            document.getElementById('kpiCuadreRate').innerText = analytics.kpis.porcentaje_cuadre + '%';
            document.getElementById('kpiCajasCuadradas').innerText = analytics.kpis.cajas_cuadradas + ' OK';
            document.getElementById('kpiCajasDescuadradas').innerText = analytics.kpis.cajas_descuadradas + ' Descuadre';
            document.getElementById('kpiTopOficina').innerText = '🏢 ' + analytics.kpis.top_oficina;
            document.getElementById('kpiHoraPico').innerText = '⚡ ' + analytics.kpis.hora_pico;

            // Update AI Hub
            if (aiReport) {
                document.getElementById('aiEngineLabel').innerText = (aiReport.engine || 'Motor de IA NESISTEMA') + ' • Actualizado: ' + (aiReport.generated_at || '');
                document.getElementById('aiExecutiveSummary').innerHTML = markdownToHtml(aiReport.executive_summary || '');

                // Highlights
                const highList = document.getElementById('aiHighlightsList');
                highList.innerHTML = (aiReport.strategic_highlights || []).map(h => `
                    <li style="color: #14532d; font-weight: 600;" class="flex items-start gap-2.5 leading-relaxed">
                        <span style="color: #15803d; font-weight: 900;" class="shrink-0 mt-0.5 text-sm">✓</span>
                        <span style="color: #14532d;">${markdownToHtml(h)}</span>
                    </li>
                `).join('');

                // Bottlenecks
                const botList = document.getElementById('aiBottlenecksList');
                botList.innerHTML = (aiReport.operational_bottlenecks || []).map(b => `
                    <li style="color: #78350f; font-weight: 600;" class="flex items-start gap-2.5 leading-relaxed">
                        <span style="color: #b45309; font-weight: 900;" class="shrink-0 mt-0.5 text-sm">⚠️</span>
                        <span style="color: #78350f;">${markdownToHtml(b)}</span>
                    </li>
                `).join('');

                // Recommendations
                const recList = document.getElementById('aiRecommendationsList');
                recList.innerHTML = (aiReport.actionable_recommendations || []).map(r => `
                    <div style="background-color: #ffffff; border: 1.5px solid #f472b6;" class="p-3 rounded-xl shadow-2xs">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span style="color: #0f172a; font-weight: 900;" class="text-xs">${r.titulo || 'Acción Sugerida'}</span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider" style="background-color: ${r.color || '#dc2626'}20; color: ${r.color || '#dc2626'}; border: 1px solid ${r.color || '#dc2626'};">
                                ${r.prioridad || 'Media'}
                            </span>
                        </div>
                        <p style="color: #334155; font-weight: 600;" class="text-[11px] leading-relaxed">${r.descripcion || ''}</p>
                    </div>
                `).join('');
            }
        }

        // Render all 6 charts
        function renderAllCharts(data) {
            renderChartVentas(data.ventas_chart);
            renderChartTramites(data.tramites_categorias);
            renderChartMetodosPago(data.metodos_pago_montos, data.metodos_pago_counts);
            renderChartUsuarios(data.usuarios_rendimiento);
            renderChartHorasSesion(data.horas_distribucion);
            renderChartOficinas(data.oficinas_rendimiento);
        }

        // 1. Chart Ventas
        function renderChartVentas(chartData) {
            const options = {
                series: [{
                    name: 'Facturación ($ USD)',
                    data: chartData.ingresos || []
                }],
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: { show: false },
                    fontFamily: 'inherit',
                    zoom: { enabled: false }
                },
                colors: ['#059669'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3.5 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [10, 100]
                    }
                },
                xaxis: {
                    categories: chartData.labels || [],
                    labels: { style: { fontSize: '11px', fontWeight: 700, colors: '#475569' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: val => '$' + Number(val).toFixed(0),
                        style: { fontSize: '11px', fontWeight: 700, colors: '#475569' }
                    }
                },
                grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
                tooltip: {
                    y: { formatter: val => '$' + Number(val).toFixed(2) + ' USD' }
                }
            };

            if (chartVentasInstance) {
                chartVentasInstance.destroy();
            }
            chartVentasInstance = new ApexCharts(document.querySelector("#chartVentas"), options);
            chartVentasInstance.render();
        }

        // 2. Chart Trámites
        function renderChartTramites(categorias) {
            const labels = (categorias || []).map(c => c.categoria);
            const series = (categorias || []).map(c => c.cantidad);
            const colors = (categorias || []).map(c => c.color);

            const options = {
                series: series.length > 0 ? series : [1],
                labels: labels.length > 0 ? labels : ['Sin trámites'],
                colors: colors.length > 0 ? colors : ['#94a3b8'],
                chart: {
                    type: 'donut',
                    height: 250,
                    fontFamily: 'inherit'
                },
                dataLabels: { enabled: false },
                legend: { show: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Casos',
                                    fontSize: '12px',
                                    fontWeight: 900,
                                    color: '#0f172a',
                                    formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    }
                },
                stroke: { width: 3, colors: ['#ffffff'] }
            };

            if (chartTramitesInstance) {
                chartTramitesInstance.destroy();
            }
            chartTramitesInstance = new ApexCharts(document.querySelector("#chartTramites"), options);
            chartTramitesInstance.render();
        }

        // 3. Chart Métodos de Pago
        function renderChartMetodosPago(montos, counts) {
            const categories = Object.keys(montos || {});
            const montoValues = Object.values(montos || {});
            const countValues = categories.map(c => (counts && counts[c]) ? counts[c] : 0);

            const options = {
                series: [
                    { name: 'Monto Recaudado ($ USD)', data: montoValues },
                    { name: 'N° de Operaciones', data: countValues }
                ],
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                colors: ['#0891b2', '#7c3aed'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 6
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: categories,
                    labels: { style: { fontSize: '11px', fontWeight: 700, colors: '#475569' } }
                },
                yaxis: {
                    labels: {
                        formatter: val => Number(val).toFixed(0),
                        style: { fontSize: '11px', fontWeight: 700, colors: '#475569' }
                    }
                },
                grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
                legend: { position: 'top', fontSize: '11px', fontWeight: 800 }
            };

            if (chartMetodosPagoInstance) {
                chartMetodosPagoInstance.destroy();
            }
            chartMetodosPagoInstance = new ApexCharts(document.querySelector("#chartMetodosPago"), options);
            chartMetodosPagoInstance.render();
        }

        // 4. Chart Usuarios
        function renderChartUsuarios(usuarios) {
            const top6 = (usuarios || []).slice(0, 6);
            const names = top6.map(u => u.name.split(' ')[0] + ' ' + (u.name.split(' ')[1] || ''));
            const scores = top6.map(u => u.score);

            const options = {
                series: [{
                    name: 'Score de Eficiencia IA',
                    data: scores
                }],
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                colors: ['#6366f1'],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 8,
                        barHeight: '55%'
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: val => val + ' pts',
                    style: { fontSize: '11px', fontWeight: 900, colors: ['#ffffff'] }
                },
                xaxis: {
                    categories: names,
                    labels: { style: { fontSize: '11px', fontWeight: 700, colors: '#475569' } },
                    max: 100
                },
                grid: { borderColor: '#e2e8f0', strokeDashArray: 4 }
            };

            if (chartUsuariosInstance) {
                chartUsuariosInstance.destroy();
            }
            chartUsuariosInstance = new ApexCharts(document.querySelector("#chartUsuarios"), options);
            chartUsuariosInstance.render();
        }

        // 5. Chart Horas de Sesión
        function renderChartHorasSesion(horas) {
            const categories = [];
            for (let h = 0; h < 24; h++) {
                categories.push((h < 10 ? '0' : '') + h + 'h');
            }

            const options = {
                series: [{
                    name: 'Inicios de Sesión',
                    data: horas || []
                }],
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                colors: ['#d97706'],
                plotOptions: {
                    bar: {
                        borderRadius: 5,
                        columnWidth: '65%'
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: categories,
                    labels: { style: { fontSize: '10px', fontWeight: 700, colors: '#475569' } }
                },
                yaxis: {
                    labels: {
                        formatter: val => Number(val).toFixed(0),
                        style: { fontSize: '11px', fontWeight: 700, colors: '#475569' }
                    }
                },
                grid: { borderColor: '#e2e8f0', strokeDashArray: 4 }
            };

            if (chartHorasSesionInstance) {
                chartHorasSesionInstance.destroy();
            }
            chartHorasSesionInstance = new ApexCharts(document.querySelector("#chartHorasSesion"), options);
            chartHorasSesionInstance.render();
        }

        // 6. Chart Oficinas
        function renderChartOficinas(oficinas) {
            const names = (oficinas || []).map(o => o.nombre);
            const series = (oficinas || []).map(o => o.activity_volume || o.total_tramites || 1);

            const options = {
                series: series.length > 0 ? series : [1],
                labels: names.length > 0 ? names : ['Brooklyn'],
                chart: {
                    type: 'polarArea',
                    height: 250,
                    fontFamily: 'inherit'
                },
                colors: ['#e11d48', '#4f46e5', '#059669', '#d97706'],
                stroke: { colors: ['#ffffff'], width: 3 },
                fill: { opacity: 0.9 },
                legend: { show: false },
                yaxis: { show: false }
            };

            if (chartOficinasInstance) {
                chartOficinasInstance.destroy();
            }
            chartOficinasInstance = new ApexCharts(document.querySelector("#chartOficinas"), options);
            chartOficinasInstance.render();
        }

        // Copy AI Diagnostic
        function copyAiSummary() {
            const text = document.getElementById('aiExecutiveSummary').innerText;
            navigator.clipboard.writeText(text).then(() => {
                const btnText = document.getElementById('copyBtnText');
                btnText.innerText = "¡Copiado!";
                setTimeout(() => { btnText.innerText = "Copiar Diagnóstico"; }, 2000);
            });
        }

        // Regenerate AI Analysis Button
        function regenerateAiAnalysis() {
            const btn = document.getElementById('btnRegenerateAi');
            const icon = document.getElementById('aiSpinnerIcon');
            icon.classList.add('animate-spin');
            btn.disabled = true;

            applyFilters();

            setTimeout(() => {
                icon.classList.remove('animate-spin');
                btn.disabled = false;
            }, 1000);
        }

        // Download complete analytical dashboard to Excel respecting active filters
        function exportExcel() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            window.location.href = "{{ route('dashboard.export') }}?" + params.toString();
        }

        // High-Contrast Markdown to HTML parser
        function markdownToHtml(md) {
            if (!md) return '';
            let html = md
                .replace(/^### (.*$)/gim, '<h3 style="color: #0f172a; font-weight: 900; margin-top: 8px; margin-bottom: 4px; font-size: 14px;">$1</h3>')
                .replace(/^## (.*$)/gim, '<h2 style="color: #0f172a; font-weight: 900; margin-top: 10px; margin-bottom: 6px; font-size: 16px;">$1</h2>')
                .replace(/^# (.*$)/gim, '<h1 style="color: #0f172a; font-weight: 900; margin-top: 12px; margin-bottom: 8px; font-size: 18px;">$1</h1>')
                .replace(/^\> (.*$)/gim, '<blockquote style="border-left: 4px solid #4f46e5; background-color: #eef2ff; color: #1e1b4b; font-weight: 700; padding: 8px 12px; border-radius: 8px; margin: 8px 0; font-size: 12px;">$1</blockquote>')
                .replace(/\*\*(.*?)\*\*/gim, '<strong style="color: #0f172a; font-weight: 900;">$1</strong>')
                .replace(/\*(.*?)\*/gim, '<em style="font-style: italic; font-weight: 600;">$1</em>')
                .replace(/^- (.*$)/gim, '<li style="margin-left: 16px; list-style-type: disc; color: #0f172a; font-weight: 600; margin-bottom: 2px;">$1</li>')
                .replace(/\n\n/gim, '<br>');
            return html;
        }
    </script>
</x-app-layout>
