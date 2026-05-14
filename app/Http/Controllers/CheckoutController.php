<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    // Hiển thị trang checkout
    public function index()
    {
        return view('checkout.index');
    }

    // Xử lý đặt hàng
    public function placeOrder(Request $request)
    {
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        // Nếu giỏ hàng trống
        if (empty($cart)) {

            return redirect()
                ->back()
                ->with('error', 'Giỏ hàng đang trống!');
        }

        // Tính tổng tiền
        $total = 0;

        foreach ($cart as $item) {

            $total += $item['price'] * $item['quantity'];
        }

        // Lưu đơn hàng
       $order = Order::create([

    'name' => $request->customer_name,

    'phone' => $request->phone,

    'address' => $request->address,

    'note' => $request->note,

    'payment_method' => $request->payment_method,

    'total_amount' => $total,

    'status' => 'pending',
]);
        // Lưu chi tiết đơn hàng
        foreach ($cart as $id => $item) {

            OrderItem::create([

                'order_id' => $order->id,

                'product_id' => $id,

                'quantity' => $item['quantity'],

                'price' => $item['price'],
            ]);
        }

        // Xóa giỏ hàng
        session()->forget('cart');

        // Chuyển về trang chủ
        return redirect('/')
            ->with('success', 'Đặt hàng thành công!');
    }
}