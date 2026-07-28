@extends('layouts.app')

@section('content')
{{-- Validación de permiso de visualización --}}
@can('view-recipes')

<div x-data="recetasManager()" class="p-6 max-w-[1600px] mx-auto">
    
    <!-- Encabezado de la página -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Recetas de Componentes</h1>
            <p class="text-sm text-slate-500 mt-1">Materiales e insumos por producto &middot; 3 recetas</p>
        </div>
        
        {{-- Botón protegido por permiso de gestión --}}
        @can('manage-recipes')
        <button @click="openModal('create')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm shadow-blue-600/30 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Receta
        </button>
        @endcan
    </div>

    <!-- Contenedor Principal (Grid / Flex) -->
    <div class="flex flex-col lg:flex-row gap-6 items-start">
        
        <!-- Panel Izquierdo: Lista de Recetas -->
        <div class="w-full lg:w-1/3 xl:w-[340px] flex flex-col gap-4">
            
            <!-- Buscador -->
            <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-6 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Buscar receta..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
            </div>

            <!-- Lista -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                
                <!-- Item Activo (Chamarra) -->
                <button @click="activeRecipe = 1" class="w-full text-left p-4 border-l-4 border-blue-600 bg-blue-50/40 border-b border-slate-100 transition-colors">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-bold text-slate-900 text-sm">Chamarra de Mezclilla Mod. A</h3>
                        <span class="bg-slate-200 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded">v3.2</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mb-2 uppercase tracking-wider">CHM-A-001</p>
                    <p class="text-[11px] text-slate-500">7 componentes &middot; Act. 18 jul 2026</p>
                </button>

                <!-- Item Inactivo (Pantalón) -->
                <button @click="activeRecipe = 2" class="w-full text-left p-4 border-l-4 border-transparent hover:bg-slate-50 border-b border-slate-100 transition-colors">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-semibold text-slate-700 text-sm">Pantalón Cargo Slim Mod. C</h3>
                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded">v2.0</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mb-2 uppercase tracking-wider">PCS-C-002</p>
                    <p class="text-[11px] text-slate-500">6 componentes &middot; Act. 12 jul 2026</p>
                </button>

                <!-- Item Inactivo (Sudadera) -->
                <button @click="activeRecipe = 3" class="w-full text-left p-4 border-l-4 border-transparent hover:bg-slate-50 transition-colors">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-semibold text-slate-700 text-sm">Sudadera Hoodie Oversize</h3>
                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded">v1.4</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mb-2 uppercase tracking-wider">SHO-004</p>
                    <p class="text-[11px] text-slate-500">8 componentes &middot; Act. 05 jul 2026</p>
                </button>
            </div>
        </div>

        <!-- Panel Derecho: Detalle de Receta -->
        <div class="w-full lg:flex-1 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            
            <!-- Cabecera del Detalle -->
            <div class="p-6 border-b border-slate-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Chamarra de Mezclilla Mod. A</h2>
                            <span class="bg-blue-50 text-blue-600 border border-blue-200 text-xs font-bold px-2 py-0.5 rounded">CHM-A-001</span>
                            <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2 py-0.5 rounded">v3.2</span>
                        </div>
                        <p class="text-xs text-slate-500">Última modificación: 18 jul 2026 por A. Martínez</p>
                    </div>
                    
                    {{-- Acciones protegidas --}}
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

            <!-- Tabla de Componentes -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100 uppercase tracking-widest text-[10px] font-bold">
                            <th class="px-6 py-4 w-16">#</th>
                            <th class="px-6 py-4">Componente</th>
                            <th class="px-6 py-4">Tipo</th>
                            <th class="px-6 py-4">Cantidad</th>
                            <th class="px-6 py-4 rounded-tr-lg">Unidad</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">01</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Tela Mezclilla 12oz</td>
                            <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Material</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">2.5</td>
                            <td class="px-6 py-4 text-slate-500">m&sup2;</td>
                        </tr>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">02</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Cierre metálico YKK #5</td>
                            <td class="px-6 py-4"><span class="bg-purple-50 text-purple-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Accesorio</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">2</td>
                            <td class="px-6 py-4 text-slate-500">pzas</td>
                        </tr>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">03</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Botón tachuela 17mm</td>
                            <td class="px-6 py-4"><span class="bg-purple-50 text-purple-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Accesorio</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">8</td>
                            <td class="px-6 py-4 text-slate-500">pzas</td>
                        </tr>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">04</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Hilo poliéster 40/2 índigo</td>
                            <td class="px-6 py-4"><span class="bg-orange-50 text-orange-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Insumo</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">150</td>
                            <td class="px-6 py-4 text-slate-500">m</td>
                        </tr>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">05</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Etiqueta composición</td>
                            <td class="px-6 py-4"><span class="bg-slate-100 text-slate-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Etiqueta</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">1</td>
                            <td class="px-6 py-4 text-slate-500">pzas</td>
                        </tr>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">06</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Etiqueta talla bordada</td>
                            <td class="px-6 py-4"><span class="bg-slate-100 text-slate-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Etiqueta</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">1</td>
                            <td class="px-6 py-4 text-slate-500">pzas</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">07</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Entretela fusionable</td>
                            <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 text-[11px] font-bold px-2.5 py-1 rounded-full">Material</span></td>
                            <td class="px-6 py-4 font-mono font-semibold">0.3</td>
                            <td class="px-6 py-4 text-slate-500">m&sup2;</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Resumen de Cantidades -->
            <div class="bg-slate-50/50 p-4 border-t border-slate-100 flex flex-wrap justify-between items-center gap-4 rounded-b-2xl">
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium">
                    <div class="flex items-center gap-1.5"><span class="text-blue-600 font-bold">Material</span> <span class="text-slate-800">2</span></div>
                    <div class="flex items-center gap-1.5"><span class="text-purple-600 font-bold">Accesorio</span> <span class="text-slate-800">2</span></div>
                    <div class="flex items-center gap-1.5"><span class="text-orange-600 font-bold">Insumo</span> <span class="text-slate-800">1</span></div>
                    <div class="flex items-center gap-1.5"><span class="text-slate-600 font-bold">Etiqueta</span> <span class="text-slate-800">2</span></div>
                </div>
                <div class="text-xs text-slate-400 font-medium">
                    7 componentes en total
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CRUD (Crear/Editar) gestionado con Alpine.js -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <!-- Backdrop -->
            <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Panel del Modal -->
            <div x-show="modalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-200">
                
                <form action="#" method="POST">
                    @csrf
                    <!-- Simulación de método PUT si es edición -->
                    <template x-if="modalMode === 'edit'">
                        @method('PUT')
                    </template>

                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-900" id="modal-title" x-text="modalMode === 'create' ? 'Crear Nueva Receta' : 'Editar Receta'"></h3>
                        <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-5">
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre del Producto</label>
                                <input type="text" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Ej. Chamarra de Mezclilla">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Código Interno</label>
                                <input type="text" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none uppercase" placeholder="Ej. CHM-A-001">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Versión</label>
                                <input type="text" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Ej. v1.0">
                            </div>
                        </div>

                        <!-- Sección dinámica de componentes (UI representativa) -->
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex justify-between items-center mb-3">
                                <label class="block text-sm font-semibold text-slate-700">Componentes de la Receta</label>
                                <button type="button" class="text-xs font-semibold text-blue-600 hover:text-blue-700">+ Añadir fila</button>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 text-center text-sm text-slate-500">
                                Funcionalidad de agregar componentes dinámicos se carga aquí.
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-100 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm transition-colors" x-text="modalMode === 'create' ? 'Guardar Receta' : 'Actualizar Receta'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endcan
@endsection

@push('scripts')
<script>
    // Componente Alpine.js para la lógica de la vista
    document.addEventListener('alpine:init', () => {
        Alpine.data('recetasManager', () => ({
            activeRecipe: 1, // ID simulado del elemento activo en la lista
            modalOpen: false,
            modalMode: 'create', // 'create' o 'edit'
            
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