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
        Schema::create('tipo_documento_identidads', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('tipo')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
        DB::table('tipo_documento_identidads')->insert(
            array(
                [
                    'id' => 0,
                    'tipo' => 'NO TIENE',
                    'descripcion' => 'NO TIENE',
                ],
                [
                    'id' => 1,
                    'tipo' => 'PERUANO',
                    'descripcion' => 'DNI',
                ],
                [
                    'id' => 2,
                    'tipo' => 'EXTRANJERO',
                    'descripcion' => 'PTP',
                ],
                [
                    'id' => 3,
                    'tipo' => 'EXTRANJERO',
                    'descripcion' => 'CARNET DE EXTRANJERIA',
                ],
                [
                    'id' => 4,
                    'tipo' => 'EXTRANJERO',
                    'descripcion' => 'PASAPORTE PERUANO',
                ],
                [
                    'id' => 5,
                    'tipo' => 'EXTRANJERO',
                    'descripcion' => 'CPP',
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documento_identidads');
    }
};
