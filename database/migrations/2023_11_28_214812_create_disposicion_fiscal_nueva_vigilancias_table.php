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
        Schema::create('disposicion_fiscal_nueva_vigilancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('df_id')->constrained('dispocicion_fiscals')->onDelete('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('documentos_id')->constrained('tipo_documentos_referencias')->onDelete('cascade');
            $table->string('numeroDocumento')->nullable();
            $table->string('siglasDocumento')->nullable();
            $table->date('fechaDocumento')->nullable();
            $table->string('asunto',500)->nullable();
            $table->string('respondea',500)->nullable();
            $table->string('evaluacion',500)->nullable();
            $table->string('conclusiones',1000)->nullable();
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
        Schema::dropIfExists('disposicion_fiscal_nueva_vigilancias');
    }
};
