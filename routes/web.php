<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
<<<<<<< HEAD
| Web Routes - DỰ ÁN VUA TRÁI CÂY
=======
| Web Routes
>>>>>>> origin/php/master
|--------------------------------------------------------------------------
*/

// --- TRANG CHỦ & CHI TIẾT ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');

<<<<<<< HEAD
// --- TÌM KIẾM SẢN PHẨM ---
Route::get('/search', [ProductController::class, 'search'])->name('search');
=======
//--- TÌM KIẾM SẢN PHẨM ---Giang
Route::get('/search', [ProductController::class, 'search'])->name('search');

>>>>>>> origin/php/master

// --- HỆ THỐNG ĐĂNG KÝ / ĐĂNG NHẬP ---
Route::get('/register', [CrudUserController::class, 'showRegister'])->name('register');
Route::post('/register', [CrudUserController::class, 'createUser']);

Route::get('/login', [CrudUserController::class, 'login'])->name('login');
Route::post('/login', [CrudUserController::class, 'authUser']);
Route::post('/logout', [CrudUserController::class, 'logout'])->name('logout');

<<<<<<< HEAD
// --- HỆ THỐNG GIỎ HÀNG (ĐÃ SỬA LỖI ĐỊNH TUYẾN) ---
// Chuyển hết về CartController để xử lý qua CartService cho mượt
Route::get('/cart', [CartController::class, 'index'])->name('cart.index'); 
Route::post('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/update-cart', [CartController::class, 'updateCart'])->name('cart.update');
Route::delete('/remove-from-cart', [CartController::class, 'removeCart'])->name('cart.remove');
Route::get('/clear-cart', [CartController::class, 'clearCart'])->name('cart.clear');

// --- HỆ THỐNG QUẢN TRỊ (ADMIN) ---
=======





// --- GIỎ HÀNG ---quyền
// Route hiển thị chi tiết (GET) - Cái này đã có sẵn
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');

// ĐỔI THÀNH POST để nhận dữ liệu từ Form trang chi tiết
// Cho phép cả GET (từ link trang chủ) và POST (từ form trang chi tiết)
Route::match(['get', 'post'], '/add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('cart.add');// Các route khác giữ nguyên
Route::get('/cart', [ProductController::class, 'cart'])->name('cart.index');
Route::patch('/update-cart', [ProductController::class, 'updateCart'])->name('cart.update');
Route::delete('/remove-from-cart', [ProductController::class, 'removeCart'])->name('cart.remove');

// Thêm dòng này để định nghĩa route xóa sạch giỏ hàng
Route::get('/clear-cart', [ProductController::class, 'clearCart'])->name('cart.clear');






// --- HỆ THỐNG QUẢN TRỊ (ADMIN) ---Hào
>>>>>>> origin/php/master
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
<<<<<<< HEAD
});
=======
}); // Đã thêm dấu chấm phẩy ở đây ní nhé!
>>>>>>> origin/php/master
