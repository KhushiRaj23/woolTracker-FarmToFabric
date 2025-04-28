<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\WoolBatchController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\RetailerController;
use App\Http\Controllers\Admin\DistributorController;
use App\Http\Controllers\Admin\QualityTestController;
use App\Http\Controllers\Admin\FarmerController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth:admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users
    Route::resource('users', UserController::class);

    // Farmers
    Route::resource('farmers', FarmerController::class);

    // Retailers
    Route::resource('retailers', RetailerController::class);

    // Distributors
    Route::resource('distributors', DistributorController::class);

    // Orders
    Route::resource('orders', OrderController::class);

    // Wool Batches
    Route::resource('wool-batches', WoolBatchController::class);

    // Quality Tests
    Route::resource('quality-tests', QualityTestController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Shipments
    Route::resource('shipments', ShipmentController::class);

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
