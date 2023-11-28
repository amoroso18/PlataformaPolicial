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
        Schema::create('estado_usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        DB::table('estado_usuarios')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'SITUACIÓN DESCONOCIDA',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'ACTIVO',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'SUSPENDIDO',
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_usuarios');
    }
};
