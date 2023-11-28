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
        Schema::create('tipo_grados', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion');
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        DB::table('tipo_grados')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'NO TIENE',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'GENERAL',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'CORONEL',
                ],
                [
                    'id' => 3,
                    'descripcion' => 'COMANDANTE',
                ],
                [
                    'id' => 4,
                    'descripcion' => 'MAYOR',
                ],
                [
                    'id' => 5,
                    'descripcion' => 'CAPITAN',
                ],
                [
                    'id' => 6,
                    'descripcion' => 'TENIENTE',
                ],
                [
                    'id' => 7,
                    'descripcion' => 'ALFEREZ',
                ],
                [
                    'id' => 8,
                    'descripcion' => 'SUBOFICIAL SUPERIOR',
                ],
                [
                    'id' => 9,
                    'descripcion' => 'SUBOFICIAL BRIGADIER',
                ],
                [
                    'id' => 10,
                    'descripcion' => 'SUBOFICIAL TÉCNICO',
                ],
                [
                    'id' => 11,
                    'descripcion' => 'SUBOFICIAL',
                ],
                [
                    'id' => 12,
                    'descripcion' => 'EMPLEADO CIVIL',
                ],
                [
                    'id' => 13,
                    'descripcion' => 'INVITADO',
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_grados');
    }
};
