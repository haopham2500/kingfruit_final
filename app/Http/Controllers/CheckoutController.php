<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
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
        $collectedCodes = session()->get('collected_vouchers', []);

        $vouchers = Voucher::whereIn('code', $collectedCodes)
                           ->where('expiry_date', '>=', now())
                           ->where('quantity', '>', 0)
                           ->get();

        return view('checkout.index', compact('vouchers'));
    }

    /**
     * Theo dõi đơn hàng của người dùng đã đăng nhập.
     */
    public function trackOrders()
    {
        $user = auth()->user();
        $orders = $this->getUserOrders($user);

        return view('orders.track', compact('orders'));
    }

    /**
     * Hủy đơn hàng với lý do.
     */
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id && auth()->id() !== $order->user_id) {
            abort(403, 'Bạn không có quyền hủy đơn hàng này.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        if (!in_array($order->status, ['pending', 'processing'])) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.order_cannot_cancel')
                ]);
            }
            return back()->with('error', __('messages.order_cannot_cancel'));
        }

        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $validated['reason'],
        ]);

        if ($request->ajax()) {
            session()->flash('success', __('messages.order_cancelled_success'));
            return response()->json([
                'success' => true,
                'message' => 'Hủy đơn hàng thành công!'
            ]);
        }

        return back()->with('success', __('messages.order_cancelled_success'));
    }

    /**
     * Xử lý lưu đơn hàng khi nhấn Xác nhận đặt hàng
     */
    public function placeOrder(Request $request)
    {
        // 1. Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng đang trống!'
                ]);
            }
            return redirect()->back()->with('error', 'Giỏ hàng đang trống!');
        }

        // 2. Tính tổng tiền hàng (Lấy giá từ DB để bảo mật)
        $subtotal = 0;
        $actualCart = [];
        foreach ($cart as $id => $item) {
            $product = \App\Models\Product::find($id);
            if ($product) {
                $actualPrice = $product->price;
                $quantity = max(1, (int)$item['quantity']);
                $subtotal += $actualPrice * $quantity;
                $actualCart[$id] = [
                    'price' => $actualPrice,
                    'quantity' => $quantity
                ];
            }
        }

        if (empty($actualCart)) {
            return redirect()->back()->with('error', 'Sản phẩm trong giỏ không hợp lệ!');
        }

        // 3. Xử lý giảm giá (Logic sửa lỗi tiền âm & sửa lỗi type)
        $discountAmount = 0;
        $appliedVoucher = null;
        if ($request->has('voucher_code') && !empty($request->voucher_code)) {
            $voucher = Voucher::where('code', $request->voucher_code)->first();
            
            // Dùng hàm isValid() có sẵn trong Model Voucher
            if ($voucher && $voucher->isValid($subtotal)) {
                $appliedVoucher = $voucher;

                if ($voucher->type == 'percent') {
                    $discountAmount = ($subtotal * $voucher->discount_value) / 100;
                } else {
                    $discountAmount = $voucher->discount_value;
                }
                
                // Nếu tiền giảm lớn hơn tổng đơn hàng, chỉ giảm tối đa bằng tổng đơn
                if ($discountAmount > $subtotal) {
                    $discountAmount = $subtotal;
                }
            }
        }

        // 4. Tính tổng tiền thực tế khách phải trả
        $finalTotal = $subtotal - $discountAmount;

        // 5. Lưu thông tin đơn hàng vào bảng orders
        $orderData = [
            'user_id'        => auth()->id(),
            'receiver_name'  => $request->customer_name,
            'phone_number'   => $request->phone,
            'address'        => $request->address,
            'total_amount'   => $finalTotal,
            'status'         => 'pending',
        ];

        if (Schema::hasColumn('orders', 'note')) {
            $orderData['note'] = $request->note;
        }

        if (Schema::hasColumn('orders', 'payment_method')) {
            $orderData['payment_method'] = $request->payment_method;
        }

        $order = Order::create($orderData);

        // 6. Lưu chi tiết từng sản phẩm vào bảng order_items
        foreach ($actualCart as $id => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $id,
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
        }

        // Trừ số lượng Voucher
        if ($appliedVoucher) {
            $appliedVoucher->decrement('quantity', 1);
        }

        // 7. Xóa giỏ hàng sau khi đặt thành công
        session()->forget('cart');

        if ($request->ajax()) {
            session()->flash('success', __('messages.order_placed_success', ['amount' => number_format($finalTotal)]));
            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công!',
                'redirect_url' => route('home')
            ]);
        }

        return redirect()->route('home')->with('success', __('messages.order_placed_success', ['amount' => number_format($finalTotal)]));
    }

    /**
     * Lấy danh sách đơn hàng của người dùng, hỗ trợ dữ liệu legacy không có user_id.
     */
    protected function getUserOrders($user)
    {
        if (!$user) {
            return collect();
        }

        $orders = $user->orders()->orderBy('created_at', 'desc')->get();

        if ($orders->isEmpty()) {
            $orders = Order::query();

            if (Schema::hasColumn('orders', 'receiver_name')) {
                $orders = $orders->where('receiver_name', $user->name);
            }

            if (Schema::hasColumn('orders', 'phone_number')) {
                $orders = $orders->orWhere('phone_number', $user->phone);
            }

            $orders = $orders->orderBy('created_at', 'desc')->get();
        }

        return $orders;
    }
}