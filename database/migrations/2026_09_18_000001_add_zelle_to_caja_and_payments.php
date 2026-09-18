<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Agregar columnas para Zelle en caja_sesiones
        Schema::table('caja_sesiones', function (Blueprint $table) {
            if (!Schema::hasColumn('caja_sesiones', 'total_sistema_zelle')) {
                $table->decimal('total_sistema_zelle', 12, 2)->default(0.00)->after('total_sistema_tarjeta');
            }
            if (!Schema::hasColumn('caja_sesiones', 'monto_cierre_zelle')) {
                $table->decimal('monto_cierre_zelle', 12, 2)->nullable()->after('monto_cierre_tarjeta');
            }
            if (!Schema::hasColumn('caja_sesiones', 'diferencia_zelle')) {
                $table->decimal('diferencia_zelle', 12, 2)->nullable()->default(0.00)->after('diferencia_tarjeta');
            }
        });

        // 2. Modificar el enum de metodo_pago en caja_movimientos para incluir 'Zelle'
        try {
            DB::statement("ALTER TABLE `caja_movimientos` MODIFY COLUMN `metodo_pago` ENUM('Efectivo', 'Cheque', 'Transferencia', 'Tarjeta', 'Crédito', 'Zelle') NOT NULL DEFAULT 'Efectivo'");
        } catch (\Throwable $e) {
            // Fallback si la base de datos no es MySQL estándar
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caja_sesiones', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('caja_sesiones', 'total_sistema_zelle')) {
                $columnsToDrop[] = 'total_sistema_zelle';
            }
            if (Schema::hasColumn('caja_sesiones', 'monto_cierre_zelle')) {
                $columnsToDrop[] = 'monto_cierre_zelle';
            }
            if (Schema::hasColumn('caja_sesiones', 'diferencia_zelle')) {
                $columnsToDrop[] = 'diferencia_zelle';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        try {
            DB::statement("ALTER TABLE `caja_movimientos` MODIFY COLUMN `metodo_pago` ENUM('Efectivo', 'Cheque', 'Transferencia', 'Tarjeta', 'Crédito') NOT NULL DEFAULT 'Efectivo'");
        } catch (\Throwable $e) {
            // Fallback
        }
    }
};
