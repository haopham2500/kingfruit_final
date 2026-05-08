<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->delete();

        User::create([
            'name' => 'Trương Giang Admin',
            'email' => 'admin@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Khách Hàng May Mắn',
            'email' => 'khachhang@gmail.com',
            'phone' => '0987654321',
            'password' => Hash::make('123456'),
            'role' => 'user',
        ]);
    }
}