<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('nombre', 'Administrador')->firstOrFail();
        $password = env('BENIGLOW_ADMIN_PASSWORD');

        $blockedProductionPasswords = ['admin123', 'password', '12345678'];

        if (
            ! $password ||
            (app()->environment('production') && (
                str_starts_with($password, 'CAMBIAR_') ||
                in_array($password, $blockedProductionPasswords, true)
            ))
        ) {
            throw new RuntimeException('Define BENIGLOW_ADMIN_PASSWORD antes de ejecutar db:seed.');
        }

        User::updateOrCreate([
            'username' => env('BENIGLOW_ADMIN_USERNAME', 'admin'),
        ], [
            'name' => env('BENIGLOW_ADMIN_NAME', 'Administrador BeniGlow'),
            'email' => env('BENIGLOW_ADMIN_EMAIL', 'admin@beniglow.com'),
            'password' => Hash::make($password),
            'role_id' => $admin->id,
            'telefono' => env('BENIGLOW_ADMIN_PHONE', '999-000-000'),
            'activo' => true,
        ]);
    }
}
