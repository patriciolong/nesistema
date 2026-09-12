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
        Schema::create('documentos_generados', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cliente');
            $table->unsignedInteger('plantilla_id')->nullable();
            $table->string('titulo_documento', 255);
            $table->longText('contenido_html');
            $table->string('usuario_creador', 100)->nullable();
            $table->timestamps();

            $table->index('id_cliente');
            $table->index('plantilla_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_generados');
    }
};
