<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CarteraExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'ID Cliente',
            'Identificación / Cédula',
            'Nombres y Apellidos',
            'Teléfono de Contacto',
            'Correo Electrónico',
            'Oficina de Registro',
            'Total Facturado ($)',
            'Total Abonado / Cobrado ($)',
            'Saldo Pendiente ($)',
            'Estado Cartera',
        ];
    }

    public function map($cliente): array
    {
        $estado = $cliente->c_saldo > 0 ? 'Con Deuda / Crédito Pendiente' : 'Al Día ($0.00)';

        return [
            $cliente->id_cliente,
            $cliente->c_identificacion ?: 'Sin C.I.',
            $cliente->c_nombre . ' ' . $cliente->c_apellido,
            $cliente->c_telefono ?: 'Sin teléfono',
            $cliente->c_email ?: 'Sin email',
            $cliente->c_oficina_registro ?: 'Oficina General',
            number_format($cliente->c_deuda, 2, '.', ''),
            number_format($cliente->c_abonado, 2, '.', ''),
            number_format($cliente->c_saldo, 2, '.', ''),
            $estado,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E293B'] // Slate 800
                ]
            ],
        ];
    }
}
