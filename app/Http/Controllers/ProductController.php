<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Category;
use App\Models\Comment; // Nhớ gọi Model Comment nếu dùng Eloquent
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * --- DÀNH CHO NGƯỜI DÙNG (FRONTEND) ---
     */

    // Trang chủ
    public function index()
    {
        // Sử dụng Model cho đúng chuẩn MVC
        $allProducts = Product::all(); 
        $hotProducts = Product::inRandomOrder()->limit(4)->get(); // Sản phẩm bán chạy
        $categories = Category::all();

        return view('home', compact('allProducts', 'hotProducts', 'categories'));
    }

    // Chi tiết sản phẩm
    public function show($id)
    {
        // Lấy sản phẩm kèm danh mục
        $product = Product::with('category')->find($id);

        if (!$product) {
            return redirect()->route('home')->with('error', 'Sản phẩm không tồn tại!');
        }

        // Lấy bình luận (Giả sử bạn dùng bảng 'comments')
        $comments = DB::table('comments')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.product_id', $id)
            ->select('comments.*', 'users.name as user_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('detail', compact('product', 'comments'));
    }

    // Tìm kiếm sản phẩm
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Tìm kiếm theo tên sản phẩm
        $allProducts = Product::where('name', 'LIKE', "%{$query}%")->get();
        $hotProducts = Product::inRandomOrder()->limit(4)->get();
        $categories = Category::all();

        return view('home', compact('allProducts', 'hotProducts', 'categories', 'query'));
    }

    /**
     * --- DÀNH CHO ADMIN (BACKEND - CRUD) ---
     */

    public function indexAdmin()
    {
        $products = Product::with('category')->get();
        $categories = Category::all(); 
        return view('admin.crud', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $fileName);
            $data['image'] = $fileName;
        }

        Product::create($data);
        return back()->with('success', 'Thêm sản phẩm thành công!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $fileName);
            $data['image'] = $fileName;
        }

        $product->update($data);
        return back()->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Xóa thành công!');
    }
}