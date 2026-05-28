<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('role')->default('user');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('orders', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

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
            'name' => 'Nguyen Van A',
            'phone' => '0909000000',
            'address' => '123 Street',
            'total_amount' => 150000,
            'status' => 'completed',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        Order::create([
            'name' => 'Nguyen Van A',
            'phone' => '0909000000',
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
