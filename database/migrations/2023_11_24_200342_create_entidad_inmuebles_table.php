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
        Schema::create('entidad_inmuebles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inmuebles_id')->constrained('tipo_inmuebles')->onDelete('cascade');
            $table->string('direccion',1000)->nullable();
            $table->string('departamento')->nullable();
            $table->string('provincia')->nullable();
            $table->string('referencia',1000)->nullable();
            $table->string('color_interior')->nullable();
            $table->string('color_exterior')->nullable();
            $table->string('dimensiones',1000)->nullable();
            $table->string('nro_habitaciones')->nullable();
            $table->string('nro_banios')->nullable();
            $table->string('caracteristicas_especiales',1000)->nullable();
            $table->string('estado_conservacion',1000)->nullable();
            $table->string('valor')->nullable();
            $table->date('fecha_construccion')->nullable();
            $table->string('agua')->nullable();
            $table->string('luz')->nullable();
            $table->string('internet')->nullable();
            $table->string('cable')->nullable();
            $table->string('pisos')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();

            $table->string('propietarios_actuales',1000)->nullable();
            $table->string('propietarios_anteriores',1000)->nullable();

            $table->string('observaciones',1000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_inmuebles');
    }
};
