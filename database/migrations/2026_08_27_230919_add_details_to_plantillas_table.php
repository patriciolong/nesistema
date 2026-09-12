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
        Schema::table('plantillas', function (Blueprint $table) {
            if (!Schema::hasColumn('plantillas', 'descripcion')) {
                $table->string('descripcion', 255)->nullable()->after('nombre_plantilla');
            }
            if (!Schema::hasColumn('plantillas', 'categoria')) {
                $table->string('categoria', 100)->default('General')->after('tipo_documento');
            }
            if (!Schema::hasColumn('plantillas', 'activo')) {
                $table->boolean('activo')->default(true)->after('categoria');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'categoria', 'activo']);
        });
    }
};
