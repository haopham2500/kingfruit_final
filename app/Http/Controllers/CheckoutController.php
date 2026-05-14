<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher; // Quan trọng: Phải có dòng này để lấy dữ liệu voucher

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        // 1. Lấy danh sách voucher còn hạn và còn số lượng từ database
        $vouchers = Voucher::where('expiry_date', '>=', now())
                           ->where('quantity', '>', 0)
                           ->get();

        // 2. Truyền biến $vouchers sang view checkout/index.blade.php
        return view('checkout.index', compact('vouchers'));
    }

    /**
     * Xử lý lưu đơn hàng khi nhấn Xác nhận đặt hàng
     */
    public function placeOrder(Request $request)
    {
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng đang trống!');
        }

        // Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Lưu thông tin đơn hàng vào bảng orders
        $order = Order::create([
            'name'           => $request->customer_name,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'note'           => $request->note,
            'payment_method' => $request->payment_method,
            'total_amount'   => $total,
            'status'         => 'pending',
        ]);

        // Lưu chi tiết từng sản phẩm vào bảng order_items
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $id,
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
        }

        // Xóa giỏ hàng sau khi đặt thành công
        session()->forget('cart');

        return redirect('/')->with('success', 'Đặt hàng thành công!');
    }
}