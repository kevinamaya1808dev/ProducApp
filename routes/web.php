<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirección inteligente según el estado de autenticación
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        // Si usas columna 'role' directa en users:
        $isAdmin = $user->role === 'admin';

        // O si usas la relación de Eloquent con roles:
        // $isAdmin = $user->roles->contains('slug', 'admin');

        return $isAdmin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('operario.inicio');
    }
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

// Grupo de rutas exclusivas para el Administrador (Dashboard, Categorías y Órdenes)
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('orders', ProductionOrderController::class);
    
    // Rutas de Gestión de Usuarios y Permisos
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-products', [UserController::class, 'toggleProductAccess'])->name('users.toggle-products');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
});

// Módulo de Productos (Accesible por Admin o por Becario con permiso 'access-products')
Route::middleware(['auth', 'can:access-products'])->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
});

// Grupo de rutas exclusivas para el Operario
Route::middleware(['auth', RoleMiddleware::class . ':operario'])
    ->prefix('operario')
    ->name('operario.')
    ->group(function () {
    
    // Rutas GET (Navegación)
    Route::get('/inicio', [OperarioController::class, 'inicio'])->name('inicio');
    Route::get('/tareas', [OperarioController::class, 'tareas'])->name('tareas');
    Route::get('/registro', [OperarioController::class, 'registro'])->name('registro');
    Route::get('/incidencias', [OperarioController::class, 'incidencias'])->name('incidencias');
    Route::get('/perfil', [OperarioController::class, 'perfil'])->name('perfil');

    // Rutas POST (Formularios)
    Route::post('/registro/guardar', [OperarioController::class, 'guardarRegistro'])->name('registro.guardar');
    Route::post('/incidencias/crear', [OperarioController::class, 'crearIncidencia'])->name('incidencias.crear');
});
Route::put('/operario/orden/{productionOrder}/estacion', [OperarioController::class, 'actualizarEstacion'])
    ->name('operario.estacion.actualizar');