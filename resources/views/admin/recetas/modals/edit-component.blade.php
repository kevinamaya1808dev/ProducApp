<div id="editComponentModal-{{ $component->id }}" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="relative w-full max-w-lg bg-white shadow-2xl rounded-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-xl font-bold text-slate-900">Editar Componente</h3>
                <button type="button" onclick="closeModal('editComponentModal-{{ $component->id }}')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('recipes.components.update', [$recipe->id, $component->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre</label>
                        <input type="text" name="name" required value="{{ $component->name }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none text-slate-700">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tipo</label>
                            <select name="component_type_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none text-slate-700 bg-white">
                                <option value="">Sin tipo</option>
                                @foreach($componentTypes as $type)
                                    <option value="{{ $type->id }}" @selected($component->component_type_id == $type->id)>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Unidad</label>
                            <select name="base_unit" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none text-slate-700 bg-white">
                                @foreach(['pzas','m','m²','cm','kg','g','l','ml','par'] as $unit)
                                    <option value="{{ $unit }}" @selected($component->base_unit === $unit)>{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Cantidad</label>
                        <input type="number" step="0.01" min="0.01" name="quantity" required value="{{ $component->pivot->quantity }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none text-slate-700">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                    <button type="button" onclick="closeModal('editComponentModal-{{ $component->id }}')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-600 rounded-lg hover:bg-orange-700 shadow-sm shadow-orange-600/30">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>