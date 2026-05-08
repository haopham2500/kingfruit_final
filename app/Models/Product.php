<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'price', 'unit', 'image', 'description'];

    /**
     * KHAI BÁO MỐI QUAN HỆ (RELATIONSHIP)
     * Đây là phần quan trọng nhất để hết lỗi "Call to undefined relationship"
     */
    public function category()
    {
        // Một sản phẩm thuộc về một danh mục
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // ==========================================================
    // --- MÔ HÌNH MVC: CÁC HÀM STATIC XỬ LÝ DATABASE ---
    // ==========================================================

    /**
     * Hàm lấy sản phẩm kèm tên danh mục
     * Dùng Eager Loading (with) thay cho Join để đúng chuẩn Eloquent
     */
    public static function getListWithCategory()
    {
        return self::with('category')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Hàm lấy sản phẩm ngẫu nhiên (Best Seller)
     */
    public static function getBestSellers($limit = 4)
    {
        return self::with('category')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Hàm tìm kiếm sản phẩm
     */
    public static function searchProducts($query)
{
    return self::with('category')
        ->where('name', 'LIKE', "%{$query}%")
        ->get();
}
}