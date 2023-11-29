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
        Schema::create('tipo_inmuebles', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        DB::table('tipo_inmuebles')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'Casa',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'Edificio',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'Terreno',
                ],
                [
                    'id' => 3,
                    'descripcion' => 'Apartamento',
                ],
                [
                    'id' => 4,
                    'descripcion' => 'Local Comercial',
                ],
                [
                    'id' => 5,
                    'descripcion' => 'Oficina',
                ],
                [
                    'id' => 6,
                    'descripcion' => 'Colegio',
                ],
                [
                    'id' => 7,
                    'descripcion' => 'Comisaría',
                ],
                [
                    'id' => 8,
                    'descripcion' => 'Hospital',
                ],
                [
                    'id' => 9,
                    'descripcion' => 'Plaza',
                ],
                [
                    'id' => 10,
                    'descripcion' => 'Parque',
                ],
                [
                    'id' => 11,
                    'descripcion' => 'Estación de Bomberos',
                ],
                [
                    'id' => 12,
                    'descripcion' => 'Centro Comunitario',
                ],
                [
                    'id' => 13,
                    'descripcion' => 'Biblioteca',
                ],
                [
                    'id' => 14,
                    'descripcion' => 'Iglesia',
                ],
                [
                    'id' => 15,
                    'descripcion' => 'Sinagoga',
                ],
                [
                    'id' => 16,
                    'descripcion' => 'Mezquita',
                ],
                [
                    'id' => 17,
                    'descripcion' => 'Templo',
                ],
                [
                    'id' => 18,
                    'descripcion' => 'Museo',
                ],
                [
                    'id' => 19,
                    'descripcion' => 'Galería de Arte',
                ],
                [
                    'id' => 20,
                    'descripcion' => 'Teatro',
                ],
                [
                    'id' => 21,
                    'descripcion' => 'Cine',
                ],
                [
                    'id' => 22,
                    'descripcion' => 'Restaurante',
                ],
                [
                    'id' => 23,
                    'descripcion' => 'Cafetería',
                ],
                [
                    'id' => 24,
                    'descripcion' => 'Supermercado',
                ],
                [
                    'id' => 25,
                    'descripcion' => 'Gimnasio',
                ],
                [
                    'id' => 26,
                    'descripcion' => 'Piscina',
                ],
                [
                    'id' => 27,
                    'descripcion' => 'Estadio',
                ],
                [
                    'id' => 28,
                    'descripcion' => 'Estación de Tren',
                ],
                [
                    'id' => 29,
                    'descripcion' => 'Estación de Autobuses',
                ],
                [
                    'id' => 30,
                    'descripcion' => 'Aeropuerto',
                ],
                // Puedes continuar agregando más tipos de inmuebles según sea necesario
            )
        );
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_inmuebles');
    }
};
