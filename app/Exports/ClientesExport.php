<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Database\Eloquent\Builder;

class ClientesExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID', 'Identificación', 'Nombres', 'Apellidos', 'Teléfono',
            'Edad', 'Dirección', 'País', 'Estado', 'Ciudad', 'Cód. Postal',
            'Email', 'N. Apartamento', 'Abonado', 'Deuda', 'Saldo', 'Registrado Por', 'Oficina'
        ];
    }
}
