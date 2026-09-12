<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Campos para portal de clientes y notificaciones en 'cliente'
        if (Schema::hasTable('cliente')) {
            Schema::table('cliente', function (Blueprint $table) {
                if (!Schema::hasColumn('cliente', 'c_password')) {
                    $table->string('c_password', 255)->nullable()->after('c_email');
                }
                if (!Schema::hasColumn('cliente', 'api_token')) {
                    $table->string('api_token', 100)->nullable()->unique()->after('c_password');
                }
                if (!Schema::hasColumn('cliente', 'fcm_token')) {
                    $table->text('fcm_token')->nullable()->after('api_token');
                }
                if (!Schema::hasColumn('cliente', 'fcm_platform')) {
                    $table->string('fcm_platform', 20)->nullable()->after('fcm_token');
                }
                if (!Schema::hasColumn('cliente', 'last_login_at')) {
                    $table->dateTime('last_login_at')->nullable()->after('fcm_platform');
                }
            });

            // Asignar contraseña inicial por defecto (número de identificación) a clientes existentes
            $clientes = DB::table('cliente')->whereNull('c_password')->orWhere('c_password', '')->get();
            foreach ($clientes as $c) {
                $initialPassword = !empty($c->c_identificacion) ? trim($c->c_identificacion) : '123456';
                DB::table('cliente')
                    ->where('id_cliente', $c->id_cliente)
                    ->update([
                        'c_password' => Hash::make($initialPassword)
                    ]);
            }
        }

        // 2. Columnas de estado en tablas de trámites
        $tramiteTables = [
            'tramites_personalizados',
            'tramite_poderes',
            'tramite_divorcio',
            'tramite_impuestos',
            'tramites_varios',
            'documentos_generados'
        ];

        foreach ($tramiteTables as $tbl) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $table) use ($tbl) {
                    if (!Schema::hasColumn($tbl, 'estado')) {
                        $table->string('estado', 30)->default('en_proceso');
                    }
                    if (!Schema::hasColumn($tbl, 'fecha_completado')) {
                        $table->dateTime('fecha_completado')->nullable();
                    }
                    if (!Schema::hasColumn($tbl, 'notificado_at')) {
                        $table->dateTime('notificado_at')->nullable();
                    }
                });
            }
        }

        // 3. Crear tabla de notificaciones para clientes
        if (!Schema::hasTable('notificaciones_cliente')) {
            Schema::create('notificaciones_cliente', function (Blueprint $table) {
                $table->id();
                $table->integer('id_cliente')->index();
                $table->string('tramite_tipo', 50)->nullable();
                $table->unsignedBigInteger('tramite_id')->nullable();
                $table->string('titulo', 191);
                $table->text('mensaje');
                $table->boolean('leido')->default(false)->index();
                $table->json('data_extra')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones_cliente');

        $tramiteTables = [
            'tramites_personalizados',
            'tramite_poderes',
            'tramite_divorcio',
            'tramite_impuestos',
            'tramites_varios',
            'documentos_generados'
        ];

        foreach ($tramiteTables as $tbl) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $table) use ($tbl) {
                    if (Schema::hasColumn($tbl, 'estado')) $table->dropColumn('estado');
                    if (Schema::hasColumn($tbl, 'fecha_completado')) $table->dropColumn('fecha_completado');
                    if (Schema::hasColumn($tbl, 'notificado_at')) $table->dropColumn('notificado_at');
                });
            }
        }

        if (Schema::hasTable('cliente')) {
            Schema::table('cliente', function (Blueprint $table) {
                if (Schema::hasColumn('cliente', 'c_password')) $table->dropColumn('c_password');
                if (Schema::hasColumn('cliente', 'api_token')) $table->dropColumn('api_token');
                if (Schema::hasColumn('cliente', 'fcm_token')) $table->dropColumn('fcm_token');
                if (Schema::hasColumn('cliente', 'fcm_platform')) $table->dropColumn('fcm_platform');
                if (Schema::hasColumn('cliente', 'last_login_at')) $table->dropColumn('last_login_at');
            });
        }
    }
};
