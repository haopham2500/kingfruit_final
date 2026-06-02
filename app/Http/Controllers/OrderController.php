<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách tất cả các đơn hàng cho Admin.
     * @return \Illuminate\View\View
     */
    public function index() {
        // Sửa 'ngay_dat' thành 'created_at' cho khớp với bảng orders trong SQL
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.orders', compact('orders'));
    }

    /**
     * Cập nhật trạng thái của đơn hàng (ví dụ: Chờ duyệt -> Đang giao).
     * Tích hợp kiểm tra Khóa Lạc Quan để tránh xung đột thao tác.
     * @param Request $request Dữ liệu gửi lên chứa 'status' và 'original_updated_at'
     * @param int $id ID của đơn hàng
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id) {
        // Tìm đơn hàng theo id
        $order = \App\Models\Order::find($id);
        
        if (!$order) {
            return redirect()->route('admin.orders.index')->with('error', 'Không tìm thấy đơn hàng hoặc URL không hợp lệ!');
        }
        
        if ($request->has('original_updated_at') && $order->updated_at != $request->original_updated_at) {
            return back()->with('error', 'Lỗi: Dữ liệu đã bị thay đổi bởi người khác trước đó. Vui lòng tải lại trang để xem dữ liệu mới nhất.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded,wait_refund,returning,refund_rejected'
        ], [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        // Cập nhật trạng thái mới từ select box gửi lên
        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Cập nhật trạng thái đơn hàng #' . $id . ' thành công!');
    }

    /**
     * Xem chi tiết một đơn hàng cụ thể.
     * @param int $id ID của đơn hàng
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Tìm đơn hàng kèm theo chi tiết sản phẩm (nếu có relationship)
        $order = Order::with('details')->find($id); 
        
        if (!$order) {
            return redirect()->route('admin.orders.index')->with('error', 'Không tìm thấy đơn hàng hoặc URL không hợp lệ!');
        }
        
        return view('admin.show', compact('order')); // Nếu file nằm ở admin/show.blade.php
    }

    /**
     * Hiển thị trang duyệt trả hàng/hoàn tiền.
     * @return \Illuminate\View\View
     */
    public function refundsIndex()
    {
        // Lấy danh sách các đơn hàng có yêu cầu hoàn tiền (chờ duyệt trước, sau đó là các trạng thái hoàn tiền khác)
        $orders = Order::whereIn('status', ['wait_refund', 'refunded', 'refund_rejected'])
            ->orderByRaw("FIELD(status, 'wait_refund', 'refund_rejected', 'refunded')")
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.refunds', compact('orders'));
    }

    /**
     * Xử lý duyệt hoặc từ chối yêu cầu hoàn tiền.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refundsProcess(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'feedback' => 'nullable|string|max:1000'
        ]);

        if ($request->action === 'approve') {
            $order->update([
                'status' => 'refunded',
                'refund_feedback' => $request->feedback
            ]);
            return back()->with('success', 'Đã duyệt hoàn tiền cho đơn hàng #' . $id . ' thành công!');
        } else {
            $order->update([
                'status' => 'refund_rejected',
                'refund_feedback' => $request->feedback
            ]);
            return back()->with('success', 'Đã từ chối yêu cầu hoàn tiền cho đơn hàng #' . $id . '.');
        }
    }
}