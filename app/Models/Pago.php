<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'monto',
        'concepto',
        'usuario',
        'oficina',
        'caja_sesion_id',
        'metodo_pago',
        'banco_id',
        'tarjeta_id',
        'numero_referencia',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    public function cajaSesion()
    {
        return $this->belongsTo(CajaSesion::class, 'caja_sesion_id');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function tarjeta()
    {
        return $this->belongsTo(Tarjeta::class, 'tarjeta_id');
    }
}
