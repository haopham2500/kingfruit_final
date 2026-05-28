<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_track_orders_and_cancel_pending_order(): void
    {
        $user = User::create([
            'name' => 'Nguyen Van A',
            'email' => 'a@example.com',
            'password' => bcrypt('password'),
            'phone' => '0909000000',
            'role' => 'user',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'receiver_name' => 'Nguyen Van A',
            'phone_number' => '0909000000',
            'address' => '123 Street',
            'total_amount' => 150000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $trackResponse = $this->actingAs($user)->get(route('orders.track'));
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee('Theo dõi đơn hàng đã đặt');
        $trackResponse->assertSee('pending');

        $cancelResponse = $this->actingAs($user)->post(route('orders.cancel', $order->id), [
            'reason' => 'Đổi ý không mua nữa',
        ]);

        $cancelResponse->assertRedirect();
        $this->assertSame('cancelled', Order::find($order->id)->status);
        $this->assertSame('Đổi ý không mua nữa', Order::find($order->id)->cancel_reason);
    }
}
