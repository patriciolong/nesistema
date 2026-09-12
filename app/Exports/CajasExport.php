<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CajasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID Caja',
            'Cajero / Usuario',
            'Oficina',
            'Fecha Apertura',
            'Fecha Cierre',
            'Estado',
            'Fondo Inicial ($)',
            'Total Sistema ($)',
            'Efectivo Sistema ($)',
            'Tarjeta Sistema ($)',
            'Transferencia Sistema ($)',
            'Cheque Sistema ($)',
            'Total Cierre Declarado ($)',
            'Diferencia ($)',
            '¿Cuadrado?',
            'Observaciones Cierre'
        ];
    }

    public function map($caja): array
    {
        return [
            $caja->id,
            $caja->user ? $caja->user->name : 'N/A',
            $caja->oficina ?? 'General',
            $caja->fecha_apertura ? $caja->fecha_apertura->format('d/m/Y H:i') : '',
            $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y H:i') : 'En curso',
            ucfirst($caja->estado),
            number_format($caja->monto_apertura, 2, '.', ''),
            number_format($caja->total_sistema_total, 2, '.', ''),
            number_format($caja->total_sistema_efectivo, 2, '.', ''),
            number_format($caja->total_sistema_tarjeta, 2, '.', ''),
            number_format($caja->total_sistema_transferencia, 2, '.', ''),
            number_format($caja->total_sistema_cheque, 2, '.', ''),
            $caja->monto_cierre_total !== null ? number_format($caja->monto_cierre_total, 2, '.', '') : 'N/A',
            $caja->diferencia_total !== null ? number_format($caja->diferencia_total, 2, '.', '') : '0.00',
            $caja->cuadrado ? 'SÍ' : ($caja->estado === 'cerrada' ? 'NO' : 'Pendiente'),
            $caja->observaciones_cierre ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1E293B']]],
        ];
    }
}
