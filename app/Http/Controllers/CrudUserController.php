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

    // Hàm hiển thị form đăng nhập (Phải có hàm này để web.php gọi)
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
            $request->session()->regenerate();
            $user = Auth::user();

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

    // Hiển thị form đăng ký
    public function showRegister()
    {
        return view('auth.register');
    }

    // Xử lý tạo tài khoản (Gọi Model)
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

        // GỌI MODEL XỬ LÝ (Chuẩn MVC)
        User::registerUser($data);

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Đăng nhập ngay cho nóng.');
    }

    /**
     * --- QUẢN LÝ USER ---
     */
    public function index() {
        $users = User::getAllUsers();
        return view('admin.users', compact('users'));
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