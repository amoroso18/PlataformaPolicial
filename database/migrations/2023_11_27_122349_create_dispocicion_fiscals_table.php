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
            $table->string('carpeta')->nullable();
            $table->foreignId('fiscal_responsable_id')->constrained('entidad_fiscals')->onDelete('cascade')->default(0);
            $table->foreignId('fiscal_asistente_id')->constrained('entidad_fiscals')->onDelete('cascade')->default(0);

            $table->string('resumen',1000)->nullable();
            $table->string('observaciones',1000)->nullable();

            $table->foreignId('plazo_id')->constrained('tipo_plazos')->onDelete('cascade')->default(0);
            $table->datetime('fecha_hora_inicio');
            $table->datetime('fecha_hora_termino');

            $table->foreignId('estado_id')->constrained('estado_disposicion_fiscals')->onDelete('cascade')->default(0);

            $table->string('pdf')->nullable();
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
