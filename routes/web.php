<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\HistoryController as AdminHistoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\ProductController as UserProductController;

Route::get('/', [GuestController::class, 'index'])->name('home');



Route::middleware('auth')->group(function () {
    // user
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [UserOrderController::class, 'index'])->name('index');
            Route::post('/order', [UserOrderController::class, 'store'])->name('store');
        });

        // Products
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [UserProductController::class, 'index'])->name('index');
        });
    });

    // admin or staff
    Route::middleware('role:admin|staff')->prefix('admin')->name('admin.')->group(function () {

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index'])->name('index');
            Route::patch('/{order}/approve', [AdminOrderController::class, 'approve'])->name('approve');
            Route::get('/approved', [AdminOrderController::class, 'show'])->name('show');
            Route::patch('/{order}/done', [AdminOrderController::class, 'done'])->name('done');
            Route::patch('/{order}/unclaimed', [AdminOrderController::class, 'unclaimed'])->name('unclaimed');
        });

        // Products
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [AdminProductController::class, 'index'])->name('index');
            Route::get('/create', [AdminProductController::class, 'create'])->name('create');
            Route::post('/store', [AdminProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
            Route::put('/{product}/update', [AdminProductController::class, 'update'])->name('update');
            Route::delete('/{product}/destroy', [AdminProductController::class, 'destroy'])->name('destroy');
        });

        // Histories
        Route::get('/history', [AdminHistoryController::class, 'index'])->name('histories.index');

        // Users
        Route::get('/user', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/user/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/user/store', [AdminUserController::class, 'store'])->name('users.store');
    });
});

require __DIR__.'/auth.php';
