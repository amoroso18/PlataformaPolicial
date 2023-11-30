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
        Schema::create('dispocicion_fiscals', function (Blueprint $table) {
            $table->id();
            $table->string('caso')->nullable();
            $table->string('nro')->nullable();
            $table->date('fecha_disposicion')->nullable();
            $table->foreignId('fiscal_responsable_id')->constrained('entidad_fiscals')->onDelete('cascade')->default(0);
            $table->foreignId('fiscal_asistente_id')->constrained('entidad_fiscals')->onDelete('cascade')->default(0);
            $table->string('resumen',1000)->nullable();
            $table->string('observaciones',1000)->nullable();
            $table->foreignId('plazo_id')->constrained('tipo_plazos')->onDelete('cascade')->default(0);
            $table->integer('plazo')->nullable();
            $table->integer('plazo_ampliacion')->nullable(); // dato secundario
            $table->integer('plazo_reduccion')->nullable(); // dato secundario
            $table->date('fecha_inicio'); //automatico
            $table->date('fecha_termino'); //automatico
            $table->foreignId('estado_id')->constrained('estado_disposicion_fiscals')->onDelete('cascade')->default(1);
            $table->integer('referencia_fiscal_anterior')->nullable(); // si esta DF tiene alguna relacion anterior
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispocicion_fiscals');
    }
};
