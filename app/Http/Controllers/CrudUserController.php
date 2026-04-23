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

    // Hiển thị trang đăng nhập
    public function login()
    {
        // Trỏ đến file: resources/views/auth/login.blade.php
        return view('auth.login'); 
    }

    // Xử lý logic đăng nhập
    public function authUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Thử đăng nhập với thông tin người dùng nhập vào
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Lấy thông tin user vừa đăng nhập
            $user = Auth::user();

            // PHÂN QUYỀN: Kiểm tra cột 'role' trong database
            if ($user->role === 'admin') {
                // Nếu là admin thì vào trang quản trị sản phẩm
                return redirect()->route('crud');
            }
            
            // Nếu là user bình thường thì về trang chủ
            return redirect()->intended('/');
        }

        // Trả về kèm thông báo lỗi nếu sai tài khoản hoặc mật khẩu
        return back()->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    /**
     * --- ĐĂNG KÝ ---
     */

    // Hiển thị trang đăng ký
    public function showRegister()
    {
        // Trỏ đến file: resources/views/auth/register.blade.php
        return view('auth.register');
    }

    // Xử lý logic tạo tài khoản
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
        ], [
            'email.unique' => 'Email này đã được đăng ký rồi ní ơi.',
            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự nhé.',
        ]);

        // Mặc định khi đăng ký qua form này, role sẽ luôn là 'user'
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'user', 
        ]);

        // Đăng ký xong chuyển về trang đăng nhập kèm thông báo thành công
        return redirect()->route('login')->with('success', 'Đăng ký thành công! Đăng nhập đi ní.');
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