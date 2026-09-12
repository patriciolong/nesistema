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
        Schema::create('tarjetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco_id')->constrained('bancos')->onDelete('cascade');
            $table->string('nombre');
            $table->enum('tipo', ['Débito', 'Crédito'])->default('Débito');
            $table->string('franquicia')->default('Visa'); // Visa, Mastercard, Amex, Discover, etc.
            $table->string('ultimos_digitos', 10)->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjetas');
    }
};
