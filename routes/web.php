<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionOrderController;
use App\Models\CategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Grupo de rutas exclusivas para el Administrador
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', ProductionOrderController::class);
});

// Grupo de rutas para el Operario
Route::middleware(['auth', 'role:operario'])->prefix('operario')->group(function () {
    Route::get('/dashboard', function () {
        return view('operario.dashboard');
    })->name('operario.dashboard');

    // Futuras rutas con permisos específicos del operario
});