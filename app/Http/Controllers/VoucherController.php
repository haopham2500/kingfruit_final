<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Hiển thị danh sách tất cả các Voucher trên trang quản trị Admin.
     * @return \Illuminate\View\View
     */
    public function index() {
        $vouchers = Voucher::orderBy('id', 'desc')->get();
        return view('admin.vouchers', compact('vouchers')); 
    }

    /**
     * Xử lý dữ liệu thêm mới một Voucher.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $request->validate([
            'code' => 'required|max:50|unique:vouchers,code',
            'discount_value' => 'required|numeric',
            'expiry_date' => 'required|date|after_or_equal:today',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:fixed,percent',
            'min_order_value' => 'required|numeric|min:0'
        ], [
            'code.required' => 'Vui lòng nhập mã khuyến mãi.',
            'code.max' => 'Mã khuyến mãi không được dài quá 50 ký tự.',
            'code.unique' => 'Mã khuyến mãi này đã tồn tại.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'expiry_date.required' => 'Vui lòng chọn ngày hết hạn.',
            'expiry_date.date' => 'Ngày hết hạn không hợp lệ.',
            'expiry_date.after_or_equal' => 'Ngày hết hạn phải từ hôm nay trở đi.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng ít nhất là 1.',
            'type.required' => 'Vui lòng chọn loại giảm.',
            'type.in' => 'Loại giảm không hợp lệ.',
            'min_order_value.required' => 'Vui lòng nhập giá trị đơn tối thiểu.',
            'min_order_value.numeric' => 'Giá trị đơn tối thiểu phải là số.',
            'min_order_value.min' => 'Giá trị tối thiểu không được nhỏ hơn 0.',
        ]);

        Voucher::create($request->all());
        return back()->with('success', 'Tạo mã thành công!');
    }

    /**
     * Xóa một Voucher khỏi hệ thống.
     * @param int $id ID của Voucher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        Voucher::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa voucher!');
    }

    /**
     * Hiển thị Form chỉnh sửa cho một Voucher cụ thể.
     * @param int $id ID Voucher
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers_edit', compact('voucher'));
    }

    /**
     * Cập nhật thông tin Voucher.
     * Tích hợp cơ chế Khóa Lạc Quan (Optimistic Locking) chống xung đột.
     * @param Request $request Dữ liệu form và original_updated_at
     * @param int $id ID Voucher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) {
        $voucher = Voucher::findOrFail($id);

        if ($request->has('original_updated_at') && $voucher->updated_at != $request->original_updated_at) {
            return back()->with('error', 'Lỗi: Dữ liệu đã bị thay đổi bởi người khác trước đó. Vui lòng tải lại trang để xem dữ liệu mới nhất.');
        }

        $data = $request->validate([
            'code' => 'required|max:50|unique:vouchers,code,'.$id,
            'discount_value' => 'required|numeric',
            'expiry_date' => 'required|date',
            'type' => 'required|in:fixed,percent',
            'min_order_value' => 'required|numeric',
            'quantity' => 'required|integer',
        ], [
            'code.required' => 'Vui lòng nhập mã khuyến mãi.',
            'code.max' => 'Mã khuyến mãi không được dài quá 50 ký tự.',
            'code.unique' => 'Mã khuyến mãi này đã tồn tại.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'expiry_date.required' => 'Vui lòng chọn ngày hết hạn.',
            'expiry_date.date' => 'Ngày hết hạn không hợp lệ.',
            'type.required' => 'Vui lòng chọn loại giảm.',
            'type.in' => 'Loại giảm không hợp lệ.',
            'min_order_value.required' => 'Vui lòng nhập giá trị đơn tối thiểu.',
            'min_order_value.numeric' => 'Giá trị đơn tối thiểu phải là số.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
        ]);

        Voucher::findOrFail($id)->update($data);
        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật xong rồi nhé ní!');
    }

    /**
     * Hiển thị danh sách các mã Khuyến mãi hiện có cho Khách hàng (Frontend).
     * @return \Illuminate\View\View
     */
    public function showPromotions() {
        $vouchers = Voucher::orderBy('expiry_date', 'desc')->get();
        return view('client.promotions', compact('vouchers'));
    }

    /**
     * Logic cho phép Khách hàng lưu trữ mã Voucher vào Session (Lấy mã).
     * @param Request $request Chứa mã code
     * @return \Illuminate\Http\JsonResponse
     */
    public function collectVoucher(Request $request) {
        $collected = session()->get('collected_vouchers', []);

        if (!in_array($request->code, $collected)) {
            $collected[] = $request->code;
            session()->put('collected_vouchers', $collected);
        }

        return response()->json(['success' => true, 'message' => 'Đã thu thập mã!']);
    }

    /**
     * Logic áp dụng mã Voucher vào Đơn hàng trong trang Thanh toán (Checkout).
     * Tính toán số tiền được giảm giá và trả về kết quả qua AJAX.
     * @param Request $request Chứa mã code
     * @return \Illuminate\Http\JsonResponse
     */
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