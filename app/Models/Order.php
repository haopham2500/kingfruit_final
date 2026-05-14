<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    // Trỏ đúng vào tên bảng trong database thủ công của ní
    protected $table = 'orders'; 

    // Khóa chính là 'id' theo file SQL
    protected $primaryKey = 'id'; 

    // Cho phép cập nhật các cột hiện có và các cột mới sẽ thêm vào
    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone_number',
        'address',
        'total_amount',
        'status',
    ];

    /**
     * Thiết lập mối quan hệ với bảng Users
     * Giúp lấy tên khách hàng thông qua $order->user->name
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function details()
    {
    // Vì bảng của ní trong SQL là 'order_items'
    // Nên ta khai báo liên kết tới Model OrderItem (ní nhớ tạo Model này nhé)
    return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    // Laravel mặc định đã hiểu created_at và updated_at nên không cần map lại 
    // trừ khi ní muốn đổi tên cột trong Database thành tên khác.
}