<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi các seeder con theo thứ tự logic
        $this->call([
            CategorySeeder::class, // Tạo danh mục trước (1)
            ProductSeeder::class,  // Tạo sản phẩm sau (2) - vì nó cần id của danh mục
            UserSeeder::class,     // Tạo tài khoản (3)
        ]);
    }
}