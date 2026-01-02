<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    dd();
    Route::get('/dashboard', function () {
        echo "Admin Dashboard";
        //return view('admin.dashboard');
    })->name('admin.dashboard');
});