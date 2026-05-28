<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_at_purchase',
        'price',
    ];

    // Accessor for price -> price_at_purchase
    public function getPriceAttribute()
    {
        return $this->attributes['price_at_purchase'] ?? null;
    }

    // Mutator for price -> price_at_purchase
    public function setPriceAttribute($value)
    {
        $this->attributes['price_at_purchase'] = $value;
    }

    public function product()
    {
        // Để lấy được tên và ảnh sản phẩm ở trang show
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}