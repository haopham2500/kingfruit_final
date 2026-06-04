<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use Carbon\Carbon;

class ReviewController extends Controller
{

    // 1. Khách gửi bình luận (Code cũ của ní)
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => ['required','string','max:200', function ($attribute, $value, $fail) {
                $invalidPatterns = [
                    '/<[^>]+>/',
                    '/(?:<\?php|<script\b|<\/script>|\b(?:SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|GRANT|UNION)\b)/i',
                ];

                foreach ($invalidPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $fail(__('messages.review_comment_invalid'));
                        return;
                    }
                }
            }],
        ], [
            'comment.required' => __('messages.review_comment_required'),
            'comment.max' => __('messages.review_comment_max'),
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

    // 3.1. Admin sửa phản hồi
    public function updateReply(Request $request, $id)
    {
        $request->validate([
            'reply_content' => 'required|string|max:500',
            'reply_updated_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $reply = Review::findOrFail($id);
        if ($reply->parent_id === null) {
            return back()->with('error', 'Không thể sửa nội dung này.');
        }

        if ($request->reply_updated_at !== $reply->updated_at->format('Y-m-d H:i:s')) {
            return back()->with('error', __('messages.review_edit_conflict'));
        }

        $reply->update([
            'comment' => $request->reply_content,
        ]);

        return back()->with('success', 'Đã cập nhật phản hồi thành công!');
    }

    // 4. Xóa bình luận
    public function destroy(Request $request, $id)
    {
        $review = Review::find($id);
        if (!$review) {
            return back()->with('error', __('messages.review_delete_conflict'));
        }

        if ($request->filled('review_updated_at')) {
            if ($request->review_updated_at !== $review->updated_at->format('Y-m-d H:i:s')) {
                return back()->with('error', __('messages.review_delete_conflict'));
            }
        }

        $review->delete();
        return back()->with('success', 'Đã xóa bình luận thành công!');
    }

    public function checkStatus($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'status' => 'changed',
                'message' => __('messages.review_changed_reload'),
            ], 409);
        }

        return response()->json([
            'status' => 'ok',
            'updated_at' => $review->updated_at->format('Y-m-d H:i:s'),
        ]);
    }

    public function checkReplyStatus($id)
    {
        $reply = Review::find($id);

        if (!$reply || $reply->parent_id === null) {
            return response()->json([
                'status' => 'changed',
                'message' => __('messages.review_changed_reload'),
            ], 409);
        }

        return response()->json([
            'status' => 'ok',
            'updated_at' => $reply->updated_at->format('Y-m-d H:i:s'),
        ]);
    }
}
