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
        Schema::create('entidad_personas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nacionalidad_id')->constrained('tipo_nacionalidads')->onDelete('cascade')->default(0);
            $table->foreignId('documento_id')->constrained('tipo_documento_identidads')->onDelete('cascade')->default(0);
            $table->string('documento')->nullable();
            $table->string('nombres')->nullable();
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('sexo')->nullable();
            $table->string('fecha_nacimiento')->nullable();

            $table->string('ubigeo_nacimiento')->nullable();
            $table->string('departamento_nacimiento')->nullable();
            $table->string('provincia_nacimiento')->nullable();
            $table->string('distrito_nacimiento')->nullable();
            $table->string('lugar_nacimiento',2500)->nullable();

            $table->string('ubigeo_domicilio')->nullable();
            $table->string('departamento_domicilio')->nullable();
            $table->string('provincia_domicilio')->nullable();
            $table->string('distrito_domicilio')->nullable();
            $table->string('lugar_domicilio',2500)->nullable();

            $table->binary('foto')->nullable();
            $table->binary('firma')->nullable();
            $table->timestamps();
        });
        // DB::table('entidad_personas')->insert(
        //     array(
        //         [
        //             'nacionalidad_id' => 1,
        //             'documento_id' => 1,
        //             'documento' => 123,
        //             'nombres' => 'Infantes',
        //             'paterno' => 'Infantes',
        //             'materno' => 'Infantes',
        //             'lugar_domicilio' => "xxxxxxxxxxx"
        //         ],
        //         [
        //             'nacionalidad_id' => 2,
        //             'documento_id' => 2,
        //             'documento' => 123,
        //             'nombres' => 'Infantes',
        //             'paterno' => 'Infantes',
        //             'materno' => 'Infantes',
        //             'lugar_domicilio' => "xxxxxxxxxxx"
        //         ],
        //         [
        //             'nacionalidad_id' => 3,
        //             'documento_id' => 3,
        //             'documento' => 123,
        //             'nombres' => 'Infantes',
        //             'paterno' => 'Infantes',
        //             'materno' => 'Infantes',
        //             'lugar_domicilio' => "xxxxxxxxxxx"
        //         ],
        //         [
        //             'nacionalidad_id' => 4,
        //             'documento_id' => 4,
        //             'documento' => 123,
        //             'nombres' => 'Infantes',
        //             'paterno' => 'Infantes',
        //             'materno' => 'Infantes',
        //             'lugar_domicilio' => "xxxxxxxxxxx"
        //         ],
        //         [
        //             'nacionalidad_id' => 5,
        //             'documento_id' => 5,
        //             'documento' => 123,
        //             'nombres' => 'Infantes',
        //             'paterno' => 'Infantes',
        //             'materno' => 'Infantes',
        //             'lugar_domicilio' => "xxxxxxxxxxx"
        //         ],
        //     )
        // );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_personas');
    }
};
