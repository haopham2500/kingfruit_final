<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes - DỰ ÁN VUA TRÁI CÂY
|--------------------------------------------------------------------------
*/

// --- 1. TRANG CHỦ, CHI TIẾT & TÌM KIẾM ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/khuyen-mai', [VoucherController::class, 'showPromotions'])->name('promotions.index');

// --- 2. HỆ THỐNG ĐĂNG KÝ / ĐĂNG NHẬP ---
Route::get('/register', [CrudUserController::class, 'showRegister'])->name('register');
Route::post('/register', [CrudUserController::class, 'createUser']);
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser']);
Route::post('/logout', [CrudUserController::class, 'logout'])->name('logout');

// --- 3. GIỎ HÀNG & THANH TOÁN ---
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::match(['get', 'post'], '/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/remove', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::get('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::post('/apply-voucher', [VoucherController::class, 'applyVoucher'])->name('voucher.apply');
});

// --- 4. HỆ THỐNG BÌNH LUẬN (DÀNH CHO MỌI NGƯỜI) ---
Route::middleware(['auth'])->group(function () {
    Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::post('/reviews/{id}/reply', [ReviewController::class, 'reply'])->name('review.reply');
});

// --- 5. HỆ THỐNG QUẢN TRỊ (ADMIN) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // 5.1. Quản lý sản phẩm
    Route::get('/crud', [ProductController::class, 'indexAdmin'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');

    // 5.2. Quản lý danh mục
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');

    // 5.3. Quản lý người dùng
    Route::get('/users', [CrudUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [CrudUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/toggle/{id}', [CrudUserController::class, 'toggleRole'])->name('users.toggle');
    Route::get('/users/edit/{id}', [CrudUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/update/{id}', [CrudUserController::class, 'update'])->name('users.update');

    // 5.4. Quản lý Vouchers
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/{id}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{id}', [VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');

    // 5.5. Quản lý đơn hàng
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // 5.6. Quản lý bình luận
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});