<?php

namespace Database\Seeders;

use App\Models\Caja;
use Illuminate\Database\Seeder;

class CajaSeeder extends Seeder
{
    public function run(): void
    {
        Caja::updateOrCreate([
            'nombre' => 'Caja Mostrador',
        ], [
            'descripcion' => 'Caja opcional para ventas presenciales de BeniGlow',
            'activo' => true,
        ]);
    }
}
