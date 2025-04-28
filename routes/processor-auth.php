<?php

use App\Http\Controllers\Processor\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Processor\Auth\RegisteredUserController;
use App\Http\Controllers\Processor\ProcessorController;
use App\Http\Controllers\Processor\ProcessorDashboardController;
use App\Http\Controllers\Processor\ProfileController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::prefix('processor')->name('processor.')->middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Authenticated processor routes
Route::prefix('processor')->name('processor.')->middleware(['auth', \App\Http\Middleware\ProcessorMiddleware::class])->group(function () {
    Route::get('/dashboard', [ProcessorDashboardController::class, 'index'])->name('dashboard');

    // Batch routes
    Route::get('/batches', [ProcessorController::class, 'index'])->name('batches.index');
    Route::get('/batches/my', [ProcessorController::class, 'myBatches'])->name('batches.my');
    Route::get('/batches/available', [ProcessorController::class, 'availableBatches'])->name('batches.available');
    Route::get('/batches/{batch}', [ProcessorController::class, 'show'])->name('batches.show');
    Route::post('/batches/{batch}/start', [ProcessorController::class, 'startProcessing'])->name('batches.start-processing');
    Route::post('/batches/{batch}/complete', [ProcessorController::class, 'completeProcessing'])->name('batches.complete');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
