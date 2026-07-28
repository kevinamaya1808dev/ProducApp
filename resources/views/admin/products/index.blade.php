@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Encabezado del Módulo -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Catálogo de Productos</h1>
            <p class="text-xs text-slate-500 mt-1">
                Gestión de insumos, prendas e inventario disponible para producción
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-xs font-semibold text-white hover:bg-blue-700 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Producto
            </a>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" placeholder="Buscar por SKU, nombre..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <select class="bg-slate-50 border border-slate-200 text-xs font-medium text-slate-700 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todas las Categorías</option>
                <option value="outerwear">Outerwear</option>
                <option value="bottoms">Bottoms</option>
                <option value="tops">Tops</option>
                <option value="dresses">Dresses</option>
            </select>

            <select class="bg-slate-50 border border-slate-200 text-xs font-medium text-slate-700 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los Estados</option>
                <option value="disponible">Disponible</option>
                <option value="produccion">En Producción</option>
                <option value="agotado">Agotado</option>
            </select>
        </div>
    </div>

    <!-- Tabla Principal de Productos -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Producto</th>
                        <th class="py-3.5 px-4">Categoría</th>
                        <th class="py-3.5 px-4">SKU / Código</th>
                        <th class="py-3.5 px-4">Stock</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    <!-- Producto 1 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0 font-bold">
                                    CM
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">Chamarra de Mezclilla Oversize</p>
                                    <p class="text-[11px] text-slate-400">Mezclilla rígida 12oz &middot; Tallas XS-XL</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-600">Outerwear</td>
                        <td class="py-4 px-4 font-mono text-slate-500">PRD-OUT-001</td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-900">320 pcs</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                Disponible
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="#" class="inline-flex items-center p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <button type="button" class="inline-flex items-center p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Producto 2 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shrink-0 font-bold">
                                    PC
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">Pantalón Cargo Slim Fit</p>
                                    <p class="text-[11px] text-slate-400">Gabardina dril 100% algodón</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-600">Bottoms</td>
                        <td class="py-4 px-4 font-mono text-slate-500">PRD-BOT-004</td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-amber-600">15 pcs</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                En Producción
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="#" class="inline-flex items-center p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <button type="button" class="inline-flex items-center p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Producto 3 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 font-bold">
                                    BL
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">Blusa Lino Temporada Verano</p>
                                    <p class="text-[11px] text-slate-400">Lino natural importado</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-600">Tops</td>
                        <td class="py-4 px-4 font-mono text-slate-500">PRD-TOP-012</td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-red-600">0 pcs</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                Agotado
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="#" class="inline-flex items-center p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <button type="button" class="inline-flex items-center p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <span class="text-xs text-slate-500">Mostrando 1 a 3 de 28 productos</span>
            <div class="flex items-center gap-1">
                <button class="px-3 py-1 rounded border border-slate-200 text-xs font-semibold text-slate-500 hover:bg-white disabled:opacity-50" disabled>Anterior</button>
                <button class="px-3 py-1 rounded bg-blue-600 text-xs font-semibold text-white shadow-sm">1</button>
                <button class="px-3 py-1 rounded border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-white">2</button>
                <button class="px-3 py-1 rounded border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-white">3</button>
                <button class="px-3 py-1 rounded border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-white">Siguiente</button>
            </div>
        </div>
    </div>
</div>
@endsection