<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscribeController;
use Illuminate\Http\Request; // Pastikan ini yang di-import
use Illuminate\Support\Facades\Route;

// Rute Publik (Jika ada)
Route::resource('products', ProductController::class);

// Rute yang Memerlukan Login
Route::middleware(['auth'])->group(function () {

    // Home dengan pengecekan limit device
    Route::get('/home', [MovieController::class, 'index']
    )->name('home');

    // Logout Kustom
    // Memanggil Controller Fortify secara manual untuk menyisipkan middleware tambahan
    Route::post('/logout', function (Request $request) {
        return app(\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class)->destroy($request);
    })->middleware(['logout.device'])->name('logout');

    // Rute Langganan (Subscription)
    Route::prefix('subscribe')->name('subscribe.')->group(function () {
        Route::get('/plan', [SubscribeController::class, 'showPlans'])->name('plans');
        Route::get('/plan/{plan}', [SubscribeController::class, 'checkoutPlan'])->name('checkout');
        Route::post('/checkout', [SubscribeController::class, 'processCheckout'])->name('process');
        Route::get('/success', [SubscribeController::class, 'showSuccess'])->name('success');
    });

});