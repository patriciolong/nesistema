<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Helper to safely add an index if it doesn't already exist.
     */
    private function addIndexSafely(string $table, string|array $columns, ?string $indexName = null): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $cols = is_array($columns) ? $columns : [$columns];
        foreach ($cols as $col) {
            if (!Schema::hasColumn($table, $col)) {
                return;
            }
        }

        try {
            Schema::table($table, function (Blueprint $t) use ($columns, $indexName) {
                if ($indexName) {
                    $t->index($columns, $indexName);
                } else {
                    $t->index($columns);
                }
            });
        } catch (\Exception $e) {
            // Index already exists or driver handled, safe to continue
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabla cliente (Legacy / Padrón principal)
        $this->addIndexSafely('cliente', 'c_identificacion', 'idx_cliente_identificacion');
        $this->addIndexSafely('cliente', ['c_nombre', 'c_apellido'], 'idx_cliente_nombre_completo');
        $this->addIndexSafely('cliente', 'c_telefono', 'idx_cliente_telefono');
        $this->addIndexSafely('cliente', 'c_oficina_registro', 'idx_cliente_oficina');
        $this->addIndexSafely('cliente', 'c_saldo', 'idx_cliente_saldo');

        // 2. Tabla pagos
        $this->addIndexSafely('pagos', 'cliente_id', 'idx_pagos_cliente');
        $this->addIndexSafely('pagos', 'caja_sesion_id', 'idx_pagos_caja_sesion');
        $this->addIndexSafely('pagos', 'created_at', 'idx_pagos_created_at');
        $this->addIndexSafely('pagos', 'metodo_pago', 'idx_pagos_metodo');
        $this->addIndexSafely('pagos', 'oficina', 'idx_pagos_oficina');

        // 3. Tabla caja_sesiones
        $this->addIndexSafely('caja_sesiones', 'user_id', 'idx_caja_user');
        $this->addIndexSafely('caja_sesiones', 'estado', 'idx_caja_estado');
        $this->addIndexSafely('caja_sesiones', 'fecha_apertura', 'idx_caja_fecha_apertura');
        $this->addIndexSafely('caja_sesiones', 'oficina', 'idx_caja_oficina');

        // 4. Tabla caja_movimientos
        $this->addIndexSafely('caja_movimientos', 'caja_sesion_id', 'idx_mov_caja_sesion');
        $this->addIndexSafely('caja_movimientos', 'user_id', 'idx_mov_user');
        $this->addIndexSafely('caja_movimientos', 'tipo', 'idx_mov_tipo');
        $this->addIndexSafely('caja_movimientos', 'metodo_pago', 'idx_mov_metodo');
        $this->addIndexSafely('caja_movimientos', 'created_at', 'idx_mov_created_at');

        // 5. Tabla users
        $this->addIndexSafely('users', 'role', 'idx_users_role');
        $this->addIndexSafely('users', 'status', 'idx_users_status');
        $this->addIndexSafely('users', 'office', 'idx_users_office');

        // 6. Tabla tipo_tramites & tramites_personalizados
        $this->addIndexSafely('tipo_tramites', 'activo', 'idx_tt_activo');
        $this->addIndexSafely('tipo_tramites', 'orden', 'idx_tt_orden');
        $this->addIndexSafely('tramites_personalizados', 'id_cliente', 'idx_tp_cliente');
        $this->addIndexSafely('tramites_personalizados', 'tipo_tramite_id', 'idx_tp_tipo');

        // 7. Tabla plantillas & documentos_generados
        $this->addIndexSafely('plantillas', 'categoria', 'idx_plantillas_categoria');
        $this->addIndexSafely('plantillas', 'activo', 'idx_plantillas_activo');
        $this->addIndexSafely('documentos_generados', 'id_cliente', 'idx_docgen_cliente');
        $this->addIndexSafely('documentos_generados', 'plantilla_id', 'idx_docgen_plantilla');

        // 8. Tablas trámites específicos legacy
        $this->addIndexSafely('tramites_varios', 'id_cliente', 'idx_tv_cliente');
        $this->addIndexSafely('tramite_divorcio', 'id_cliente', 'idx_tdiv_cliente');
        $this->addIndexSafely('tramite_impuestos', 'id_cliente', 'idx_timp_cliente');
        $this->addIndexSafely('tramite_poderes', 'id_cliente', 'idx_tpod_cliente');

        // 9. Bancos, tarjetas y oficinas
        $this->addIndexSafely('bancos', 'estado', 'idx_bancos_estado');
        $this->addIndexSafely('tarjetas', 'banco_id', 'idx_tarjetas_banco');
        $this->addIndexSafely('tarjetas', 'estado', 'idx_tarjetas_estado');
        $this->addIndexSafely('oficinas', 'status', 'idx_oficinas_status');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe down - non destructive
    }
};
