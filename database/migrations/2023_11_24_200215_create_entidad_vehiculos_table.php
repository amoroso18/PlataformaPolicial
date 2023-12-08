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
        Schema::create('entidad_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->nullable();
            $table->string('serie')->nullable();
            $table->string('numero_motor')->nullable();
            $table->string('color')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('ano')->nullable();
            $table->string('tipo_carroceria')->nullable();
            $table->string('vin')->nullable();
            $table->string('tipo_motor')->nullable();
            $table->string('cilindrada_motor')->nullable();
            $table->string('tipo_combustible')->nullable();
            $table->string('tipo_transmision')->nullable();
            $table->string('tipo_traccion')->nullable();
            $table->string('kilometraje')->nullable()->default(0);
            $table->string('placaanterior')->nullable();
            $table->enum('estado_vehiculo', ['EN_CIRCULACION', 'EN_REPARACION'])->nullable()->default('EN_CIRCULACION');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_vehiculos');
    }
};
