@extends('layouts.app')

@section('content')
@can('view-recipes')

<div x-data="recetasManager()" class="p-6 max-w-[1700px] mx-auto">
    
    <!-- Encabezado de la página -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Recetas de Componentes</h1>
            <p class="text-sm text-slate-500 mt-1">Materiales e insumos por producto &middot; {{ isset($recipes) ? $recipes->count() : 3 }} recetas</p>
        </div>
        
        @can('manage-recipes')
        <button @click="openModal('create')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm shadow-blue-600/30 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Receta
        </button>
        @endcan
    </div>

    <!-- Contenedor Principal en Grid de 12 columnas para control total de espacios -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Panel Izquierdo: Lista de Recetas (Ocupa 4 columnas) -->
        <div class="lg:col-span-4 xl:col-span-3 flex flex-col gap-4">
            
            <!-- Buscador -->
            <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-6 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Buscar receta..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
            </div>

            <!-- Lista de Tarjetas -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col divide-y divide-slate-100">
                
                <!-- Item Activo -->
                <button @click="activeRecipe = 1" class="w-full text-left p-4 border-l-4 border-blue-600 bg-blue-50/40 transition-colors">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-bold text-slate-900 text-sm">Chamarra de Mezclilla Mod. A</h3>
                        <span class="bg-slate-200 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded">v3.2</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mb-2 uppercase tracking-wider font-mono">CHM-A-001</p>
                    <p class="text-[11px] text-slate-500">7 componentes &middot; Act. 18 jul 2026</p>
                </button>

                <!-- Item Inactivo -->
                <button @click="activeRecipe = 2" class="w-full text-left p-4 border-l-4 border-transparent hover:bg-slate-50 transition-colors">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-semibold text-slate-700 text-sm">Pantalón Cargo Slim Mod. C</h3>
                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded">v2.0</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mb-2 uppercase tracking-wider font-mono">PCS-C-002</p>
                    <p class="text-[11px] text-slate-500">6 componentes &middot; Act. 12 jul 2026</p>
                </button>

                <!-- Item Inactivo -->
                <button @click="activeRecipe = 3" class="w-full text-left p-4 border-l-4 border-transparent hover:bg-slate-50 transition-colors">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-semibold text-slate-700 text-sm">Sudadera Hoodie Oversize</h3>
                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded">v1.4</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mb-2 uppercase tracking-wider font-mono">SHO-004</p>
                    <p class="text-[11px] text-slate-500">8 componentes &middot; Act. 05 jul 2026</p>
                </button>
            </div>
        </div>

        <!-- Panel Derecho: Detalle de Receta (Ocupa 8 columnas) -->
        <div class="lg:col-span-8 xl:col-span-9 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Cabecera del Detalle -->
            <div class="p-6 border-b border-slate-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Chamarra de Mezclilla Mod. A</h2>
                            <span class="bg-blue-50 text-blue-600 border border-blue-200 text-xs font-bold px-2.5 py-0.5 rounded font-mono">CHM-A-001</span>
                            <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2.5 py-0.5 rounded">v3.2</span>
                        </div>
                        <p class="text-xs text-slate-500">Última modificación: 18 jul 2026 por A. Martínez</p>
                    </div>
                    
                    @can('manage-recipes')
                    <div class="flex items-center gap-2">
                        <button class="px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium transition-colors">
                            Duplicar
                        </button>
                        <button @click="openModal('edit')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm shadow-blue-600/20">
                            Editar Receta
                        </button>
                    </div>
                    @endcan
                </div>
            </div>

            <!-- Tabla de Componentes Optimizada -->
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100 uppercase tracking-widest text-[10px] font-bold bg-slate-50/50">
                            <th class="px-6 py-3.5 w-16">#</th>
                            <th class="px-6 py-3.5">Componente</th>
                            <th class="px-6 py-3.5">Tipo</th>
                            <th class="px-6 py-3.5">Cantidad</th>
                            <th class="px-6 py-3.5">Unidad</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">01</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Tela Mezclilla 12oz</td>
                            <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Material</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">2.5</td>
                            <td class="px-6 py-4 text-slate-500">m&sup2;</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">02</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Cierre metálico YKK #5</td>
                            <td class="px-6 py-4"><span class="bg-purple-50 text-purple-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Accesorio</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">2</td>
                            <td class="px-6 py-4 text-slate-500">pzas</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">03</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Botón tachuela 17mm</td>
                            <td class="px-6 py-4"><span class="bg-purple-50 text-purple-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Accesorio</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">8</td>
                            <td class="px-6 py-4 text-slate-500">pzas</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">04</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Hilo poliéster 40/2 índigo</td>
                            <td class="px-6 py-4"><span class="bg-orange-50 text-orange-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Insumo</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">150</td>
                            <td class="px-6 py-4 text-slate-500">m</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Resumen -->
            <div class="bg-slate-50/50 p-4 border-t border-slate-100 flex flex-wrap justify-between items-center gap-4 mt-auto">
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium">
                    <div class="flex items-center gap-1.5"><span class="text-blue-600 font-bold">Material</span> <span class="text-slate-800">1</span></div>
                    <div class="flex items-center gap-1.5"><span class="text-purple-600 font-bold">Accesorio</span> <span class="text-slate-800">2</span></div>
                    <div class="flex items-center gap-1.5"><span class="text-orange-600 font-bold">Insumo</span> <span class="text-slate-800">1</span></div>
                </div>
                <div class="text-xs text-slate-400 font-medium">
                    4 componentes listados
                </div>
            </div>
        </div>
    </div>
</div>

@endcan
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('recetasManager', () => ({
            activeRecipe: 1,
            modalOpen: false,
            modalMode: 'create',
            openModal(mode) {
                this.modalMode = mode;
                this.modalOpen = true;
                document.body.classList.add('overflow-hidden');
            },
            closeModal() {
                this.modalOpen = false;
                document.body.classList.remove('overflow-hidden');
            }
        }))
    })
</script>
@endpush