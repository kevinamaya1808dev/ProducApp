<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OperarioController;
use App\Http\Middleware\RoleMiddleware; // 1. Importas tu Middleware aquí
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirección inteligente
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('operario.inicio');
    }
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

// 2. Usas la clase directamente con el parámetro tras los dos puntos (:admin)
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', ProductionOrderController::class);
});

// 3. Igual para el grupo del Operario (:operario)
Route::middleware(['auth', RoleMiddleware::class . ':operario'])
    ->prefix('operario')
    ->name('operario.')
    ->group(function () {
    
    Route::get('/inicio', [OperarioController::class, 'inicio'])->name('inicio');
    Route::get('/tareas', [OperarioController::class, 'tareas'])->name('tareas');
    Route::get('/registro', [OperarioController::class, 'registro'])->name('registro');
    Route::get('/incidencias', [OperarioController::class, 'incidencias'])->name('incidencias');
    Route::get('/perfil', [OperarioController::class, 'perfil'])->name('perfil');

    Route::post('/registro/guardar', [OperarioController::class, 'guardarRegistro'])->name('registro.guardar');
    Route::post('/incidencias/crear', [OperarioController::class, 'crearIncidencia'])->name('incidencias.crear');
});