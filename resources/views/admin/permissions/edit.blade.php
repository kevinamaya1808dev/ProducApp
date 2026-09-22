@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-slate-900 dark:text-stone-100 mb-6">Editar Permiso: {{ $permission->name }}</h1>

    <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" class="bg-white dark:bg-stone-900 rounded-2xl border border-slate-200 dark:border-stone-800 p-6 space-y-4">
        @csrf
        @method('PUT')
        @include('admin.permissions.components.form', ['permission' => $permission, 'actions' => $actions])

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection