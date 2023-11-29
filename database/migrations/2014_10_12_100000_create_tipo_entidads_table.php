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
        Schema::create('tipo_entidads', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        DB::table('tipo_entidads')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'EFECTIVO POLICIAL',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'CIUDADANO PERUANO',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'CIUDADANO EXTRANJERO',
                ],
                [
                    'id' => 3,
                    'descripcion' => 'VEHICULO',
                ],
                [
                    'id' => 4,
                    'descripcion' => 'INMUEBLE',
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_entidads');
    }
};
