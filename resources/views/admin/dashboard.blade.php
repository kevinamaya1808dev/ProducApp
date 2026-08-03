@extends('layouts.app')

@section('content')
<div class="space-y-6">

    @include('admin.components.dashboard.header')

    <!-- Tarjetas KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @include('admin.components.dashboard.kpi-card', [
            'title' => 'Lotes Activos',
            'value' => $activeOrdersCount ?? 0,
            'badge' => 'En proceso actual',
            'color' => 'orange',
            'icon'  => 'boxes',
        ])
        @include('admin.components.dashboard.kpi-card', [
            'title' => 'Piezas Terminadas Hoy',
            'value' => number_format($completedPiecesToday ?? 0),
            'badge' => 'Actualizado hoy',
            'color' => 'emerald',
            'icon'  => 'check',
        ])
        @include('admin.components.dashboard.kpi-card', [
            'title' => 'Rendimiento Global',
            'value' => $globalPerformance ?? '0%',
            'badge' => 'Eficiencia de planta',
            'color' => 'purple',
            'icon'  => 'clock',
        ])
        @include('admin.components.dashboard.kpi-card', [
            'title' => 'Incidencias / Alertas',
            'value' => $activeIncidentsCount ?? 0,
            'badge' => 'Requieren atención',
            'color' => 'amber',
            'icon'  => 'alert',
        ])
    </div>

    <!-- Tabla + Actividad -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        @include('admin.components.dashboard.orders-table', ['orders' => $orders ?? null])
        @include('admin.components.dashboard.activity-panel', ['recentActivities' => $recentActivities ?? null])
    </div>
</div>

@include('admin.modals.export')
@include('admin.modals.filter')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.openModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        };

        window.addEventListener('click', function(event) {
            ['filter-modal', 'export-modal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    closeModal(modalId);
                }
            });
        });
    });
</script>
@endpush
@endsection