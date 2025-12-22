<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscribeController;
use App\Models\Movie;
use Illuminate\Http\Request; // Pastikan ini yang di-import
use Illuminate\Support\Facades\Route;

// Rute Publik (Jika ada)

// Rute yang Memerlukan Login
Route::middleware(['auth'])->group(function () {

    // Home dengan pengecekan limit device
    Route::get('/', [MovieController::class, 'index']
    )->name('home');

    Route::get('/movies', [MovieController::class, 'all'])
    ->name('movies.index');

    Route::get('/movies/search', [MovieController::class, 'search'])
    ->name('movies.search');

    Route::get('/movies/{movie:slug}', [MovieController::class, 'show'])
    ->name('movies.show');

    Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])
    ->name('categories.show');


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