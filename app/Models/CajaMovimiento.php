<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaMovimiento extends Model
{
    use HasFactory;

    protected $table = 'caja_movimientos';

    protected $fillable = [
        'caja_sesion_id',
        'user_id',
        'cliente_id',
        'tipo',
        'monto',
        'metodo_pago',
        'banco_id',
        'tarjeta_id',
        'pago_id',
        'concepto',
        'numero_referencia',
        'tramite_tipo',
        'tramite_id',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
        ];
    }

    public function cajaSesion()
    {
        return $this->belongsTo(CajaSesion::class, 'caja_sesion_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id_cliente');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function tarjeta()
    {
        return $this->belongsTo(Tarjeta::class, 'tarjeta_id');
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}
