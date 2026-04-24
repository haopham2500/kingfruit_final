<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // ==========================================
    // 1. LOGIC HIỂN THỊ & TÌM KIẾM
    // ==========================================

    // Hiển thị trang chủ (Danh sách sản phẩm & Bán chạy)
    public function index()
    {
        $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->get();

        $bestSellers = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('home', compact('products', 'bestSellers'));
    }

    // Hiển thị chi tiết sản phẩm
    public function show($id)
    {
        $product = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->where('products.id', $id)
            ->first();

        if (!$product) {
            return redirect()->route('home');
        }

        $comments = DB::table('reviews')
            ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
            ->where('reviews.product_id', $id)
            ->select('reviews.*', 'users.name as user_name')
            ->get();

        return view('detail', compact('product', 'comments'));
    }

    // Tìm kiếm sản phẩm
    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->where('products.name', 'LIKE', "%{$query}%")
            ->orWhere('categories.name', 'LIKE', "%{$query}%")
            ->get();

        $bestSellers = DB::table('products')->inRandomOrder()->limit(4)->get();
        
        return view('home', compact('products', 'query', 'bestSellers'));
    }

    // ==========================================
    // 2. LOGIC QUẢN TRỊ (ADMIN CRUD)
    // ==========================================

    public function indexAdmin()
    {
        $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->get();

        $categories = DB::table('categories')->get();

        return view('admin.crud', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fileName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fileName);
        }

        DB::table('products')->insert([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'unit' => $request->unit,
            'description' => $request->description,
            'image' => $fileName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('crud')->with('success', 'Đã thêm sản phẩm mới thành công!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'unit' => $request->unit,
            'description' => $request->description,
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fileName);
            $data['image'] = $fileName;
        }

        DB::table('products')->where('id', $id)->update($data);

        return redirect()->route('crud')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        DB::table('products')->where('id', $id)->delete();
        return back()->with('success', 'Xóa sản phẩm thành công!');
    }

    // ==========================================
    // 3. LOGIC GIỎ HÀNG (CART)
    // ==========================================

    // Xem giỏ hàng
    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    // Thêm vào giỏ
    public function addToCart(Request $request, $id)
    {
        $product = DB::table('products')->where('id', $id)->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại!');
        }

        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image,
                "unit" => $product->unit
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Đã thêm ' . $product->name . ' vào giỏ hàng!');
    }

    // Cập nhật giỏ hàng (Số lượng)
    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);
                return response()->json(['status' => 'success']);
            }
        }
        return response()->json(['status' => 'error'], 400);
    }

    // Xóa sản phẩm khỏi giỏ
    public function removeCart(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                return response()->json(['status' => 'success']);
            }
        }
        return response()->json(['status' => 'error'], 400);
    }

    // Làm trống giỏ hàng
    // Xóa sạch giỏ hàng
public function clearCart()
{
    session()->forget('cart');
    return redirect()->back()->with('success', 'Đã xóa toàn bộ giỏ hàng.');
}
}