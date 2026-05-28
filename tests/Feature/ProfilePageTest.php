<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile_with_order_history(): void
    {
        $user = User::create([
            'name' => 'Nguyen Van A',
            'email' => 'a@example.com',
            'password' => bcrypt('password'),
            'phone' => '0909000000',
            'role' => 'user',
        ]);

        Order::create([
            'receiver_name' => 'Nguyen Van A',
            'phone_number' => '0909000000',
            'address' => '123 Street',
            'total_amount' => 150000,
            'status' => 'completed',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        Order::create([
            'receiver_name' => 'Nguyen Van A',
            'phone_number' => '0909000000',
            'address' => '123 Street',
            'total_amount' => 250000,
            'status' => 'processing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('Nguyen Van A');
        $response->assertSee('0909000000');
        $response->assertSee('a@example.com');
        $response->assertSee('Lịch sử đơn hàng đã mua');
        $response->assertSee('Theo dõi đơn hàng đã đặt');
        $response->assertSee('Đã hoàn thành');
        $response->assertSee('Đang xử lý');
    }
}
