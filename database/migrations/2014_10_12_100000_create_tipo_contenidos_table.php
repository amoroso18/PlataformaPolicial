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
        Schema::create('tipo_contenidos', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion');
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        DB::table('tipo_contenidos')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'TEXTO',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'PDF',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'VIDEO',
                ],
                [
                    'id' => 3,
                    'descripcion' => 'AUDIO',
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_contenidos');
    }
};
