<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ProfileUserController;
use App\Http\Controllers\ProductController;

// route đăng xuất
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// route hiển thị sản phẩm trang index
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// route hiển thị sản phẩm khi chọn nhà sản xuất
Route::get('/filter', [ProductController::class, 'filter'])->name('products.filter');

// Route cho tìm kiếm sản phẩm
Route::get('/search', [ProductController::class, 'search'])->name('products.search');


//route blog
Route::get('/{blog?}', [BlogController::class, 'index'])->name('blog.index');
// //route cate
// Route::get('/blog/{type?}', [CategoryController::class, 'index'])->name('cate.index');



// route hiển thị trang index khi chạy lên đầu tiên 
Route::get('/{page?}', [LoadController::class, 'page'])->name('index');


Route::group(['middleware' => 'guest'], function () {
    // route đăng nhập
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');

    // route đăng ký
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // route đổi mật khẩu
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('change-password');

    // route cập nhật thông tin cá nhân
    Route::put('/Profile-user/update', [ProfileUserController::class, 'updateProfile'])->name('user.update');

    // route thay đổi ảnh user
    Route::post('/Profile-user/upload-profile-image', [ProfileUserController::class, 'updateProfileImage']);

    // Thêm route mới cho trang Profile-User
    Route::get('/Profile-user', [ProfileUserController::class, 'show'])->name('profile.show');
});