<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Xóa sạch dữ liệu cũ và reset ID
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $password = Hash::make('123456');
        $faker = Faker::create('vi_VN'); // Sử dụng dữ liệu tiếng Việt cho thân thiện

        // 2. Tạo tài khoản Admin cố định để ní và Giang đăng nhập
        User::create([
            'name' => 'Trương Giang Admin',
            'email' => 'admin@gmail.com',
            'phone' => '0123456789',
            'password' => $password,
            'role' => 'admin',
            'address' => 'Hà Nội, Việt Nam',
        ]);

        // 3. Tạo thêm 10 users ngẫu nhiên
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'password' => $password,
                'role' => 'user',
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Tạo thêm 1 user bị khóa (banned) để test chức năng chặn đăng nhập
        User::create([
            'name' => 'Người Dùng Bị Khóa',
            'email' => 'banned@gmail.com',
            'phone' => '0999888777',
            'password' => $password,
            'role' => 'banned',
            'address' => 'Sài Gòn, Việt Nam',
        ]);
    }
}