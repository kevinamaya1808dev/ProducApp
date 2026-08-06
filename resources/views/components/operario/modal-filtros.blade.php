<div id="modalFiltros" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-stone-900 border border-amber-100 dark:border-stone-800 rounded-2xl p-6 w-full max-w-md shadow-xl space-y-4 m-4">
        <div class="flex justify-between items-center">
            <h3 class="text-base font-bold text-stone-800 dark:text-stone-100">Filtros Avanzados</h3>
            <button onclick="toggleModalFiltros(false)" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="space-y-4 text-xs">
            <!-- Mes -->
            <div>
                <label class="block font-medium text-stone-500 dark:text-stone-400 mb-1">Filtrar por Mes</label>
                <select id="filtroMes" class="w-full bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 rounded-xl px-3 py-2.5 focus:outline-none focus:border-orange-500">
                    <option value="">Todos los meses</option>
                    <option value="ago">Agosto</option>
                    <!-- Agrega el resto de meses -->
                </select>
            </div>
            <!-- Ordenamiento -->
            <div>
                <label class="block font-medium text-stone-500 dark:text-stone-400 mb-1">Ordenamiento</label>
                <select id="ordenamientoSelect" class="w-full bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 rounded-xl px-3 py-2.5 focus:outline-none focus:border-orange-500">
                    <option value="reciente">Más reciente a la más antigua</option>
                    <option value="antigua">Más antigua a la más reciente</option>
                    <option value="az">A - Z</option>
                    <option value="za">Z - A</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <button onclick="toggleModalFiltros(false)" class="px-4 py-2.5 text-xs font-semibold rounded-xl bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-300 hover:bg-stone-200 cursor-pointer">Cerrar</button>
            <button onclick="aplicarFiltrosModal()" class="px-4 py-2.5 text-xs font-semibold rounded-xl bg-orange-600 hover:bg-orange-700 text-white cursor-pointer transition-colors">Aplicar</button>
        </div>
    </div>
</div>