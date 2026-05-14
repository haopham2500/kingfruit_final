<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        $vouchers = Voucher::where('expiry_date', '>=', now())
                           ->where('quantity', '>', 0)
                           ->get();

        return view('checkout.index', compact('vouchers'));
    }

    /**
     * Xử lý lưu đơn hàng khi nhấn Xác nhận đặt hàng
     */
    public function placeOrder(Request $request)
    {
        // 1. Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng đang trống!');
        }

        // 2. Tính tổng tiền hàng (Tạm tính)
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // 3. Xử lý giảm giá (Logic sửa lỗi tiền âm)
        $discountAmount = 0;
        if ($request->has('voucher_code') && !empty($request->voucher_code)) {
            $voucher = Voucher::where('code', $request->voucher_code)
                              ->where('expiry_date', '>=', now())
                              ->first();
            
            if ($voucher) {
                $discountAmount = $voucher->discount_value;
                
                // Nếu tiền giảm lớn hơn tổng đơn hàng, chỉ giảm tối đa bằng tổng đơn
                if ($discountAmount > $subtotal) {
                    $discountAmount = $subtotal;
                }
            }
        }

        // 4. Tính tổng tiền thực tế khách phải trả
        $finalTotal = $subtotal - $discountAmount;

        // 5. Lưu thông tin đơn hàng vào bảng orders
        $order = Order::create([
            'name'           => $request->customer_name,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'note'           => $request->note,
            'payment_method' => $request->payment_method,
            'total_amount'   => $finalTotal, // Lưu số tiền đã xử lý (không bị âm)
            'status'         => 'pending',
        ]);

        // 6. Lưu chi tiết từng sản phẩm vào bảng order_items
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $id,
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
        }

        // 7. Xóa giỏ hàng sau khi đặt thành công
        session()->forget('cart');

        return redirect('/')->with('success', 'Đặt hàng thành công! Tổng thanh toán: ' . number_format($finalTotal) . 'đ');
    }
}