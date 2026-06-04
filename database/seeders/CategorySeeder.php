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
            ['id' => 4, 'name' => 'Trái cây sấy khô', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Trái cây hữu cơ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Trái cây theo mùa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Trái cây cắt sẵn', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Nước ép trái cây', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Hạt dinh dưỡng', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Đặc sản vùng miền', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}