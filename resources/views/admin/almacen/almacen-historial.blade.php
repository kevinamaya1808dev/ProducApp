@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.almacen.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 dark:text-stone-400 hover:text-orange-600 dark:hover:text-orange-400 mb-1 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Almacén
                </a>
                <h2 class="font-semibold text-xl text-slate-800 dark:text-stone-200 leading-tight">
                    Historial General de Stock
                </h2>
                <p class="text-sm text-slate-500 dark:text-stone-400 mt-0.5">Todas las entradas de material registradas, de más reciente a más antigua.</p>
            </div>
        </div>

        <!-- Filtro por material -->
        <form method="GET" action="{{ route('admin.almacen.historial') }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-3 bg-white dark:bg-stone-800 p-4 rounded-xl border border-slate-200 dark:border-stone-700">
            <label for="material_id" class="text-xs font-semibold text-slate-600 dark:text-stone-400 whitespace-nowrap">Filtrar por material</label>
            <select name="material_id" id="material_id" onchange="this.form.submit()" class="w-full sm:w-64 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-900 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-stone-200">
                <option value="">Todos los materiales</option>
                @foreach($materials as $mat)
                    <option value="{{ $mat->id }}" {{ request('material_id') == $mat->id ? 'selected' : '' }}>{{ $mat->name }}</option>
                @endforeach
            </select>
            @if(request()->filled('material_id'))
                <a href="{{ route('admin.almacen.historial') }}" class="text-xs font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">Quitar filtro</a>
            @endif
        </form>

        <!-- Tabla de historial -->
        <div class="bg-white dark:bg-stone-900 rounded-2xl border border-slate-200 dark:border-stone-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-stone-800/50 border-b border-slate-100 dark:border-stone-800 text-xs font-bold text-slate-500 dark:text-stone-400 uppercase tracking-wider">
                            <th class="p-4">Fecha</th>
                            <th class="p-4">Material</th>
                            <th class="p-4">Cantidad Agregada</th>
                            <th class="p-4">Stock Resultante</th>
                            <th class="p-4">Registrado por</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-stone-800 text-sm">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-stone-800/40 transition-colors">
                                <td class="p-4 text-slate-500 dark:text-stone-400 whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="p-4 font-medium text-slate-800 dark:text-stone-200">
                                    {{ $log->material->name ?? 'Material eliminado' }}
                                    @if($log->material)
                                        <span class="block text-xs font-mono text-slate-400">{{ $log->material->sku }}</span>
                                    @endif
                                </td>
                                <td class="p-4 font-bold text-emerald-600 dark:text-emerald-400">
                                    +{{ $log->quantity_added }} {{ $log->material->unit ?? '' }}
                                </td>
                                <td class="p-4 font-bold text-slate-700 dark:text-stone-200">
                                    {{ $log->stock_resultante }} {{ $log->material->unit ?? '' }}
                                </td>
                                <td class="p-4 text-slate-600 dark:text-stone-300">
                                    {{ $log->user->name ?? 'Usuario eliminado' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 dark:text-stone-500">
                                    @if(request()->filled('material_id'))
                                        No hay entradas de stock registradas para este material.
                                    @else
                                        Aún no se ha registrado ninguna entrada de stock.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->hasPages())
            <div>
                {{ $logs->links() }}
            </div>
        @endif

    </div>
</div>
@endsection