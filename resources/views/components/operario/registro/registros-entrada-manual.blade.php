@props(['routeGuardar' => '#', 'ordenId' => null])

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-5 space-y-4">
    <h3 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide">
        Entrada Manual / Notas
    </h3>

    @if($ordenId)
        <form action="{{ $routeGuardar }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="production_order_id" value="{{ $ordenId }}">

            <!-- Cantidad -->
            <div>
                <label class="block text-xs font-medium text-stone-500 dark:text-stone-400 mb-1">Cantidad</label>
                <input type="number" 
                       name="cantidad" 
                       placeholder="Ej. 15" 
                       min="0" 
                       class="w-full bg-stone-50 dark:bg-stone-800 border border-amber-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-orange-500 dark:focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all">
            </div>

            <!-- Nota / Observación -->
            <div>
                <label class="block text-xs font-medium text-stone-500 dark:text-stone-400 mb-1">Nota / Observación (Opcional)</label>
                <input type="text" 
                       name="nota" 
                       placeholder="Escribe una observación..." 
                       maxlength="255" 
                       class="w-full bg-stone-50 dark:bg-stone-800 border border-amber-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-orange-500 dark:focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all">
            </div>

            <!-- Botón Único de Guardar -->
            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 rounded-xl transition-colors cursor-pointer text-sm shadow-sm">
                Guardar Registro
            </button>
        </form>
    @else
        <p class="text-sm text-stone-400 dark:text-stone-500 text-center py-4">Sin orden activa asignada.</p>
    @endif
</div>