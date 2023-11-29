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
        Schema::create('tipo_plazos', function (Blueprint $table) {
            // $table->id(); 
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        // DB::statement('ALTER TABLE tipo_plazos AUTO_INCREMENT = 0;');
        DB::table('tipo_plazos')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'DÍAS NATURALES',
                    'estado' => 1
                ],
                [
                    'id' => 1,
                    'descripcion' => 'DÍAS HÁBILES',
                    'estado' => 2
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_plazos');
    }
};
