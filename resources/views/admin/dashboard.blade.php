@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Encabezado del Dashboard -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Resumen de Producción</h1>
            <p class="text-xs text-slate-500 mt-1">
                Turno Matutino &middot; Planta Principal &middot; Actualizado en tiempo real
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Botón para abrir modal de Filtros -->
            <button type="button" onclick="openModal('filter-modal')" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition-colors">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filtrar
            </button>
            <!-- Botón para abrir modal de Exportación -->
            <button type="button" onclick="openModal('export-modal')" class="inline-flex items-center gap-2 px-3.5 py-2 bg-blue-600 border border-transparent rounded-lg text-xs font-semibold text-white hover:bg-blue-700 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Exportar
            </button>
        </div>
    </div>

    <!-- Tarjetas de Métricas KPI (Dinámicas) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tarjeta 1: Lotes Activos -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Lotes Activos</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-3">{{ $activeOrdersCount ?? 0 }}</div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">
                En proceso actual
            </span>
        </div>

        <!-- Tarjeta 2: Piezas Terminadas Hoy -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Piezas Terminadas Hoy</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-3">{{ number_format($completedPiecesToday ?? 0) }}</div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                Actualizado hoy
            </span>
        </div>

        <!-- Tarjeta 3: Rendimiento Global -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Rendimiento Global</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-3">{{ $globalPerformance ?? '0%' }}</div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">
                Eficiencia de planta
            </span>
        </div>

        <!-- Tarjeta 4: Incidencias Activas -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Incidencias / Alertas</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-3">{{ $activeIncidentsCount ?? 0 }}</div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                Requieren atención
            </span>
        </div>
    </div>

    <!-- Sección Principal: Tabla de Órdenes y Panel de Actividad -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        <!-- Tabla de Órdenes de Producción en Curso -->
        <div class="xl:col-span-3 bg-white border border-slate-200/80 rounded-xl shadow-sm p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Órdenes de Producción en Curso</h2>
                    <p class="text-xs text-slate-500">{{ isset($orders) ? $orders->count() : 0 }} órdenes activas</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="relative w-full sm:w-48">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..." class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </form>
                </div>
            </div>

            <!-- Estructura de la Tabla con datos reales -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="pb-3 pr-4">ID de Orden</th>
                            <th class="pb-3 px-4">Producto</th>
                            <th class="pb-3 px-4">Progreso</th>
                            <th class="pb-3 px-4">Fecha Límite</th>
                            <th class="pb-3 px-4">Operario</th>
                            <th class="pb-3 pl-4 text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($orders ?? [] as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 pr-4 font-semibold text-blue-600 whitespace-nowrap">{{ $order->order_code ?? 'ORD-'.$order->id }}</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-800">{{ $order->product->name ?? 'N/D' }}</p>
                                <p class="text-[11px] text-slate-400">{{ $order->product->category->name ?? 'General' }}</p>
                            </td>
                            <td class="py-3.5 px-4 w-40">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $order->progress ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-500 w-8 text-right">{{ $order->progress ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-600 whitespace-nowrap">{{ $order->due_date ? \Carbon\Carbon::parse($order->due_date)->format('d M Y') : 'N/D' }}</td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 font-bold text-[10px] flex items-center justify-center">
                                        {{ substr($order->user->name ?? 'U', 0, 2) }}
                                    </span>
                                    <span class="font-medium text-slate-700">{{ $order->user->name ?? 'Sin asignar' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    {{ ucfirst($order->status ?? 'En proceso') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 text-xs">No hay órdenes de producción registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panel Lateral de Actividad Reciente -->
        <div class="xl:col-span-1 bg-white border border-slate-200/80 rounded-xl shadow-sm p-6 flex flex-col">
            <div class="mb-6">
                <h2 class="text-base font-bold text-slate-900">Actividad Reciente</h2>
                <p class="text-xs text-slate-500">Últimos eventos del sistema</p>
            </div>

            <div class="space-y-6 relative before:absolute before:inset-0 before:left-2 before:w-0.5 before:bg-slate-100">
                @forelse($recentActivities ?? [] as $activity)
                <div class="relative pl-6">
                    <span class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-white border-2 border-blue-600 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                    </span>
                    <p class="text-xs font-semibold text-slate-800 leading-snug">
                        {{ $activity->description }}
                    </p>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">{{ $activity->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <div class="relative pl-6">
                    <p class="text-xs text-slate-400">Sin actividad reciente registrada.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Incluir Modales de Admin -->
@include('admin.modals.filter')
@include('admin.modals.export')

@push('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId)?.classList.remove('hidden');
    }
    function closeModal(modalId) {
        document.getElementById(modalId)?.classList.add('hidden');
    }
</script>
@endpush
@endsection