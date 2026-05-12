<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa sạch dữ liệu cũ để reset
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Voucher::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $vouchers = [
            [
                'code' => 'KINGFRUIT10',
                'type' => 'percent',
                'discount_value' => 10,
                'min_order_value' => 150000,
                'quantity' => 100,
                'expiry_date' => '2026-12-31',
                'is_active' => 1
            ],
            [
                'code' => 'GIAYPHUTDAU', // Giảm 20k cho đơn đầu
                'type' => 'fixed',
                'discount_value' => 20000,
                'min_order_value' => 0,
                'quantity' => 50,
                'expiry_date' => '2026-08-15',
                'is_active' => 1
            ],
            [
                'code' => 'FREESHIP', // Giảm 15k coi như phí ship
                'type' => 'fixed',
                'discount_value' => 15000,
                'min_order_value' => 200000,
                'quantity' => 200,
                'expiry_date' => '2026-10-10',
                'is_active' => 1
            ],
            [
                'code' => 'FRUITVIP50', // Giảm 50k cho đơn lớn
                'type' => 'fixed',
                'discount_value' => 50000,
                'min_order_value' => 500000,
                'quantity' => 30,
                'expiry_date' => '2026-11-20',
                'is_active' => 1
            ],
            [
                'code' => 'HELLOSUMMER', // Giảm 15% mùa hè
                'type' => 'percent',
                'discount_value' => 15,
                'min_order_value' => 300000,
                'quantity' => 80,
                'expiry_date' => '2026-07-30',
                'is_active' => 1
            ],
            [
                'code' => 'DEALHOCSINH', // Giảm 5k cho đơn nhỏ
                'type' => 'fixed',
                'discount_value' => 5000,
                'min_order_value' => 50000,
                'quantity' => 500,
                'expiry_date' => '2026-12-31',
                'is_active' => 1
            ],
            [
                'code' => 'ANSATCHAO', // Giảm mạnh 30%
                'type' => 'percent',
                'discount_value' => 30,
                'min_order_value' => 400000,
                'quantity' => 20,
                'expiry_date' => '2026-09-09',
                'is_active' => 1
            ],
            [
                'code' => 'LUCKY777', // Mã may mắn
                'type' => 'fixed',
                'discount_value' => 7777,
                'min_order_value' => 77000,
                'quantity' => 77,
                'expiry_date' => '2026-07-07',
                'is_active' => 1
            ],
            [
                'code' => 'HETHAN2025', // Mã để test lỗi hết hạn
                'type' => 'fixed',
                'discount_value' => 100000,
                'min_order_value' => 0,
                'quantity' => 10,
                'expiry_date' => '2025-01-01', 
                'is_active' => 1
            ],
            [
                'code' => 'HETLUOTDUNG', // Mã để test lỗi hết số lượng
                'type' => 'percent',
                'discount_value' => 50,
                'min_order_value' => 0,
                'quantity' => 0, 
                'expiry_date' => '2026-12-31',
                'is_active' => 1
            ],
        ];

        foreach ($vouchers as $v) {
            Voucher::create($v);
        }
    }
}