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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->integer('cliente_id');
            $table->double('monto');
            $table->string('concepto')->nullable();
            $table->string('usuario');
            $table->string('oficina')->nullable();
            $table->timestamps();

            // Foreign key relation
            // Note: the original `cliente` table uses `id_cliente` as PK which is int(11)
            $table->foreign('cliente_id')->references('id_cliente')->on('cliente')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
