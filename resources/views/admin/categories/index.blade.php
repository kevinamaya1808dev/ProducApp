@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8 w-full">

    @include('admin.categories.components.header')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        @include('admin.categories.components.sidebar')

        <!-- Columna Derecha: Detalle -->
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            @if(isset($activeCategory))
                @include('admin.categories.components.detail-header', ['category' => $activeCategory])

                <div class="p-6 space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Descripción</h4>
                        <p class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200/60">
                            {{ $activeCategory->description ?? 'Esta categoría no cuenta con una descripción detallada.' }}
                        </p>
                    </div>
                </div>
            @else
                @include('admin.categories.components.empty-detail')
            @endif
        </div>
    </div>
</div>

{{-- Inclusión de Modales Organizados protegidos por permiso --}}
@can('manage-categories')
    @include('admin.categories.modals.create')
    @if(isset($activeCategory))
        @include('admin.categories.modals.edit', ['category' => $activeCategory])
        @include('admin.categories.modals.delete', ['category' => $activeCategory])
    @endif
@endcan

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