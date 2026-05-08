<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // << THÊM DÒNG NÀY NÈ NÍ

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Vì ní dùng hệ Import SQL, nên dùng delete() thay vì truncate() cho an toàn
        DB::table('categories')->delete(); 

        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Trái cây nội địa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Trái cây nhập khẩu', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Giỏ quà trái cây', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}