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
        Schema::create('tipo_video_vigilancias', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        DB::table('tipo_video_vigilancias')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'VIGILANCIA',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'SEGUIMIENTO',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'VIGILANCIA Y SEGUIMIENTO',
                ],
                [
                    'id' => 3,
                    'descripcion' => 'VIGILANCIA ELECTRÓNICA',
                ],
                [
                    'id' => 4,
                    'descripcion' => 'OTROS',
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_video_vigilancias');
    }
};
