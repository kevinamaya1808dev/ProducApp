@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-stone-100">Gestión de Permisos</h1>
        @can('users.manage')
            <a href="{{ route('admin.permissions.create') }}" class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors">
                Nuevo Permiso
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-400 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 mb-8">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-stone-800/50 border-b border-slate-200 dark:border-stone-800">
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide">Módulo</th>
                    @foreach($actions as $key => $label)
                        <th class="text-center px-3 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide">{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-stone-800">
                @foreach($modules as $moduleSlug => $modulePermissions)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-stone-800/40 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-700 dark:text-stone-200">
                            {{ \Illuminate\Support\Str::headline($moduleSlug) }}
                        </td>
                        @foreach($actions as $actionKey => $actionLabel)
                            @php $perm = $modulePermissions->firstWhere('action', $actionKey); @endphp
                            <td class="text-center px-3 py-3">
                                @if($perm)
                                    <a href="{{ route('admin.permissions.edit', $perm) }}" class="inline-flex items-center gap-1 text-xs font-mono text-orange-600 dark:text-orange-400 hover:underline" title="{{ $perm->slug }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </a>
                                @else
                                    <span class="text-slate-300 dark:text-stone-700">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="text-sm font-bold text-slate-500 dark:text-stone-400 uppercase tracking-wide mb-3">Permisos Especiales (Operario)</h2>
    <div class="rounded-2xl border border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 divide-y divide-slate-100 dark:divide-stone-800">
        @foreach($special as $permission)
            <div class="flex items-center justify-between px-4 py-3">
                <div>
                    <p class="text-sm font-medium text-slate-700 dark:text-stone-200">{{ $permission->name }}</p>
                    <p class="text-xs font-mono text-slate-400 dark:text-stone-500">{{ $permission->slug }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-xs font-medium text-orange-600 dark:text-orange-400 hover:underline">Editar</a>
                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('¿Eliminar este permiso? Se quitará de todos los usuarios que lo tengan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection