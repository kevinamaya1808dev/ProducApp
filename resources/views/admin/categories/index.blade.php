@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8 w-full">

    @include('admin.categories.components.header')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        @include('admin.categories.components.sidebar')

        <!-- Columna Derecha: Detalle -->
        <div class="lg:col-span-8 bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-slate-200/80 dark:border-stone-800 overflow-hidden">
            @if(isset($activeCategory))
                @include('admin.categories.components.detail-header', ['category' => $activeCategory])
            @else
                @include('admin.categories.components.empty-detail')
            @endif
        </div>
    </div>
</div>

{{-- Inclusión de Modales Organizados protegidos por permiso --}}
@can('categories.create')
    @include('admin.categories.modals.create')
@endcan
@if(isset($activeCategory))
    @can('categories.edit')
        @include('admin.categories.modals.edit', ['category' => $activeCategory])
    @endcan
    @can('categories.delete')
        @include('admin.categories.modals.delete', ['category' => $activeCategory])
    @endcan
@endif

@endsection

@push('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId)?.classList.remove('hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId)?.classList.add('hidden');
    }

    // Cierre global de modales mediante la tecla Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id$="Modal"], [id^="modal"]').forEach(modal => {
                modal.classList.add('hidden');
            });
        }
    });

    // Reapertura automática de modales en caso de errores de validación de Laravel
    @if ($errors->any() && old('form_source'))
        document.addEventListener('DOMContentLoaded', function () { 
            openModal(@json(old('form_source'))); 
        });
    @endif
</script>
@endpush