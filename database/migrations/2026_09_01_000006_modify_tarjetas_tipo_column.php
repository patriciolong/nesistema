<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarjetas', function (Blueprint $table) {
            $table->string('tipo')->default('Débito')->change();
            $table->string('estado')->default('Activo')->change();
        });
    }

    public function down(): void
    {
        Schema::table('tarjetas', function (Blueprint $table) {
            $table->enum('tipo', ['Débito', 'Crédito'])->default('Débito')->change();
        });
    }
};
