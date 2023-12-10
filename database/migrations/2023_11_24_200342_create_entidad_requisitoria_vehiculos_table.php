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
        Schema::create('entidad_requisitoria_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('documento', 10)->nullable();
            $table->string('fechadocumento')->nullable();
            $table->string('autoridad', 40)->nullable();
            $table->string('placa', 10)->nullable();
            $table->string('motivo', 40)->nullable();
            $table->string('situacion', 10)->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_requisitoria_vehiculos');
    }
};
