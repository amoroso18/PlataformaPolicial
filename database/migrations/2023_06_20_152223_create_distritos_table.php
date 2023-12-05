<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('distrito', function (Blueprint $table) {
            $table->foreignId('iddistrito')->unique();
            $table->string('opcion',125)->nullable();
            $table->unsignedBigInteger('relacion');
            $table->integer('relacion_zona');
            $table->string('ubigeo',20)->nullable();
            $table->string('completo',500)->nullable();
        });
        // Schema::table('distrito', function(Blueprint $table) {
        //     $table->foreign('relacion')->references('idprovincia')->on('provincia')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('distrito');
    }
};


