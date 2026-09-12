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
        $encabezadoHtml = '<div class="header-notaria" style="width: 100%; margin-bottom: 12px; font-family: Arial, Helvetica, sans-serif;">
    <table style="width: 100%; border-collapse: collapse; border: none; margin-bottom: 4px;">
        <tr>
            <td style="width: 70px; vertical-align: middle; text-align: left; padding: 0;">
                <img src="/img/escudo_ecuador.jpg" style="height: 65px; width: auto; display: block;" alt="Escudo del Ecuador">
            </td>
            <td style="width: 12px; vertical-align: middle; text-align: center; color: #1e293b; font-size: 30px; font-weight: 300; line-height: 1; padding: 0 4px;">
                |
            </td>
            <td style="vertical-align: middle; text-align: left; padding-left: 4px;">
                <div style="font-size: 19px; font-weight: 900; letter-spacing: 1.2px; color: #0f172a; line-height: 1.1; text-transform: uppercase;">
                    NOTARÍA ECUADOR
                </div>
                <div style="width: 100%; height: 1.5px; background-color: #0f172a; margin: 3px 0 2px 0;"></div>
                <div style="font-size: 13px; font-weight: 800; color: #1e293b; letter-spacing: 0.5px; line-height: 1.2;">
                    Christian Moreno
                </div>
                <div style="font-size: 10.5px; font-weight: 600; color: #475569; letter-spacing: 0.3px; line-height: 1.2;">
                    New York &ndash; Notary Public
                </div>
            </td>
            <td style="vertical-align: middle; text-align: right;"></td>
        </tr>
    </table>
    <div style="width: 100%; text-align: center; margin-top: 2px;">
        <img src="/img/divisor_balanza.jpg" style="width: 100%; max-height: 22px; object-fit: contain; display: block;" alt="Divisor Balanza">
    </div>
</div>';

        $pieHtml = '<div class="footer-notaria" style="width: 100%; font-family: Arial, Helvetica, sans-serif; border-top: 1px dashed #94a3b8; padding-top: 6px; margin-top: 12px; font-size: 8pt; color: #334155;">
    <table style="width: 100%; border-collapse: collapse; border: none;">
        <tr>
            <td style="vertical-align: top; width: 23%; padding: 0 4px; text-align: center; border-right: 1px double #cbd5e1;">
                <div style="font-weight: 800; color: #0f172a; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.3px;">Brooklyn &ndash; N.Y.</div>
                <div style="font-size: 7pt; color: #475569; line-height: 1.25; margin-top: 2px;">190 Wyckoff Ave.<br>Brooklyn, N.Y. 11237</div>
            </td>
            <td style="vertical-align: top; width: 25%; padding: 0 4px; text-align: center; border-right: 1px double #cbd5e1;">
                <div style="font-weight: 800; color: #0f172a; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.3px;">Spring Valley &ndash; N.Y.</div>
                <div style="font-size: 7pt; color: #475569; line-height: 1.25; margin-top: 2px;">99 S. Central Ave, No. 107<br>Spring Valley, N.Y. 10977</div>
            </td>
            <td style="vertical-align: top; width: 25%; padding: 0 4px; text-align: center; border-right: 1px double #cbd5e1;">
                <div style="font-weight: 800; color: #0f172a; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.3px;">Cuenca &ndash; Ecuador</div>
                <div style="font-size: 7pt; color: #475569; line-height: 1.25; margin-top: 2px;">Av. Solano y Av. del Estadio<br>Edif. ProduBanco, Ofi. 201</div>
            </td>
            <td style="vertical-align: top; width: 20%; padding: 0 4px; text-align: center;">
                <div style="font-size: 7pt; color: #1e293b; line-height: 1.3;">
                    WhatsApp (212) 810-7721<br>
                    Teléfono (718) 864-7908<br>
                    <strong style="color: #0f172a; font-size: 7pt;">www.NotariaEcuador.com</strong>
                </div>
            </td>
            <td style="vertical-align: middle; width: 7%; padding: 0 2px; text-align: right;">
                <img src="/img/qr_notaria.jpg" style="height: 44px; width: 44px; display: inline-block;" alt="QR Notaría">
            </td>
        </tr>
    </table>
</div>';

        $encabezadoId = DB::table('plantilla_membretes')->insertGetId([
            'nombre' => 'Encabezado Oficial Notaría Ecuador',
            'tipo' => 'encabezado',
            'contenido_html' => $encabezadoHtml,
            'datos_json' => json_encode([
                'logo_url' => '/img/escudo_ecuador.jpg',
                'titulo' => 'NOTARÍA ECUADOR',
                'subtitulo_1' => 'Christian Moreno',
                'subtitulo_2' => 'New York – Notary Public',
                'divisor_url' => '/img/divisor_balanza.jpg',
            ]),
            'es_predeterminado' => true,
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pieId = DB::table('plantilla_membretes')->insertGetId([
            'nombre' => 'Pie de Página Notaría Ecuador (4 Sedes + QR)',
            'tipo' => 'pie',
            'contenido_html' => $pieHtml,
            'datos_json' => json_encode([
                'sedes' => [
                    ['ciudad' => 'Brooklyn – N.Y.', 'direccion' => "190 Wyckoff Ave.\nBrooklyn, N.Y. 11237"],
                    ['ciudad' => 'Spring Valley – N.Y.', 'direccion' => "99 S. Central Ave, No. 107\nSpring Valley, N.Y. 10977"],
                    ['ciudad' => 'Cuenca – Ecuador', 'direccion' => "Av. Solano y Av. del Estadio\nEdif. ProduBanco, Ofi. 201"],
                ],
                'contacto' => [
                    'whatsapp' => '(212) 810-7721',
                    'telefono' => '(718) 864-7908',
                    'web' => 'www.NotariaEcuador.com',
                ],
                'qr_url' => '/img/qr_notaria.jpg',
            ]),
            'es_predeterminado' => true,
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Asignar a todas las plantillas existentes como membrete predeterminado
        DB::table('plantillas')->whereNull('encabezado_id')->update([
            'encabezado_id' => $encabezadoId,
            'pie_id' => $pieId,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('plantilla_membretes')->whereIn('nombre', [
            'Encabezado Oficial Notaría Ecuador',
            'Pie de Página Notaría Ecuador (4 Sedes + QR)',
        ])->delete();
    }
};
