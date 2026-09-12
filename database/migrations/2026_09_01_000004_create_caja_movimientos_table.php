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
        Schema::create('caja_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_sesion_id')->constrained('caja_sesiones')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('cliente_id')->nullable();
            $table->enum('tipo', ['ingreso_tramite', 'ingreso_abono', 'ingreso_extra', 'egreso_gasto', 'egreso_retiro'])->default('ingreso_tramite');
            $table->decimal('monto', 12, 2);
            $table->enum('metodo_pago', ['Efectivo', 'Cheque', 'Transferencia', 'Tarjeta', 'Crédito'])->default('Efectivo');
            $table->foreignId('banco_id')->nullable()->constrained('bancos')->nullOnDelete();
            $table->foreignId('tarjeta_id')->nullable()->constrained('tarjetas')->nullOnDelete();
            $table->foreignId('pago_id')->nullable();
            $table->string('concepto');
            $table->string('numero_referencia')->nullable(); // Número de comprobante, voucher, cheque
            $table->string('tramite_tipo')->nullable(); // Divorcio, Impuesto, Poder, Vario, Personalizado
            $table->unsignedBigInteger('tramite_id')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id_cliente')->on('cliente')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_movimientos');
    }
};
