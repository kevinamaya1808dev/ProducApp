@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-stone-100">Permisos de {{ $user->name }}</h1>
            <p class="text-sm text-slate-500 dark:text-stone-400">{{ $user->email }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-slate-500 dark:text-stone-400 hover:text-slate-700 dark:hover:text-stone-200 transition-colors">
            ← Volver a Usuarios
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-400 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.users.permissions.update', $user) }}" method="POST" class="bg-white dark:bg-stone-900 rounded-2xl border border-slate-200 dark:border-stone-800 p-6">
        @csrf
        @method('PUT')

        @include('admin.users.components.permissions-matrix', [
            'modules' => $permissionModules,
            'special' => $specialPermissions,
            'selectedIds' => $selectedIds,
        ])

        <div class="flex justify-end gap-2 pt-6 mt-6 border-t border-slate-100 dark:border-stone-800">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</a>
            <button type="submit" class="px-5 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors">Guardar Permisos</button>
        </div>
    </form>
</div>
@endsection