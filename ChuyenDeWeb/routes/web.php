<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoadController;
use PHPUnit\Event\TestSuite\Loaded;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChangePasswordController;

// route đăng xuất
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');



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
});
