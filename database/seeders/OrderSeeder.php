<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Xóa dữ liệu cũ (Tắt check khóa ngoại để tránh lỗi)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Order::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create('vi_VN');
        
        // Lấy danh sách ID của user đang có trong máy ní
        $userIds = User::pluck('id')->toArray();
        
        if (empty($userIds)) {
            $this->command->info('Loi: Bang users dang trong. Hay chay UserSeeder truoc!');
            return;
        }

        $statuses = ['pending', 'processing', 'completed', 'cancelled', 'refunded', 'returning'];

        // 2. Tạo 10 đơn hàng mẫu
        for ($i = 1; $i <= 10; $i++) {
            Order::create([
                'user_id'       => $faker->randomElement($userIds),
                'receiver_name' => $faker->name,
                'phone_number'  => $faker->phoneNumber,
                'address'       => $faker->address,
                'total_amount'  => $faker->numberBetween(100, 2000) * 1000, // Từ 100k đến 2 triệu
                'status'        => $faker->randomElement($statuses),
                'created_at'    => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at'    => now(),
            ]);
        }

        $this->command->info('Da tao thanh cong 10 don hang mau cho King Fruit!');
    }
}