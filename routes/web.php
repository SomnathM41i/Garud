<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JewelleryCategoryController;
use App\Http\Controllers\JewelleryProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;


use Illuminate\Support\Facades\Artisan;

Route::get('/migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrations ran successfully!';
});

Route::get('/migrate-fresh', function () {
    // Run fresh migrations
    Artisan::call('migrate:fresh', [
        '--force' => true, // required to run in production safely
    ]);

    return 'Fresh migrations ran successfully!';
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Categories
        Route::resource('categories', JewelleryCategoryController::class);

        // Products
        Route::resource('products', JewelleryProductController::class);

        Route::resource('customers', CustomerController::class);
        Route::resource('cart', CartController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('payments', PaymentController::class);

        Route::get('reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');
    });