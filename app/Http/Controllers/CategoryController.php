<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index()
    {
        $categories = DB::table('categories')->get();
        return view('admin.categories', compact('categories'));
    }

    // Thêm danh mục mới
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories']);
        
        DB::table('categories')->insert([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Thêm danh mục thành công!');
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        DB::table('categories')->where('id', $id)->update([
            'name' => $request->name,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    // Xóa danh mục
    public function destroy($id)
    {
        // Kiểm tra xem có sản phẩm nào thuộc danh mục này không trước khi xóa
        $count = DB::table('products')->where('category_id', $id)->count();
        if ($count > 0) {
            return back()->with('error', 'Không thể xóa! Có sản phẩm đang thuộc danh mục này.');
        }

        DB::table('categories')->where('id', $id)->delete();
        return back()->with('success', 'Xóa danh mục thành công!');
    }
}