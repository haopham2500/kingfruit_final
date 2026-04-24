
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\CategoryController;

// --- TRANG CHỦ, CHI TIẾT & TÌM KIẾM ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/search', [ProductController::class, 'search'])->name('search'); // Route tìm kiếm quan trọng nhất

// --- HỆ THỐNG ĐĂNG KÝ / ĐĂNG NHẬP ---
Route::get('/register', [CrudUserController::class, 'showRegister'])->name('register');
Route::post('/register', [CrudUserController::class, 'createUser']);
Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser']);
Route::post('/logout', [CrudUserController::class, 'logout'])->name('logout');

// --- HỆ THỐNG QUẢN TRỊ (ADMIN) ---
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/crud', [ProductController::class, 'indexAdmin'])->name('crud');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');
});
