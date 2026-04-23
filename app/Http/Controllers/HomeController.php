<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Lấy 4 sản phẩm làm "Bán chạy" (Khớp với bảng products và categories)
        $bestSellers = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // 2. Lấy tất cả sản phẩm
        $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->orderBy('products.id', 'desc')
            ->get();

        // 3. Trả về view 'home'
        return view('home', compact('bestSellers', 'products'));
    }
}