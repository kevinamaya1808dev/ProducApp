@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header & Acciones -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-stone-800 dark:text-stone-100">Gestión de Incidencias</h1>
            <p class="text-sm text-stone-500 dark:text-stone-400">Reporta problemas en órdenes de producción, analiza métricas y exporta reportes.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <!-- Botón Exportar -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-stone-50 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-200 font-medium text-sm rounded-xl border border-stone-200 dark:border-stone-700 shadow-sm transition-colors">
                    <svg class="w-4 h-4 text-stone-500 dark:text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Exportar Reporte
                </button>
                
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-stone-800 rounded-xl shadow-lg border border-stone-100 dark:border-stone-700 py-1 z-20">
                    <a href="{{ route('admin.export.dashboard.excel') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-700">
                        Exportar Excel
                    </a>
                    <a href="{{ route('admin.export.dashboard.pdf') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-700">
                        Exportar PDF
                    </a>
                </div>
            </div>

            @can('incidences.create')
<button onclick="openModal('createModal')" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-medium text-sm rounded-xl transition-all shadow-sm shadow-orange-600/30">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
    Reportar Incidencia
</button>
@endcan
        </div>
    </div>

    <!-- Mensajes de Alerta -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm rounded-xl flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm rounded-xl flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 dark:hover:text-rose-300">&times;</button>
        </div>
    @endif

    <!-- TARJETAS DE ESTADÍSTICAS / KPIS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 bg-white/80 dark:bg-stone-900/80 backdrop-blur-md rounded-2xl border border-stone-200 dark:border-stone-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-stone-500 dark:text-stone-400">Total Incidencias</p>
                <h3 class="text-2xl font-bold text-stone-800 dark:text-stone-100 mt-1">{{ $stats['total'] ?? $incidences->total() }}</h3>
            </div>
            <div class="p-3 bg-stone-100 dark:bg-stone-800 rounded-xl text-stone-600 dark:text-stone-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>

        <div class="p-4 bg-white/80 dark:bg-stone-900/80 backdrop-blur-md rounded-2xl border border-stone-200 dark:border-stone-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-stone-500 dark:text-stone-400">Pendientes</p>
                <h3 class="text-2xl font-bold text-orange-600 dark:text-orange-500 mt-1">{{ $stats['pendiente'] ?? $incidences->where('status', 'pendiente')->count() }}</h3>
            </div>
            <div class="p-3 bg-orange-50 dark:bg-orange-950/50 rounded-xl text-orange-600 dark:text-orange-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="p-4 bg-white/80 dark:bg-stone-900/80 backdrop-blur-md rounded-2xl border border-stone-200 dark:border-stone-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-stone-500 dark:text-stone-400">En Proceso</p>
                <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['en_proceso'] ?? $incidences->where('status', 'en_proceso')->count() }}</h3>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-950/50 rounded-xl text-blue-600 dark:text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>

        <div class="p-4 bg-white/80 dark:bg-stone-900/80 backdrop-blur-md rounded-2xl border border-stone-200 dark:border-stone-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-stone-500 dark:text-stone-400">Resueltas</p>
                <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['resuelta'] ?? $incidences->where('status', 'resuelta')->count() }}</h3>
            </div>
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/50 rounded-xl text-emerald-600 dark:text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE GRÁFICAS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfica de Estados -->
        <div class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-md p-5 rounded-2xl border border-stone-200 dark:border-stone-800 shadow-sm">
            <h4 class="text-sm font-bold text-stone-800 dark:text-stone-100 mb-4">Distribución por Estado</h4>
            <div class="relative h-56 flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Gráfica de Prioridades -->
        <div class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-md p-5 rounded-2xl border border-stone-200 dark:border-stone-800 shadow-sm">
            <h4 class="text-sm font-bold text-stone-800 dark:text-stone-100 mb-4">Nivel de Prioridad</h4>
            <div class="relative h-56 flex items-center justify-center">
                <canvas id="importanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filtros de búsqueda -->
<div class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-md p-4 rounded-2xl border border-stone-200 dark:border-stone-800 shadow-sm">
    <form id="incidenceFiltersForm" method="GET" action="{{ route('admin.incidences.index') }}" class="flex flex-wrap gap-4 items-center">

        {{-- Dropdown Personalizado: Estado
             Mismo patrón x-teleport="body" que en los modales de incidencias/órdenes.
             Al ser un filtro con auto-submit (antes onchange="this.form.submit()"), y
             como el panel vive teletransportado fuera del <form> una vez abierto, el
             submit se dispara por id ("incidenceFiltersForm") en vez de closest('form'). --}}
        <div
            class="relative w-full sm:w-auto sm:min-w-[190px]"
            x-data="{
                open: false,
                selectedStatus: @js(request('status', '')),
                selectedName: @js(['' => 'Todos los Estados', 'pendiente' => 'Pendiente', 'en_proceso' => 'En Proceso', 'resuelta' => 'Resuelta'][request('status', '')] ?? 'Todos los Estados'),
                panelStyle: '',
                options: {
                    '': 'Todos los Estados',
                    'pendiente': 'Pendiente',
                    'en_proceso': 'En Proceso',
                    'resuelta': 'Resuelta'
                },
                sync() {
                    this.selectedStatus = this.$refs.hiddenInput.value || '';
                    this.selectedName = this.options[this.selectedStatus] || 'Todos los Estados';
                },
                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.$nextTick(() => {
                            const rect = this.$refs.trigger.getBoundingClientRect();
                            this.panelStyle = `top:${rect.bottom + 4}px; left:${rect.left}px; width:${rect.width}px;`;
                        });
                    }
                }
            }"
            x-init="$refs.hiddenInput.value = selectedStatus;"
            @scroll.window.capture="open = false"
        >
            <input type="hidden" name="status" x-ref="hiddenInput">

            <button
                type="button"
                x-ref="trigger"
                @click="toggle()"
                @click.outside="open = false"
                class="w-full flex items-center justify-between text-sm py-2.5 px-3.5 rounded-xl bg-white dark:bg-stone-800 border border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 focus:outline-none focus:border-orange-500 transition-colors cursor-pointer"
            >
                <span x-text="selectedName" class="truncate"></span>
                <svg class="w-4 h-4 text-stone-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <template x-teleport="body">
                <div
                    x-show="open"
                    x-cloak
                    :style="panelStyle"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="fixed z-[9999] max-h-64 overflow-auto bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-xl shadow-2xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                    style="display: none;"
                >
                    @foreach([
                        '' => 'Todos los Estados',
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En Proceso',
                        'resuelta' => 'Resuelta'
                    ] as $val => $label)
                        <button
                            type="button"
                            @click="selectedStatus = @js($val); $refs.hiddenInput.value = selectedStatus; sync(); open = false; document.getElementById('incidenceFiltersForm').submit();"
                            class="w-full text-left px-3.5 py-2 text-sm text-stone-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                        >
                            <span class="truncate">{{ $label }}</span>
                            <svg x-show="selectedStatus === @js($val)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    @endforeach
                </div>
            </template>
        </div>

        {{-- Dropdown Personalizado: Prioridad --}}
        <div
            class="relative w-full sm:w-auto sm:min-w-[190px]"
            x-data="{
                open: false,
                selectedImportance: @js(request('importance', '')),
                selectedName: @js(['' => 'Todas las Prioridades', 'baja' => 'Prioridad Baja', 'media' => 'Prioridad Media', 'alta' => 'Prioridad Alta'][request('importance', '')] ?? 'Todas las Prioridades'),
                panelStyle: '',
                options: {
                    '': 'Todas las Prioridades',
                    'baja': 'Prioridad Baja',
                    'media': 'Prioridad Media',
                    'alta': 'Prioridad Alta'
                },
                sync() {
                    this.selectedImportance = this.$refs.hiddenInput.value || '';
                    this.selectedName = this.options[this.selectedImportance] || 'Todas las Prioridades';
                },
                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.$nextTick(() => {
                            const rect = this.$refs.trigger.getBoundingClientRect();
                            this.panelStyle = `top:${rect.bottom + 4}px; left:${rect.left}px; width:${rect.width}px;`;
                        });
                    }
                }
            }"
            x-init="$refs.hiddenInput.value = selectedImportance;"
            @scroll.window.capture="open = false"
        >
            <input type="hidden" name="importance" x-ref="hiddenInput">

            <button
                type="button"
                x-ref="trigger"
                @click="toggle()"
                @click.outside="open = false"
                class="w-full flex items-center justify-between text-sm py-2.5 px-3.5 rounded-xl bg-white dark:bg-stone-800 border border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 focus:outline-none focus:border-orange-500 transition-colors cursor-pointer"
            >
                <span x-text="selectedName" class="truncate"></span>
                <svg class="w-4 h-4 text-stone-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <template x-teleport="body">
                <div
                    x-show="open"
                    x-cloak
                    :style="panelStyle"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="fixed z-[9999] max-h-64 overflow-auto bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-xl shadow-2xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                    style="display: none;"
                >
                    @foreach([
                        '' => 'Todas las Prioridades',
                        'baja' => 'Prioridad Baja',
                        'media' => 'Prioridad Media',
                        'alta' => 'Prioridad Alta'
                    ] as $val => $label)
                        <button
                            type="button"
                            @click="selectedImportance = @js($val); $refs.hiddenInput.value = selectedImportance; sync(); open = false; document.getElementById('incidenceFiltersForm').submit();"
                            class="w-full text-left px-3.5 py-2 text-sm text-stone-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                        >
                            <span class="truncate">{{ $label }}</span>
                            <svg x-show="selectedImportance === @js($val)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    @endforeach
                </div>
            </template>
        </div>

        @if(request('status') || request('importance'))
            <a href="{{ route('admin.incidences.index') }}" class="text-xs text-orange-600 dark:text-orange-400 hover:underline">Limpiar Filtros</a>
        @endif
    </form>
</div>

    <!-- Tabla de Incidencias -->
    <div class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-md rounded-2xl border border-stone-200 dark:border-stone-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-stone-100/70 dark:bg-stone-800/70 text-stone-600 dark:text-stone-300 font-semibold border-b border-stone-200 dark:border-stone-800">
                        <th class="py-3.5 px-4">Orden / Título</th>
                        <th class="py-3.5 px-4">Reportado Por</th>
                        <th class="py-3.5 px-4">Prioridad</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4">Fecha</th>
                        <th class="py-3.5 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200/60 dark:divide-stone-800/60">
                    @forelse($incidences as $incidence)
                        <tr class="hover:bg-amber-50/30 dark:hover:bg-stone-800/50 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="text-xs font-mono px-2 py-0.5 rounded bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300 font-bold">#OP-{{ $incidence->order->id ?? 'N/A' }}</span>
                                <div class="font-medium text-stone-800 dark:text-stone-100 mt-1">{{ $incidence->title }}</div>
                                <p class="text-xs text-stone-500 dark:text-stone-400 line-clamp-1">{{ $incidence->description }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-stone-700 dark:text-stone-300">
                                {{ $incidence->operario->name ?? 'Usuario borrado' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($incidence->importance == 'alta')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300">Alta</span>
                                @elseif($incidence->importance == 'media')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300">Media</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300">Baja</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($incidence->status == 'resuelta')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Resuelta
                                    </span>
                                @elseif($incidence->status == 'en_proceso')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> En Proceso
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-stone-500 dark:text-stone-400">
                                {{ $incidence->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1">
    @can('incidences.edit')
        <button onclick='openStatusModal(@json($incidence))' class="px-2.5 py-1.5 text-xs bg-stone-100 hover:bg-stone-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 rounded-lg transition-colors font-medium">Estado</button>
    @endcan
    <button onclick='openHistoryModal(@json($incidence))' class="px-2.5 py-1.5 text-xs bg-orange-50 hover:bg-orange-100 dark:bg-orange-950/50 dark:hover:bg-orange-900/50 text-orange-700 dark:text-orange-300 rounded-lg transition-colors font-medium">Historial ({{ $incidence->logs->count() }})</button>

    @can('incidences.delete')
        <button onclick='openDeleteModal(@json($incidence))' title="Eliminar" class="px-2.5 py-1.5 text-xs bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 text-rose-700 dark:text-rose-300 rounded-lg transition-colors font-medium">
            Eliminar
        </button>
    @endcan
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-stone-400 dark:text-stone-500 text-sm">No se encontraron incidencias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-stone-200 dark:border-stone-800">
            {{ $incidences->links() }}
        </div>
    </div>
</div>

@can('incidences.create')
    @include('admin.incidences.partials.create-modal')
@endcan
@can('incidences.edit')
    @include('admin.incidences.partials.status-modal')
@endcan
@include('admin.incidences.partials.history-modal')
@can('incidences.delete')
    @include('admin.incidences.partials.delete-modal')
@endcan

@push('scripts')
<!-- CDN de Chart.js y AlpineJS (si no están incluidos en la plantilla principal) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- LÓGICA DE MODALES ---
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
    function openStatusModal(incidence) {
        document.getElementById('statusForm').action = `/admin/incidencias/${incidence.id}/status`;
        document.getElementById('modalStatusSelect').value = incidence.status;
        const importanceForm = document.getElementById('importanceForm');
        if(importanceForm) {
            importanceForm.action = `/admin/incidencias/${incidence.id}/importance`;
            document.getElementById('modalImportanceSelect').value = incidence.importance;
        }
        openModal('statusModal');
    }
    function openDeleteModal(incidence) {
        document.getElementById('deleteForm').action = `/admin/incidencias/${incidence.id}`;
        document.getElementById('deleteIncidenceOrder').innerText = `#OP-${incidence.production_order_id ?? incidence.order_id ?? 'N/A'}`;
        document.getElementById('deleteIncidenceTitle').innerText = incidence.title;
        openModal('deleteModal');
    }
    function openHistoryModal(incidence) {
        document.getElementById('historyModalTitle').innerText = `#OP-${incidence.production_order_id ?? incidence.order_id}: ${incidence.title}`;
        document.getElementById('addNoteForm').action = `/admin/incidencias/${incidence.id}/notes`;
        const container = document.getElementById('historyLogsContainer');
        container.innerHTML = '';
        if(incidence.logs && incidence.logs.length > 0) {
            incidence.logs.forEach(log => {
                const date = new Date(log.created_at).toLocaleString();
                const userName = log.user ? log.user.name : 'Sistema';
                let badge = '<span class="px-2 py-0.5 text-[10px] bg-stone-100 dark:bg-stone-800 font-bold rounded text-stone-600 dark:text-stone-300">NOTA</span>';
                if(log.type === 'creacion') badge = '<span class="px-2 py-0.5 text-[10px] bg-orange-100 dark:bg-orange-950/60 font-bold rounded text-orange-700 dark:text-orange-300">CREACIÓN</span>';
                if(log.type === 'cambio_estado') badge = '<span class="px-2 py-0.5 text-[10px] bg-blue-100 dark:bg-blue-950/60 font-bold rounded text-blue-700 dark:text-blue-300">ESTADO</span>';
                if(log.type === 'cambio_prioridad') badge = '<span class="px-2 py-0.5 text-[10px] bg-amber-100 dark:bg-amber-950/60 font-bold rounded text-amber-700 dark:text-amber-300">PRIORIDAD</span>';
                container.innerHTML += `
                    <div class="p-3 bg-stone-50 dark:bg-stone-800/50 border border-stone-200 dark:border-stone-700 rounded-xl space-y-1">
                        <div class="flex justify-between items-center text-xs text-stone-500 dark:text-stone-400">
                            <span class="font-semibold text-stone-700 dark:text-stone-200">${userName}</span>
                            <span>${date}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            ${badge}
                            <p class="text-xs text-stone-800 dark:text-stone-200">${log.comment}</p>
                        </div>
                    </div>
                `;
            });
        } else {
            container.innerHTML = '<p class="text-xs text-stone-400 dark:text-stone-500 text-center py-4">No hay historial para esta incidencia.</p>';
        }
        openModal('historyModal');
    }
    // --- INICIALIZACIÓN DE GRÁFICAS ADAPTADAS A MODO OSCURO ---
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        // Data proveniente de Blade / Controller
        const pendingCount = {{ $stats['pendiente'] ?? $incidences->where('status', 'pendiente')->count() }};
        const processCount = {{ $stats['en_proceso'] ?? $incidences->where('status', 'en_proceso')->count() }};
        const resolvedCount = {{ $stats['resuelta'] ?? $incidences->where('status', 'resuelta')->count() }};
        const lowPriorityCount = {{ $stats['baja'] ?? $incidences->where('importance', 'baja')->count() }};
        const mediumPriorityCount = {{ $stats['media'] ?? $incidences->where('importance', 'media')->count() }};
        const highPriorityCount = {{ $stats['alta'] ?? $incidences->where('importance', 'alta')->count() }};
        // Chart 1: Donut de Estado
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Pendiente', 'En Proceso', 'Resuelta'],
                datasets: [{
                    data: [pendingCount, processCount, resolvedCount],
                    backgroundColor: ['#ea580c', '#2563eb', '#059669'],
                    borderWidth: 2,
                    borderColor: isDark ? '#1c1917' : '#ffffff' // Borde acorde al fondo de la tarjeta
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11 },
                            color: isDark ? '#d6d3d1' : '#44403c'
                        }
                    }
                }
            }
        });
        // Chart 2: Barras de Prioridad
        const ctxImportance = document.getElementById('importanceChart').getContext('2d');
        new Chart(ctxImportance, {
            type: 'bar',
            data: {
                labels: ['Baja', 'Media', 'Alta'],
                datasets: [{
                    label: 'Cantidad',
                    data: [lowPriorityCount, mediumPriorityCount, highPriorityCount],
                    backgroundColor: ['#78716c', '#d97706', '#e11d48'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: isDark ? '#a8a29e' : '#78716c'
                        },
                        grid: {
                            color: isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: isDark ? '#a8a29e' : '#78716c'
                        }
                    }
                }
            }
        });
    });
</script> 
@endpush
@endsection