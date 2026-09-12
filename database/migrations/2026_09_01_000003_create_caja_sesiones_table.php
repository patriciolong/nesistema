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
        Schema::create('caja_sesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('oficina')->nullable();
            $table->decimal('monto_apertura', 12, 2)->default(0.00);
            $table->dateTime('fecha_apertura');
            $table->dateTime('fecha_cierre')->nullable();
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');

            // Totales teóricos calculados por el sistema
            $table->decimal('total_sistema_efectivo', 12, 2)->default(0.00);
            $table->decimal('total_sistema_cheque', 12, 2)->default(0.00);
            $table->decimal('total_sistema_transferencia', 12, 2)->default(0.00);
            $table->decimal('total_sistema_tarjeta', 12, 2)->default(0.00);
            $table->decimal('total_sistema_credito', 12, 2)->default(0.00);
            $table->decimal('total_sistema_ingresos_extra', 12, 2)->default(0.00);
            $table->decimal('total_sistema_egresos_extra', 12, 2)->default(0.00);
            $table->decimal('total_sistema_total', 12, 2)->default(0.00);

            // Totales contados/declarados físicamente por el cajero
            $table->decimal('monto_cierre_efectivo', 12, 2)->nullable();
            $table->decimal('monto_cierre_cheque', 12, 2)->nullable();
            $table->decimal('monto_cierre_transferencia', 12, 2)->nullable();
            $table->decimal('monto_cierre_tarjeta', 12, 2)->nullable();
            $table->decimal('monto_cierre_credito', 12, 2)->nullable();
            $table->decimal('monto_cierre_total', 12, 2)->nullable();

            // Diferencias y estado de cuadre
            $table->decimal('diferencia_efectivo', 12, 2)->nullable()->default(0.00);
            $table->decimal('diferencia_cheque', 12, 2)->nullable()->default(0.00);
            $table->decimal('diferencia_transferencia', 12, 2)->nullable()->default(0.00);
            $table->decimal('diferencia_tarjeta', 12, 2)->nullable()->default(0.00);
            $table->decimal('diferencia_total', 12, 2)->nullable()->default(0.00);
            $table->boolean('cuadrado')->default(false);

            $table->text('observaciones_apertura')->nullable();
            $table->text('observaciones_cierre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_sesiones');
    }
};
