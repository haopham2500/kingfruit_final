<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Hiển thị trang Quản lý danh mục (dành cho Admin).
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        $categories = Category::getAllCategories();
        return view('admin.categories', compact('categories'));
    }

    /**
     * Xử lý dữ liệu thêm danh mục mới, kiểm tra tính hợp lệ và lưu vào DB.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) 
    {
        $request->validate(
            ['name' => 'required|max:255|unique:categories'],
            ['name.required' => 'Ní chưa nhập tên mà!', 'name.unique' => 'Tên này có rồi ní ơi!', 'name.max' => 'Tên danh mục không được dài quá 255 ký tự.']
        );

        Category::addCategory($request->name);
        return back()->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Xử lý cập nhật danh mục.
     * Có kiểm tra Optimistic Locking để chống xung đột dữ liệu.
     * @param Request $request Dữ liệu form và original_updated_at
     * @param int $id ID danh mục
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($request->has('original_updated_at') && $category->updated_at != $request->original_updated_at) {
            return back()->with('error', 'Lỗi: Dữ liệu đã bị thay đổi bởi người khác trước đó. Vui lòng tải lại trang để xem dữ liệu mới nhất.');
        }

        $request->validate(
            ['name' => 'required|max:255|unique:categories,name,' . $id],
            ['name.required' => 'Tên không được để trống nha.', 'name.unique' => 'Tên bị trùng rồi ní.', 'name.max' => 'Tên danh mục không được dài quá 255 ký tự.']
        );

        // Gọi hàm update từ Model Category
        Category::updateCategory($id, $request->name);

        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa danh mục. Sẽ chặn lại và báo lỗi nếu danh mục đang chứa sản phẩm.
     * @param int $id ID danh mục cần xóa
     * @return \Illuminate\Http\RedirectResponse
     */
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