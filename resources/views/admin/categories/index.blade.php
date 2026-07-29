@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 p-6 lg:p-8 w-full"> 
    
    <!-- Cabecera de la sección -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Categorías</h1>
            <p class="text-slate-500 text-sm mt-1">Gestión de categorías y clasificaciones &middot; {{ $categories->count() }} registros</p>
        </div>
        
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('gestionar-categorias'))
        <div>
            <button type="button" onclick="openModal('createCategoryModal')" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-blue-600/20 transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Categoría
            </button>
        </div>
        @endif
    </div>

    <!-- Contenedor Principal (Grid de dos columnas exactas) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Columna Izquierda: Buscador + Listado (4 columnas) -->
        <div class="lg:col-span-4 space-y-4">
            <!-- Buscador -->
            <form action="{{ route('categories.index') }}" method="GET" class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar categoría..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all shadow-sm">
            </form>

            <!-- Listado dinámico de Categorías -->
            <div class="space-y-3 max-h-[calc(100vh-250px)] overflow-y-auto pr-1">
                @forelse($categories as $category)
                    @php
                        $isActive = isset($activeCategory) && $activeCategory->id === $category->id;
                    @endphp
                    
                    <a href="{{ route('categories.index', ['category' => $category->id, 'search' => request('search')]) }}" 
                       class="block bg-white {{ $isActive ? 'border-l-4 border-blue-600 ring-1 ring-slate-900/5 shadow-sm' : 'hover:bg-slate-50/80 border border-slate-200/80' }} rounded-2xl p-4 transition-all">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-{{ $isActive ? 'bold text-slate-900' : 'semibold text-slate-800' }} text-sm leading-snug">
                                {{ $category->name }}
                            </h3>
                            <span class="text-[11px] font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                {{ $category->slug }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">
                            {{ $category->description ?? 'Sin descripción registrada.' }}
                        </p>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-slate-100">
                            <span>Productos: <strong class="text-slate-700 font-semibold">{{ $category->products_count ?? 0 }}</strong></span>
                            <span>Act. {{ $category->updated_at->format('d M Y') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center text-slate-500 text-sm shadow-sm">
                        No se encontraron categorías registradas.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Columna Derecha: Detalle de la Categoría Seleccionada (8 columnas) -->
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            @if(isset($activeCategory))
                <!-- Cabecera del Detalle -->
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-xl font-bold text-slate-900">{{ $activeCategory->name }}</h2>
                            <span class="bg-blue-50 text-blue-700 text-xs font-mono font-semibold px-2.5 py-1 rounded-lg border border-blue-200/60">
                                Slug: {{ $activeCategory->slug }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5">Creada el {{ $activeCategory->created_at->format('d M Y') }} &middot; Última actualización: {{ $activeCategory->updated_at->format('d M Y') }}</p>
                    </div>

                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('gestionar-categorias'))
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="openModal('editCategoryModal-{{ $activeCategory->id }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl transition-all shadow-md shadow-blue-600/25">
                            Editar Categoría
                        </button>
                        <button type="button" onclick="openModal('deleteCategoryModal-{{ $activeCategory->id }}')" class="px-4 py-2 bg-white border border-red-200 hover:bg-red-50 text-red-600 font-medium text-sm rounded-xl transition-all shadow-sm">
                            Eliminar
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Cuerpo de la Información -->
                <div class="p-6 space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Descripción</h4>
                        <p class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200/60">
                            {{ $activeCategory->description ?? 'Esta categoría no cuenta con una descripción detallada.' }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Productos Asociados Recientes</h4>
                        <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                                        <th class="py-3 px-4">SKU</th>
                                        <th class="py-3 px-4">Nombre del Producto</th>
                                        <th class="py-3 px-4">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    @forelse($activeCategory->products ?? [] as $product)
                                        <tr class="hover:bg-slate-50/60 transition-colors">
                                            <td class="py-3 px-4 font-mono text-xs text-slate-500 font-semibold">{{ $product->sku }}</td>
                                            <td class="py-3 px-4 font-semibold text-slate-800">{{ $product->name }}</td>
                                            <td class="py-3 px-4">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                    Activo
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-6 text-center text-slate-400 text-sm">
                                                No hay productos asignados a esta categoría.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-16 text-center text-slate-400 flex flex-col items-center justify-center min-h-[350px]">
                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-sm font-medium text-slate-500">Selecciona una categoría de la lista izquierda para ver sus detalles.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Inclusión de Modales Organizados --}}
@include('admin.categories.modals.create')
@if(isset($activeCategory))
    @include('admin.categories.modals.edit', ['category' => $activeCategory])
    @include('admin.categories.modals.delete', ['category' => $activeCategory])
@endif

<script>
    function openModal(modalId) {
        document.getElementById(modalId)?.classList.remove('hidden');
    }
    function closeModal(modalId) {
        document.getElementById(modalId)?.classList.add('hidden');
    }
</script>
@endsection