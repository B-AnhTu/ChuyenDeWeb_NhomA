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
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductLikeController;
use App\Http\Controllers\CartProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductReviewController;
use App\Mail\NewsletterWelcome;
use Illuminate\Support\Facades\Mail;

// route đăng xuất
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


//route cart
Route::get('/cart/cartAdmin', [CartProductController::class, 'index'])->name('cart.index');
Route::delete('/cart/{cart_id}/product/{product_id}', [CartProductController::class, 'destroy'])->name('cart.destroy');

// route hiển thị sản phẩm trang index
Route::get('/', [IndexController::class, 'index'])->name('products.index');

// route hiển thị sản phẩm khi chọn nhà sản xuất
Route::get('/filterByManufacturer', [IndexController::class, 'filter']);

// route để lọc sản phẩm theo loại sản phẩm
Route::get('/filterByCategory', [IndexController::class, 'filterByCategory']);

// route cho tìm kiếm sản phẩm
Route::get('/search', [IndexController::class, 'search']);

// route sắp xếp
Route::get('/sort', [IndexController::class, 'sort']);

// route hiển thị sản phẩm trang product
Route::get('/product', [ProductController::class, 'index'])->name('products.product');

// route hiển thị sản phẩm khi chọn nhà sản xuất
Route::get('/filterByManufacturers', [ProductController::class, 'filter'])->name('products.filter');


// route cho tìm kiếm sản phẩm
Route::get('/searchProduct', [ProductController::class, 'search'])->name('products.search');

// route sắp xếp
Route::get('/products/sort', [ProductController::class, 'sort'])->name('products.sort');

// route chi tiết sản phẩm 
Route::get('/productDetail/{slug}', [IndexController::class, 'showProductDetail']);

// route thêm sản phẩm vào giỏ hàng
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');

// route hiển thị sản phẩm trong giỏ hàng
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
//route cập nhật giỏ hàng
Route::post('/update-cart', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
//Route thanh toán
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'showCheckoutForm'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::put('/admin/update', [AdminController::class, 'update'])->name('admin.update');
    Route::post('/admin/upload-profile-image', [AdminController::class, 'uploadProfileImage'])->name('admin.upload.profile.image');
});
// route thích và bỏ thích sản phẩm
Route::post('/product-toggle-like', [ProductLikeController::class, 'toggleLike'])->middleware('auth');

// route hiển thị sản phẩm thích 
Route::get('/wishlist', [ProductLikeController::class, 'wishlist'])->name('wishlist');
//route blog
Route::get('/blog/{slug}', [BlogCommentController::class, 'show'])->name('blog.detail');

Route::get('blog/{slug?}', [BlogController::class, 'index'])->name('blog.index');
// Routes cho quản lý bình luận
Route::middleware(['auth'])->group(function () {
    // Route cho quản lý bình luận đã duyệt
    Route::get('/comments/manage', [BlogCommentController::class, 'manageComments'])->name('comments.manage');

    // Route cho quản lý bình luận đang chờ duyệt
    Route::get('/comments/unapproved', [BlogCommentController::class, 'unapprovedComments'])->name('comments.unapproved');

    // Route cho phê duyệt bình luận
    Route::post('/comments/{id}/approve', [BlogCommentController::class, 'approve'])->name('comments.approve');

    // Route cho không phê duyệt bình luận
    Route::post('/comments/{id}/disapprove', [BlogCommentController::class, 'disapprove'])->name('comments.disapprove');

    // Route để xóa bình luận
    Route::delete('/comments/{id}', [BlogCommentController::class, 'destroy'])->name('comments.destroy');

    // Route để lưu trữ bình luận
    Route::post('/comments', [BlogCommentController::class, 'store'])->name('comments.store');
});

Route::post('/product-review', [ProductReviewController::class, 'store'])->name('review.store');
Route::get('/product/{slug}', [ProductReviewController::class, 'show'])->name('product.details');
//Route quản lý review product
Route::middleware(['auth'])->group(function () {
    Route::get('/reviews/pending', [ProductReviewController::class, 'pendingReviews'])->name('reviews.pending');
    Route::get('/reviews/approved', [ProductReviewController::class, 'approvedReviews'])->name('reviews.approved');
    Route::post('/reviews/{id}/approve', [ProductReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{id}/reject', [ProductReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{id}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Routes cho theo dõi đơn hàng
Route::get('/orders/track', [CheckoutController::class, 'showTrackingForm'])->name('orders.track-form');
Route::post('/orders/track', [CheckoutController::class, 'trackOrder'])->name('orders.track');

// routes/web.php
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
Route::post('/send-contact', [ContactController::class, 'sendMail'])->name('send.contact');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/newsletter', [NewsletterController::class, 'index'])->name('admin.newsletter.index');
    Route::post('/admin/newsletter/send', [NewsletterController::class, 'sendNotification'])->name('admin.newsletter.send');
});

Route::group(['middleware' => 'role:admin,editor'], function () {
    // Route for admin dashboard
    Route::get('/adminPage', [UserController::class, 'listRole'])->name('admin.index');
    Route::put('/userAdmin/{slug}/update-permissions', [AdminDashboardController::class, 'updatePermissions'])->name('userAdmin.updatePermissions');

    //route manufacturer
    Route::get('/manufacturerAdmin', [ManufacturerController::class, 'index'])->name('manufacturer.index');

    Route::get('/manufacturerAdmin/{slug}', [ManufacturerController::class, 'show'])->name('manufacturer.show');

    Route::get('/manufacturerCreate', [ManufacturerController::class, 'create'])->name('manufacturer.create');
    Route::post('/manufacturerCreate', [ManufacturerController::class, 'store'])->name('manufacturer.store');

    Route::get('/manufacturerUpdate/{slug}', [ManufacturerController::class, 'edit'])->name('manufacturer.edit');
    Route::put('/manufacturerUpdate/{slug}', [ManufacturerController::class, 'update'])->name('manufacturer.update');

    Route::delete('/manufacturerDelete/{slug}', [ManufacturerController::class, 'destroy'])->name('manufacturer.delete');

    //route category
    Route::get('/categoryAdmin', [CategoryController::class, 'list'])->name('category.index');

    Route::get('/categoryAdmin/{slug}', [CategoryController::class, 'show'])->name('category.show');

    Route::get('/categoryCreate', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/categoryCreate', [CategoryController::class, 'store'])->name('category.store');

    Route::get('/categoryUpdate/{slug}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/categoryUpdate/{slug}', [CategoryController::class, 'update'])->name('category.update');

    Route::delete('/categoryDelete/{slug}', [CategoryController::class, 'destroy'])->name('category.delete');

    //route product
    Route::get('/productAdmin', [ProductController::class, 'list'])->name('product.index');

    Route::get('/productAdmin/{slug}', [ProductController::class, 'show'])->name('product.show');

    Route::get('/productCreate', [ProductController::class, 'create'])->name('product.create');
    Route::post('/productCreate', [ProductController::class, 'store'])->name('product.store');

    Route::get('/productUpdate/{slug}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/productUpdate/{slug}', [ProductController::class, 'update'])->name('product.update');

    //Route delete
    Route::delete('/productDelete/{slug}', [ProductController::class, 'destroy'])->name('product.delete');

    Route::get('/products/trashed', [ProductController::class, 'trashed'])->name('product.trashed');
    Route::put('/products/{id}/restore', [ProductController::class, 'restore'])->name('product.restore');
    Route::delete('/products/{id}/forceDelete', [ProductController::class, 'forceDelete'])->name('product.forceDelete');

    //route user
    Route::get('/userAdmin', [UserController::class, 'list'])->name('userAdmin.index');

    Route::get('/userAdmin/{slug}', [UserController::class, 'show'])->name('userAdmin.show');

    Route::get('/userCreate', [UserController::class, 'create'])->name('userAdmin.create');
    Route::post('/userCreate', [UserController::class, 'store'])->name('userAdmin.store');

    Route::get('/userUpdate/{slug}', [UserController::class, 'edit'])->name('userAdmin.edit');
    Route::put('/userUpdate/{slug}', [UserController::class, 'update'])->name('userAdmin.update');

    Route::delete('/userDelete/{slug}', [UserController::class, 'destroy'])->name('userAdmin.delete');


    //route blog (admin)
    Route::get('/blogAdmin', [BlogController::class, 'list'])->name('blogAdmin.index');

    Route::get('/blogAdmin/{slug}', [BlogController::class, 'show'])->name('blogAdmin.show');

    Route::get('/blogCreate', [BlogController::class, 'create'])->name('blogAdmin.create');
    Route::post('/blogCreate', [BlogController::class, 'store'])->name('blogAdmin.store');

    Route::get('/blogUpdate/{slug}', [BlogController::class, 'edit'])->name('blogAdmin.edit');
    Route::put('/blogUpdate/{slug}', [BlogController::class, 'update'])->name('blogAdmin.update');

    Route::delete('/blogDelete/{slug}', [BlogController::class, 'destroy'])->name('blogAdmin.delete');

    //Route sorting cho trang quản trị
    //Route::get('/sortProducts', [ProductController::class, 'sortProducts'])->name('sortProducts');
    //Route::get('/sortCategories', [CategoryController::class, 'sortCategories'])->name('sortCategories');
    //Route::get('/sortManufacturers', [ManufacturerController::class, 'sortManufacturers'])->name('sortManufacturers');
    //Route::get('/sortBlogs', [BlogController::class, 'sortBlogs'])->name('sortBlogs');
    //Route::get('/sortUsers', [UserController::class, 'sortUsers'])->name('sortUsers');
    //Route::get('/sortAdmin', [UserController::class, 'sortAdmin'])->name('sortAdmin');

    //Route tìm kiếm 
    //Route::get('/searchProducts', [ProductController::class, 'searchProducts'])->name('searchProducts');
    //Route::get('/searchCategories', [CategoryController::class, 'searchCategories'])->name('searchCategories');
    //Route::get('/searchManufacturers', [ManufacturerController::class, 'searchManufacturers'])->name('searchManufacturers');
    //Route::get('/searchBlogs', [BlogController::class, 'searchBlogs'])->name('searchBlogs');
    //Route::get('/searchUsers', [UserController::class, 'searchUsers'])->name('searchUsers');
    //Route::get('/searchPage', [UserController::class, 'searchPage'])->name('searchPage');

    //Route order
    Route::get('/orders/statistics', [AdminOrderController::class, 'statistics'])->name('orders.statistics');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});


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

// Route dành cho người dùng đã đăng nhập
Route::middleware(['auth'])->group(function () {
    // route đổi mật khẩu
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('change-password');

    // route cập nhật thông tin cá nhân
    Route::put('/Profile-user/update', [ProfileUserController::class, 'updateProfile'])->name('user.update');

    // route thay đổi ảnh user
    Route::post('/Profile-user/upload-profile-image', [ProfileUserController::class, 'updateProfileImage']);

    // Thêm route mới cho trang Profile-User
    Route::get('/Profile-user', [ProfileUserController::class, 'show'])->name('profile.show');

    //Orders
    Route::get('/my-orders', [CheckoutController::class, 'myOrders'])->name('orders.my-orders');
    
    Route::get('/order/{order_id}', [CheckoutController::class, 'orderDetail'])->name('orders.detail');
    
    Route::post('/orders/{id}/cancel', [CheckoutController::class, 'cancelOrder'])->name('orders.cancel');
    
    Route::post('/orders/{id}/confirm-received', [CheckoutController::class, 'confirmReceived'])->name('orders.confirm-received');
});
