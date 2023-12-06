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
        Schema::create('entidad_inmuebles_propietarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inmuebles_id')->constrained('entidad_inmuebles')->onDelete('cascade')->default(0);
            $table->foreignId('personas_id')->constrained('entidad_personas')->onDelete('cascade')->default(0);
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_inmuebles_propietarios');
    }
};
