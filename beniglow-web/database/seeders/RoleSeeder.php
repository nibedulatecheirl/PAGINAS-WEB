<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso completo al sistema',
                'permisos' => ['*'],
            ],
            [
                'nombre' => 'Gerente',
                'descripcion' => 'Gestion completa excepto configuracion del sistema',
                'permisos' => ['productos', 'ventas', 'compras', 'clientes', 'proveedores', 'caja', 'reportes', 'promociones', 'pedidos-web'],
            ],
            [
                'nombre' => 'Cajero',
                'descripcion' => 'Acceso al punto de venta y caja',
                'permisos' => ['ventas', 'caja', 'clientes'],
            ],
            [
                'nombre' => 'Almacenero',
                'descripcion' => 'Gestion de inventario y compras',
                'permisos' => ['productos', 'compras', 'proveedores', 'reportes'],
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate([
                'nombre' => $role['nombre'],
            ], [
                'descripcion' => $role['descripcion'],
                'permisos' => $role['permisos'],
                'activo' => true,
            ]);
        }
    }
}
