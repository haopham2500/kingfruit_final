<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    // Vì bảng của ní không có s (vouchers), Laravel mặc định hiểu được. 
    // Nếu ní đặt tên khác thì dùng: protected $table = 'vouchers';

    protected $fillable = [
        'code', 'discount_value', 'expiry_date', 'type', 
        'min_order_value', 'quantity', 'is_active'
    ];

    // Logic kiểm tra voucher có hợp lệ không
    public function isValid($orderTotal)
    {
        if (!$this->is_active || $this->quantity <= 0) return false;
        if ($this->expiry_date && $this->expiry_date < now()->format('Y-m-d')) return false;
        if ($orderTotal < $this->min_order_value) return false;
        return true;
    }
}