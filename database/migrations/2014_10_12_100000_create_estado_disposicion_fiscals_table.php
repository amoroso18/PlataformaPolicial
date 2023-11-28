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
        Schema::create('estado_disposicion_fiscals', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary()->unique()->index();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->integer('estado')->default(1);
        });
        DB::table('estado_disposicion_fiscals')->insert(
            array(
                [
                    'id' => 0,
                    'descripcion' => 'PENDIENTE',
                ],
                [
                    'id' => 1,
                    'descripcion' => 'ACTIVO',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'CULMINADO',
                ],
                [
                    'id' => 3,
                    'descripcion' => 'ELIMINADO',
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_disposicion_fiscals');
    }
};
