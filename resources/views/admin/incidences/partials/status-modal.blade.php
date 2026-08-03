<!-- MODAL 2: CAMBIAR ESTADO Y PRIORIDAD -->
<div id="statusModal" class="fixed inset-0 z-50 hidden bg-stone-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-stone-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-stone-800">Actualizar Incidencia</h3>
            <button onclick="closeModal('statusModal')" class="text-stone-400 hover:text-stone-600 text-xl font-bold">&times;</button>
        </div>

        <form id="statusForm" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-semibold text-stone-600 mb-1">Estado actual</label>
                <select id="modalStatusSelect" name="status" class="w-full text-sm rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500">
                    <option value="pendiente">Pendiente</option>
                    <option value="en_proceso">En Proceso</option>
                    <option value="resuelta">Resuelta / Completada</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-600 mb-1">Comentario o Nota sobre el cambio</label>
                <textarea name="comment" rows="2" placeholder="Agrega un comentario sobre esta actualización..." class="w-full text-sm rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="submit" class="w-full py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-xl shadow-sm">Actualizar Estado</button>
            </div>
        </form>

        @if(Auth::user()->role !== 'operario')
            <hr class="my-4 border-stone-200">
            <form id="importanceForm" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-semibold text-stone-600 mb-1">Cambiar Prioridad (Solo Admin/Supervisor)</label>
                    <select id="modalImportanceSelect" name="importance" class="w-full text-sm rounded-xl border-stone-200 focus:border-orange-500 focus:ring-orange-500">
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2 bg-stone-800 hover:bg-stone-900 text-white text-xs font-medium rounded-xl">Cambiar Prioridad</button>
            </form>
        @endif
    </div>
</div>