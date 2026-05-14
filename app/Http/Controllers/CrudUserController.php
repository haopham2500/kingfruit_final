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

    // Hiển thị form đăng nhập
    public function login()
    {
        return view('auth.login'); 
    }

    // Xử lý thực hiện đăng nhập
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

    public function showRegister()
    {
        return view('auth.register');
    }

    public function createUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
        ], [
            'email.unique' => 'Email này có người dùng rồi ní.',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự nhé.',
        ]);

        User::registerUser($data);

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Đăng nhập ngay cho nóng.');
    }

    /**
     * --- QUẢN LÝ USER (ADMIN) ---
     */
    
    // Danh sách người dùng
    public function index() {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Ní không có quyền vào đây!');
        }

        $users = User::getAllUsers(); 
        return view('admin.users', compact('users'));
    }

    // HIỂN THỊ FORM CHỈNH SỬA (Mới cập nhật)
    public function edit($id) {
        $user = User::getUserById($id);
        
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Không tìm thấy người dùng này ní ơi!');
        }
        
        return view('admin.users_edit', compact('user'));
    }

    // XỬ LÝ CẬP NHẬT THÔNG TIN (Mới cập nhật - Đã sửa lỗi 500)
    public function update(Request $request, $id) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string',
            'role' => 'required|in:admin,user,banned',
        ], [
            'name.required' => 'Tên không được để trống.',
            'email.unique' => 'Email này bị trùng mất rồi.',
        ]);

        // Gọi logic lưu từ Model
        $result = User::updateUserInfo($id, $data);

        if ($result) {
            return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật thông tin thành công!');
        }
        
        return back()->with('error', 'Có lỗi xảy ra, cập nhật thất bại.');
    }

    // CHỨC NĂNG KHÓA/MỞ KHÓA NHANH (Đổi Role)
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

    // XÓA NGƯỜI DÙNG
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
}