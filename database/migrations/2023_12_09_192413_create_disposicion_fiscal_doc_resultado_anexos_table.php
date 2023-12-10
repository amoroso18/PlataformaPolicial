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
        Schema::create('disposicion_fiscal_doc_resultado_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dfdr_id')->constrained('disposicion_fiscal_doc_resultados')->onDelete('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('contenidos_id')->constrained('tipo_contenidos')->onDelete('cascade');
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
        Schema::dropIfExists('disposicion_fiscal_doc_resultado_anexos');
    }
};
