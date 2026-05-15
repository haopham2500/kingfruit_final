<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProductController,
    CartController,
    CrudUserController,
    CategoryController,
    VoucherController,
    OrderController,
    ReviewController,
    CheckoutController
};

/*
|--------------------------------------------------------------------------
| Web Routes - DỰ ÁN VUA TRÁI CÂY
|--------------------------------------------------------------------------
*/

// --- 1. TRANG CHỦ, CHI TIẾT, TÌM KIẾM & KHUYẾN MÃI ---
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


// --- 3. HỆ THỐNG GIỎ HÀNG ---
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::match(['get', 'post'], '/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/remove', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::get('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
});


// --- 4. THANH TOÁN & VOUCHER (Yêu cầu đăng nhập) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
    
    // Voucher
    Route::post('/apply-voucher', [VoucherController::class, 'applyVoucher'])->name('voucher.apply');
    Route::post('/collect-voucher', [VoucherController::class, 'collectVoucher'])->name('voucher.collect');

    // Bình luận (Khách hàng)
    Route::post('/product/{id}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::post('/reviews/{id}/reply-user', [ReviewController::class, 'reply'])->name('review.reply');
});


// --- 5. HỆ THỐNG QUẢN TRỊ (ADMIN) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // 5.1. Quản lý sản phẩm (Vẫn giữ name 'crud' theo ý bạn)
    Route::get('/crud', [ProductController::class, 'indexAdmin'])->name('product.index'); // Bạn có thể dùng route('admin.product.index')
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');

    // 5.2. Quản lý danh mục
    Route::prefix('categories')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
    });

    // 5.3. Quản lý người dùng
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [CrudUserController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [CrudUserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CrudUserController::class, 'update'])->name('update');
        Route::delete('/{id}', [CrudUserController::class, 'destroy'])->name('destroy');
        Route::patch('/toggle/{id}', [CrudUserController::class, 'toggleRole'])->name('toggle');
    });

    // 5.4. Quản lý Vouchers
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
        Route::get('/', [VoucherController::class, 'index'])->name('index');
        Route::post('/', [VoucherController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [VoucherController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VoucherController::class, 'update'])->name('update');
        Route::delete('/{id}', [VoucherController::class, 'destroy'])->name('destroy');
    });

    // 5.5. Quản lý Đơn hàng
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/update-status/{id}', [OrderController::class, 'updateStatus'])->name('updateStatus');
    });

    // 5.6. Quản lý Bình luận
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::post('/{id}/reply', [ReviewController::class, 'reply'])->name('reply');
        Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
    });
});