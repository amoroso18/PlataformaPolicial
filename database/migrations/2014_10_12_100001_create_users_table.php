<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('carnet')->nullable();
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('unidad_id')->constrained('tipo_unidads')->onDelete('cascade')->default(0);
            $table->foreignId('estado_id')->constrained('estado_usuarios')->onDelete('cascade')->default(0);
            $table->foreignId('perfil_id')->constrained('tipo_perfils')->onDelete('cascade')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->datetime('reseteo_contrasena')->nullable();
            $table->string('token_seguridad')->nullable();
        });
        DB::table('users')->insert(
            array(
                [
                    'carnet' => '123',
                    'nombres' => 'Amoroso',
                    'apellidos' => 'COLQUE CAYPA',
                    'phone' => '957555221',
                    'email' => 'codicioso18@gmail.com',
                    'password' => '$2y$10$swaDVJtnZcHKDcLBYEFqsuEVx/8XsY2fdWRCVocQ7vtn9LV472Qry',
                    'unidad_id' => 1,
                    'estado_id' => 1,
                    'perfil_id' => 1,
                ],
                
              

            )
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
