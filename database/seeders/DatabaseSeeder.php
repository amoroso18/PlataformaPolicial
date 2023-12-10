<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DepaSeeder::class);
        $this->call(ProvSeeder::class);
        $this->call(DistSeeder::class);
        $this->call(EntidadesSeeder::class);
    }
}
