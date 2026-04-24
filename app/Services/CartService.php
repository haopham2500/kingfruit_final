<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CartService
{
    public function addToCart($id, $quantity)
    {
        // Lấy dữ liệu bằng Query Builder thay vì Model
        $product = DB::table('products')->where('id', $id)->first();

        if (!$product) return false;

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => (int)$quantity,
                "price" => $product->price,
                "image" => $product->image,
                "unit" => $product->unit ?? 'Kg'
            ];
        }

        session()->put('cart', $cart);
        return true;
    }

    public function getCart()
    {
        return session()->get('cart', []);
    }
}