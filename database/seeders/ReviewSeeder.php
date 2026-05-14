<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Review::create([
            'product_id' => 1, // ID sản phẩm có thật
            'user_id' => 1,    // ID user có thật
            'rating' => 5,
            'comment' => 'Trái cây King Fruit tươi lắm nha, mlem mlem!',
        ]);
    }
}
