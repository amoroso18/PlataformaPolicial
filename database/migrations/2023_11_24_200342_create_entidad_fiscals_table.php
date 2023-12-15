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
        Schema::create('entidad_fiscals', function (Blueprint $table) {
            $table->id();
            $table->integer('carnet')->nullable();
            $table->integer('dni')->nullable();
            $table->string('nombres')->nullable();
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('celular')->nullable();
            $table->string('correo')->nullable();
            $table->string('procedencia')->nullable();
            $table->string('ficalia')->nullable();
            $table->string('despacho')->nullable();
            $table->string('ubigeo')->nullable();
            $table->timestamps();
        });
        // DB::table('entidad_fiscals')->insert(
        //     array(
        //         [
        //             'carnet' => null,
        //             'dni' => null,
        //             'nombres' => '',
        //             'paterno' => '',
        //             'materno' => '',
        //             'celular' => '',
        //             'correo' => '',
        //              'procedencia' => 'NO TIENE',
        //             'ficalia' => 'NO TIENE',
        //             'despacho' => 'NO TIENE',
        //             'ubigeo' => 'NO TIENE',
        //             // 'procedencia' => 'MINISTERIO PÚBLICO',
        //             // 'ficalia' => '2DA. FISCALÍA PROVINCIAL PENAL CORPORATIVADE CARABAYLLO',
        //             // 'despacho' => 'SEGUNDO DESPACHO',
        //             // 'ubigeo' => 'DISTRITO FISCAL DE LIMA NORTE',
        //         ],
               
             
        //     )
        // );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_fiscals');
    }
};
