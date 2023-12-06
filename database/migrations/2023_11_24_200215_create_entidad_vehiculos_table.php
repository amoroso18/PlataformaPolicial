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
            $table->string('vin')->nullable();
            $table->string('numero_motor')->nullable();
            $table->string('color')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('placaanterior')->nullable();
            $table->string('estado')->default("EN CIRCULACION");
            $table->string('sede')->nullable();
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
