<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->delete();

        $products = [
            // Nhóm 1: Nội địa (id_category = 1)
            ['name' => 'Xoài Cát Hòa Lộc', 'category_id' => 1, 'price' => 85000, 'unit' => 'kg', 'image' => 'xoai.jpg', 'description' => 'Xoài cát Hòa Lộc loại 1, thơm ngon đặc sản.'],
            ['name' => 'Vú Sữa Lò Rèn', 'category_id' => 1, 'price' => 65000, 'unit' => 'kg', 'image' => 'vusua.jpg', 'description' => 'Vú sữa chín cây, ngọt lịm.'],
            ['name' => 'Sầu Riêng Ri6', 'category_id' => 1, 'price' => 150000, 'unit' => 'kg', 'image' => 'saurieng.jpg', 'description' => 'Sầu riêng cơm vàng hạt lép.'],
            ['name' => 'Bưởi Da Xanh', 'category_id' => 1, 'price' => 70000, 'unit' => 'quả', 'image' => 'buoi.jpg', 'description' => 'Bưởi da xanh Bến Tre, mọng nước.'],
            ['name' => 'Măng Cụt Lái Thiêu', 'category_id' => 1, 'price' => 95000, 'unit' => 'kg', 'image' => 'mangcut.jpg', 'description' => 'Măng cụt đầu mùa giòn ngọt.'],
            ['name' => 'Cam Sành Hàm Yên', 'category_id' => 1, 'price' => 35000, 'unit' => 'kg', 'image' => 'cam.jpg', 'description' => 'Cam sành ngọt đậm, nhiều vitamin C.'],

            // Nhóm 2: Nhập khẩu (id_category = 2)
            ['name' => 'Táo Envy Mỹ', 'category_id' => 2, 'price' => 180000, 'unit' => 'kg', 'image' => 'taoenvy.jpg', 'description' => 'Táo Envy nhập khẩu Mỹ, giòn ngọt.'],
            ['name' => 'Nho Mẫu Đơn Nhật', 'category_id' => 2, 'price' => 850000, 'unit' => 'chùm', 'image' => 'nhomaudon.jpg', 'description' => 'Nho Shine Muscat thượng hạng.'],
            ['name' => 'Cherry Đỏ Mỹ', 'category_id' => 2, 'price' => 450000, 'unit' => 'kg', 'image' => 'cherry.jpg', 'description' => 'Cherry đỏ size lớn, mọng nước.'],
            ['name' => 'Kiwi Vàng New Zealand', 'category_id' => 2, 'price' => 120000, 'unit' => 'kg', 'image' => 'kiwi.jpg', 'description' => 'Kiwi vàng giàu dinh dưỡng.'],
            ['name' => 'Việt Quất Peru', 'category_id' => 2, 'price' => 90000, 'unit' => 'hộp', 'image' => 'vietquat.jpg', 'description' => 'Việt quất tươi ngon, bổ mắt.'],
            ['name' => 'Lê Hàn Quốc', 'category_id' => 2, 'price' => 130000, 'unit' => 'kg', 'image' => 'lehan.jpg', 'description' => 'Lê nâu Hàn Quốc, ngọt mát.'],

            // Nhóm 3: Giỏ quà (id_category = 3)
            ['name' => 'Giỏ Quà Phú Quý', 'category_id' => 3, 'price' => 1200000, 'unit' => 'giỏ', 'image' => 'gio1.jpg', 'description' => 'Kết hợp táo, nho và cherry.'],
            ['name' => 'Giỏ Quà An Khang', 'category_id' => 3, 'price' => 800000, 'unit' => 'giỏ', 'image' => 'gio2.jpg', 'description' => 'Giỏ trái cây nội địa cao cấp.'],
            ['name' => 'Lẵng Hoa Trái Cây', 'category_id' => 3, 'price' => 1500000, 'unit' => 'giỏ', 'image' => 'gio3.jpg', 'description' => 'Sự kết hợp hoàn hảo giữa hoa và quả.'],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}