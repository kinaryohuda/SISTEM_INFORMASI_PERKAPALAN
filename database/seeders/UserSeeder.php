<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk user.
     */
    public function run(): void
    {
        //Super Admin (hanya satu)
        User::create([
            'name' => 'Super Admin',
            'nik' => '1234567890123456',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Super Admin No.1',
            'email' => 'superAdmin@example.com',
            'password' => Hash::make('SuperSecure123!'),
            'role' => 'superAdmin',
        ]);

        //Admin biasa
        User::create([
            'name' => 'Admin',
            'nik' => '1234567890123457',
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Admin No.2',
            'email' => 'admin@example.com',
            'password' => Hash::make('AdminSecure123!'),
            'role' => 'admin',
        ]);

        //User contoh 1
        User::create([
            'name' => 'Kinaryo',
            'nik' => '1234567890123458',
            'no_hp' => '081234567892',
            'alamat' => 'Jl. User No.1',
            'email' => 'kinaryo@gmail.com',
            'password' => Hash::make('Password123@'),
            'role' => 'user',
        ]);

        //User contoh 2
        User::create([
            'name' => 'User Dua',
            'nik' => '1234567890123459',
            'no_hp' => '081234567893',
            'alamat' => 'Jl. User No.2',
            'email' => 'user2@example.com',
            'password' => Hash::make('UserSecure123!'),
            'role' => 'user',
        ]);
    }
}
