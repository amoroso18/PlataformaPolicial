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
        Schema::create('tipo_delitos', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('tipo')->nullable();
            $table->string('subtipo')->nullable();
            $table->string('modalidad')->nullable();
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
        // DB::table('tipo_delitos')->insert(
        //     array(
        //         [
        //             'id' => 0,
        //             'tipo' => 'NO TIENE',
        //             'subtipo' => 'NO TIENE',
        //             'modalidad' => 'NO TIENE',
        //         ],
        //         [
        //             'id' => 1,
        //             'tipo' => 'Robo',
        //             'subtipo' => 'Robo a mano armada',
        //             'modalidad' => 'Asalto',
        //         ],
        //         [
        //             'id' => 2,
        //             'tipo' => 'Fraude',
        //             'subtipo' => 'Estafa telefónica',
        //             'modalidad' => 'Phishing',
        //         ],
        //         [
        //             'id' => 3,
        //             'tipo' => 'Violencia',
        //             'subtipo' => 'Violencia doméstica',
        //             'modalidad' => 'Agresión física',
        //         ],
        //         [
        //             'id' => 4,
        //             'tipo' => 'Homicidio',
        //             'subtipo' => 'Asesinato',
        //             'modalidad' => 'Arma de fuego',
        //         ],
        //         [
        //             'id' => 5,
        //             'tipo' => 'Secuestro',
        //             'subtipo' => 'Secuestro exprés',
        //             'modalidad' => 'Rapto breve con rescate',
        //         ],
        //         [
        //             'id' => 6,
        //             'tipo' => 'Fraude',
        //             'subtipo' => 'Fraude bancario',
        //             'modalidad' => 'Clonación de tarjetas',
        //         ],
        //         [
        //             'id' => 7,
        //             'tipo' => 'Tráfico de drogas',
        //             'subtipo' => 'Tráfico de cocaína',
        //             'modalidad' => 'Transporte en vehículo',
        //         ],
        //         [
        //             'id' => 8,
        //             'tipo' => 'Acoso sexual',
        //             'subtipo' => 'Acoso en línea',
        //             'modalidad' => 'Ciberacoso',
        //         ],
        //         [
        //             'id' => 9,
        //             'tipo' => 'Incendio intencional',
        //             'subtipo' => 'Incendio en propiedad',
        //             'modalidad' => 'Uso de acelerantes',
        //         ],
        //         [
        //             'id' => 10,
        //             'tipo' => 'Lesiones',
        //             'subtipo' => 'Lesiones graves',
        //             'modalidad' => 'Ataque con arma contundente',
        //         ],
        //         [
        //             'id' => 11,
        //             'tipo' => 'Falsificación de documentos',
        //             'subtipo' => 'Falsificación de pasaporte',
        //             'modalidad' => 'Uso de identidad falsa',
        //         ],
        //         [
        //             'id' => 12,
        //             'tipo' => 'Extorsión',
        //             'subtipo' => 'Extorsión telefónica',
        //             'modalidad' => 'Llamadas amenazantes',
        //         ],
        //         [
        //             'id' => 13,
        //             'tipo' => 'Robo de vehículos',
        //             'subtipo' => 'Robo de automóviles',
        //             'modalidad' => 'Violación de cerraduras',
        //         ],
        //         [
        //             'id' => 14,
        //             'tipo' => 'Terrorismo',
        //             'subtipo' => 'Atentado con bomba',
        //             'modalidad' => 'Uso de explosivos',
        //         ],
        //         [
        //             'id' => 15,
        //             'tipo' => 'Secuestro',
        //             'subtipo' => 'Secuestro político',
        //             'modalidad' => 'Motivado por razones políticas',
        //         ],
        //         [
        //             'id' => 16,
        //             'tipo' => 'Tráfico de personas',
        //             'subtipo' => 'Tráfico de menores',
        //             'modalidad' => 'Explotación laboral',
        //         ],
        //         [
        //             'id' => 17,
        //             'tipo' => 'Violación',
        //             'subtipo' => 'Violación en grupo',
        //             'modalidad' => 'Agresión sexual múltiple',
        //         ],
        //         [
        //             'id' => 18,
        //             'tipo' => 'Robo a comercios',
        //             'subtipo' => 'Robo a mano armada',
        //             'modalidad' => 'Asalto a tienda',
        //         ],
        //         [
        //             'id' => 19,
        //             'tipo' => 'Fraude',
        //             'subtipo' => 'Fraude de seguros',
        //             'modalidad' => 'Presentación de reclamaciones falsas',
        //         ],
        //         [
        //             'id' => 20,
        //             'tipo' => 'Homicidio',
        //             'subtipo' => 'Homicidio por negligencia',
        //             'modalidad' => 'Conducción imprudente',
        //         ],
        //     )
        // );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_delitos');
    }
};
