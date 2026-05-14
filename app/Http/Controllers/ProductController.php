<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Quan trọng: Gọi Model Product
use App\Models\Category; // Quan trọng: Gọi Model Category
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * --- DÀNH CHO NGƯỜI DÙNG (FRONTEND) ---
     */

    // Trang chủ: Hiển thị sản phẩm và sản phẩm bán chạy
    public function index()
{
    // Lấy tất cả sản phẩm
    $products = DB::table('san_pham')
        ->join('loai_trai_cay', 'san_pham.id_loai', '=', 'loai_trai_cay.id_loai')
        ->select('san_pham.*', 'loai_trai_cay.ten_loai')
        ->get();

    // Lấy 4 sản phẩm ngẫu nhiên cho Best Seller
    $bestSellers = DB::table('san_pham')
        ->join('loai_trai_cay', 'san_pham.id_loai', '=', 'loai_trai_cay.id_loai')
        ->inRandomOrder()
        ->limit(4)
        ->get();

    return view('home', compact('products', 'bestSellers'));
}

    // Logic của trang chi_tiet.php
    public function show($id)
{
    // Lấy chi tiết sản phẩm và tên loại
    $product = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.*', 'categories.name as category_name')
        ->where('products.id', $id)
        ->first();

    if (!$product) { return redirect()->route('home'); }

    // Tạm thời lấy bình luận nếu ní đã có bảng comments
    // Sửa thành 'reviews' và kiểm tra lại tên cột
    $comments = DB::table('reviews')
    ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
    ->where('reviews.product_id', $id)
    ->select('reviews.*', 'users.name as user_name')
    ->get();

    return view('detail', compact('product', 'comments'));
}
public function indexAdmin() {
    // Lấy danh sách sản phẩm kèm tên danh mục
    $products = DB::table('products')
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.*', 'categories.name as category_name')
        ->get();

        // 2. Lấy danh sách danh mục (CÁI NÀY ĐANG THIẾU NÈ)
    $categories = DB::table('categories')->get();

    // 3. Gửi CẢ HAI biến sang View
    return view('admin.crud', compact('products', 'categories'));
}
// Hàm thêm sản phẩm
public function store(Request $request)
{
    // 1. Kiểm tra dữ liệu đầu vào
    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Bắt buộc chọn ảnh khi thêm mới
    ]);

    // 2. Xử lý lưu file ảnh
    $fileName = null;
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $fileName);

    {
        // Sử dụng các hàm Static đã viết trong Model Product (Chuẩn MVC)
        $products = Product::getListWithCategory();
        $bestSellers = Product::getBestSellers(4);
        
        return view('home', compact('products', 'bestSellers'));
    }

    // Trang tìm kiếm sản phẩm
  

    // Xem chi tiết một sản phẩm (Xử lý khi bấm nút "Chi tiết")
    public function show($id)
    {
        // Eager loading 'category' để lấy tên loại trái cây
        $product = Product::with('category')->find($id);
        
        if (!$product) {
            return redirect()->route('home')->with('error', 'Sản phẩm không tồn tại!');
        }
        
        return view('detail', compact('product'));
    }


    /**
     * --- DÀNH CHO ADMIN (BACKEND - CRUD) ---
     */

    // Trang quản trị sản phẩm (Hàm này khớp với route 'crud')
    public function indexAdmin()
    {
        // Lấy danh sách sản phẩm kèm danh mục
        $products = Product::getListWithCategory();
        // Lấy tất cả danh mục để hiện trong Form Thêm/Sửa
        $categories = Category::all(); 

        return view('admin.crud', compact('products', 'categories'));
    }

    // Hiển thị form tạo mới (Nếu ní dùng trang riêng, còn nếu dùng Modal ở trang crud thì không cần)
    public function create()
    {
        $categories = Category::all();
        return view('admin.product_create', compact('categories'));
    }

    // Xử lý lưu sản phẩm mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'unit' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Xử lý upload ảnh vào thư mục public/images
        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $fileName);
            $data['image'] = $fileName;
        }

        Product::create($data);

        return back()->with('success', 'Thêm sản phẩm thành công!');
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.product_edit', compact('product', 'categories'));
    }

    // Xử lý cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'unit' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Upload ảnh mới
            $fileName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $fileName);
            $data['image'] = $fileName;
            
            // Lưu ý: Ní có thể code thêm đoạn xóa ảnh cũ ở đây để nhẹ máy chủ
        }

        $product->update($data);

        return back()->with('success', 'Cập nhật sản phẩm thành công!');
    }
    // Trang tìm kiếm sản phẩm
public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Gọi hàm từ Model Product
        $products = Product::searchProducts($query); 
        
        // Lấy Best Sellers để trang web không bị trống nếu tìm không ra
        $bestSellers = Product::getBestSellers(4);
        
        // Trả về view 'home' như ní mong muốn
        return view('home', compact('products', 'bestSellers', 'query'));
    }

    // Xử lý xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Xóa sản phẩm thành công!');
    }
}