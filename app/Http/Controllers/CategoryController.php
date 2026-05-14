<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // 1. Hiển thị danh sách danh mục (MVC)
    public function index() 
    {
        $categories = Category::getAllCategories();
        return view('admin.categories', compact('categories'));
    }

    // 2. Xử lý thêm danh mục mới
    public function store(Request $request) 
    {
        $request->validate(
            ['name' => 'required|unique:categories|max:255'],
            ['name.required' => 'Ní chưa nhập tên mà!', 'name.unique' => 'Tên này có rồi ní ơi!']
        );

        Category::addCategory($request->name);
        return back()->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * 3. HÀM CẬP NHẬT DANH MỤC (FIX LỖI 500)
     * Hàm này sẽ nhận dữ liệu từ Form sửa gửi lên
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            ['name' => 'required|max:255|unique:categories,name,' . $id],
            ['name.required' => 'Tên không được để trống nha.', 'name.unique' => 'Tên bị trùng rồi ní.']
        );

        // Gọi hàm update từ Model Category
        Category::updateCategory($id, $request->name);

        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    // 4. Xử lý xóa danh mục
    public function destroy($id) 
    {
        $category = Category::findOrFail($id);
        
        // Kiểm tra xem có sản phẩm không (Dùng hàm trong Model)
        if ($category->hasProducts()) {
            return back()->with('error', 'Không thể xóa! Có sản phẩm đang thuộc danh mục này.');
        }

        $category->delete();
        return back()->with('success', 'Xóa danh mục thành công!');
    }
}