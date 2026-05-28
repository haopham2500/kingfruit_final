<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPlaceOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_redirects_to_home_with_success_message_after_order_placement(): void
    {
        $user = User::create([
            'name' => 'Nguyen Van A',
            'email' => 'a@example.com',
            'password' => bcrypt('password'),
            'phone' => '0909000000',
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'cart' => [
                    1 => [
                        'name' => 'Cam',
                        'price' => 500000,
                        'quantity' => 2,
                    ],
                ],
            ])
            ->post(route('checkout.placeOrder'), [
                'customer_name' => 'Nguyen Van A',
                'phone' => '0909000000',
                'address' => '123 Street',
                'note' => 'Giao buổi sáng',
                'payment_method' => 'cod',
            ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');
    }
}
