<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ComponentTypeController; 
use App\Http\Controllers\ExportController;
use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\RecipeComponentController;
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
    
    // Panel de Control Principal
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // --- NUEVAS RUTAS DE EXPORTACIÓN (Dashboard e Incidencias) ---
    Route::name('admin.export.')->prefix('export')->group(function () {
        Route::get('/dashboard/excel', [ExportController::class, 'dashboardExcel'])->name('dashboard.excel');
        Route::get('/dashboard/pdf', [ExportController::class, 'dashboardPdf'])->name('dashboard.pdf');
        Route::get('/incidences/excel', [ExportController::class, 'incidencesExcel'])->name('incidences.excel');
        Route::get('/incidences/pdf', [ExportController::class, 'incidencesPdf'])->name('incidences.pdf');
    });

    // Módulos Principales
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', ProductionOrderController::class);
    
    // Módulo de Recetas
    Route::resource('recipes', RecipeController::class);
    Route::post('/recipes/{recipe}/duplicate', [RecipeController::class, 'duplicate'])->name('recipes.duplicate');
    Route::post('/recipes/{recipe}/components', [RecipeComponentController::class, 'store'])->name('recipes.components.store');
    Route::put('/recipes/{recipe}/components/{component}', [RecipeComponentController::class, 'update'])->name('recipes.components.update');
    Route::delete('/recipes/{recipe}/components/{component}', [RecipeComponentController::class, 'destroy'])->name('recipes.components.destroy');

    // Módulo de Tipos de Componentes y Componentes
    Route::resource('component-types', ComponentTypeController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('components', ComponentController::class)->only(['index', 'store', 'update', 'destroy']);

    // Módulo de Incidencias (Administración & Historial)
    Route::name('admin.incidences.')->prefix('incidencias')->group(function () {
        Route::get('/', [IncidenceController::class, 'index'])->name('index');
        Route::post('/', [IncidenceController::class, 'store'])->name('store');
        Route::patch('/{incidence}/status', [IncidenceController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{incidence}/notes', [IncidenceController::class, 'addNote'])->name('addNote');
        Route::patch('/{incidence}/importance', [IncidenceController::class, 'updateImportance'])->name('updateImportance');
        Route::delete('/{incidence}', [IncidenceController::class, 'destroy'])->name('destroy');
    });

    // Rutas de Gestión de Usuarios y Permisos
    Route::name('admin.users.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('index');
        Route::post('/users', [UserController::class, 'store'])->name('store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('update-role');
    });
});

// ==========================================
// MÓDULO: PRODUCTOS (Admin o usuarios con permiso explícito)
// ==========================================
Route::middleware(['auth', 'can:access-products'])->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
});

// ==========================================
// MÓDULO HíBRIDO: FUNCIONES ESPECIALES PARA OPERARIOS AUTORIZADOS VÍA RUTA
// ==========================================
// Permite que un operario interactúe con módulos avanzados (ej. gestión de órdenes o recetas) 
// si el Administrador le otorgó el permiso correspondiente mediante su usuario.
Route::middleware(['auth', 'can:gestionar-ordenes'])->prefix('operario/gestion')->name('operario.gestion.')->group(function () {
    Route::get('/ordenes', [ProductionOrderController::class, 'index'])->name('orders.index');
    Route::get('/ordenes/crear', [ProductionOrderController::class, 'create'])->name('orders.create');
    Route::post('/ordenes', [ProductionOrderController::class, 'store'])->name('orders.store');
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
        Route::post('/registro/guardar', [OperarioController::class, 'guardارRegistro'])->name('registro.guardar');
        Route::post('/incidencias/guardar', [OperarioController::class, 'crearIncidencia'])->name('incidencias.guardar');
        
        // Acciones de Tareas y Estaciones
        Route::put('/orden/{productionOrder}/estacion', [OperarioController::class, 'actualizarEstacion'])->name('estacion.actualizar');
        Route::put('/tareas/{productionOrder}/iniciar', [OperarioController::class, 'iniciarTarea'])->name('tareas.iniciar');
        Route::put('/tareas/{productionOrder}/completar', [OperarioController::class, 'completarTarea'])->name('tareas.completar');
    });