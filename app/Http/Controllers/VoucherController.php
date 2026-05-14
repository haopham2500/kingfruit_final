<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index() {
    $vouchers = Voucher::orderBy('id', 'desc')->get();
    // Đổi từ 'admin.vouchers.index' thành 'admin.vouchers'
    return view('admin.vouchers', compact('vouchers')); 
    }

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

    public function destroy($id) {
        Voucher::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa voucher!');
    }
    // Hàm hiển thị trang sửa
public function edit($id) {
    $voucher = Voucher::findOrFail($id);
    return view('admin.vouchers_edit', compact('voucher'));
}

// Hàm xử lý cập nhật dữ liệu vào DB
public function update(Request $request, $id) {
    $data = $request->validate([
        'code' => 'required|unique:vouchers,code,'.$id,
        'discount_value' => 'required|numeric',
        'expiry_date' => 'required|date',
        'type' => 'required|in:fixed,percent',
        'min_order_value' => 'required|numeric',
        'quantity' => 'required|integer',
    ]);

    $voucher = Voucher::findOrFail($id);
    $voucher->update($data);

    return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật voucher xong rồi nhé ní!');
}
public function showPromotions() {
    $vouchers = Voucher::where('quantity', '>', 0)->get();
    // Phải có "client." ở phía trước tên file
    return view('client.promotions', compact('vouchers')); 
}
public function applyVoucher(Request $request) {
    $voucher = Voucher::where('code', $request->code)
                      ->where('expiry_date', '>=', now())
                      ->where('quantity', '>', 0)
                      ->first();

    if (!$voucher) {
        return response()->json(['success' => false, 'message' => 'Mã không hợp lệ hoặc đã hết hạn']);
    }

    // Giả sử bạn lấy tổng tiền từ session giỏ hàng
    $cart = session()->get('cart');
    $total = 0;
    foreach($cart as $item) { $total += $item['price'] * $item['quantity']; }

    $discount = $voucher->discount_value; // Hoặc tính % tùy bạn
    $newTotal = $total - $discount;

    return response()->json([
        'success' => true,
        'discount' => $discount,
        'newTotal' => $newTotal,
        'message' => 'Áp dụng mã thành công!'
    ]);
}
}