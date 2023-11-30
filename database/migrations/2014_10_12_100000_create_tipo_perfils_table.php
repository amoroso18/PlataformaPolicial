<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_perfils', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        DB::table('tipo_perfils')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'NO TIENE PERFIL',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'COMANDO',
                ],
                [
                    'id' => 20,
                    'descripcion' => 'ADMINSITRADOR',
                ],
                [
                    'id' => 30,
                    'descripcion' => 'ANALISTA',
                ],
              

            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_perfils');
    }
};
