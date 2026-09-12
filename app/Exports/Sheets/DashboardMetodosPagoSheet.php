<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DashboardMetodosPagoSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    protected array $analytics;

    public function __construct(array $analytics)
    {
        $this->analytics = $analytics;
    }

    public function title(): string
    {
        return 'Métodos de Pago & Ingresos';
    }

    public function array(): array
    {
        $montos = $this->analytics['metodos_pago_montos'] ?? [];
        $counts = $this->analytics['metodos_pago_counts'] ?? [];
        $totalMonto = array_sum($montos) ?: 1;
        $totalOperaciones = array_sum($counts) ?: 1;

        $rows = [
            ['INGRESOS & MÉTODOS DE PAGO - NESISTEMA 2.0'],
            [''],
            [
                'Método de Pago',
                'Monto Recaudado ($ USD)',
                'N° de Operaciones',
                'Participación en Facturación (%)',
                'Participación en Operaciones (%)',
            ]
        ];

        foreach ($montos as $metodo => $monto) {
            $cant = $counts[$metodo] ?? 0;
            $rows[] = [
                $metodo,
                number_format((float)$monto, 2, '.', ''),
                $cant,
                round(($monto / $totalMonto) * 100, 1) . '%',
                round(($cant / $totalOperaciones) * 100, 1) . '%',
            ];
        }

        $rows[] = [
            'TOTAL CONSOLIDADO',
            number_format(array_sum($montos), 2, '.', ''),
            array_sum($counts),
            '100.0%',
            '100.0%',
        ];

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
            9 => [
                'font' => ['bold' => true, 'color' => ['rgb' => '0F172A']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E8F0']],
            ],
        ];
    }
}
