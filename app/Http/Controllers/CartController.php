<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // 1. Trang danh sách giỏ hàng
    public function index()
    {
        $cartItems = $this->cartService->getCart();
        return view('cart', compact('cartItems'));
    }

    // 2. Thêm sản phẩm vào giỏ
    public function addToCart(Request $request, $id)
    {
        $quantity = $request->input('quantity', 1);
        $result = $this->cartService->addToCart($id, $quantity);

        if (!$result) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại!');
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // 3. Cập nhật số lượng (Dùng cho Ajax PATCH)
    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);
                return response()->json(['status' => 'success', 'message' => 'Đã cập nhật số lượng!']);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm!'], 404);
    }

    // 4. Xóa từng sản phẩm (Dùng cho Ajax DELETE)
    public function removeCart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                return response()->json(['status' => 'success', 'message' => 'Đã xóa sản phẩm!']);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm!'], 404);
    }

    // 5. Xóa sạch giỏ hàng (Dùng cho thẻ <a>)
    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được dọn sạch!');
    }
}