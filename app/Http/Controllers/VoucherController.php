<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    // Trang quản trị danh sách Voucher
    public function index() {
        $vouchers = Voucher::orderBy('id', 'desc')->get();
        return view('admin.vouchers', compact('vouchers')); 
    }

    // Lưu voucher mới
    public function store(Request $request) {
        $request->validate([
            'code' => 'required|unique:vouchers,code',
            'discount_value' => 'required|numeric',
            'expiry_date' => 'required|date|after_or_equal:today',
            'quantity' => 'required|integer|min:1'
        ]);

        Voucher::create($request->all());
        return back()->with('success', 'Tạo mã thành công!');
    }

    // Xóa voucher
    public function destroy($id) {
        Voucher::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa voucher!');
    }

    // Trang sửa voucher
    public function edit($id) {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers_edit', compact($voucher));
    }

    // Cập nhật voucher
    public function update(Request $request, $id) {
        $data = $request->validate([
            'code' => 'required|unique:vouchers,code,'.$id,
            'discount_value' => 'required|numeric',
            'expiry_date' => 'required|date',
            'type' => 'required|in:fixed,percent',
            'min_order_value' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        Voucher::findOrFail($id)->update($data);
        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật xong rồi nhé ní!');
    }

    // Trang hiển thị khuyến mãi cho khách (Hiển thị tất cả)
    public function showPromotions() {
        $vouchers = Voucher::orderBy('expiry_date', 'desc')->get();
        return view('client.promotions', compact('vouchers'));
    }

    // Logic "Lấy mã" - Lưu vào Session
    public function collectVoucher(Request $request) {
        $collected = session()->get('collected_vouchers', []);

        if (!in_array($request->code, $collected)) {
            $collected[] = $request->code;
            session()->put('collected_vouchers', $collected);
        }

        return response()->json(['success' => true, 'message' => 'Đã thu thập mã!']);
    }

    // Logic áp dụng mã trong trang Checkout
    public function applyVoucher(Request $request) {
        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã không tồn tại!']);
        }

        if ($voucher->expiry_date < now()->format('Y-m-d')) {
            return response()->json(['success' => false, 'message' => 'Mã này đã hết hạn!']);
        }

        if ($voucher->quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Mã này đã hết lượt dùng!']);
        }

        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) { $total += $item['price'] * $item['quantity']; }

        // Tính toán số tiền giảm
        $discount = $voucher->discount_value; 
        if ($voucher->type == 'percent') {
            $discount = ($total * $voucher->discount_value) / 100;
        }

        // Chặn tiền âm
        $actualDiscount = ($discount > $total) ? $total : $discount;
        $newTotal = $total - $actualDiscount;

        return response()->json([
            'success' => true,
            'discount' => $actualDiscount,
            'newTotal' => $newTotal,
            'message' => 'Áp dụng mã thành công!'
        ]);
    }
}