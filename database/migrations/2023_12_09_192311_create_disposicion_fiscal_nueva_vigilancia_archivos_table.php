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
        Schema::create('disposicion_fiscal_nueva_vigilancia_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dfnve_id')->constrained('disposicion_fiscal_nueva_vigilancia_entidads')->onDelete('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ta_id')->constrained('tipo_contenidos')->onDelete('cascade')->default(0);
            $table->string('archivo')->nullable();
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposicion_fiscal_nueva_vigilancia_archivos');
    }
};
