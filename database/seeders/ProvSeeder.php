<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = database_path("sql/provincia.sql");
        DB::unprepared(file_get_contents($sql));
        $sql = database_path("sql/entidad_personas.sql");
        DB::unprepared(file_get_contents($sql));
    }
}