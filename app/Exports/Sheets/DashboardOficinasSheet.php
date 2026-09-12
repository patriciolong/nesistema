<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DashboardOficinasSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    protected array $analytics;

    public function __construct(array $analytics)
    {
        $this->analytics = $analytics;
    }

    public function title(): string
    {
        return 'Movimiento por Oficinas';
    }

    public function array(): array
    {
        $oficinas = $this->analytics['oficinas_rendimiento'] ?? [];

        $rows = [
            ['COMPARATIVA DE RENDIMIENTO POR OFICINAS & SEDES - NESISTEMA 2.0'],
            [''],
            [
                'Sede / Oficina',
                'Trámites Procesados',
                'Recaudación Total ($ USD)',
                'Sesiones de Caja',
                'Clientes Registrados',
                'Personal Asignado',
                'Participación en Actividad (%)'
            ]
        ];

        foreach ($oficinas as $ofi) {
            $rows[] = [
                $ofi['nombre'] ?? '',
                $ofi['total_tramites'] ?? 0,
                number_format((float)($ofi['total_recaudado'] ?? 0), 2, '.', ''),
                $ofi['total_cajas'] ?? 0,
                $ofi['total_clientes'] ?? 0,
                $ofi['staff_count'] ?? 0,
                ($ofi['porcentaje'] ?? 0) . '%',
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
            3 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            ],
        ];
    }
}
