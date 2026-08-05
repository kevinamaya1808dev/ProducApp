<div id="createRecipeModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="relative w-full max-w-lg bg-white dark:bg-stone-900 shadow-2xl rounded-2xl border border-slate-200 dark:border-stone-800">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-stone-800">
                <h3 class="text-xl font-bold text-slate-900 dark:text-stone-100">Nueva Receta</h3>
                <button type="button" onclick="closeModal('createRecipeModal')" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('recipes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="form_source" value="createRecipeModal">
                <div class="px-6 py-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Nombre de la receta <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="Ej: Camisa manga larga - Talla M" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 focus:border-orange-500 dark:focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500">
                        @error('name') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Producto asociado <span class="text-red-500">*</span></label>
                        <select name="product_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 focus:border-orange-500 dark:focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none text-slate-700 dark:text-stone-100">
                            <option value="" class="dark:bg-stone-800">Selecciona un producto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" class="dark:bg-stone-800" @selected(old('product_id') == $product->id)>{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Instrucciones / Procedimiento</label>
                        <textarea name="instructions" rows="4" placeholder="Describe el procedimiento..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 focus:border-orange-500 dark:focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 resize-none">{{ old('instructions') }}</textarea>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-stone-500">Podrás agregar los componentes (BOM) desde la tabla de la receta una vez creada.</p>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 dark:border-stone-800 flex items-center justify-end gap-3 bg-slate-50 dark:bg-stone-800/50 rounded-b-2xl">
                    <button type="button" onclick="closeModal('createRecipeModal')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-stone-300 bg-white dark:bg-stone-800 border border-slate-300 dark:border-stone-700 rounded-lg hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-600 rounded-lg hover:bg-orange-700 shadow-sm shadow-orange-600/30 transition-colors">Guardar Receta</button>
                </div>
            </form>
        </div>
    </div>
</div>