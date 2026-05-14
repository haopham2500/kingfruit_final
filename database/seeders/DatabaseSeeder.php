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
            CategorySeeder::class, // (1) Danh mục trước để sản phẩm có chỗ bám
            ProductSeeder::class,  // (2) Sản phẩm cần id danh mục
            UserSeeder::class,     // (3) Tài khoản khách hàng/admin
            VoucherSeeder::class,  // (4) Voucher (nếu có dùng trong đơn hàng)
            OrderSeeder::class,    // (5) Đơn hàng cuối cùng (vì nó cần id của User)
        ]);
    }
}