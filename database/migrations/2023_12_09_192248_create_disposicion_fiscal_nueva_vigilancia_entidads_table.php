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
        Schema::create('disposicion_fiscal_nueva_vigilancia_entidads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dfnva_id')->constrained('disposicion_fiscal_nueva_vigilancia_actividads')->onDelete('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('entidads_id')->constrained('tipo_entidads')->onDelete('cascade');
            $table->integer('codigo_relacion');
            $table->text('detalle')->nullable();
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposicion_fiscal_nueva_vigilancia_entidads');
    }
};
