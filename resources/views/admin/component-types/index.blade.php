@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 p-6 lg:p-8 w-full max-w-3xl mx-auto">

    <a href="{{ route('recipes.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Volver a Recetas
    </a>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-4 py-3 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tipos de Componente</h1>
            <p class="text-slate-500 text-sm mt-1">Catálogo usado para clasificar los componentes de tus recetas (BOM).</p>
        </div>
        <button type="button" onclick="openModal('createTypeModal')" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-blue-600/20 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Tipo
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                    <th class="py-3 px-5">Tipo</th>
                    <th class="py-3 px-5">Componentes</th>
                    <th class="py-3 px-5 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($componentTypes as $type)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3 px-5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $type->badgeClasses() }}">{{ $type->name }}</span>
                        </td>
                        <td class="py-3 px-5 text-slate-500">{{ $type->components_count }}</td>
                        <td class="py-3 px-5">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="openModal('editTypeModal-{{ $type->id }}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button type="button" onclick="openModal('deleteTypeModal-{{ $type->id }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <div id="deleteTypeModal-{{ $type->id }}" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
                        <div class="flex items-center justify-center min-h-screen px-4">
                            <div class="relative w-full max-w-sm bg-white shadow-2xl rounded-2xl p-6 text-center">
                                <div class="mx-auto w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Eliminar Tipo</h3>
                                <p class="text-sm text-slate-500 mb-6">¿Eliminar "{{ $type->name }}"? @if($type->components_count > 0)<br><span class="text-red-500 font-medium">Tiene {{ $type->components_count }} componente(s) asignados.</span>@endif</p>
                                <form action="{{ route('component-types.destroy', $type->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="flex gap-3">
                                        <button type="button" onclick="closeModal('deleteTypeModal-{{ $type->id }}')" class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl">Cancelar</button>
                                        <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl">Eliminar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="editTypeModal-{{ $type->id }}" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
                        <div class="flex items-center justify-center min-h-screen px-4 py-8">
                            <div class="relative w-full max-w-md bg-white shadow-2xl rounded-2xl">
                                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                                    <h3 class="text-lg font-bold text-slate-900">Editar Tipo</h3>
                                    <button type="button" onclick="closeModal('editTypeModal-{{ $type->id }}')" class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <form action="{{ route('component-types.update', $type->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="px-6 py-6 space-y-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                                            <input type="text" name="name" required value="{{ $type->name }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-slate-700">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Color</label>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach(\App\Models\ComponentType::colorPalette() as $key => $classes)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="color" value="{{ $key }}" class="peer sr-only" @checked($type->color === $key)>
                                                        <span class="block w-8 h-8 rounded-full {{ \App\Models\ComponentType::swatchDot($key) }} ring-offset-2 peer-checked:ring-2 ring-slate-900"></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50 rounded-b-2xl">
                                        <button type="button" onclick="closeModal('editTypeModal-{{ $type->id }}')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Cancelar</button>
                                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="3" class="py-8 text-center text-slate-400 text-sm">Aún no hay tipos de componente registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="createTypeModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="relative w-full max-w-md bg-white shadow-2xl rounded-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Nuevo Tipo de Componente</h3>
                <button type="button" onclick="closeModal('createTypeModal')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('component-types.store') }}" method="POST">
                @csrf
                <div class="px-6 py-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Ej: Empaque" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-slate-700">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Color</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(\App\Models\ComponentType::colorPalette() as $key => $classes)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $key }}" class="peer sr-only" @checked($key === 'slate')>
                                    <span class="block w-8 h-8 rounded-full {{ \App\Models\ComponentType::swatchDot($key) }} ring-offset-2 peer-checked:ring-2 ring-slate-900"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50 rounded-b-2xl">
                    <button type="button" onclick="closeModal('createTypeModal')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id)?.classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id)?.classList.add('hidden'); }
</script>
@endsection