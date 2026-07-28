@extends('layouts.app')

@section('content')
{{-- Validación de permiso de visualización --}}


<div x-data="ordersManager()" class="min-h-screen bg-slate-50/50 p-6 max-w-[1600px] mx-auto overflow-hidden">
    
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Órdenes de Producción</h1>
            <p class="text-sm text-slate-500 mt-1">Gestión completa de órdenes &middot; 8 registros</p>
        </div>
        
        {{-- Botón protegido por permiso de gestión --}}
        @can('manage-orders')
        <button @click="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm shadow-blue-600/30 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Orden
        </button>
        @endcan
    </div>

    <!-- KPI Cards (Tarjetas de resumen) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Total Órdenes</p>
            <p class="text-3xl font-black text-slate-900">8</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">En Progreso</p>
            <p class="text-3xl font-black text-blue-600">4</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Completadas</p>
            <p class="text-3xl font-black text-emerald-500">1</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Urgentes</p>
            <p class="text-3xl font-black text-red-600">3</p>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <div class="flex flex-col lg:flex-row gap-4 mb-6 items-start lg:items-center">
        <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm relative w-full lg:w-96 shrink-0">
            <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Buscar por ID, producto u operario..." class="w-full pl-8 pr-2 text-sm bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-400 outline-none">
        </div>

        <div class="flex flex-wrap gap-2">
            <button class="px-4 py-1.5 rounded-lg text-sm font-medium bg-blue-600 text-white shadow-sm shadow-blue-600/20">Todos</button>
            <button class="px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">En Progreso</button>
            <button class="px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Completada</button>
            <button class="px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Iniciando</button>
            <button class="px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Revisión</button>
            <button class="px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Pendiente</button>
        </div>
    </div>

    <!-- Contenedor Flex para Tabla y Panel Lateral -->
    <div class="flex items-start gap-6 relative">
        
        <!-- Área de la Tabla -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto flex-1 transition-all duration-300">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 uppercase tracking-widest text-[10px] font-bold">
                        <th class="px-5 py-4">ID de Orden</th>
                        <th class="px-5 py-4">Producto</th>
                        <th class="px-5 py-4">Prioridad</th>
                        <th class="px-5 py-4 min-w-[180px]">Progreso</th>
                        <th class="px-5 py-4">Fecha Límite</th>
                        <th class="px-5 py-4">Operario</th>
                        <th class="px-5 py-4">Estado</th>
                        <th class="px-5 py-4"></th>
                    </tr>
                </thead>
                <tbody class="text-slate-700">
                    
                    <!-- Fila 1 -->
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors" :class="{'bg-slate-50': activeOrder.id === 'ORD-2024-0091'}">
                        <td class="px-5 py-4">
                            <span class="bg-blue-50 text-blue-600 border border-blue-100 text-xs font-bold px-2.5 py-1 rounded-md">ORD-2024-0091</span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-900">Chamarra de Mezclilla Mod. A</p>
                            <p class="text-xs text-slate-400 mt-0.5">Outerwear</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="bg-red-50 text-red-600 border border-red-100 text-[11px] font-bold px-2 py-0.5 rounded-full">Alta</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" style="width: 72%"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600">72%</span>
                            </div>
                            <p class="text-[10px] font-medium text-slate-400 mt-1 tracking-wider">360/500 pzas</p>
                        </td>
                        <td class="px-5 py-4 text-xs font-semibold text-slate-600">25 jul 2026</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-bold">RL</span>
                                <span class="text-xs font-semibold text-slate-700">R. López</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-blue-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> En Progreso
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button @click="viewOrder('ORD-2024-0091')" class="text-blue-600 hover:text-blue-800 text-xs font-bold transition-colors">Ver &rarr;</button>
                        </td>
                    </tr>

                    <!-- Fila 2 -->
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors" :class="{'bg-slate-50': activeOrder.id === 'ORD-2024-0090'}">
                        <td class="px-5 py-4">
                            <span class="bg-blue-50 text-blue-600 border border-blue-100 text-xs font-bold px-2.5 py-1 rounded-md">ORD-2024-0090</span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-900">Pantalón Cargo Slim Mod. C</p>
                            <p class="text-xs text-slate-400 mt-0.5">Bottoms</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="bg-red-50 text-red-600 border border-red-100 text-[11px] font-bold px-2 py-0.5 rounded-full">Alta</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-orange-500 rounded-full" style="width: 45%"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600">45%</span>
                            </div>
                            <p class="text-[10px] font-medium text-slate-400 mt-1 tracking-wider">135/300 pzas</p>
                        </td>
                        <td class="px-5 py-4 text-xs font-bold text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            23 jul 2026
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-bold">MG</span>
                                <span class="text-xs font-semibold text-slate-700">M. García</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-blue-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> En Progreso
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button @click="viewOrder('ORD-2024-0090')" class="text-blue-600 hover:text-blue-800 text-xs font-bold transition-colors">Ver &rarr;</button>
                        </td>
                    </tr>

                    <!-- Fila 3 -->
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors" :class="{'bg-slate-50': activeOrder.id === 'ORD-2024-0089'}">
                        <td class="px-5 py-4">
                            <span class="bg-blue-50 text-blue-600 border border-blue-100 text-xs font-bold px-2.5 py-1 rounded-md">ORD-2024-0089</span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-900">Blusa Lino Temporada 26</p>
                            <p class="text-xs text-slate-400 mt-0.5">Tops</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-[11px] font-bold px-2 py-0.5 rounded-full">Media</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 100%"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600">100%</span>
                            </div>
                            <p class="text-[10px] font-medium text-slate-400 mt-1 tracking-wider">200/200 pzas</p>
                        </td>
                        <td class="px-5 py-4 text-xs font-semibold text-slate-600">20 jul 2026</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-bold">AT</span>
                                <span class="text-xs font-semibold text-slate-700">A. Torres</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Completada
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button @click="viewOrder('ORD-2024-0089')" class="text-blue-600 hover:text-blue-800 text-xs font-bold transition-colors">Ver &rarr;</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Panel Lateral (Detalle de Orden) -->
        <div x-show="isPanelOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-10"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-10"
             class="w-full max-w-[340px] shrink-0 bg-white border border-slate-200 rounded-2xl shadow-sm hidden lg:flex flex-col relative"
             style="display: none;">
            
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                <h3 class="font-bold text-slate-900 text-sm">Detalle de Orden</h3>
                <button @click="closePanel()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-5 flex-1 overflow-y-auto">
                <span class="bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-bold px-2 py-0.5 rounded-md mb-3 inline-block" x-text="activeOrder.id"></span>
                <h2 class="text-lg font-black text-slate-900 leading-tight" x-text="activeOrder.product"></h2>
                <p class="text-xs text-slate-400 mt-1" x-text="activeOrder.category"></p>

                <!-- Gráfico de Progreso Grande -->
                <div class="my-8 text-center bg-slate-50 p-6 rounded-xl border border-slate-100">
                    <div class="text-5xl font-black text-slate-900 tracking-tighter" x-text="activeOrder.progress + '%'"></div>
                    <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden mt-4 mb-2">
                        <div class="h-full bg-blue-600 rounded-full transition-all duration-500" :style="'width: ' + activeOrder.progress + '%'"></div>
                    </div>
                    <div class="text-xs font-bold text-slate-500 tracking-widest" x-text="activeOrder.progressText"></div>
                </div>

                <!-- Lista de Detalles -->
                <ul class="space-y-4">
                    <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Estado</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white border border-slate-200 text-blue-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> <span x-text="activeOrder.status"></span>
                        </span>
                    </li>
                    <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Prioridad</span>
                        <span class="text-red-600 bg-red-50 border border-red-100 px-2 py-0.5 rounded-full text-[11px] font-bold" x-text="activeOrder.priority"></span>
                    </li>
                    <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Operario</span>
                        <span class="text-slate-800 font-bold" x-text="activeOrder.operator"></span>
                    </li>
                    <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Estación</span>
                        <span class="text-slate-800 font-bold" x-text="activeOrder.station"></span>
                    </li>
                    <li class="flex justify-between items-center text-sm">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Fecha Límite</span>
                        <span class="text-slate-800 font-bold" x-text="activeOrder.deadline"></span>
                    </li>
                </ul>
            </div>
            
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
                <button class="w-full bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-sm py-2 rounded-lg transition-colors shadow-sm">
                    Actualizar Progreso
                </button>
            </div>
        </div>
    </div>

    <!-- Modal "Nueva Orden" -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="isModalOpen" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-slate-200">
                
                <form action="#" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <h3 class="text-lg font-bold text-slate-900">Crear Nueva Orden</h3>
                        <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Producto a fabricar</label>
                            <select class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option>Seleccione un producto...</option>
                                <option>Chamarra de Mezclilla Mod. A</option>
                                <option>Pantalón Cargo Slim Mod. C</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Cantidad (Pzas)</label>
                                <input type="number" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500" placeholder="Ej. 500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Prioridad</label>
                                <select class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    <option>Baja</option>
                                    <option>Media</option>
                                    <option>Alta</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Operario Asignado</label>
                                <select class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    <option>Sin asignar</option>
                                    <option>R. López</option>
                                    <option>M. García</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha Límite</label>
                                <input type="date" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-100">Cancelar</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm">Generar Orden</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ordersManager', () => ({
            isPanelOpen: false,
            isModalOpen: false,
            activeOrder: {
                id: '', product: '', category: '', progress: 0, progressText: '', status: '', priority: '', operator: '', station: '', deadline: ''
            },
            ordersDB: {
                'ORD-2024-0091': { id: 'ORD-2024-0091', product: 'Chamarra de Mezclilla Mod. A', category: 'Outerwear', progress: 72, progressText: '360 / 500 pzas', status: 'En Progreso', priority: 'Alta', operator: 'R. López', station: 'Estación 4', deadline: '25 jul 2026' },
                'ORD-2024-0090': { id: 'ORD-2024-0090', product: 'Pantalón Cargo Slim Mod. C', category: 'Bottoms', progress: 45, progressText: '135 / 300 pzas', status: 'En Progreso', priority: 'Alta', operator: 'M. García', station: 'Estación 2', deadline: '23 jul 2026' },
                'ORD-2024-0089': { id: 'ORD-2024-0089', product: 'Blusa Lino Temporada 26', category: 'Tops', progress: 100, progressText: '200 / 200 pzas', status: 'Completada', priority: 'Media', operator: 'A. Torres', station: 'Empaque', deadline: '20 jul 2026' }
            },
            viewOrder(id) {
                this.activeOrder = this.ordersDB[id];
                this.isPanelOpen = true;
            },
            closePanel() {
                this.isPanelOpen = false;
            },
            openModal() {
                this.isModalOpen = true;
                document.body.classList.add('overflow-hidden');
            },
            closeModal() {
                this.isModalOpen = false;
                document.body.classList.remove('overflow-hidden');
            }
        }))
    })
</script>
@endpush