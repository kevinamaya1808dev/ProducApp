<div id="createOrderModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeCreateModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-slate-200">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Crear Nueva Orden</h3>
                    <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Número de Orden <span class="text-red-500">*</span></label>
                        <input type="text" name="order_number" required placeholder="Ej: ORD-2026-0001" value="{{ old('order_number') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-orange-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Producto a fabricar <span class="text-red-500">*</span></label>
                        <select name="product_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-orange-500">
                            <option value="">Seleccione un producto...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Cantidad (Pzas) <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" min="1" required placeholder="Ej. 500" value="{{ old('quantity') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Prioridad <span class="text-red-500">*</span></label>
                            <select name="priority" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-orange-500">
                                <option value="low">Baja</option>
                                <option value="medium" selected>Media</option>
                                <option value="high">Alta</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Operario Asignado <span class="text-red-500">*</span></label>
                            <select name="user_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-orange-500">
                                <option value="">Sin asignar</option>
                                @foreach($operarios as $operario)
                                    <option value="{{ $operario->id }}" {{ old('user_id') == $operario->id ? 'selected' : '' }}>{{ $operario->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Estado <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-orange-500">
                                <option value="pending" selected>Pendiente</option>
                                <option value="in_progress">En Progreso</option>
                                <option value="completed">Completada</option>
                                <option value="cancelled">Cancelada</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Estación</label>
                            <input type="text" name="estacion" placeholder="Ej: Estación 4" value="{{ old('estacion') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha Límite</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-orange-500">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-100">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 shadow-sm">Generar Orden</button>
                </div>
            </form>
        </div>
    </div>
</div>