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
        Schema::create('entidad_policias', function (Blueprint $table) {
            $table->id();
            $table->integer('carnet')->nullable();
            $table->integer('dni')->nullable();
            $table->string('nombres')->nullable();
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('grado')->nullable();
            $table->foreignId('grado_id')->constrained('tipo_grados')->onDelete('cascade')->default(0);
            $table->string('unidad')->nullable();
            $table->foreignId('unidad_id')->constrained('tipo_unidads')->onDelete('cascade')->default(0);
            $table->string('situacion')->nullable();
            $table->timestamps();
        });
        DB::table('entidad_policias')->insert(
            array(
                [
                    'carnet' => 223356,
                    'dni' => 778899,
                    'nombres' => 'Pedro',
                    'paterno' => 'Infantes',
                    'materno' => 'Infantes',
                    'grado' => 'General',
                    'grado_id' => 1,
                    'unidad' => 'Sin información',
                    'unidad_id' => 1,
                    'situacion' => 'En Actividad',
                ],
                [
                    'carnet' => 223357,
                    'dni' => 66998877,
                    'nombres' => 'Dina',
                    'paterno' => 'Garcia',
                    'materno' => 'Garcia',
                    'grado' => 'General',
                    'grado_id' => 1,
                    'unidad' => 'Sin información',
                    'unidad_id' => 1,
                    'situacion' => 'En Actividad',
                ],
                [
                    'carnet' => 366565,
                    'dni' => 65659898,
                    'nombres' => 'Ollanta',
                    'paterno' => 'Humala',
                    'materno' => 'Humala',
                    'grado' => 'General',
                    'grado_id' => 1,
                    'unidad' => 'Sin información',
                    'unidad_id' => 1,
                    'situacion' => 'En Actividad',
                ],
             
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidad_policias');
    }
};
