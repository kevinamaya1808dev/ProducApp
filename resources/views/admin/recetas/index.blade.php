@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 p-6 lg:p-8 w-full">

    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-4 py-3 rounded-xl">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Recetas</h1>
            <p class="text-slate-500 text-sm mt-1">Gestión de recetas y fichas técnicas &middot; {{ $recipes->count() }} registros</p>
        </div>

        @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('gestionar-recetas'))
        <div class="flex items-center gap-2">
            <a href="{{ route('component-types.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Tipos de Componente
            </a>
            <button type="button" onclick="openModal('createRecipeModal')" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-blue-600/20 transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nueva Receta
            </button>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Columna Izquierda -->
        <div class="lg:col-span-4 space-y-4">
            <form action="{{ route('recipes.index') }}" method="GET" class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar receta..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all shadow-sm">
            </form>

            <div class="space-y-3 max-h-[calc(100vh-250px)] overflow-y-auto pr-1">
                @forelse($recipes as $recipe)
                    @php $isActive = isset($activeRecipe) && $activeRecipe->id === $recipe->id; @endphp
                    <a href="{{ route('recipes.index', ['recipe' => $recipe->id, 'search' => request('search')]) }}"
                       class="block bg-white {{ $isActive ? 'border-l-4 border-blue-600 ring-1 ring-slate-900/5 shadow-sm' : 'hover:bg-slate-50/80 border border-slate-200/80' }} rounded-2xl p-4 transition-all">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-{{ $isActive ? 'bold text-slate-900' : 'semibold text-slate-800' }} text-sm leading-snug">{{ $recipe->name }}</h3>
                            <span class="text-[11px] font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200 shrink-0">REC-00{{ $recipe->id }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">{{ $recipe->instructions ?? 'Sin instrucciones registradas.' }}</p>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-slate-100">
                            <span>Producto: <strong class="text-slate-700 font-semibold">{{ $recipe->product->name ?? 'N/A' }}</strong></span>
                            <span>Act. {{ $recipe->updated_at->format('d M Y') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center text-slate-500 text-sm shadow-sm">No se encontraron recetas registradas.</div>
                @endforelse
            </div>
        </div>

        <!-- Columna Derecha: Detalle -->
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            @if(isset($activeRecipe))
                @php
                    $sinTipoClasses = 'bg-slate-100 text-slate-500 border-slate-200';
                @endphp

                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-xl font-bold text-slate-900">{{ $activeRecipe->name }}</h2>
                            <span class="bg-blue-50 text-blue-700 text-xs font-mono font-semibold px-2.5 py-1 rounded-lg border border-blue-200/60">REC-00{{ $activeRecipe->id }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5">Modificado: {{ $activeRecipe->updated_at->translatedFormat('d M Y') }}</p>
                    </div>
                </div>

                <div class="px-6 pt-5 flex flex-wrap items-center gap-2">
                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('gestionar-recetas'))
                        <button type="button" onclick="openModal('addComponentModal')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-sm rounded-xl border border-emerald-200 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Componente
                        </button>
                        <button type="button" onclick="openModal('editRecipeModal-{{ $activeRecipe->id }}')" class="px-4 py-2 bg-white border border-blue-200 hover:bg-blue-50 text-blue-600 font-semibold text-sm rounded-xl transition-all">
                            Editar receta
                        </button>
                        <form action="{{ route('recipes.duplicate', $activeRecipe->id) }}" method="POST" onsubmit="return confirm('¿Duplicar esta receta junto con sus componentes?');">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold text-sm rounded-xl transition-all">
                                Duplicar
                            </button>
                        </form>
                        <button type="button" onclick="openModal('deleteRecipeModal-{{ $activeRecipe->id }}')" class="px-4 py-2 bg-white border border-red-200 hover:bg-red-50 text-red-600 font-semibold text-sm rounded-xl transition-all">
                            Eliminar
                        </button>
                    @endif
                </div>

                @if($activeRecipe->instructions)
                    <div class="mx-6 mt-5 flex items-start gap-2 bg-amber-50 border border-amber-200/60 text-amber-800 text-xs px-4 py-3 rounded-xl">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span class="whitespace-pre-line">{{ $activeRecipe->instructions }}</span>
                    </div>
                @endif

                <div class="p-6">
                    <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                                    <th class="py-3 px-4 w-12">#</th>
                                    <th class="py-3 px-4">Componente</th>
                                    <th class="py-3 px-4">Tipo</th>
                                    <th class="py-3 px-4">Cantidad</th>
                                    <th class="py-3 px-4">Unidad</th>
                                    <th class="py-3 px-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($activeRecipe->components as $component)
                                    <tr class="hover:bg-slate-50/60 transition-colors group">
                                        <td class="py-3 px-4 text-xs font-mono text-slate-400 font-semibold">{{ sprintf('%02d', $loop->iteration) }}</td>
                                        <td class="py-3 px-4 font-semibold text-slate-800">{{ $component->name }}</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $component->componentType?->badgeClasses() ?? $sinTipoClasses }}">
                                                {{ $component->componentType->name ?? 'Sin tipo' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 font-bold text-slate-800">{{ rtrim(rtrim(number_format($component->pivot->quantity, 2), '0'), '.') }}</td>
                                        <td class="py-3 px-4 text-slate-500">{{ $component->base_unit }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center justify-end gap-1">
                                                <button type="button" onclick="openModal('editComponentModal-{{ $component->id }}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                <button type="button" onclick="openModal('deleteComponentModal-{{ $component->id }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="py-8 text-center text-slate-400 text-sm">No hay componentes asignados a esta receta.</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if($activeRecipe->components->isNotEmpty())
                            <div class="flex items-center justify-between px-4 py-3 bg-slate-50/50 border-t border-slate-100 flex-wrap gap-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @foreach($activeRecipe->components->groupBy(fn($c) => $c->componentType->name ?? 'Sin tipo') as $tipo => $items)
                                        @php $badge = $items->first()->componentType?->badgeClasses() ?? $sinTipoClasses; @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badge }}">
                                            {{ $tipo }} <span class="opacity-70">{{ $items->count() }}</span>
                                        </span>
                                    @endforeach
                                </div>
                                <span class="text-xs text-slate-400">{{ $activeRecipe->components->count() }} componentes</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-16 text-center text-slate-400 flex flex-col items-center justify-center min-h-[350px]">
                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="text-sm font-medium text-slate-500">Selecciona una receta de la lista izquierda para ver sus detalles.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@include('admin.recetas.modals.create')
@if(isset($activeRecipe))
    @include('admin.recetas.modals.edit', ['recipe' => $activeRecipe])
    @include('admin.recetas.modals.delete', ['recipe' => $activeRecipe])
    @include('admin.recetas.modals.add-component', ['recipe' => $activeRecipe])
    @foreach($activeRecipe->components as $component)
        @include('admin.recetas.modals.edit-component', ['recipe' => $activeRecipe, 'component' => $component])
        @include('admin.recetas.modals.delete-component', ['recipe' => $activeRecipe, 'component' => $component])
    @endforeach
@endif

<script>
    function openModal(modalId) { document.getElementById(modalId)?.classList.remove('hidden'); }
    function closeModal(modalId) { document.getElementById(modalId)?.classList.add('hidden'); }

    @if ($errors->any() && old('form_source'))
        document.addEventListener('DOMContentLoaded', function () { openModal(@json(old('form_source'))); });
    @endif
</script>
@endsection