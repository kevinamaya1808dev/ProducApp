<div id="editCategoryModal-{{ $category->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden border border-slate-100">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-lg">Editar Categoría: {{ $category->name }}</h3>
            <button type="button" onclick="closeModal('editCategoryModal-{{ $category->id }}')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nombre de la Categoría</label>
                    <input type="text" name="name" value="{{ $category->name }}" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Descripción</label>
                    <textarea name="description" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600">{{ $category->description }}</textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('editCategoryModal-{{ $category->id }}')" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-50">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20">Actualizar Categoría</button>
            </div>
        </form>
    </div>
</div>