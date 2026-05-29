<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::updateOrCreate([
            'id' => 1,
        ], [
            'nombre_comercial' => env('BENIGLOW_NOMBRE_COMERCIAL', 'BeniGlow Store'),
            'razon_social' => env('BENIGLOW_RAZON_SOCIAL', 'Beniglow E.I.R.L.'),
            'ruc_nit' => env('BENIGLOW_RUC', '20600000001'),
            'direccion' => env('BENIGLOW_DIRECCION', 'Ciudad de Tacna, Perú'),
            'ciudad' => env('BENIGLOW_CIUDAD', 'Tacna'),
            'telefono' => env('BENIGLOW_TELEFONO', '993 902 669'),
            'email' => env('BENIGLOW_EMAIL', 'binitostore15@gmail.com'),
            'sitio_web' => env('BENIGLOW_SITIO_WEB', 'https://beniglow.com'),
            'logo' => 'beniglow-logo.png',
            'moneda' => 'S/',
            'codigo_moneda' => 'PEN',
            'impuesto' => 18.00,
            'impuesto_incluido' => true,
            'mensaje_ticket' => 'Gracias por comprar en BeniGlow.',
            'terminos_condiciones' => 'Cambios sujetos a verificacion del producto y comprobante de compra.',
        ]);
    }
}
