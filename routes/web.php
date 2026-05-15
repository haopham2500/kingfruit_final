<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\OrderController;
// Route cho trang khuyến mãi công khai
Route::get('/khuyen-mai', [App\Http\Controllers\VoucherController::class, 'showPromotions'])->name('promotions.index');
// routes/web.php
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/checkout/place-order',
    [CheckoutController::class, 'placeOrder'])
    ->name('checkout.placeOrder');
/*
|--------------------------------------------------------------------------
| Web Routes - DỰ ÁN VUA TRÁI CÂY
|--------------------------------------------------------------------------
*/

// --- 1. TRANG CHỦ & CHI TIẾT SẢN PHẨM ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/search', [ProductController::class, 'search'])->name('search');


// --- 2. HỆ THỐNG ĐĂNG KÝ / ĐĂNG NHẬP ---
Route::get('/register', [CrudUserController::class, 'showRegister'])->name('register');
Route::post('/register', [CrudUserController::class, 'createUser']);

Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser']);
Route::post('/logout', [CrudUserController::class, 'logout'])->name('logout');


// --- 3. HỆ THỐNG GIỎ HÀNG (SỬ DỤNG CARTCONTROLLER & SERVICE) ---
Route::prefix('cart')->group(function () {
    // Hiển thị giỏ hàng
    Route::get('/', [CartController::class, 'index'])->name('cart.index');

    // Thêm vào giỏ (Dùng match để nhận cả nút "Mua ngay" và Form từ trang chi tiết)
    Route::match(['get', 'post'], '/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');

    // Cập nhật số lượng (Ajax Patch)
    Route::patch('/update', [CartController::class, 'updateCart'])->name('cart.update');

    // Xóa từng món (Ajax Delete)
    Route::delete('/remove', [CartController::class, 'removeCart'])->name('cart.remove');

    // Xóa sạch giỏ hàng
    Route::get('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
});


// --- 4. HỆ THỐNG QUẢN TRỊ (ADMIN) ---
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // 4.1. Quản lý sản phẩm (CRUD)
    Route::get('/crud', [ProductController::class, 'indexAdmin'])->name('crud');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');

    // 4.2. Quản lý danh mục (Categories)
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');

    // 4.3. QUẢN LÝ NGƯỜI DÙNG (MỚI THÊM)
    // Hiển thị danh sách user
    Route::get('/users', [\App\Http\Controllers\CrudUserController::class, 'index'])->name('admin.users.index');

    // Xóa người dùng (Dùng DELETE cho đúng chuẩn Laravel)
    Route::delete('/users/{id}', [\App\Http\Controllers\CrudUserController::class, 'destroy'])->name('admin.users.destroy');

    // khóa người dùng
    Route::patch('/admin/users/toggle/{id}', [CrudUserController::class, 'toggleRole'])->name('admin.users.toggle');
    // Cập nhật người dùng
    Route::get('/users/edit/{id}', [CrudUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/update/{id}', [CrudUserController::class, 'update'])->name('admin.users.update');

    // 4.4 QUẢN LÝ vouchers
    Route::prefix('admin')->group(function () {
        // Tên route vẫn giữ admin.vouchers.index cho chuẩn Laravel 
        // nhưng trỏ vào URL /vouchers
        Route::get('/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
        Route::post('/vouchers', [VoucherController::class, 'store'])->name('admin.vouchers.store');
        Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
        // cập nhật voucher
        Route::get('/vouchers/{id}/edit', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
        Route::put('/vouchers/{id}', [VoucherController::class, 'update'])->name('admin.vouchers.update');
    });

    // 4.5 QUẢN LÝ ĐƠN HÀNG
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        // Trang danh sách đơn hàng
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        //xem chi tiết đơn hàng
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

        // Xử lý cập nhật trạng thái đơn hàng (Dùng POST vì có gửi dữ liệu thay đổi lên DB)
        Route::post('/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
    //route cho áp dụng voucher khi checkout
    Route::post('/apply-voucher', [App\Http\Controllers\VoucherController::class, 'applyVoucher'])->name('voucher.apply');

    // Route để lưu voucher vào danh sách "đã lấy" của người dùng
Route::post('/collect-voucher', [App\Http\Controllers\VoucherController::class, 'collectVoucher'])->name('voucher.collect');





Route::post('/product/{id}/review', [ReviewController::class, 'store'])
    ->name('review.store')
    ->middleware('auth');
});
