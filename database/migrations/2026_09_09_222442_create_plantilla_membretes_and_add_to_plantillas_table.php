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
        if (!Schema::hasTable('plantilla_membretes')) {
            Schema::create('plantilla_membretes', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 150);
                $table->enum('tipo', ['encabezado', 'pie']);
                $table->longText('contenido_html')->nullable();
                $table->json('datos_json')->nullable();
                $table->boolean('es_predeterminado')->default(false);
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }

        Schema::table('plantillas', function (Blueprint $table) {
            if (!Schema::hasColumn('plantillas', 'encabezado_id')) {
                $table->unsignedBigInteger('encabezado_id')->nullable()->after('categoria');
            }
            if (!Schema::hasColumn('plantillas', 'pie_id')) {
                $table->unsignedBigInteger('pie_id')->nullable()->after('encabezado_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            if (Schema::hasColumn('plantillas', 'encabezado_id')) {
                $table->dropColumn('encabezado_id');
            }
            if (Schema::hasColumn('plantillas', 'pie_id')) {
                $table->dropColumn('pie_id');
            }
        });

        Schema::dropIfExists('plantilla_membretes');
    }
};
