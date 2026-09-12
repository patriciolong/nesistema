<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DashboardUsuariosSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    protected array $analytics;

    public function __construct(array $analytics)
    {
        $this->analytics = $analytics;
    }

    public function title(): string
    {
        return 'Rendimiento Usuarios';
    }

    public function array(): array
    {
        $usuarios = $this->analytics['usuarios_rendimiento'] ?? [];

        $rows = [
            ['ESCALAFÓN & RENDIMIENTO DE COLABORADORES - NESISTEMA 2.0'],
            [''],
            [
                'Posición',
                'Colaborador',
                'Usuario',
                'Rol',
                'Oficina',
                'Trámites Elaborados',
                'Documentos Notariales',
                'Recaudación Directa ($)',
                'Sesiones de Caja',
                'Efectividad Cuadre (%)',
                'Total Inicios de Sesión',
                'Último Acceso Registrado',
                'Score de Eficiencia IA'
            ]
        ];

        foreach ($usuarios as $idx => $u) {
            $rows[] = [
                $idx + 1,
                $u['name'] ?? '',
                $u['username'] ?? '',
                $u['role'] ?? '',
                $u['office'] ?? '',
                $u['total_tramites'] ?? 0,
                $u['total_documentos'] ?? 0,
                number_format($u['total_recaudado'] ?? 0, 2, '.', ''),
                $u['sesiones_caja'] ?? 0,
                ($u['efectividad_caja'] ?? 100) . '%',
                $u['total_logins'] ?? 0,
                $u['ultimo_login'] ?? 'Sin registro',
                ($u['score'] ?? 0) . ' / 100',
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
