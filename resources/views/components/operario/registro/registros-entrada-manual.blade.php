@props(['routeGuardar' => '#', 'ordenId' => null])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide">
        Entrada Manual / Notas
    </h3>

    @if($ordenId)
        <form action="{{ $routeGuardar }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="production_order_id" value="{{ $ordenId }}">

            <!-- Cantidad -->
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Cantidad</label>
                <input type="number" 
                       name="cantidad" 
                       placeholder="Ej. 15" 
                       min="0" 
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
            </div>

            <!-- Nota / Observación -->
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Nota / Observación (Opcional)</label>
                <input type="text" 
                       name="nota" 
                       placeholder="Escribe una observación..." 
                       maxlength="255" 
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
            </div>

            <!-- Botón Único de Guardar -->
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 rounded-xl transition-colors cursor-pointer text-sm shadow-sm">
                Guardar Registro
            </button>
        </form>
    @else
        <p class="text-sm text-slate-400 text-center py-4">Sin orden activa asignada.</p>
    @endif
</div>