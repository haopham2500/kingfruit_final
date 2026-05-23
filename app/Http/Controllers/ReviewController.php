<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{

    // 1. Khách gửi bình luận (Code cũ của ní)
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        Review::create([
            'product_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Cảm ơn ní đã đánh giá sản phẩm!');
    }

    // 2. Trang quản lý bình luận cho Admin


    public function index()
    {
        // Chỉ lấy bình luận gốc (parent_id là null) để hiển thị
        $reviews = Review::with(['user', 'product', 'replies.user'])
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('admin.reviews', compact('reviews'));
    }


    // 3. Admin trả lời bình luận
    public function reply(Request $request, $id)
    {
        $request->validate([
        'reply_content' => 'required|string|max:500',
    ]);

    $parent = Review::findOrFail($id);

    // Tạo bình luận mới đóng vai trò là câu trả lời
    Review::create([
        'product_id' => $parent->product_id,
        'user_id' => Auth::id(), 
        'parent_id' => $id, // Gắn ID của bình luận gốc vào đây
        'comment' => $request->reply_content,
        'rating' => 5, // Trả lời mặc định 5 sao
    ]);

    return back()->with('success', 'Đã gửi phản hồi thành công!');
    }

    // 4. Xóa bình luận
    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa bình luận thành công!');
    }
}
