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
// MÓDULO: DASHBOARD ADMIN
// ==========================================
Route::middleware(['auth', 'can:view-admin-dashboard'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::name('admin.export.')->prefix('export')->group(function () {
        Route::get('/dashboard/excel', [ExportController::class, 'dashboardExcel'])->name('dashboard.excel');
        Route::get('/dashboard/pdf', [ExportController::class, 'dashboardPdf'])->name('dashboard.pdf');
    });
});

// ==========================================
// MÓDULO: CATEGORÍAS
// ==========================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Rutas de lectura protegidas por 'view-categories'
    Route::middleware(['can:view-categories'])->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    });

    // Rutas de escritura/modificación protegidas estrictamente por 'manage-categories'
    Route::middleware(['can:manage-categories'])->group(function () {
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});

// ==========================================
// MÓDULO: RECETAS Y COMPONENTES DE RECETA
// ==========================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Rutas de lectura protegidas por 'view-recipes'
    Route::middleware(['can:view-recipes'])->group(function () {
        Route::get('recipes', [RecipeController::class, 'index'])->name('recipes.index');
        Route::get('recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
    });

    // Rutas de creación/edición/eliminación protegidas estrictamente por 'manage-recipes'
    Route::middleware(['can:manage-recipes'])->group(function () {
        Route::get('recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
        Route::post('recipes', [RecipeController::class, 'store'])->name('recipes.store');
        Route::get('recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
        Route::put('recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
        Route::delete('recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
        
        // Acciones especiales de componentes y duplicación
        Route::post('/recipes/{recipe}/duplicate', [RecipeController::class, 'duplicate'])->name('recipes.duplicate');
        Route::post('/recipes/{recipe}/components', [RecipeComponentController::class, 'store'])->name('recipes.components.store');
        Route::put('/recipes/{recipe}/components/{component}', [RecipeComponentController::class, 'update'])->name('recipes.components.update');
        Route::delete('/recipes/{recipe}/components/{component}', [RecipeComponentController::class, 'destroy'])->name('recipes.components.destroy');
        
        // Tipos de Componentes y Catálogo de Componentes
        Route::resource('component-types', ComponentTypeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('components', ComponentController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});

// ==========================================
// MÓDULO: ÓRDENES DE PRODUCCIÓN
// ==========================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Rutas de lectura protegidas por 'view-orders'
    Route::middleware(['can:view-orders'])->group(function () {
        Route::get('orders', [ProductionOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [ProductionOrderController::class, 'show'])->name('orders.show');
    });

    // Rutas de escritura/modificación protegidas estrictamente por 'manage-orders'
    Route::middleware(['can:manage-orders'])->group(function () {
        Route::get('orders/create', [ProductionOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [ProductionOrderController::class, 'store'])->name('orders.store');
        Route::get('orders/{order}/edit', [ProductionOrderController::class, 'edit'])->name('orders.edit');
        Route::put('orders/{order}', [ProductionOrderController::class, 'update'])->name('orders.update');
        Route::delete('orders/{order}', [ProductionOrderController::class, 'destroy'])->name('orders.destroy');
    });

    // Rutas de Exportación de Incidencias
    Route::middleware(['can:manage-orders'])->name('admin.export.')->prefix('export')->group(function () {
        Route::get('/incidences/excel', [ExportController::class, 'incidencesExcel'])->name('incidences.excel');
        Route::get('/incidences/pdf', [ExportController::class, 'incidencesPdf'])->name('incidences.pdf');
    });
});

// ==========================================
// MÓDULO: PRODUCTOS
// ==========================================
Route::middleware(['auth', 'can:access-products'])->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
});

// ==========================================
// MÓDULO: GESTIÓN DE INCIDENCIAS (ADMIN)
// ==========================================
Route::middleware(['auth', 'can:manage-orders'])->prefix('admin')->group(function () {
    Route::name('admin.incidences.')->prefix('incidencias')->group(function () {
        Route::get('/', [IncidenceController::class, 'index'])->name('index');
        Route::post('/', [IncidenceController::class, 'store'])->name('store');
        Route::patch('/{incidence}/status', [IncidenceController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{incidence}/notes', [IncidenceController::class, 'addNote'])->name('addNote');
        Route::patch('/{incidence}/importance', [IncidenceController::class, 'updateImportance'])->name('updateImportance');
        Route::delete('/{incidence}', [IncidenceController::class, 'destroy'])->name('destroy');
    });
});

// ==========================================
// MÓDULO: GESTIÓN DE USUARIOS Y PERMISOS
// ==========================================
Route::middleware(['auth', 'can:manage-users'])->prefix('admin')->group(function () {
    Route::name('admin.users.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('index');
        Route::post('/users', [UserController::class, 'store'])->name('store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('update-role');
    });
});

// ==========================================
// MÓDULO HÍBRIDO: FUNCIONES ESPECIALES PARA OPERARIOS AUTORIZADOS
// ==========================================
Route::middleware(['auth', 'can:manage-orders'])->prefix('operario/gestion')->name('operario.gestion.')->group(function () {
    Route::get('/ordenes', [ProductionOrderController::class, 'index'])->name('orders.index');
    Route::get('/ordenes/crear', [ProductionOrderController::class, 'create'])->name('orders.create');
    Route::post('/ordenes', [ProductionOrderController::class, 'store'])->name('orders.store');
});

// ==========================================
// GRUPO: MÓDULO OPERARIO (Basado en slug: access-operario)
// ==========================================
Route::middleware(['auth', 'can:access-operario'])
    ->prefix('operario')
    ->name('operario.')
    ->group(function () {
    
        Route::get('/inicio', [OperarioController::class, 'inicio'])->name('inicio');
        
        // Protegido específicamente con el permiso 'view-assigned-orders' para visualizar tareas
        Route::middleware(['can:view-assigned-orders'])->group(function () {
            Route::get('/tareas', [OperarioController::class, 'tareas'])->name('tareas');
        });

        Route::get('/registro', [OperarioController::class, 'registro'])->name('registro');
        Route::get('/incidencias', [OperarioController::class, 'incidencias'])->name('incidencias');
        Route::get('/perfil', [OperarioController::class, 'perfil'])->name('perfil');

        Route::post('/registro/guardar', [OperarioController::class, 'guardarRegistro'])->name('registro.guardar');
        
        // Protegido específicamente con el slug create-incidences
        Route::middleware(['can:create-incidences'])->group(function () {
            Route::post('/incidencias/guardar', [OperarioController::class, 'crearIncidencia'])->name('incidencias.guardar');
        });
        
        // Protegido específicamente con el slug update-progress
        Route::middleware(['can:update-progress'])->group(function () {
            Route::put('/orden/{productionOrder}/estacion', [OperarioController::class, 'actualizarEstacion'])->name('estacion.actualizar');
            Route::put('/tareas/{productionOrder}/iniciar', [OperarioController::class, 'iniciarTarea'])->name('tareas.iniciar');
            Route::put('/tareas/{productionOrder}/completar', [OperarioController::class, 'completarTarea'])->name('tareas.completar');
        });
    });