<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- MÔ HÌNH MVC: VIẾT DATABASE TRONG MODEL ---

    // Hàm lấy tất cả user
    public static function getAllUsers() {
        return self::all();
    }

    // Hàm tạo user mới (Đăng ký)
    public static function registerUser($data) {
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => 'user', 
        ]);
    }
    

    // Hàm xóa user
    public static function removeUser($id) {
        return self::where('id', $id)->delete();
    }
}