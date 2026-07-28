@extends('layouts.app')

@section('content')

<div x-data="operatorsManager()" class="p-6 max-w-[1600px] mx-auto overflow-hidden">
    
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Operarios</h1>
            <p class="text-sm text-slate-500 mt-1">Personal de producción &middot; 8 registros</p>
        </div>
        
        {{-- Solo los administradores o usuarios con permiso de gestión pueden agregar operarios --}}
        @can('manage-operators')
        <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm shadow-blue-600/30 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Agregar Operario
        </button>
        @endcan
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Total Operarios</p>
            <p class="text-3xl font-black text-slate-900">8</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Activos Hoy</p>
            <p class="text-3xl font-black text-emerald-600">7</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Eficiencia Promedio</p>
            <p class="text-3xl font-black text-blue-600">90%</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Vacaciones</p>
            <p class="text-3xl font-black text-amber-500">1</p>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <div class="flex flex-col lg:flex-row justify-between gap-4 mb-6 items-start lg:items-center">
        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto items-center">
            <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm relative w-full sm:w-80">
                <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Buscar por nombre, rol o estación..." class="w-full pl-8 pr-2 text-sm bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-400 outline-none">
            </div>

            <div class="flex flex-wrap gap-2">
                <button class="px-4 py-1.5 rounded-lg text-sm font-medium bg-blue-600 text-white shadow-sm">Todos</button>
                <button class="px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">Matutino</button>
                <button class="px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">Vespertino</button>
            </div>
        </div>
    </div>

    <!-- Contenedor Principal: Tarjetas y Panel Deslizable -->
    <div class="flex items-start gap-6 relative">
        
        <!-- Grid de Tarjetas (Visible para todos los autorizados a ver la vista) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 flex-1 transition-all duration-300">
            
            <!-- Tarjeta Ejemplo: Roberto López -->
            <div @click="selectOperator('OP-001')" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:border-blue-300 hover:shadow-md transition-all cursor-pointer relative overflow-hidden group">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">RL</div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base group-hover:text-blue-600 transition-colors">Roberto López</h3>
                            <span class="inline-block bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-bold px-2 py-0.5 rounded-md mt-0.5">Operario Senior</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Activo</span>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wider">
                        <span>Eficiencia</span>
                        <span class="text-slate-900">94%</span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 rounded-full" style="width: 94%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100 text-xs">
                    <div>
                        <p class="text-slate-400 font-medium uppercase tracking-wider text-[10px]">Estación</p>
                        <p class="font-bold text-slate-800 mt-0.5">Estación 4</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-medium uppercase tracking-wider text-[10px]">Turno</p>
                        <p class="font-bold text-blue-600 mt-0.5">Matutino</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Panel Lateral (Perfil del Operario) -->
        <div x-show="isPanelOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-10"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-10"
             class="w-full max-w-[360px] shrink-0 bg-white border border-slate-200 rounded-2xl shadow-sm hidden lg:flex flex-col relative"
             style="display: none;">
            
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                <h3 class="font-bold text-slate-900 text-sm">Perfil del Operario</h3>
                <button @click="closePanel()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-5 flex-1 overflow-y-auto">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-blue-600 text-white font-black text-xl flex items-center justify-center mx-auto mb-3 shadow-md" x-text="activeOperator.initials"></div>
                    <h2 class="text-lg font-black text-slate-900" x-text="activeOperator.name"></h2>
                    <span class="inline-block bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-bold px-2.5 py-0.5 rounded-md mt-1" x-text="activeOperator.role"></span>
                </div>

                <ul class="space-y-3.5 text-sm mb-6">
                    <li class="flex justify-between items-center border-b border-slate-50 pb-2.5">
                        <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Estación</span>
                        <span class="text-slate-800 font-bold" x-text="activeOperator.station"></span>
                    </li>
                    <li class="flex justify-between items-center border-b border-slate-50 pb-2.5">
                        <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Órdenes Completadas</span>
                        <span class="text-slate-800 font-bold" x-text="activeOperator.ordersCount"></span>
                    </li>
                </ul>
            </div>
            
            <!-- Acciones del Panel: Controladas por Permisos de Administrador -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
                @can('manage-operators')
                <button @click="openEditModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar Perfil y Permisos
                </button>
                @else
                <div class="text-center text-xs text-slate-400 py-1">
                    Modo visualización (Sin privilegios de edición)
                </div>
                @endcan
            </div>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN Y ASIGNACIÓN DE PERMISOS -->
    @can('manage-operators')
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="isModalOpen" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-200">
                
                <form action="#" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <h3 class="text-lg font-bold text-slate-900" x-text="modalTitle"></h3>
                        <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Información del Colaborador</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre Completo</label>
                                    <input type="text" x-model="formData.name" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Rol / Puesto</label>
                                    <select x-model="formData.role" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                        <option>Operario</option>
                                        <option>Operario Senior</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Panel de Permisos Exclusivos de Administrador -->
                        <div class="border-t border-slate-100 pt-5">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Permisos y Accesos a Vistas de Administrador</h4>
                            <p class="text-xs text-slate-500 mb-4">Autoriza qué funciones administrativas puede operar este usuario en ProducApp:</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <label class="flex items-center gap-3 p-2 bg-white rounded-lg border border-slate-200 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="manage-orders" class="w-4 h-4 text-blue-600 rounded border-slate-300">
                                    <span class="text-sm font-semibold text-slate-800">Gestionar Órdenes</span>
                                </label>
                                <label class="flex items-center gap-3 p-2 bg-white rounded-lg border border-slate-200 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="manage-operators" class="w-4 h-4 text-blue-600 rounded border-slate-300">
                                    <span class="text-sm font-semibold text-slate-800">Administrar Usuarios</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-medium">Cancelar</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('operatorsManager', () => ({
            isPanelOpen: false,
            isModalOpen: false,
            modalTitle: 'Editar Perfil y Permisos',
            activeOperator: { initials: 'RL', name: 'Roberto López', role: 'Operario Senior', station: 'Estación 4', ordersCount: 312 },
            formData: { name: '', role: '' },

            selectOperator(id) {
                this.isPanelOpen = true;
            },
            closePanel() {
                this.isPanelOpen = false;
            },
            openCreateModal() {
                this.modalTitle = 'Agregar Nuevo Operario';
                this.isModalOpen = true;
            },
            openEditModal() {
                this.modalTitle = 'Editar Perfil y Permisos';
                this.formData = { name: this.activeOperator.name, role: this.activeOperator.role };
                this.isModalOpen = true;
            },
            closeModal() {
                this.isModalOpen = false;
            }
        }))
    })
</script>
@endpush