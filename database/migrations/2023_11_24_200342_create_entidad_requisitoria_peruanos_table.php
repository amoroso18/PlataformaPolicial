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
        Schema::create('entidad_requisitoria_peruanos', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('tipo', 5)->nullable();
            $table->string('nrodocumento', 10)->nullable();
            $table->string('paterno', 30)->nullable();
            $table->string('materno', 30)->nullable();
            $table->string('nombres', 30)->nullable();
            $table->string('delitos', 50)->nullable();
            $table->string('situacion', 20)->nullable();
            $table->string('autoridadjudicial', 50)->nullable();
            $table->string('documento', 10)->nullable();
            $table->string('fechadocumento')->nullable();
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_requisitoria_peruanos');
    }
};
