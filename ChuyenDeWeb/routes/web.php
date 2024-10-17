<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoadController;
use PHPUnit\Event\TestSuite\Loaded;

    // route hiển thị trang index khi chạy lên đầu tiên 
    Route::get('/{page?}', [LoadController::class, 'page'])->name('index');
