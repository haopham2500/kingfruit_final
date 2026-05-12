<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. LOGIC LẤY DANH MỤC (Cho bộ lọc) ---
        $categories = Category::all();

        // --- 2. LOGIC SẢN PHẨM BÁN CHẠY (Giữ nguyên không lọc) ---
        // Sử dụng Query Builder như code cũ của bạn nhưng đổi tên biến cho khớp với View
        $hotProducts = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->inRandomOrder() // Hoặc ->where('is_hot', 1) nếu bạn đã có cột này
            ->limit(4)
            ->get();

        // --- 3. LOGIC TẤT CẢ SẢN PHẨM (Có áp dụng lọc) ---
        $query = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name');

        // Áp dụng bộ lọc nếu có request gửi lên
        if ($request->filled('category_id')) {
            $query->where('products.category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('products.price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('products.price', '<=', $request->max_price);
        }

        // Thực thi lấy danh sách sản phẩm
        $allProducts = $query->orderBy('products.id', 'desc')->get();

        // --- 4. LOGIC GIỎ HÀNG ---
        $cart = session()->get('cart', []);
        $cartCount = count($cart);

        // --- 5. CHỈ TRẢ VỀ 1 LỆNH RETURN DUY NHẤT ---
        return view('home', compact('hotProducts', 'allProducts', 'categories', 'cartCount'));
    }
}