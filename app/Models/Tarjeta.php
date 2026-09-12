<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use HasFactory;

    protected $table = 'tarjetas';

    protected $fillable = [
        'banco_id',
        'nombre',
        'tipo',
        'franquicia',
        'ultimos_digitos',
        'estado'
    ];

    public function scopeActivas($query)
    {
        return $query->whereIn('estado', ['Activo', 'Activa', 'activo', 'activa', '1'])
                     ->orWhereNull('estado');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function movimientos()
    {
        return $this->hasMany(CajaMovimiento::class, 'tarjeta_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'tarjeta_id');
    }
}
