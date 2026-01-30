<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', [PageController::class, 'home'])->name('home');

// Страница товара
Route::get('/products/{id}', [PageController::class, 'productShow'])->name('product.show');

// Аутентификация
Route::get('/login', [PageController::class, 'login'])->name('login');

Route::middleware('web')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
});

// Административная часть
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/products', [PageController::class, 'adminProducts'])->name('admin.products.index');
    Route::get('/products/create', [PageController::class, 'adminProductCreate'])->name('admin.products.create');
    Route::get('/products/{id}/edit', [PageController::class, 'adminProductEdit'])->name('admin.products.edit');
});
