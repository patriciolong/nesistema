<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $templates = [
            [
                'nombre_plantilla' => 'Autorización de Salida del País para Menor de Edad',
                'descripcion' => 'Permiso notarial para que un menor viaje fuera del país solo o acompañado.',
                'categoria' => 'Cartas y Autorizaciones',
                'tipo_documento' => 'autorizacion_viaje',
                'activo' => 1,
                'contenido_html' => '<h1>AUTORIZACIÓN NOTARIAL DE VIAJE Y SALIDA DEL PAÍS</h1>
<h2>PARA MENOR DE EDAD</h2>

<p>En la ciudad de <strong>[[notary_address]]</strong>, a los <strong>[[fecha_actual]]</strong>, ante mí, <strong>[[notario_nombre]]</strong>, comparece por sus propios derechos el/la señor(a) <strong>[[cliente_nombre_completo]]</strong>, portador(a) del Pasaporte / Documento de Identidad N° <strong>[[cliente_identificacion]]</strong>, domiciliado(a) en <strong>[[cliente_direccion_completa]]</strong>, teléfono <strong>[[cliente_telefono]]</strong>, en su calidad de padre/madre del menor.</p>

<p>El/La compareciente en pleno uso de sus facultades legales, <strong>AUTORIZA EXPRESAMENTE</strong> para que su hijo(a) menor de edad, de nombres <strong>[[menor_nombre_completo]]</strong>, con Documento de Identidad / Pasaporte N° <strong>[[menor_identificacion]]</strong>, pueda salir del territorio nacional con destino a <strong>[[pais_destino]]</strong>, durante el período comprendido entre el <strong>[[fecha_salida]]</strong> y el <strong>[[fecha_retorno]]</strong>.</p>

<p>El menor viajará bajo el cuidado y responsabilidad del/la señor(a) <strong>[[acompanante_nombre]]</strong>, portador(a) del Documento de Identidad N° <strong>[[acompanante_identificacion]]</strong>, a quien se faculta para realizar todos los trámites migratorios, de aduana y transporte aéreo.</p>

<p>Para constancia y validez legal, se suscribe la presente autorización en fecha y lugar señalados.</p>

<br><br>
<table style="width: 100%; border: none; margin-top: 40px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; text-align: center; vertical-align: top; padding: 10px;">
            <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto; padding-top: 6px;">
                <strong>[[cliente_nombre_completo]]</strong><br>
                NUI: [[cliente_identificacion]]<br>
                <strong>PADRE / MADRE AUTORIZANTE</strong>
            </div>
        </td>
        <td style="width: 50%; text-align: center; vertical-align: top; padding: 10px;">
            <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto; padding-top: 6px;">
                <strong>[[notario_nombre]]</strong><br>
                <strong>NOTARIO PÚBLICO AUTORIZADO</strong>
            </div>
        </td>
    </tr>
</table>'
            ],
            [
                'nombre_plantilla' => 'Declaración Juramentada de Soltería y Convivencia',
                'descripcion' => 'Declaración notarial jurada de estado civil libre y unión de hecho.',
                'categoria' => 'Declaraciones Juramentadas',
                'tipo_documento' => 'declaracion_jurada',
                'activo' => 1,
                'contenido_html' => '<h1>DECLARACIÓN JURAMENTADA NOTARIAL</h1>
<h2>DE ESTADO CIVIL Y NO IMPEDIMENTO MATRIMONIAL</h2>

<p>En el Estado de New York, a los <strong>[[fecha_actual]]</strong>, comparece ante mí, <strong>[[notario_nombre]]</strong>, el/la señor(a) <strong>[[cliente_nombre_completo]]</strong>, de nacionalidad <strong>[[cliente_pais]]</strong>, mayor de edad, con Pasaporte / Identificación N° <strong>[[cliente_identificacion]]</strong>, domiciliado(a) en <strong>[[cliente_direccion_completa]]</strong>, teléfono <strong>[[cliente_telefono]]</strong>, bien instruido(a) de la gravedad del juramento y de las penas que sancionan el perjurio.</p>

<p>Bajo la formalidad de juramento solemne, <strong>DECLARA:</strong></p>

<p><strong>PRIMERO:</strong> Que su estado civil actual es <strong>SOLTERO(A)</strong>, no existiendo vínculo matrimonial vigente con ninguna persona ni impedimento legal alguno conforme a las leyes vigentes.</p>

<p><strong>SEGUNDO:</strong> Que mantiene una relación de convivencia y unión de hecho libre, voluntaria y estable con el/la señor(a) <strong>[[pareja_nombre_completo]]</strong>, portador(a) del Documento N° <strong>[[pareja_identificacion]]</strong>, desde hace <strong>[[tiempo_convivencia]]</strong> años de manera pública y notoria.</p>

<p><strong>TERCERO:</strong> Que rinde la presente declaración para los fines legales y administrativos que correspondan ante cualquier entidad pública, consular o privada que lo requiera.</p>

<br><br>
<table style="width: 100%; border: none; margin-top: 40px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; text-align: center; vertical-align: top; padding: 10px;">
            <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto; padding-top: 6px;">
                <strong>[[cliente_nombre_completo]]</strong><br>
                NUI: [[cliente_identificacion]]<br>
                <strong>DECLARANTE</strong>
            </div>
        </td>
        <td style="width: 50%; text-align: center; vertical-align: top; padding: 10px;">
            <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto; padding-top: 6px;">
                <strong>[[notario_nombre]]</strong><br>
                <strong>NOTARIO PÚBLICO</strong>
            </div>
        </td>
    </tr>
</table>'
            ],
            [
                'nombre_plantilla' => 'Carta de Invitación y Sostenimiento Consular USA',
                'descripcion' => 'Carta formal de patrocinio y hospedaje para solicitud de visa americana.',
                'categoria' => 'Trámites Consulares',
                'tipo_documento' => 'carta_invitacion',
                'activo' => 1,
                'contenido_html' => '<h1>AFFIDAVIT OF SUPPORT / CARTA DE INVITACIÓN</h1>
<h2>PARA SOLICITUD DE VISA DE TURISMO (B1/B2)</h2>

<p>A las Autoridades Consulares y de Inmigración de los Estados Unidos de América:</p>

<p>Yo, <strong>[[cliente_nombre_completo]]</strong>, con Documento / ID N° <strong>[[cliente_identificacion]]</strong>, con residencia legal en <strong>[[cliente_direccion_completa]]</strong>, teléfono <strong>[[cliente_telefono]]</strong>, por medio del presente documento me dirijo respetuosamente a ustedes para exponer:</p>

<p>Que deseo extender una formal invitación a mi <strong>[[parentesco_invitado]]</strong>, el/la señor(a) <strong>[[invitado_nombre_completo]]</strong>, con Pasaporte N° <strong>[[invitado_pasaporte]]</strong>, nacional de <strong>[[invitado_nacionalidad]]</strong>, para que visite los Estados Unidos por motivos turísticos y familiares durante el período aproximado de <strong>[[duracion_estadia]]</strong>.</p>

<p>Durante su permanencia, me comprometo formalmente a asumir todos los gastos de alojamiento, alimentación, transporte interno y seguro médico que pudieran surgir, garantizando que el/la invitado(a) retornará a su país de origen al concluir su visita.</p>

<br><br>
<div style="margin-top: 40px; text-align: center;">
    <div style="border-top: 1px solid #000; width: 50%; margin: 0 auto; padding-top: 6px;">
        <strong>[[cliente_nombre_completo]]</strong><br>
        ID: [[cliente_identificacion]]<br>
        <strong>ANFITRIÓN / PATROCINADOR</strong>
    </div>
</div>'
            ],
            [
                'nombre_plantilla' => 'Poder General Amplio y Suficiente',
                'descripcion' => 'Mandato general para representación judicial, bancaria y mercantil completa.',
                'categoria' => 'Poderes',
                'tipo_documento' => 'poder_general',
                'activo' => 1,
                'contenido_html' => '<h1>ESCRITURA PÚBLICA DE PODER GENERAL</h1>
<h2>AMPLIO, SUFICIENTE Y SIN LIMITACIÓN</h2>

<p>En el Estado de New York, a los <strong>[[fecha_actual]]</strong>, ante mí, <strong>[[notario_nombre]]</strong>, comparece <strong>[[cliente_nombre_completo]]</strong>, mayor de edad, con Identificación N° <strong>[[cliente_identificacion]]</strong>, domiciliado(a) en <strong>[[cliente_direccion_completa]]</strong>, teléfono <strong>[[cliente_telefono]]</strong>; hábil por derecho para este otorgamiento.</p>

<p>El/La mandante confiere <strong>PODER GENERAL AMPLIO Y SUFICIENTE</strong> a favor de <strong>[[apoderado_nombre]]</strong>, con Documento N° <strong>[[apoderado_cedula]]</strong>, para que en su nombre y representación administre libremente todos sus bienes, derechos y acciones, pudiendo:</p>

<p><strong>1.</strong> Realizar aperturas, depósitos, retiros y cancelación de cuentas bancarias en entidades financieras públicas o privadas.<br>
<strong>2.</strong> Comparecer ante cualquier autoridad judicial, administrativa, notarial o tributaria con facultades de procuración judicial.<br>
<strong>3.</strong> Celebrar contratos de arrendamiento, compraventa, permuta, recibir dineros y otorgar cartas de pago y cancelaciones correspondientes.</p>

<br><br>
<table style="width: 100%; border: none; margin-top: 40px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; text-align: center; vertical-align: top; padding: 10px;">
            <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto; padding-top: 6px;">
                <strong>[[cliente_nombre_completo]]</strong><br>
                NUI: [[cliente_identificacion]]<br>
                <strong>PODERDANTE</strong>
            </div>
        </td>
        <td style="width: 50%; text-align: center; vertical-align: top; padding: 10px;">
            <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto; padding-top: 6px;">
                <strong>[[notario_nombre]]</strong><br>
                <strong>NOTARIO PÚBLICO</strong>
            </div>
        </td>
    </tr>
</table>'
            ]
        ];

        foreach ($templates as $t) {
            $exists = DB::table('plantillas')->where('nombre_plantilla', $t['nombre_plantilla'])->exists();
            if (!$exists) {
                DB::table('plantillas')->insert($t);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
