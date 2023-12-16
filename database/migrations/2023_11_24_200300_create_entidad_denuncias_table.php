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
        Schema::create('entidad_denuncias', function (Blueprint $table) {
            $table->id();
            $table->string('comisaria', 1000)->nullable();
            $table->string('fecha_denuncia')->nullable();
            $table->string('nrodocumento', 100)->nullable();
            $table->string('paterno', 100)->nullable();
            $table->string('materno', 100)->nullable();
            $table->string('nombres', 100)->nullable();
            $table->string('condicion', 1000)->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->text('contenido',5000)->nullable();
            $table->string('created_at')->nullable();
            $table->string('update_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_denuncias');
    }
};
