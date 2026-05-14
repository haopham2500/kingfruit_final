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

        // 1. Lấy kết quả tìm kiếm
        $allProducts = Product::where('name', 'LIKE', "%{$query}%")->get();

        // 2. Bổ sung các biến mà View home.blade.php đang yêu cầu
        $hotProducts = Product::inRandomOrder()->limit(4)->get();
        $categories = Category::all();

        // 3. Truyền tất cả sang View
        return view('home', compact('allProducts', 'query', 'hotProducts', 'categories'));
    }

    // Xử lý xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Xóa sản phẩm thành công!');
    }
}
