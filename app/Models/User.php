<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tên bảng trong Database
     */
    protected $table = 'users';

    /**
     * Các trường được phép chèn dữ liệu hàng loạt
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone', 
        'role',
        'address', 
    ];

    /**
     * Các trường ẩn khi trả về dữ liệu
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * Ép kiểu dữ liệu
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================
    // --- MÔ HÌNH MVC: LOGIC TRUY XUẤT DATABASE TRONG MODEL ---
    // =========================================================

    /**
     * Lấy tất cả user
     */
    public static function getAllUsers() {
        return self::orderBy('created_at', 'desc')->get();
    }

    /**
     * Lấy thông tin 1 user theo ID
     */
    public static function getUserById($id) {
        return self::find($id);
    }

    /**
     * Hàm tạo user mới (Đăng ký)
     */
    public static function registerUser($data) {
        return self::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
            'address'  => $data['address'] ?? null,
            'role'     => 'user',
        ]);
    }

    /**
     * Hàm cập nhật thông tin người dùng
     */
    public static function updateUserInfo($id, $data) {
        $user = self::find($id);
        if ($user) {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? null;
            
            // Chỉ cập nhật role nếu không phải là chính mình đang tự sửa mình
            if ($id != auth()->id()) {
                $user->role = $data['role'];
            }
            
            return $user->save();
        }
        return false;
    }

    /**
     * Khóa/Mở khóa bằng cách đổi Role
     */
    public static function toggleUserLock($id) {
        $user = self::find($id);
        if ($user) {
            $user->role = ($user->role === 'banned') ? 'user' : 'banned';
            return $user->save();
        }
        return false;
    }

    /**
     * Hàm xóa user
     */
    public static function removeUser($id) {
        $user = self::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }

    /**
     * Kiểm tra quyền Admin
     */
    public function isAdmin() {
        return $this->role === 'admin';
    }
}