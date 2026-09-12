<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoTramite extends Model
{
    protected $table = 'tipo_tramites';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'color_gradient',
        'icono',
        'campos',
        'orden',
        'activo',
        'created_by',
    ];

    protected $casts = [
        'campos' => 'array',
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    public function tramites(): HasMany
    {
        return $this->hasMany(TramitePersonalizado::class, 'tipo_tramite_id');
    }
}
