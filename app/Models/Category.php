<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * THIẾT LẬP MỐI QUAN HỆ (RELATIONSHIP)
     * Một danh mục có nhiều sản phẩm
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    // ==========================================================
    // --- MÔ HÌNH MVC: CÁC HÀM STATIC XỬ LÝ DATABASE ---
    // ==========================================================

    // 1. Lấy tất cả danh mục (Sắp xếp mới nhất lên đầu)
    public static function getAllCategories() 
    {
        return self::orderBy('id', 'desc')->get();
    }

    // 2. Hàm thêm danh mục mới
    public static function addCategory($name) 
    {
        return self::create(['name' => $name]);
    }

    // 3. HÀM CẬP NHẬT (Hàm này giúp sửa lỗi 500 ní vừa gặp)
    public static function updateCategory($id, $name)
    {
        $category = self::findOrFail($id);
        return $category->update(['name' => $name]);
    }

    // 4. Hàm xóa danh mục
    public static function deleteCategory($id)
    {
        $category = self::findOrFail($id);
        return $category->delete();
    }

    // 5. Kiểm tra xem danh mục có sản phẩm không trước khi xóa
    public function hasProducts() 
    {
        return $this->products()->count() > 0;
    }
}