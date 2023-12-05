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
        Schema::create('tipo_nacionalidads', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
                $table->string('descripcion')->nullable();
                $table->integer('estado')->default(1);
                $table->timestamps();
         
        });
        DB::table('tipo_nacionalidads')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'NO TIENE',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'PERUANO',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'VENEZOLANO',
                ],
                [
                    'id' => 3,
                    'descripcion' => 'COLOMBIANO',
                ],
                [
                    'id' => 4,
                    'descripcion' => 'ECUATORIANO',
                ],
                [
                    'id' => 5,
                    'descripcion' => 'CHILENO',
                ],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_nacionalidads');
    }
};
