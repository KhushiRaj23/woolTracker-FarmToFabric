<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Processor\ProcessorDashboardController;
use App\Http\Controllers\Processor\ProcessorController;
use App\Http\Controllers\Distributor\OrderController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test-auth', function () {
    if (auth()->check()) {
        return 'Logged in as: ' . auth()->user()->name . ' (Role: ' . auth()->user()->role . ')';
    }
    return 'Not logged in';
});

Route::middleware(['auth', 'role:processor'])->prefix('processor')->name('processor.')->group(function () {
    Route::get('/dashboard', [ProcessorDashboardController::class, 'index'])->name('dashboard');
    
    // Batch Routes
    Route::get('/batches', [ProcessorController::class, 'index'])->name('batches.index');
    Route::get('/batches/my', [ProcessorController::class, 'myBatches'])->name('batches.my');
    Route::get('/batches/available', [ProcessorController::class, 'availableBatches'])->name('batches.available');
    Route::get('/batches/{batch}', [ProcessorController::class, 'show'])->name('batches.show');
    Route::post('/batches/{batch}/start', [ProcessorController::class, 'startProcessing'])->name('batches.start-processing');
    Route::post('/batches/{batch}/complete', [ProcessorController::class, 'completeProcessing'])->name('batches.complete');
});

Route::middleware(['auth', 'role:distributor'])->prefix('distributor')->name('distributor.')->group(function () {
    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/assign-retailer', [OrderController::class, 'assignRetailer'])->name('orders.assign-retailer');
    
    // Analytics
    Route::get('/analytics', [App\Http\Controllers\Distributor\AnalyticsController::class, 'index'])->name('analytics');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
require __DIR__.'/farmer-auth.php';
require __DIR__.'/processor-auth.php';
require __DIR__.'/distributor-auth.php';
require __DIR__.'/retailer-auth.php';


