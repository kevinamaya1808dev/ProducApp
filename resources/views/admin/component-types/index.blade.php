@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8 w-full max-w-3xl mx-auto">

    <a href="{{ route('recipes.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 dark:text-stone-400 hover:text-slate-700 dark:hover:text-stone-200 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Volver a Recetas
    </a>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-sm font-medium px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-300 text-sm font-medium px-4 py-3 rounded-xl">{{ session('error') }}</div>
    @endif

    @include('admin.component-types.components.header')

    @include('admin.component-types.components.types-table', ['componentTypes' => $componentTypes])
</div>

@include('admin.component-types.modals.create')
@foreach($componentTypes as $type)
    @include('admin.component-types.modals.edit', ['type' => $type])
    @include('admin.component-types.modals.delete', ['type' => $type])
@endforeach

<script>
    function openModal(id) { document.getElementById(id)?.classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id)?.classList.add('hidden'); }
</script>
@endsection