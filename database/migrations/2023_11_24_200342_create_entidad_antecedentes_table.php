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
        Schema::create('entidad_antecedentes', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('nrodocumento', 50)->nullable();
            $table->string('paterno', 100)->nullable();
            $table->string('materno', 100)->nullable();
            $table->string('nombres', 100)->nullable();
            $table->string('delitos', 1000)->nullable();
            $table->string('situacion', 100)->nullable();
            $table->string('autoridadjudicial', 100)->nullable();
            $table->string('documento', 100)->nullable();
            $table->string('fechadocumento')->nullable();
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_antecedentes');
    }
};
