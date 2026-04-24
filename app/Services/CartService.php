<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    // Lấy toàn bộ giỏ hàng
    public function getCart()
    {
        return Session::get('cart', []);
    }

    // Thêm sản phẩm
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
                "unit" => $product->unit // Lấy từ DB kingfruit_full
            ];
        }

        Session::put('cart', $cart);
    }

    // Cập nhật số lượng
    public function updateCart($id, $quantity)
    {
        $cart = $this->getCart();
        if (isset($cart[$id]) && $quantity > 0) {
            $cart[$id]['quantity'] = $quantity;
            Session::put('cart', $cart);
            return true;
        }
        return false;
    }

    // Xóa sản phẩm
    public function removeFromCart($id)
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
            return true;
        }
        return false;
    }

    // Tính tổng tiền
    public function getTotalPrice()
    {
        $cart = $this->getCart();
        return array_reduce($cart, function($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }
}