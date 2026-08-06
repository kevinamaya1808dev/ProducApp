@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8 w-full">

    @include('admin.recetas.components.header')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        @include('admin.recetas.components.sidebar')

        <!-- Columna Derecha: Detalle -->
        <div class="lg:col-span-8 bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-slate-200/80 dark:border-stone-800 overflow-hidden">
            @if(isset($activeRecipe))
                @include('admin.recetas.components.detail-header', ['recipe' => $activeRecipe])
                @include('admin.recetas.components.components-table', ['recipe' => $activeRecipe])
            @else
                @include('admin.recetas.components.empty-detail')
            @endif
        </div>
    </div>
</div>

{{-- Modales protegidos por la directiva de permisos con el slug exacto: manage-recipes --}}
@can('manage-recipes')
    @include('admin.recetas.modals.create')
    @if(isset($activeRecipe))
        @include('admin.recetas.modals.edit', ['recipe' => $activeRecipe])
        @include('admin.recetas.modals.duplicate', ['recipe' => $activeRecipe])
        @include('admin.recetas.modals.delete', ['recipe' => $activeRecipe])
        @include('admin.recetas.modals.add-component', ['recipe' => $activeRecipe])
        @foreach($activeRecipe->components as $component)
            @include('admin.recetas.modals.edit-component', ['recipe' => $activeRecipe, 'component' => $component])
            @include('admin.recetas.modals.delete-component', ['recipe' => $activeRecipe, 'component' => $component])
        @endforeach
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

    // Cierre de modales con la tecla Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id$="Modal"], [id^="modal"]').forEach(modal => {
                modal.classList.add('hidden');
            });
        }
    });

    @if ($errors->any() && old('form_source'))
        document.addEventListener('DOMContentLoaded', function () { 
            openModal(@json(old('form_source'))); 
        });
    @endif
</script>
@endpush