<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $table = 'bancos';

    protected $fillable = [
        'nombre',
        'codigo',
        'tipo_cuenta',
        'titular',
        'estado'
    ];

    public function scopeActivos($query)
    {
        return $query->whereIn('estado', ['Activo', 'Activa', 'activo', 'activa', '1'])
                     ->orWhereNull('estado');
    }

    public function tarjetas()
    {
        return $this->hasMany(Tarjeta::class, 'banco_id');
    }

    public function movimientos()
    {
        return $this->hasMany(CajaMovimiento::class, 'banco_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'banco_id');
    }
}
