<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaSesion extends Model
{
    use HasFactory;

    protected $table = 'caja_sesiones';

    protected $fillable = [
        'user_id',
        'oficina',
        'monto_apertura',
        'fecha_apertura',
        'fecha_cierre',
        'estado',
        'total_sistema_efectivo',
        'total_sistema_cheque',
        'total_sistema_transferencia',
        'total_sistema_tarjeta',
        'total_sistema_credito',
        'total_sistema_ingresos_extra',
        'total_sistema_egresos_extra',
        'total_sistema_total',
        'monto_cierre_efectivo',
        'monto_cierre_cheque',
        'monto_cierre_transferencia',
        'monto_cierre_tarjeta',
        'monto_cierre_credito',
        'monto_cierre_total',
        'diferencia_efectivo',
        'diferencia_cheque',
        'diferencia_transferencia',
        'diferencia_tarjeta',
        'diferencia_total',
        'cuadrado',
        'observaciones_apertura',
        'observaciones_cierre',
    ];

    protected function casts(): array
    {
        return [
            'fecha_apertura' => 'datetime',
            'fecha_cierre' => 'datetime',
            'monto_apertura' => 'decimal:2',
            'total_sistema_efectivo' => 'decimal:2',
            'total_sistema_cheque' => 'decimal:2',
            'total_sistema_transferencia' => 'decimal:2',
            'total_sistema_tarjeta' => 'decimal:2',
            'total_sistema_credito' => 'decimal:2',
            'total_sistema_ingresos_extra' => 'decimal:2',
            'total_sistema_egresos_extra' => 'decimal:2',
            'total_sistema_total' => 'decimal:2',
            'monto_cierre_efectivo' => 'decimal:2',
            'monto_cierre_cheque' => 'decimal:2',
            'monto_cierre_transferencia' => 'decimal:2',
            'monto_cierre_tarjeta' => 'decimal:2',
            'monto_cierre_credito' => 'decimal:2',
            'monto_cierre_total' => 'decimal:2',
            'diferencia_efectivo' => 'decimal:2',
            'diferencia_cheque' => 'decimal:2',
            'diferencia_transferencia' => 'decimal:2',
            'diferencia_tarjeta' => 'decimal:2',
            'diferencia_total' => 'decimal:2',
            'cuadrado' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function movimientos()
    {
        return $this->hasMany(CajaMovimiento::class, 'caja_sesion_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'caja_sesion_id');
    }

    /**
     * Helper to get current total cash in hand for this session
     */
    public function getEfectivoActualAttribute(): float
    {
        $ingresosEfectivo = $this->movimientos()
            ->whereIn('tipo', ['ingreso_tramite', 'ingreso_abono', 'ingreso_extra'])
            ->where('metodo_pago', 'Efectivo')
            ->sum('monto');

        $egresosEfectivo = $this->movimientos()
            ->whereIn('tipo', ['egreso_gasto', 'egreso_retiro'])
            ->where('metodo_pago', 'Efectivo')
            ->sum('monto');

        return (float) $this->monto_apertura + (float) $ingresosEfectivo - (float) $egresosEfectivo;
    }
}
