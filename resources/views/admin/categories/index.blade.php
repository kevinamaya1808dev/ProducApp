@extends('layouts.app') {{-- O el layout principal que utilices --}}

@section('content')
<div class="min-h-screen bg-slate-100 p-6 lg:p-8 ml-64"> <!-- ml-64 por tu sidebar fijo -->
    
    <!-- Cabecera de la sección -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Recetas de Componentes</h1>
            <p class="text-slate-500 text-sm mt-1">Materiales e insumos por producto &middot; 3 recetas</p>
        </div>
        <div>
            <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-blue-600/20 transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Receta
            </button>
        </div>
    </div>

    <!-- Contenedor Principal (Grid de dos columnas) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Columna Izquierda: Lista de Recetas -->
        <div class="lg:col-span-4 space-y-4">
            
            <!-- Buscador de Recetas -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" placeholder="Buscar receta..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all shadow-sm">
            </div>

            <!-- Receta Seleccionada (Activa) -->
            <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-4 shadow-sm cursor-pointer transition-all ring-1 ring-slate-900/5">
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-bold text-slate-900 text-sm leading-snug">Chamarra de Mezclilla Mod. A</h3>
                    <span class="bg-blue-50 text-blue-700 text-[11px] font-bold px-2 py-0.5 rounded-md border border-blue-200/60 shrink-0">v3.2</span>
                </div>
                <p class="text-xs font-mono text-blue-600 mt-1">CHM-A-001</p>
                <div class="flex items-center justify-between text-[11px] text-slate-500 mt-3 pt-3 border-t border-slate-100">
                    <span>7 componentes</span>
                    <span>Act. 18 jul 2026</span>
                </div>
            </div>

            <!-- Receta Inactiva 1 -->
            <div class="bg-white hover:bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 shadow-sm cursor-pointer transition-all">
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-semibold text-slate-800 text-sm leading-snug">Pantalón Cargo Slim Mod. C</h3>
                    <span class="bg-slate-100 text-slate-600 text-[11px] font-semibold px-2 py-0.5 rounded-md border border-slate-200 shrink-0">v2.0</span>
                </div>
                <p class="text-xs font-mono text-slate-500 mt-1">PCS-C-002</p>
                <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-slate-100">
                    <span>6 componentes</span>
                    <span>Act. 12 jul 2026</span>
                </div>
            </div>

            <!-- Receta Inactiva 2 -->
            <div class="bg-white hover:bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 shadow-sm cursor-pointer transition-all">
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-semibold text-slate-800 text-sm leading-snug">Sudadera Hoodie Oversize</h3>
                    <span class="bg-slate-100 text-slate-600 text-[11px] font-semibold px-2 py-0.5 rounded-md border border-slate-200 shrink-0">v1.4</span>
                </div>
                <p class="text-xs font-mono text-slate-500 mt-1">SHO-004</p>
                <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-slate-100">
                    <span>8 componentes</span>
                    <span>Act. 05 jul 2026</span>
                </div>
            </div>

        </div>

        <!-- Columna Derecha: Detalle de la Receta Seleccionada -->
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            
            <!-- Cabecera del Detalle -->
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="text-xl font-bold text-slate-900">Chamarra de Mezclilla Mod. A</h2>
                        <span class="bg-blue-50 text-blue-700 text-xs font-mono font-semibold px-2.5 py-1 rounded-lg border border-blue-200/60">CHM-A-001</span>
                        <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-lg border border-slate-200">v3.2</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1.5">Última modificación: 18 jul 2026 por A. Martínez</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium text-sm rounded-xl transition-all shadow-sm">
                        Duplicar
                    </button>
                    <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl transition-all shadow-md shadow-blue-600/25">
                        Editar Receta
                    </button>
                </div>
            </div>

            <!-- Tabla de Componentes -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                            <th class="py-3 px-6 w-16">#</th>
                            <th class="py-3 px-6">Componente</th>
                            <th class="py-3 px-6">Tipo</th>
                            <th class="py-3 px-6">Cantidad</th>
                            <th class="py-3 px-6">Unidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <!-- Fila 1 -->
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-400 font-semibold">01</td>
                            <td class="py-4 px-6 font-semibold text-slate-800">Tela Mezclilla 12oz</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200/60">Material</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-900">2.5</td>
                            <td class="py-4 px-6 text-slate-500">m²</td>
                        </tr>
                        <!-- Fila 2 -->
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-400 font-semibold">02</td>
                            <td class="py-4 px-6 font-semibold text-slate-800">Cierre metálico YKK #5</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200/60">Accesorio</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-900">2</td>
                            <td class="py-4 px-6 text-slate-500">pzas</td>
                        </tr>
                        <!-- Fila 3 -->
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-400 font-semibold">03</td>
                            <td class="py-4 px-6 font-semibold text-slate-800">Botón tachuela 17mm</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200/60">Accesorio</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-900">8</td>
                            <td class="py-4 px-6 text-slate-500">pzas</td>
                        </tr>
                        <!-- Fila 4 -->
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-400 font-semibold">04</td>
                            <td class="py-4 px-6 font-semibold text-slate-800">Hilo poliéster 40/2 índigo</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/60">Insumo</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-900">150</td>
                            <td class="py-4 px-6 text-slate-500">m</td>
                        </tr>
                        <!-- Fila 5 -->
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-400 font-semibold">05</td>
                            <td class="py-4 px-6 font-semibold text-slate-800">Etiqueta composición</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">Etiqueta</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-900">1</td>
                            <td class="py-4 px-6 text-slate-500">pzas</td>
                        </tr>
                        <!-- Fila 6 -->
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-400 font-semibold">06</td>
                            <td class="py-4 px-6 font-semibold text-slate-800">Etiqueta talla bordada</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">Etiqueta</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-900">1</td>
                            <td class="py-4 px-6 text-slate-500">pzas</td>
                        </tr>
                        <!-- Fila 7 -->
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-400 font-semibold">07</td>
                            <td class="py-4 px-6 font-semibold text-slate-800">Entretela fusionable</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200/60">Material</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-900">0.3</td>
                            <td class="py-4 px-6 text-slate-500">m²</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer / Resumen de Tipos -->
            <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-medium">
                <div class="flex items-center gap-4 flex-wrap">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-600"></span> Material <strong class="text-slate-700 font-semibold">2</strong></span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-purple-600"></span> Accesorio <strong class="text-slate-700 font-semibold">2</strong></span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Insumo <strong class="text-slate-700 font-semibold">1</strong></span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-500"></span> Etiqueta <strong class="text-slate-700 font-semibold">2</strong></span>
                </div>
                <div class="text-slate-600 font-bold">
                    7 componentes en total
                </div>
            </div>

        </div>

    </div>
</div>
@endsection