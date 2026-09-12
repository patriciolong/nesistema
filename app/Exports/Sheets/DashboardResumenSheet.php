<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DashboardResumenSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    protected array $analytics;
    protected array $aiReport;

    public function __construct(array $analytics, array $aiReport)
    {
        $this->analytics = $analytics;
        $this->aiReport = $aiReport;
    }

    public function title(): string
    {
        return 'Resumen Ejecutivo & IA';
    }

    public function array(): array
    {
        $kpis = $this->analytics['kpis'] ?? [];
        $filters = $this->analytics['filters'] ?? [];
        
        $rows = [
            ['NESISTEMA 2.0 - REPORTE EJECUTIVO & ANÁLISIS CON IA'],
            ['Generado el:', now()->format('d/m/Y H:i:s'), 'Periodo:', strtoupper($filters['periodo'] ?? 'TODO'), 'Sede:', strtoupper($filters['oficina'] ?? 'TODAS')],
            ['Desde:', $filters['fecha_desde'] ?? '', 'Hasta:', $filters['fecha_hasta'] ?? ''],
            [''],
            ['--- 1. INDICADORES CLAVE DE DESEMPEÑO (KPIS) ---', 'VALOR', 'DETALLES'],
            ['Total Facturación / Recaudado', '$ ' . number_format($kpis['total_recaudado'] ?? 0, 2), ($kpis['total_pagos_count'] ?? 0) . ' Recibos emitidos'],
            ['Ticket Promedio por Cobro', '$ ' . number_format($kpis['ticket_promedio'] ?? 0, 2), 'Promedio por operación'],
            ['Total Trámites Procesados', $kpis['total_tramites'] ?? 0, 'Casos en sistema'],
            ['Cartera de Clientes Activos', $kpis['total_clientes'] ?? 0, 'Base consolidada'],
            ['Total Sesiones de Caja', $kpis['total_cajas'] ?? 0, ($kpis['cajas_cuadradas'] ?? 0) . ' Cuadradas / ' . ($kpis['cajas_descuadradas'] ?? 0) . ' Descuadres'],
            ['Tasa de Cuadre de Cajas', ($kpis['porcentaje_cuadre'] ?? 100) . '%', 'Efectividad en arqueos'],
            ['Colaborador con Mayor Impacto', $kpis['top_usuario'] ?? 'N/A', 'Líder en productividad'],
            ['Sede con Mayor Actividad', $kpis['top_oficina'] ?? 'Brooklyn', 'Principal volumen'],
            ['Horario Pico del Sistema', $kpis['hora_pico'] ?? 'N/A', 'Mayor concurrencia'],
            [''],
            ['--- 2. DIAGNÓSTICO ESTRATÉGICO DE LA IA ---'],
            ['Resumen Ejecutivo:', strip_tags(str_replace(['**', '*'], '', $this->aiReport['executive_summary'] ?? ''))],
            ['Motor de IA Utilizado:', $this->aiReport['engine'] ?? 'Motor de IA NESISTEMA'],
            [''],
            ['--- 3. PATRONES POSITIVOS Y LOGROS ---'],
        ];

        foreach ($this->aiReport['strategic_highlights'] ?? [] as $high) {
            $rows[] = ['✓', strip_tags(str_replace(['**', '*'], '', $high))];
        }

        $rows[] = [''];
        $rows[] = ['--- 4. RIESGOS OPERATIVOS & ALERTAS DE CAJA ---'];
        foreach ($this->aiReport['operational_bottlenecks'] ?? [] as $bot) {
            $rows[] = ['⚠️', strip_tags(str_replace(['**', '*'], '', $bot))];
        }

        $rows[] = [''];
        $rows[] = ['--- 5. PLAN DE ACCIÓN RECOMENDADO POR IA ---', 'PRIORIDAD', 'DESCRIPCIÓN'];
        foreach ($this->aiReport['actionable_recommendations'] ?? [] as $rec) {
            $rows[] = [$rec['titulo'] ?? '', strtoupper($rec['prioridad'] ?? 'MEDIA'), $rec['descripcion'] ?? ''];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            ],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            ],
            16 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F766E']],
            ],
            20 => [
                'font' => ['bold' => true, 'color' => ['rgb' => '166534']],
            ],
        ];
    }
}
