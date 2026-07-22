<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Administrator Kampung Mbu', 'admin@kampungmbu.go.id', 'administrator', env('SEED_ADMIN_PASSWORD', 'password')],
            ['Wemiron Kiwo', 'operator@kampungmbu.go.id', 'operator', env('SEED_OPERATOR_PASSWORD', 'password')],
        ] as [$name, $email, $role, $password]) {
            $user = User::query()->firstOrNew(['email' => $email]);
            $user->fill([
                'name' => $name,
                'email_verified_at' => $user->email_verified_at ?? now(),
                'role' => $role,
                'status' => 'aktif',
            ]);

            if (! $user->exists) {
                $user->password = Hash::make($password);
            }

            $user->save();
        }
    }
}
