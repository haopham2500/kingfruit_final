<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

class CrudUserController extends Controller
{
    /**
     * --- ĐĂNG NHẬP ---
     */

    /**
     * Hiển thị trang Form đăng nhập.
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return view('auth.login'); 
    }

    /**
     * Xử lý xác thực người dùng khi submit form đăng nhập.
     * Kiểm tra cả trạng thái bị khóa (banned).
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Ní chưa nhập email kìa.',
            'password.required' => 'Mật khẩu đâu ní ơi?',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // KIỂM TRA TÀI KHOẢN CÓ BỊ KHÓA KHÔNG
            if ($user->role === 'banned') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Tài khoản của ní đã bị khóa. Vui lòng liên hệ Admin!');
            }

            $request->session()->regenerate();

            // Lưu session ID cuối cùng để chặn đăng nhập đồng thời
            $user->last_session_id = $request->session()->getId();
            $user->save();

            // Phân quyền admin/user
            if ($user->role === 'admin') {
                return redirect()->route('crud'); 
            }
            return redirect()->intended('/');
        }

        return back()->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    /**
     * --- ĐĂNG KÝ ---
     */

    /**
     * Hiển thị trang Đăng ký tài khoản.
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Xử lý tạo người dùng mới khi submit form đăng ký.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|string|email|max:255|unique:users|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            'password' => 'required|string|min:6|max:255',
            'phone' => 'nullable|string|max:11',
        ], [
            'name.required' => 'Vui lòng nhập tên.',
            'name.max' => 'Họ và tên không được dài quá 30 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được dài quá 255 ký tự.',
            'email.unique' => 'Email này có người dùng rồi ní.',
            'email.regex' => 'Vui lòng sử dụng địa chỉ Gmail chính thức (@gmail.com) để đăng ký.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự nhé.',
            'password.max' => 'Mật khẩu không được dài quá 255 ký tự.',
            'phone.max' => 'Số điện thoại không được dài quá 11 số.',
        ]);

        User::registerUser($data);

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Đăng nhập ngay cho nóng.');
    }

    /**
     * --- QUẢN LÝ USER (ADMIN) ---
     */    
    /**
     * Hiển thị danh sách tất cả người dùng (Dành riêng cho Admin).
     * Chặn các user thường truy cập.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index() {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Ní không có quyền vào đây!');
        }

        $users = User::getAllUsers(); 
        return view('admin.users', compact('users'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin của một người dùng.
     * @param int $id ID người dùng
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id) {
        $user = User::getUserById($id);
        
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Không tìm thấy người dùng này ní ơi!');
        }
        
        return view('admin.users_edit', compact('user'));
    }

    /**
     * Xử lý cập nhật thông tin người dùng từ Form sửa.
     * Có tích hợp kiểm tra Khóa Lạc Quan (Optimistic Locking).
     * @param Request $request Dữ liệu form và original_updated_at
     * @param int $id ID người dùng
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        if ($request->has('original_updated_at') && $user->updated_at != $request->original_updated_at) {
            return back()->with('error', 'Lỗi: Dữ liệu đã bị thay đổi bởi người khác trước đó. Vui lòng tải lại trang để xem dữ liệu mới nhất.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:11',
            'role' => 'required|in:admin,user,banned',
        ], [
            'name.required' => 'Tên không được để trống.',
            'name.max' => 'Họ và tên không được dài quá 30 ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.regex' => 'Vui lòng nhập email có đuôi @gmail.com',
            'email.max' => 'Email không được dài quá 255 ký tự.',
            'email.unique' => 'Email này bị trùng mất rồi.',
            'phone.max' => 'Số điện thoại không được dài quá 11 số.',
            'role.required' => 'Vui lòng chọn vai trò.',
            'role.in' => 'Vai trò không hợp lệ.',
        ]);

        // Gọi logic lưu từ Model
        $result = User::updateUserInfo($id, $data);

        if ($result) {
            return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật thông tin thành công!');
        }
        
        return back()->with('error', 'Có lỗi xảy ra, cập nhật thất bại.');
    }

    /**
     * Khóa hoặc Mở khóa nhanh một tài khoản bằng cách thay đổi Role.
     * Không cho phép tự khóa chính mình.
     * @param int $id ID người dùng
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleRole($id)
    {
        if ($id == Auth::id()) {
            return back()->with('error', 'Ní định tự khóa mình à? Không được đâu!');
        }

        $result = User::toggleUserLock($id);

        if ($result) {
            return back()->with('success', 'Cập nhật trạng thái tài khoản thành công!');
        }

        return back()->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái.');
    }

    /**
     * Xóa vĩnh viễn một tài khoản khỏi hệ thống.
     * Không cho phép Admin tự xóa chính mình.
     * @param int $id ID người dùng
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return back()->with('error', 'Ní không thể tự xóa chính mình được!');
        }

        $result = User::removeUser($id);

        if ($result) {
            return back()->with('success', 'Đã tiễn người dùng này lên đường thành công.');
        }

        return back()->with('error', 'Có lỗi gì đó rồi ní ơi, không xóa được.');
    }

    /**
     * --- ĐĂNG XUẤT ---
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Hiển thị trang Profile của người dùng
     */
    public function profile()
    {
        $user = auth()->user();

        $orders = $user->orders()->orderBy('created_at', 'desc')->get();

        if ($orders->isEmpty()) {
            $orders = \App\Models\Order::query();

            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'receiver_name')) {
                $orders = $orders->where('receiver_name', $user->name);
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'phone_number')) {
                $orders = $orders->orWhere('phone_number', $user->phone);
            }

            $orders = $orders->orderBy('created_at', 'desc')->get();
        }

        return view('profile', compact('user', 'orders'));
    }

    /**
     * Xử lý đổi mật khẩu của người dùng.
     */
    public function changePassword(Request $request)
    {
        $user = auth()->user();

        // 1. Validate dữ liệu đầu vào
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|different:current_password',
            'confirm_password' => 'required|string|same:new_password',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.different' => 'Mật khẩu mới không được trùng với mật khẩu hiện tại.',
            'confirm_password.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'confirm_password.same' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        // 2. Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không chính xác.'
                ]);
            }
            return back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        // 3. Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công!'
            ]);
        }

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}