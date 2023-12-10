<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EntidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = database_path("sql/entidad_antecedentes.sql");
        DB::unprepared(file_get_contents($sql));
        $sql = database_path("sql/entidad_denuncias.sql");
        DB::unprepared(file_get_contents($sql));
        $sql = database_path("sql/entidad_requisitoria_peruanos.sql");
        DB::unprepared(file_get_contents($sql));
        $sql = database_path("sql/entidad_requisitoria_vehiculos.sql");
        DB::unprepared(file_get_contents($sql));
    }
}