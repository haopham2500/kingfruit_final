<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    public function product()
    {
        // Để lấy được tên và ảnh sản phẩm ở trang show
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}