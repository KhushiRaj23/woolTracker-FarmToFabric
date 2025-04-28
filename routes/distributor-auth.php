<?php

use App\Http\Controllers\Distributor\Auth\AuthenticatedSessionController;

use App\Http\Controllers\Distributor\Auth\RegisteredUserController;
use App\Http\Controllers\Distributor\ProfileController;
use App\Http\Controllers\Distributor\DistributorController;
use App\Http\Controllers\Distributor\OrderController;
use App\Http\Controllers\Distributor\ShipmentController;
use App\Http\Controllers\Distributor\InventoryController;
use App\Http\Controllers\Distributor\ProductController;
use App\Http\Controllers\Distributor\AnalyticsController;
use App\Http\Controllers\Distributor\BatchController;
use Illuminate\Support\Facades\Route;

Route::prefix('distributor')
    ->name('distributor.')
    ->group(function () {
    
    // guest-only
    Route::middleware('guest:distributor')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);
        Route::get('login',    [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login',   [AuthenticatedSessionController::class, 'store']);
    });

    // authenticated
    Route::middleware(['auth:distributor', 'verified'])->group(function () {
        Route::get('dashboard', [DistributorController::class, 'dashboard'])->name('dashboard');
        Route::get('shipments', [DistributorController::class, 'shipments'])->name('shipments');
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');

        Route::get('profile',  [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile',[ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile',[ProfileController::class,'destroy'])->name('profile.destroy');

        Route::post('logout',  [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        // Orders
        Route::resource('orders', OrderController::class);

        // Products
        Route::resource('products', ProductController::class);

        // Inventory
        Route::resource('inventory', InventoryController::class);

        // Shipments
        Route::resource('shipments', ShipmentController::class);

        // Batch management routes
        Route::prefix('batches')->name('batches.')->group(function () {
            Route::get('/', [BatchController::class, 'index'])->name('index');
            Route::get('/my', [BatchController::class, 'myBatches'])->name('my');
            Route::get('/{batch}', [BatchController::class, 'show'])->name('show');
            Route::post('/{batch}/claim', [BatchController::class, 'claim'])->name('claim');
        });
    });
});