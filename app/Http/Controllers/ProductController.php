<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Để dùng Query Builder giống file cũ của ní

class ProductController extends Controller
{
    // Logic của trang index.php
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
    $product = \DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.*', 'categories.name as category_name')
        ->where('products.id', $id)
        ->first();

    if (!$product) { return redirect()->route('home'); }

    // Tạm thời lấy bình luận nếu ní đã có bảng comments
    // Sửa thành 'reviews' và kiểm tra lại tên cột
$comments = \DB::table('reviews')
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
    }

    // 3. Chèn dữ liệu vào bảng products
    DB::table('products')->insert([
        'name' => $request->name,
        'category_id' => $request->category_id,
        'price' => $request->price,
        'unit' => $request->unit,
        'description' => $request->description,
        'image' => $fileName,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 4. Quay lại trang CRUD kèm thông báo
    return redirect()->route('crud')->with('success', 'Ngon lành! Đã thêm sản phẩm mới.');
}
// Hàm sửa sản phẩm
public function update(Request $request, $id)
{
    // 1. Kiểm tra dữ liệu đầu vào
    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required',
    ]);

    // 2. Chuẩn bị dữ liệu để update
    $data = [
        'name' => $request->name,
        'category_id' => $request->category_id,
        'price' => $request->price,
        'unit' => $request->unit,
        'description' => $request->description,
        'updated_at' => now(),
    ];

    // 3. Xử lý ảnh (nếu ní có up ảnh mới)
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $fileName);
        $data['image'] = $fileName;
    }

    // 4. Thực hiện update vào database
    DB::table('products')->where('id', $id)->update($data);

    // 5. Quay lại trang cũ với thông báo thành công
    return redirect()->route('crud')->with('success', 'Đã cập nhật sản phẩm thành công!');
}

public function destroy($id) {
    DB::table('products')->where('id', $id)->delete();
    return back()->with('success', 'Xóa sản phẩm thành công!');
}
}
