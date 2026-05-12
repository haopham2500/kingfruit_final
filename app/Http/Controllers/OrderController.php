<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
    // Sửa 'ngay_dat' thành 'created_at' cho khớp với bảng orders trong SQL
    $orders = Order::orderBy('created_at', 'desc')->get();
    return view('admin.orders', compact('orders'));
    }

    public function updateStatus(Request $request, $id) {
    // Tìm đơn hàng theo id
    $order = \App\Models\Order::findOrFail($id);
    
    // Cập nhật trạng thái mới từ select box gửi lên
    $order->update([
        'status' => $request->status
    ]);

    return back()->with('success', 'Cập nhật trạng thái đơn hàng #' . $id . ' thành công!');
    }
    public function show($id)
    {
    // Tìm đơn hàng kèm theo chi tiết sản phẩm (nếu có relationship)
    $order = Order::with('details')->findOrFail($id); 
    
    return view('admin.show', compact('order')); // Nếu file nằm ở admin/show.blade.php
    }
}