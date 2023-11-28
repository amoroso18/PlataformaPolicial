<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_unidads', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion');
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        DB::table('tipo_unidads')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'NO TIENE',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'COMISARIA',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'EMERGENCIA',
                ],
                [
                    'id' => 3,
                    'descripcion' => 'OPERACIONES ESPECIALES',
                ],
                [
                    'id' => 4,
                    'descripcion' => 'GRUPO TERNA',
                ],
                [
                    'id' => 5,
                    'descripcion' => 'ANTIDROGAS',
                ],
                [
                    'id' => 6,
                    'descripcion' => 'INVESTIGACIÓN CRIMINAL',
                ],
                [
                    'id' => 7,
                    'descripcion' => 'CRIMINALISTICA',
                ],
                [
                    'id' => 8,
                    'descripcion' => 'INTELIGENCIA',
                ],
                [
                    'id' => 9,
                    'descripcion' => 'LAVADO DE ACTIVOS',
                ],
                [
                    'id' => 10,
                    'descripcion' => 'TRATA DE PERSONAS',
                ],
                [
                    'id' => 11,
                    'descripcion' => 'CONTRA LA CORRUPCIÓN',
                ],
                [
                    'id' => 12,
                    'descripcion' => 'CONTRA EL TERRORISMO',
                ],
                [
                    'id' => 13,
                    'descripcion' => 'SEGURIDAD DEL ESTADO',
                ],
                [
                    'id' => 14,
                    'descripcion' => 'POLICÍA FISCAL',
                ],
                [
                    'id' => 15,
                    'descripcion' => strtoupper('Tránsito'),
                ],
                [
                    'id' => 16,
                    'descripcion' => 'CARRETERAS',
                ],
                [
                    'id' => 17,
                    'descripcion' => 'TURISMO',
                ],
                [
                    'id' => 18,
                    'descripcion' => 'SANIDAD',
                ],
                [
                    'id' => 19,
                    'descripcion' => 'TICS',
                ],
                [
                    'id' => 20,
                    'descripcion' => 'PLANEAMIENTO',
                ],
                [
                    'id' => 21,
                    'descripcion' => 'TRÁMITE DOCUMENTARIO',
                ],
                [
                    'id' => 22,
                    'descripcion' => 'RECURSOS HUMANOS',
                ],
                [
                    'id' => 23,
                    'descripcion' => 'BIENESTAR',
                ],
                [
                    'id' => 24,
                    'descripcion' => 'LOGISTICA',
                ],
                [
                    'id' => 25,
                    'descripcion' => 'INSPECTORIA',
                ],
                [
                    'id' => 26,
                    'descripcion' => 'ASESORÍA JURIDÍCA',
                ],
               
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_unidads');
    }
};
