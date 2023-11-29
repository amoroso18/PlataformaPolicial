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
        Schema::create('disposicion_fiscal_entidad_vigilancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('df_id')->constrained('dispocicion_fiscals')->onDelete('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');

            // relacionar con entidades PERUANOS, EXTRANJEROS, DOMICILIOS ....
            $table->string('observaciones',1000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposicion_fiscal_entidad_vigilancias');
    }
};
