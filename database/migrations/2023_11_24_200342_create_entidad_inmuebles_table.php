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
            $table->integer('dirección')->nullable();
            $table->integer('departamento')->nullable();
            $table->string('provincia')->nullable();
            $table->string('referencia')->nullable();
            $table->string('color')->nullable();
            $table->string('pisos')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
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
