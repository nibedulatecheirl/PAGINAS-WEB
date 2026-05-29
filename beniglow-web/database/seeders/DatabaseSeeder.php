<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            EmpresaSeeder::class,
            ConfiguracionSeeder::class,
            CajaSeeder::class,
            BeniglowCatalogSeeder::class,
        ]);
    }
}
