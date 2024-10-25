<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ProfileUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserController;

// route đăng xuất
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Route for admin dashboard
Route::get('/adminPage', [AdminDashboardController::class, 'index'])->name('admin.index');

//route manufacturer (admin)
Route::get('/manufacturerAdmin', [ManufacturerController::class, 'index'])->name('manufacturer.index');

Route::get('/manufacturerAdmin/{manufacturer_id}', [ManufacturerController::class, 'show'])->name('manufacturer.show');

Route::get('/manufacturerCreate', [ManufacturerController::class, 'create'])->name('manufacturer.create');
Route::post('/manufacturerCreate', [ManufacturerController::class, 'store'])->name('manufacturer.store');

Route::get('/manufacturerUpdate/{manufacturer_id}', [ManufacturerController::class, 'edit'])->name('manufacturer.edit');
Route::put('/manufacturerUpdate/{manufacturer_id}', [ManufacturerController::class, 'update'])->name('manufacturer.update');

Route::delete('/manufacturerDelete/{manufacturer_id}', [ManufacturerController::class, 'destroy'])->name('manufacturer.delete');

//route category (admin)
Route::get('/categoryAdmin', [CategoryController::class, 'list'])->name('category.index');

Route::get('/categoryAdmin/{category_id}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/categoryCreate', [CategoryController::class, 'create'])->name('category.create');
Route::post('/categoryCreate', [CategoryController::class, 'store'])->name('category.store');

Route::get('/categoryUpdate/{category_id}', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/categoryUpdate/{category_id}', [CategoryController::class, 'update'])->name('category.update');

Route::delete('/categoryDelete/{category_id}', [CategoryController::class, 'destroy'])->name('category.delete');

//route product (admin)
Route::get('/productAdmin', [ProductController::class, 'list'])->name('product.index');

Route::get('/productAdmin/{product_id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/productCreate', [ProductController::class, 'create'])->name('product.create');
Route::post('/productCreate', [ProductController::class, 'store'])->name('product.store');

Route::get('/productUpdate/{product_id}', [ProductController::class, 'edit'])->name('product.edit');
Route::put('/productUpdate/{product_id}', [ProductController::class, 'update'])->name('product.update');

Route::delete('/productDelete/{product_id}', [ProductController::class, 'destroy'])->name('product.delete');

//route user (admin)
Route::get('/userAdmin', [UserController::class, 'list'])->name('userAdmin.index');

Route::get('/userAdmin/{user_id}', [UserController::class, 'show'])->name('userAdmin.show');

Route::get('/userCreate', [UserController::class, 'create'])->name('userAdmin.create');
Route::post('/userCreate', [UserController::class, 'store'])->name('userAdmin.store');

Route::get('/userUpdate/{user_id}', [UserController::class, 'edit'])->name('userAdmin.edit');
Route::put('/userUpdate/{user_id}', [UserController::class, 'update'])->name('userAdmin.update');

Route::delete('/userDelete/{user_id}', [UserController::class, 'destroy'])->name('userAdmin.delete');

//route blog (admin)
Route::get('/blogAdmin', [BlogController::class, 'list'])->name('blogAdmin.index');

Route::get('/blogAdmin/{blog_id}', [BlogController::class, 'show'])->name('blogAdmin.show');

Route::get('/blogCreate', [BlogController::class, 'create'])->name('blogAdmin.create');
Route::post('/blogCreate', [BlogController::class, 'store'])->name('blogAdmin.store');

Route::get('/blogUpdate/{blog_id}', [BlogController::class, 'edit'])->name('blogAdmin.edit');
Route::put('/blogUpdate/{blog_id}', [BlogController::class, 'update'])->name('blogAdmin.update');

Route::delete('/blogDelete/{blog_id}', [BlogController::class, 'destroy'])->name('blogAdmin.delete');


// route hiển thị sản phẩm trang index
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// route hiển thị sản phẩm khi chọn nhà sản xuất
Route::get('/filterByManufacturer', [ProductController::class, 'filter'])->name('products.filter');

// route để lọc sản phẩm theo loại sản phẩm
Route::get('/filterByCategory', [ProductController::class, 'filterByCategory'])->name('products.filterByCategory');

// route cho tìm kiếm sản phẩm
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// route sắp xếp
Route::get('/sort', [ProductController::class, 'sort'])->name('products.sort');

//route blog
Route::get('/blog/{id?}', [BlogController::class, 'index'])->name('blog.index');

// route hiển thị trang index khi chạy lên đầu tiên
Route::get('/{page?}', [LoadController::class, 'page'])->name('index');

Route::group(['middleware' => 'guest'], function () {
    // route đăng nhập
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

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


