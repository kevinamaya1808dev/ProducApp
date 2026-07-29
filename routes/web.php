<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirección inteligente según el estado de autenticación
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');

        return $isAdmin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('operario.inicio');
    }
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

// ==========================================
// GRUPO: ADMINISTRADOR
// ==========================================
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('orders', ProductionOrderController::class);
    
    // Módulo de Recetas (Añadido para solucionar el error del Sidebar y completar el módulo)
    Route::resource('recipes', RecipeController::class);
    
    // Rutas de Gestión de Usuarios y Permisos
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
});

// ==========================================
// MÓDULO: PRODUCTOS (Admin o Becario con permiso)
// ==========================================
Route::middleware(['auth', 'can:access-products'])->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
});

// ==========================================
// GRUPO: OPERARIO (Seguro y Centralizado)
// ==========================================
Route::middleware(['auth', RoleMiddleware::class . ':operario'])
    ->prefix('operario')
    ->name('operario.')
    ->group(function () {
    
        // Rutas GET (Navegación de vistas)
        Route::get('/inicio', [OperarioController::class, 'inicio'])->name('inicio');
        Route::get('/tareas', [OperarioController::class, 'tareas'])->name('tareas');
        Route::get('/registro', [OperarioController::class, 'registro'])->name('registro');
        Route::get('/incidencias', [OperarioController::class, 'incidencias'])->name('incidencias');
        Route::get('/perfil', [OperarioController::class, 'perfil'])->name('perfil');

        // Rutas POST / PUT (Acciones y Formularios)
        Route::post('/registro/guardar', [OperarioController::class, 'guardarRegistro'])->name('registro.guardar');
        Route::post('/incidencias/crear', [OperarioController::class, 'crearIncidencia'])->name('incidencias.crear');
        
        // Acciones de Tareas y Estaciones (Protegidas dentro del grupo)
        Route::put('/orden/{productionOrder}/estacion', [OperarioController::class, 'actualizarEstacion'])->name('estacion.actualizar');
        Route::put('/tareas/{productionOrder}/iniciar', [OperarioController::class, 'iniciarTarea'])->name('tareas.iniciar');
        Route::put('/tareas/{productionOrder}/completar', [OperarioController::class, 'completarTarea'])->name('tareas.completar');

        // Ruta duplicada de incidencias unificada correctamente bajo el prefijo 'operario.'
        Route::post('/incidencias/guardar', [OperarioController::class, 'crearIncidencia'])->name('incidencias.guardar');
    });