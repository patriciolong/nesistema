<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DashboardTramitesSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    protected array $analytics;

    public function __construct(array $analytics)
    {
        $this->analytics = $analytics;
    }

    public function title(): string
    {
        return 'Trámites & Servicios';
    }

    public function array(): array
    {
        $categorias = $this->analytics['tramites_categorias'] ?? [];
        $topMotivos = $this->analytics['top_motivos'] ?? [];

        $rows = [
            ['TRÁMITES MÁS USADOS & DEMANDA POR SERVICIOS - NESISTEMA 2.0'],
            [''],
            ['--- 1. DISTRIBUCIÓN POR CATEGORÍA DE TRÁMITE ---'],
            ['Categoría de Trámite', 'Cantidad de Casos', 'Participación (%)'],
        ];

        foreach ($categorias as $cat) {
            $rows[] = [
                $cat['categoria'] ?? '',
                $cat['cantidad'] ?? 0,
                ($cat['porcentaje'] ?? 0) . '%',
            ];
        }

        $rows[] = [''];
        $rows[] = ['--- 2. TOP MOTIVOS ESPECÍFICOS MÁS DEMANDADOS ---'];
        $rows[] = ['Motivo / Razón del Trámite', 'Tipo de Servicio', 'Cantidad de Solicitudes'];

        foreach ($topMotivos as $tm) {
            $rows[] = [
                $tm['nombre'] ?? '',
                $tm['tipo'] ?? '',
                $tm['cantidad'] ?? 0,
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            ],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            ],
            13 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            ],
        ];
    }
}
