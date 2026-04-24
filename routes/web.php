<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\CategoryController;

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
    
    // Quản lý sản phẩm (CRUD)
    Route::get('/crud', [ProductController::class, 'indexAdmin'])->name('crud');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');

    // Quản lý danh mục (Categories)
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');
});