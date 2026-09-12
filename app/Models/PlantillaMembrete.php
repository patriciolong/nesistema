<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantillaMembrete extends Model
{
    protected $table = 'plantilla_membretes';

    protected $fillable = [
        'nombre',
        'tipo',
        'contenido_html',
        'datos_json',
        'es_predeterminado',
        'activo',
    ];

    protected $casts = [
        'datos_json' => 'array',
        'es_predeterminado' => 'boolean',
        'activo' => 'boolean',
    ];

    /**
     * Scope for encabezados
     */
    public function scopeEncabezados($query)
    {
        return $query->where('tipo', 'encabezado')->where('activo', true);
    }

    /**
     * Scope for pies
     */
    public function scopePies($query)
    {
        return $query->where('tipo', 'pie')->where('activo', true);
    }

    /**
     * Relación con las plantillas que usan este membrete como encabezado
     */
    public function plantillasEncabezado()
    {
        return $this->hasMany(Plantilla::class, 'encabezado_id', 'id_plantilla');
    }

    /**
     * Relación con las plantillas que usan este membrete como pie
     */
    public function plantillasPie()
    {
        return $this->hasMany(Plantilla::class, 'pie_id', 'id_plantilla');
    }
}
