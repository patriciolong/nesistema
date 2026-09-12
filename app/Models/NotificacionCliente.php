<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificacionCliente extends Model
{
    use HasFactory;

    protected $table = 'notificaciones_cliente';

    protected $fillable = [
        'id_cliente',
        'tramite_tipo',
        'tramite_id',
        'titulo',
        'mensaje',
        'leido',
        'data_extra',
    ];

    protected $casts = [
        'leido' => 'boolean',
        'data_extra' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
}
