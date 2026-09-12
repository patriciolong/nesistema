<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $table = 'plantillas';
    protected $primaryKey = 'id_plantilla';

    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'ultima_modificacion';

    protected $fillable = [
        'nombre_plantilla',
        'descripcion',
        'contenido_html',
        'tipo_documento',
        'categoria',
        'encabezado_id',
        'pie_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'encabezado_id' => 'integer',
        'pie_id' => 'integer',
        'fecha_creacion' => 'datetime',
        'ultima_modificacion' => 'datetime',
    ];

    /**
     * Relación con el Encabezado seleccionado
     */
    public function encabezado()
    {
        return $this->belongsTo(PlantillaMembrete::class, 'encabezado_id');
    }

    /**
     * Relación con el Pie de página seleccionado
     */
    public function pie()
    {
        return $this->belongsTo(PlantillaMembrete::class, 'pie_id');
    }

    /**
     * Interpola y reemplaza todas las variables dinámicas de la plantilla con los datos del cliente.
     */
    public function renderForCliente(?Cliente $cliente = null, array $extraData = []): string
    {
        $html = $this->contenido_html ?? '';

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $dia = date('d');
        $mes = $meses[(int)date('m')];
        $anio = date('Y');
        $fechaLarga = "{$dia} de {$mes} de {$anio}";

        $reemplazos = [
            // Fecha y Sistema
            '[[fecha_actual]]' => $fechaLarga,
            '[[fecha_otorgamiento]]' => $fechaLarga,
            '[[fecha_actual_corta]]' => date('d/m/Y'),
            '[[anio_actual]]' => $anio,
            '{{ fecha_actual }}' => $fechaLarga,
            '{{ anio_actual }}' => $anio,

            // Notaría / Notario
            '[[notary_info_name]]' => 'Christian Moreno',
            '[[notario_nombre]]' => 'Christian Moreno, Notario Público',
            '[[notary_info_title]]' => 'Notary Public, State of New York',
            '[[notary_address]]' => 'Brooklyn, New York, USA',
            '[[notary_tel]]' => '+1 (718) 555-0199',
            '[[notary_whatsapp]]' => '+1 (718) 555-0199',
            '[[notary_email]]' => 'info@notarianewyork.com',
            '[[lugar_otorgamiento]]' => 'Kings',
            '[[fecha_otorgamiento_raw_usa]]' => date('F j, Y'),
        ];

        if ($cliente) {
            $nombreCompleto = trim(($cliente->c_nombre ?? '') . ' ' . ($cliente->c_apellido ?? ''));
            $direccionCompleta = trim(($cliente->c_direccion ?? '') . ($cliente->c_napartamento ? ', Apt ' . $cliente->c_napartamento : '') . ($cliente->c_ciudad ? ', ' . $cliente->c_ciudad : '') . ($cliente->c_estado ? ', ' . $cliente->c_estado : ''));

            $reemplazos += [
                // Cliente Personal
                '[[cliente_nombre_completo]]' => $nombreCompleto,
                '[[poderdante_nombre]]' => $nombreCompleto,
                '{{ cliente.nombre_completo }}' => $nombreCompleto,

                '[[cliente_nombre]]' => $cliente->c_nombre ?? '',
                '{{ cliente.nombre }}' => $cliente->c_nombre ?? '',

                '[[cliente_apellido]]' => $cliente->c_apellido ?? '',
                '{{ cliente.apellido }}' => $cliente->c_apellido ?? '',

                '[[cliente_identificacion]]' => $cliente->c_identificacion ?? '',
                '[[poderdante_identificacion]]' => $cliente->c_identificacion ?? '',
                '{{ cliente.identificacion }}' => $cliente->c_identificacion ?? '',

                '[[cliente_telefono]]' => $cliente->c_telefono ?? '',
                '[[poderdante_telefono]]' => $cliente->c_telefono ?? '',
                '[[poderdante_telefono_formatted]]' => $cliente->c_telefono ?? '',
                '{{ cliente.telefono }}' => $cliente->c_telefono ?? '',

                '[[cliente_email]]' => $cliente->c_email ?? '',
                '{{ cliente.email }}' => $cliente->c_email ?? '',

                // Ubicación
                '[[cliente_direccion]]' => $cliente->c_direccion ?? '',
                '[[poderdante_domicilio_calle]]' => $cliente->c_direccion ?? '',
                '{{ cliente.direccion }}' => $cliente->c_direccion ?? '',

                '[[cliente_ciudad]]' => $cliente->c_ciudad ?? 'New York',
                '[[poderdante_domicilio_ciudad]]' => $cliente->c_ciudad ?? 'New York',
                '{{ cliente.ciudad }}' => $cliente->c_ciudad ?? '',

                '[[cliente_estado]]' => $cliente->c_estado ?? 'NY',
                '[[poderdante_domicilio_estado]]' => $cliente->c_estado ?? 'NY',
                '{{ cliente.estado }}' => $cliente->c_estado ?? '',

                '[[cliente_pais]]' => 'Estados Unidos',
                '[[poderdante_domicilio_pais]]' => 'Estados Unidos de América',
                '{{ cliente.pais }}' => 'Estados Unidos',

                '[[cliente_codpostal]]' => $cliente->c_codpostal ?? '',
                '{{ cliente.codpostal }}' => $cliente->c_codpostal ?? '',

                '[[cliente_napartamento]]' => $cliente->c_napartamento ?? '',
                '{{ cliente.apartamento }}' => $cliente->c_napartamento ?? '',

                '[[cliente_direccion_completa]]' => $direccionCompleta,
                '{{ cliente.direccion_completa }}' => $direccionCompleta,

                '[[cliente_deuda]]' => '$' . number_format($cliente->c_deuda ?? 0, 2),
                '[[cliente_saldo]]' => '$' . number_format($cliente->c_saldo ?? 0, 2),
            ];
        }

        // Reemplazar extras si existen
        foreach ($extraData as $k => $v) {
            $reemplazos["[[{$k}]]"] = $v;
            $reemplazos["{{ {$k} }}"] = $v;
        }

        return str_replace(array_keys($reemplazos), array_values($reemplazos), $html);
    }
}
