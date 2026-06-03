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

    /**
     * Hiển thị trang chủ với danh sách sản phẩm mới nhất và bán chạy.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Sử dụng các hàm Static đã viết trong Model Product (Chuẩn MVC)
        $products = Product::getListWithCategory();
        $bestSellers = Product::getBestSellers(4);

        return view('home', compact('products', 'bestSellers'));
    }

    // Trang tìm kiếm sản phẩm


    /**
     * Lấy dữ liệu chi tiết của 1 sản phẩm theo ID và hiển thị trang Chi tiết.
     * @param int $id ID của sản phẩm
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        if (!is_numeric($id)) {
            return view('detail', ['product' => null]);
        }

        // Eager loading 'category' để lấy tên loại trái cây
        $product = Product::with('category')->find($id);

        if (!$product) {
            return redirect()->route('home')->with('error', 'Không có sản phẩm, tìm sản phẩm khác!');

            return view('detail', ['product' => null]);
        }

        return view('detail', compact('product'));
    }


    /**
     * --- DÀNH CHO ADMIN (BACKEND - CRUD) ---
     */

    /**
     * Lấy toàn bộ sản phẩm và danh mục để hiển thị trên trang Quản lý sản phẩm (Admin).
     * @return \Illuminate\View\View
     */
    public function indexAdmin()
    {
        // Lấy danh sách sản phẩm kèm danh mục
        $products = Product::getListWithCategory();
        // Lấy tất cả danh mục để hiện trong Form Thêm/Sửa
        $categories = Category::all();

        return view('admin.crud', compact('products', 'categories'));
    }

    /**
     * Hiển thị form tạo mới sản phẩm (Trang riêng nếu không dùng Modal).
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.product_create', compact('categories'));
    }

    /**
     * Xử lý dữ liệu từ form Thêm sản phẩm, kiểm tra tính hợp lệ và lưu vào Database.
     * Bao gồm cả logic xử lý upload file ảnh.
     * @param Request $request Dữ liệu người dùng gửi lên
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|max:999999999',
            'unit' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'category_id.required' => 'Vui lòng chọn loại sản phẩm.',
            'category_id.exists' => 'Loại sản phẩm không tồn tại.',
            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá sản phẩm phải là một số.',
            'price.min' => 'Giá sản phẩm không được là số âm.',
            'price.max' => 'Giá sản phẩm quá lớn, vui lòng nhập số nhỏ hơn 1 tỷ.',
            'unit.required' => 'Vui lòng nhập đơn vị.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
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

    /**
     * Lấy thông tin sản phẩm cần sửa và các danh mục để hiển thị lên form sửa.
     * @param int $id ID sản phẩm cần sửa
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return redirect()->route('crud')->with('error', 'Không tìm thấy sản phẩm hoặc URL không hợp lệ!');
        }
        $categories = Category::all();
        return view('admin.product_edit', compact('product', 'categories'));
    }

    /**
     * Xử lý cập nhật sản phẩm. Bao gồm kiểm tra Khóa Lạc Quan (Optimistic Locking)
     * để chống xung đột cập nhật dữ liệu khi có nhiều người cùng thao tác.
     * @param Request $request Dữ liệu form và original_updated_at
     * @param int $id ID sản phẩm
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->has('original_updated_at') && $product->updated_at != $request->original_updated_at) {
            return back()->with('error', 'Lỗi: Dữ liệu đã bị thay đổi bởi người khác trước đó. Vui lòng tải lại trang để xem dữ liệu mới nhất.');
        }

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|max:999999999',
            'unit' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'category_id.required' => 'Vui lòng chọn loại sản phẩm.',
            'category_id.exists' => 'Loại sản phẩm không tồn tại.',
            'price.required' => 'Vui lòng nhập giá.',
            'price.numeric' => 'Giá sản phẩm phải là một số.',
            'price.min' => 'Giá sản phẩm không được là số âm.',
            'price.max' => 'Giá sản phẩm quá lớn, vui lòng nhập số nhỏ hơn 1 tỷ.',
            'unit.required' => 'Vui lòng nhập đơn vị.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Upload ảnh mới
            $fileName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('images'), $fileName);
            $data['image'] = $fileName;

            // Xóa ảnh cũ để nhẹ máy chủ
            if ($product->image && file_exists(public_path('images/' . $product->image))) {
                unlink(public_path('images/' . $product->image));
            }
        }

        $product->update($data);

        return back()->with('success', 'Cập nhật sản phẩm thành công!');
    }
    /**
     * Xử lý logic tìm kiếm sản phẩm theo tên và trả về trang chủ kèm kết quả tìm kiếm.
     * @param Request $request Chứa tham số 'query' (từ khóa tìm kiếm)
     * @return \Illuminate\View\View
     */
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

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return back()->with('error', 'Xóa không hợp lệ! Mục này có thể đã bị xóa trước đó.');
        }
        
        // Tùy chọn: Xóa ảnh khỏi máy chủ để tiết kiệm dung lượng
        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }

        $product->delete();

        return back()->with('success', 'Xóa sản phẩm thành công!');
    }

    /**
     * Xóa nhiều sản phẩm cùng lúc.
     * @param Request $request Chứa chuỗi các ID cách nhau bằng dấu phẩy
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            $idArray = explode(',', $ids);
            
            // Lấy ra các sản phẩm để xóa ảnh (nếu cần)
            $products = Product::whereIn('id', $idArray)->get();
            foreach ($products as $product) {
                if ($product->image && file_exists(public_path('images/' . $product->image))) {
                    unlink(public_path('images/' . $product->image));
                }
            }

            // Xóa hàng loạt
            Product::whereIn('id', $idArray)->delete();
            return back()->with('success', 'Đã xóa thành công các sản phẩm được chọn!');
        }

        return back()->with('error', 'Chưa có sản phẩm nào được chọn để xóa.');
    }
}
