<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review; 
use App\Models\Product; 
use App\Models\User; 

class ReviewController extends Controller
{
    // Đổi $productId thành $id cho khớp với route {id}
// App\Http\Controllers\ReviewController.php
public function store(Request $request, $id) // Khớp với {id} trong route
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
}
