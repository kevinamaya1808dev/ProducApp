<div id="editOrderModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-slate-200">
            <form id="editOrderForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Editar Orden de Producción</h3>
                    <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Número de Orden <span class="text-red-500">*</span></label>
                        <input type="text" id="editOrderNumber" name="order_number" required placeholder="Ej: ORD-2026-0001" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Producto a fabricar <span class="text-red-500">*</span></label>
                        <select id="editProductId" name="product_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                            <option value="">Seleccione un producto...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Cantidad (Pzas) <span class="text-red-500">*</span></label>
                            <input type="number" id="editQuantity" name="quantity" min="1" required placeholder="Ej. 500" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Prioridad <span class="text-red-500">*</span></label>
                            <select id="editPriority" name="priority" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                <option value="low">Baja</option>
                                <option value="medium">Media</option>
                                <option value="high">Alta</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Operario Asignado <span class="text-red-500">*</span></label>
                            <select id="editUserId" name="user_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                <option value="">Sin asignar</option>
                                @foreach($operarios as $operario)
                                    <option value="{{ $operario->id }}">{{ $operario->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Estado <span class="text-red-500">*</span></label>
                            <select id="editStatus" name="status" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                <option value="pending">Pendiente</option>
                                <option value="in_progress">En Progreso</option>
                                <option value="completed">Completada</option>
                                <option value="cancelled">Cancelada</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Estación</label>
                            <input type="text" id="editEstacion" name="estacion" placeholder="Ej: Estación 4" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha Inicio</label>
                            <input type="date" id="editStartDate" name="start_date" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha Límite</label>
                            <input type="date" id="editEndDate" name="end_date" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-100">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm">Actualizar Orden</button>
                </div>
            </form>
        </div>
    </div>
</div>