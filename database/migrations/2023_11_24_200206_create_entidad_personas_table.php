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
        Schema::create('entidad_personas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nacionalidad_id')->constrained('tipo_nacionalidads')->onDelete('cascade')->default(0);
            $table->foreignId('documento_id')->constrained('tipo_documento_identidads')->onDelete('cascade')->default(0);
            $table->string('documento')->nullable();
            $table->string('nombres')->nullable();
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('sexo')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('distrito_nacimiento')->nullable();
            $table->string('lugar_nacimiento',2500)->nullable();
            $table->integer('distrito_domicilio')->nullable();
            $table->string('lugar_domicilio',2500)->nullable();
            $table->string('foto')->nullable();
            $table->string('firma')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_personas');
    }
};
