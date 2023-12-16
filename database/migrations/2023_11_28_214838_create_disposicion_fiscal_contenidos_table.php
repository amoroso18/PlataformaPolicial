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
        Schema::create('disposicion_fiscal_contenidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dfnv_id')->constrained('disposicion_fiscal_nueva_vigilancias')->onDelete('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('contenido_id')->constrained('tipo_contenidos')->onDelete('cascade');
            $table->datetime('fecha_hora_obtencion');
            $table->text('descripcion')->nullable();
            $table->string('observaciones',1500)->nullable();
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposicion_fiscal_contenidos');
    }
};
