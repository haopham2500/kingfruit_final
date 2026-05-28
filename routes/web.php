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

// --- ĐA NGÔN NGỮ ---
Route::get('/lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');

// --- 1. TRANG CHỦ, CHI TIẾT & TÌM KIẾM ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/search', [ProductController::class, 'search'])->name('search');

// Route Khuyến mãi công khai (Sửa lỗi Route [promotions.index] not defined)
Route::get('/khuyen-mai', [VoucherController::class, 'showPromotions'])->name('promotions.index');


// --- 2. HỆ THỐNG ĐĂNG KÝ / ĐĂNG NHẬP ---
Route::get('/register', [CrudUserController::class, 'showRegister'])->name('register');
Route::post('/register', [CrudUserController::class, 'createUser']);
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser']);
Route::post('/logout', [CrudUserController::class, 'logout'])->name('logout');


// --- 3. HỆ THỐNG GIỎ HÀNG ---
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::match(['get', 'post'], '/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/remove', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::get('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
});


// --- 4. THANH TOÁN & VOUCHER (Dành cho khách hàng đã đăng nhập) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

    // Voucher cho người dùng
    Route::post('/apply-voucher', [VoucherController::class, 'applyVoucher'])->name('voucher.apply');
    Route::post('/collect-voucher', [VoucherController::class, 'collectVoucher'])->name('voucher.collect');

    // Bình luận
    Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::post('/reviews/{id}/reply-user', [ReviewController::class, 'reply'])->name('review.reply');
});

// --- 6. HỆ THỐNG QUẢN TRỊ (ADMIN) ---
// Lưu ý: Mình giữ nguyên name('crud') để khớp với Controller và View hiện tại của ní
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // 6.1. Quản lý sản phẩm (CRUD)
    Route::get('/crud', [ProductController::class, 'indexAdmin'])->name('crud');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');

    // 6.2. Quản lý danh mục
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');

    // 6.3. Quản lý người dùng
    Route::get('/users', [CrudUserController::class, 'index'])->name('admin.users.index');
    Route::delete('/users/{id}', [CrudUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/users/toggle/{id}', [CrudUserController::class, 'toggleRole'])->name('admin.users.toggle');
    Route::get('/users/edit/{id}', [CrudUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/update/{id}', [CrudUserController::class, 'update'])->name('admin.users.update');

    // 6.4. Quản lý Vouchers
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('admin.vouchers.store');
    Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
    Route::get('/vouchers/{id}/edit', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
    Route::put('/vouchers/{id}', [VoucherController::class, 'update'])->name('admin.vouchers.update');

    // 6.5. Quản lý Đơn hàng (Dùng prefix admin. cho đồng bộ)
    Route::name('admin.')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

    // 6.6. Quản lý Bình luận (Admin)
    Route::name('admin.')->group(function () {
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{id}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });
});

// Route cho trang Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [CrudUserController::class, 'profile'])->name('profile');
});