<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ComponentTypeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\RecipeComponentController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SubOrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['can:dashboard.view'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    });

    Route::middleware(['can:dashboard.manage'])->name('export.')->prefix('export')->group(function () {
        Route::get('/dashboard/excel', [ExportController::class, 'dashboardExcel'])->name('dashboard.excel');
        Route::get('/dashboard/pdf', [ExportController::class, 'dashboardPdf'])->name('dashboard.pdf');
    });
});

// ==========================================
// MÓDULO: CATEGORÍAS
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['can:categories.view'])->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    });

    Route::middleware(['can:categories.create'])->group(function () {
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    });

    Route::middleware(['can:categories.edit'])->group(function () {
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    });

    Route::middleware(['can:categories.delete'])->group(function () {
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});

// ==========================================
// MÓDULO: RECETAS Y COMPONENTES DE RECETA
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['can:recipes.view'])->group(function () {
        Route::get('recipes', [RecipeController::class, 'index'])->name('recipes.index');
        Route::get('recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
    });

    Route::middleware(['can:recipes.create'])->group(function () {
        Route::get('recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
        Route::post('recipes', [RecipeController::class, 'store'])->name('recipes.store');
    });

    Route::middleware(['can:recipes.edit'])->group(function () {
        Route::get('recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
        Route::put('recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    });

    Route::middleware(['can:recipes.delete'])->group(function () {
        Route::delete('recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    });

    Route::middleware(['can:recipes.manage'])->group(function () {
        Route::post('/recipes/{recipe}/duplicate', [RecipeController::class, 'duplicate'])->name('recipes.duplicate');
        Route::post('/recipes/{recipe}/components', [RecipeComponentController::class, 'store'])->name('recipes.components.store');
        Route::put('/recipes/{recipe}/components/{component}', [RecipeComponentController::class, 'update'])->name('recipes.components.update');
        Route::delete('/recipes/{recipe}/components/{component}', [RecipeComponentController::class, 'destroy'])->name('recipes.components.destroy');

        Route::resource('component-types', ComponentTypeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('components', ComponentController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});

// ==========================================
// MÓDULO: ÓRDENES DE PRODUCCIÓN Y SUBÓRDENES
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['can:orders.view'])->group(function () {
        Route::get('orders', [ProductionOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [ProductionOrderController::class, 'show'])->name('orders.show');
    });

    Route::middleware(['can:orders.create'])->group(function () {
        Route::get('orders/create', [ProductionOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [ProductionOrderController::class, 'store'])->name('orders.store');
    });

    Route::middleware(['can:orders.edit'])->group(function () {
        Route::get('orders/{order}/edit', [ProductionOrderController::class, 'edit'])->name('orders.edit');
        Route::put('orders/{order}', [ProductionOrderController::class, 'update'])->name('orders.update');
    });

    Route::middleware(['can:orders.delete'])->group(function () {
        Route::delete('orders/{order}', [ProductionOrderController::class, 'destroy'])->name('orders.destroy');
    });

    Route::middleware(['can:orders.manage'])->group(function () {
        Route::post('sub-orders', [SubOrderController::class, 'store'])->name('sub-orders.store');
        Route::put('sub-orders/{subOrder}', [SubOrderController::class, 'update'])->name('sub-orders.update');
        Route::delete('sub-orders/{subOrder}', [SubOrderController::class, 'destroy'])->name('sub-orders.destroy');
    });
});

// ==========================================
// MÓDULO: PRODUCTOS
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['can:products.view'])->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    });

    Route::middleware(['can:products.create'])->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
    });

    Route::middleware(['can:products.edit'])->group(function () {
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    });

    Route::middleware(['can:products.delete'])->group(function () {
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
});

// ==========================================
// MÓDULO: ALMACÉN (MATERIALES Y RECETAS DE PRODUCTOS)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['can:almacen.view'])->group(function () {
        Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen.index');
        Route::get('/almacen/historial', [AlmacenController::class, 'historial'])->name('almacen.historial');
    });

    Route::middleware(['can:almacen.create'])->group(function () {
        Route::post('/almacen/material', [AlmacenController::class, 'storeMaterial'])->name('almacen.material.store');
    });

    Route::middleware(['can:almacen.edit'])->group(function () {
        Route::put('/almacen/material/{material}', [AlmacenController::class, 'updateMaterial'])->name('almacen.material.update');
    });

    Route::middleware(['can:almacen.delete'])->group(function () {
        Route::delete('/almacen/material/{material}', [AlmacenController::class, 'destroyMaterial'])->name('almacen.material.destroy');
    });

    Route::middleware(['can:almacen.manage'])->group(function () {
        Route::post('/almacen/material/{material}/add-stock', [AlmacenController::class, 'addStock'])->name('almacen.material.add-stock');
        Route::post('/almacen/recipe', [AlmacenController::class, 'storeRecipe'])->name('almacen.recipe.store');
    });
});

// ==========================================
// MÓDULO: PROVEEDORES
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['can:proveedores.view'])->group(function () {
        Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
    });

    Route::middleware(['can:proveedores.create'])->group(function () {
        Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    });

    Route::middleware(['can:proveedores.edit'])->group(function () {
        Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedores.update');
    });

    Route::middleware(['can:proveedores.delete'])->group(function () {
        Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
    });
});

// ==========================================
// MÓDULO: GESTIÓN DE INCIDENCIAS (ADMIN)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::name('incidences.')->prefix('incidencias')->group(function () {
        Route::middleware(['can:incidences.view'])->group(function () {
            Route::get('/', [IncidenceController::class, 'index'])->name('index');
        });

        Route::middleware(['can:incidences.create'])->group(function () {
            Route::post('/', [IncidenceController::class, 'store'])->name('store');
        });

        Route::middleware(['can:incidences.edit'])->group(function () {
            Route::patch('/{incidence}/status', [IncidenceController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{incidence}/notes', [IncidenceController::class, 'addNote'])->name('addNote');
            Route::patch('/{incidence}/importance', [IncidenceController::class, 'updateImportance'])->name('updateImportance');
        });

        Route::middleware(['can:incidences.delete'])->group(function () {
            Route::delete('/{incidence}', [IncidenceController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware(['can:incidences.manage'])->name('export.')->prefix('export')->group(function () {
        Route::get('/incidences/excel', [ExportController::class, 'incidencesExcel'])->name('incidences.excel');
        Route::get('/incidences/pdf', [ExportController::class, 'incidencesPdf'])->name('incidences.pdf');
    });
});

// ==========================================
// MÓDULO: GESTIÓN DE USUARIOS Y PERMISOS
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['can:users.view'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    Route::middleware(['can:users.create'])->group(function () {
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    Route::middleware(['can:users.edit'])->group(function () {
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
    });

    Route::middleware(['can:users.delete'])->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // CRUD de la entidad Permiso (catálogo de permisos)
    Route::middleware(['can:users.manage'])->group(function () {
        Route::resource('permissions', PermissionController::class)->except(['show']);
    });

    Route::middleware(['can:users.edit'])->group(function () {
        Route::get('/users/{user}/permissions', [UserController::class, 'editPermissions'])->name('users.permissions.edit');
        Route::put('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
    });
});

// ==========================================
// MÓDULO HÍBRIDO: FUNCIONES ESPECIALES PARA OPERARIOS AUTORIZADOS
// ==========================================
Route::middleware(['auth', 'can:orders.view'])->prefix('operario/gestion')->name('operario.gestion.')->group(function () {
    Route::get('/ordenes', [ProductionOrderController::class, 'index'])->name('orders.index');
});

Route::middleware(['auth', 'can:orders.create'])->prefix('operario/gestion')->name('operario.gestion.')->group(function () {
    Route::get('/ordenes/crear', [ProductionOrderController::class, 'create'])->name('orders.create');
    Route::post('/ordenes', [ProductionOrderController::class, 'store'])->name('orders.store');
});

// ==========================================
// GRUPO: MÓDULO OPERARIO (sin cambios — permisos especiales)
// ==========================================
Route::middleware(['auth', 'can:access-operario'])
    ->prefix('operario')
    ->name('operario.')
    ->group(function () {

        Route::get('/inicio', [OperarioController::class, 'inicio'])->name('inicio');

        Route::middleware(['can:view-assigned-orders'])->group(function () {
            Route::get('/tareas', [OperarioController::class, 'tareas'])->name('tareas');
        });

        Route::get('/registro', [OperarioController::class, 'registro'])->name('registro');
        Route::get('/incidencias', [OperarioController::class, 'incidencias'])->name('incidencias');
        Route::get('/perfil', [OperarioController::class, 'perfil'])->name('perfil');

        Route::post('/registro/guardar', [OperarioController::class, 'guardarRegistro'])->name('registro.guardar');

        Route::middleware(['can:create-incidences'])->group(function () {
            Route::post('/incidencias/guardar', [OperarioController::class, 'crearIncidencia'])->name('incidencias.guardar');
        });

        Route::middleware(['can:update-progress'])->group(function () {
            Route::put('/orden/{productionOrder}/estacion', [OperarioController::class, 'actualizarEstacion'])->name('estacion.actualizar');
            Route::put('/tareas/{productionOrder}/iniciar', [OperarioController::class, 'iniciarTarea'])->name('tareas.iniciar');
            Route::put('/tareas/{productionOrder}/completar', [OperarioController::class, 'completarTarea'])->name('tareas.completar');
            Route::post('/sub-orders/{subOrder}/progress', [SubOrderController::class, 'registerProgress'])->name('suborders.progress');
        });

        Route::get('/suborden/{subOrder}/estado', [OperarioController::class, 'estadoSuborden'])->name('suborden.estado');
    });