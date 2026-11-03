@extends('layouts.app')

@section('content')
<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.almacen.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 dark:text-stone-400 hover:text-orange-600 dark:hover:text-orange-400 mb-1 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Almacén
                </a>
                <h2 class="font-bold text-xl sm:text-2xl text-slate-800 dark:text-white leading-tight">
                    Historial General de Stock
                </h2>
                <p class="text-sm text-slate-500 dark:text-stone-400 mt-0.5">Todas las entradas de material registradas, de más reciente a más antigua.</p>
            </div>
        </div>

        @php
            $reqId = request('material_id');
            $selectedMat = $materials->firstWhere('id', $reqId) ?? $materials->firstWhere('id', (int) $reqId);
            $initialName = $selectedMat ? $selectedMat->name : 'Todos los materiales';
            $initialId = $selectedMat ? (string) $selectedMat->id : '';
        @endphp

        <!-- Filtro por material con Custom Dropdown blindado -->
        <form 
            x-data="{ 
                selectOpen: false, 
                selectedId: @js($initialId), 
                selectedName: @js($initialName),
                selectOption(id, name) {
                    this.selectedId = id;
                    this.selectedName = name;
                    this.selectOpen = false;
                    this.$nextTick(() => this.$el.closest('form').submit());
                }
            }" 
            method="GET" 
            action="{{ route('admin.almacen.historial') }}" 
            class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 bg-white dark:bg-stone-900 p-3 sm:p-4 rounded-xl border border-slate-200 dark:border-stone-800"
        >
            <label class="text-xs font-semibold text-slate-600 dark:text-stone-400 whitespace-nowrap">Filtrar por material</label>
            
            <div class="relative w-full sm:w-64">
                <input type="hidden" name="material_id" :value="selectedId">
                
                <button 
                    type="button" 
                    @click="selectOpen = !selectOpen" 
                    @click.outside="selectOpen = false" 
                    class="w-full flex items-center justify-between gap-2 px-3 py-2.5 sm:py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 cursor-pointer"
                    :class="selectedId ? 'text-slate-800 dark:text-white' : 'text-slate-500 dark:text-stone-400'"
                >
                    <span x-text="selectedName" class="truncate"></span>
                    <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="selectOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div 
                    x-show="selectOpen" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute left-0 right-0 z-50 mt-1 max-h-56 overflow-auto bg-white dark:bg-stone-950 border border-slate-200 dark:border-stone-800 rounded-lg shadow-xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                    style="display: none;"
                >
                    <button 
                        type="button" 
                        @click="selectOption('', 'Todos los materiales')"
                        class="w-full text-left px-3 py-2.5 sm:py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between gap-2 cursor-pointer"
                    >
                        <span>Todos los materiales</span>
                        <svg x-show="selectedId === ''" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>

                    @foreach($materials as $mat)
                        <button 
                            type="button" 
                            @click="selectOption(@js((string)$mat->id), @js($mat->name))"
                            class="w-full text-left px-3 py-2.5 sm:py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between gap-2 cursor-pointer"
                        >
                            <span class="truncate">{{ $mat->name }}</span>
                            <svg x-show="selectedId == @js((string)$mat->id)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    @endforeach
                </div>
            </div>

            @if(request()->filled('material_id'))
                <a href="{{ route('admin.almacen.historial') }}" class="text-xs font-semibold text-slate-400 hover:text-slate-600 dark:text-stone-500 dark:hover:text-white transition-colors">Quitar filtro</a>
            @endif
        </form>

        @php
            $mensajeVacio = request()->filled('material_id')
                ? 'No hay entradas de stock registradas para este material.'
                : 'Aún no se ha registrado ninguna entrada de stock.';
        @endphp

        <!-- Vista tarjetas (celular) -->
        <div class="md:hidden space-y-3">
            @forelse($logs as $log)
                <div class="bg-white dark:bg-stone-900 rounded-xl border border-slate-200 dark:border-stone-800 shadow-sm p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-medium text-slate-800 dark:text-white break-words">{{ $log->material->name ?? 'Material eliminado' }}</p>
                            @if($log->material)
                                <p class="text-xs font-mono text-slate-400 dark:text-stone-500">{{ $log->material->sku }}</p>
                            @endif
                        </div>
                        <p class="shrink-0 text-sm font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                            +{{ $log->quantity_added }} {{ $log->material->unit ?? '' }}
                        </p>
                    </div>

                    <dl class="mt-3 grid grid-cols-2 gap-x-3 gap-y-2 text-xs">
                        <div>
                            <dt class="text-slate-400 dark:text-stone-500 uppercase tracking-wide text-[10px]">Fecha</dt>
                            <dd class="text-slate-600 dark:text-stone-300">{{ $log->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 dark:text-stone-500 uppercase tracking-wide text-[10px]">Stock resultante</dt>
                            <dd class="font-bold text-slate-700 dark:text-white">{{ $log->stock_resultante }} {{ $log->material->unit ?? '' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 dark:text-stone-500 uppercase tracking-wide text-[10px]">Proveedor</dt>
                            <dd class="text-slate-600 dark:text-stone-300 break-words">{{ $log->proveedor_nombre }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 dark:text-stone-500 uppercase tracking-wide text-[10px]">Registrado por</dt>
                            <dd class="text-slate-600 dark:text-stone-300 break-words">{{ $log->user->name ?? 'Usuario eliminado' }}</dd>
                        </div>
                    </dl>
                </div>
            @empty
                <div class="bg-white dark:bg-stone-900 rounded-xl border border-slate-200 dark:border-stone-800 p-6 text-center text-sm text-slate-400 dark:text-stone-500">
                    {{ $mensajeVacio }}
                </div>
            @endforelse
        </div>

        <!-- Tabla de historial (tablet y escritorio) -->
        <div class="hidden md:block bg-white dark:bg-stone-900 rounded-2xl border border-slate-200 dark:border-stone-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-stone-950/60 border-b border-slate-100 dark:border-stone-800 text-xs font-bold text-slate-500 dark:text-stone-500 uppercase tracking-wider">
                            <th class="p-4">Fecha</th>
                            <th class="p-4">Material</th>
                            <th class="p-4">Cantidad Agregada</th>
                            <th class="p-4">Stock Resultante</th>
                            <th class="p-4">Proveedor</th>
                            <th class="p-4">Registrado por</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-stone-800 text-sm">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-stone-800/40 transition-colors">
                                <td class="p-4 text-slate-500 dark:text-stone-400 whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="p-4 font-medium text-slate-800 dark:text-white">
                                    {{ $log->material->name ?? 'Material eliminado' }}
                                    @if($log->material)
                                        <span class="block text-xs font-mono text-slate-400 dark:text-stone-500">{{ $log->material->sku }}</span>
                                    @endif
                                </td>
                                <td class="p-4 font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                    +{{ $log->quantity_added }} {{ $log->material->unit ?? '' }}
                                </td>
                                <td class="p-4 font-bold text-slate-700 dark:text-white whitespace-nowrap">
                                    {{ $log->stock_resultante }} {{ $log->material->unit ?? '' }}
                                </td>
                                <td class="p-4 text-slate-600 dark:text-stone-300">
                                    {{ $log->proveedor_nombre }}
                                </td>
                                <td class="p-4 text-slate-600 dark:text-stone-300">
                                    {{ $log->user->name ?? 'Usuario eliminado' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400 dark:text-stone-500">
                                    {{ $mensajeVacio }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->hasPages())
            <div>
                {{ $logs->links() }}
            </div>
        @endif

    </div>
</div>
@endsection