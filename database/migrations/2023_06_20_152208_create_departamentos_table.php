<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('departamento', function (Blueprint $table) {
            $table->foreignId('iddepartamento')->unique();
            $table->string('opcion',255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departamento');
    }
};
