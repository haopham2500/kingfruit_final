<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // Thêm parent_id vào đây
    // File: app/Models/Review.php
    protected $fillable = ['product_id', 'user_id', 'rating', 'comment', 'parent_id']; // Đảm bảo có parent_id
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Review::class, 'parent_id');
    }
}
