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
        Schema::create('tipo_tramites', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('slug', 120)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->string('color_gradient', 150)->default('linear-gradient(135deg, #6366f1 0%, #4338ca 100%)');
            $table->string('icono', 50)->default('document');
            $table->json('campos')->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->string('created_by', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('tramites_personalizados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_tramite_id');
            $table->integer('id_cliente');
            $table->json('datos_formulario')->nullable();
            $table->decimal('valor_tramite', 10, 2)->default(0);
            $table->decimal('abono_tramite', 10, 2)->default(0);
            $table->decimal('saldo', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->date('fecha')->nullable();
            $table->string('usuario', 100)->nullable();
            $table->string('oficina', 100)->nullable();
            $table->timestamps();

            $table->foreign('tipo_tramite_id')->references('id')->on('tipo_tramites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramites_personalizados');
        Schema::dropIfExists('tipo_tramites');
    }
};
