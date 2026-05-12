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
}