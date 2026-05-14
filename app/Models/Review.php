<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_id', 'user_id', 'rating', 'comment'];

    // Khai báo để Laravel tự động chuyển string thành đối tượng Carbon
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Nếu bạn không dùng cột updated_at như ở bước trước
    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

