<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DashboardHorariosSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    protected array $analytics;

    public function __construct(array $analytics)
    {
        $this->analytics = $analytics;
    }

    public function title(): string
    {
        return 'Horarios & Inicios de Sesión';
    }

    public function array(): array
    {
        $horas = $this->analytics['horas_distribucion'] ?? [];
        $loginsStats = $this->analytics['usuarios_login_stats'] ?? [];
        $horaPico = $this->analytics['kpis']['hora_pico'] ?? 'N/A';

        $rows = [
            ['AUDITORÍA DE INICIOS DE SESIÓN & HORARIOS DE CONCURRENCIA - NESISTEMA 2.0'],
            ['Hora Pico del Sistema:', $horaPico],
            [''],
            ['--- 1. AUDITORÍA DE INICIOS DE SESIÓN POR USUARIO ---'],
            ['Colaborador', 'Rol', 'Sede', 'Hora Más Frecuente de Conexión', 'Total Inicios de Sesión', 'Último Acceso Registrado'],
        ];

        foreach ($loginsStats as $ls) {
            $rows[] = [
                $ls['name'] ?? '',
                $ls['role'] ?? '',
                $ls['office'] ?? '',
                $ls['hora_mas_frecuente'] ?? '',
                $ls['total_logins'] ?? 0,
                $ls['ultimo_login'] ?? 'Sin registro',
            ];
        }

        $rows[] = [''];
        $rows[] = ['--- 2. DISTRIBUCIÓN HORARIA DEL SISTEMA (00:00 - 23:00) ---'];
        $rows[] = ['Franja Horaria', 'Inicios de Sesión Registrados'];

        for ($h = 0; $h < 24; $h++) {
            $label = sprintf('%02d:00 - %02d:00', $h, ($h + 1) % 24);
            $cnt = $horas[$h] ?? 0;
            $rows[] = [$label, $cnt];
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
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            ],
        ];
    }
}
