<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignId('caja_sesion_id')->nullable()->after('oficina')->constrained('caja_sesiones')->nullOnDelete();
            $table->string('metodo_pago')->default('Efectivo')->after('caja_sesion_id');
            $table->foreignId('banco_id')->nullable()->after('metodo_pago')->constrained('bancos')->nullOnDelete();
            $table->foreignId('tarjeta_id')->nullable()->after('banco_id')->constrained('tarjetas')->nullOnDelete();
            $table->string('numero_referencia')->nullable()->after('tarjeta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['caja_sesion_id']);
            $table->dropForeign(['banco_id']);
            $table->dropForeign(['tarjeta_id']);
            $table->dropColumn(['caja_sesion_id', 'metodo_pago', 'banco_id', 'tarjeta_id', 'numero_referencia']);
        });
    }
};
