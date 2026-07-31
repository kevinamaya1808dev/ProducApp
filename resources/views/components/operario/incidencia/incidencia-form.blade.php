@props(['ordenes'])

<div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-6">
    <h3 class="text-lg font-bold text-stone-800 mb-5">Reportar Incidencia</h3>

    <form action="{{ route('operario.incidencias.guardar') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="text-xs font-bold text-stone-400 uppercase tracking-wide block mb-1">Orden relacionada</label>
            <select name="production_order_id" required class="w-full border border-amber-200 bg-white rounded-xl px-3 py-2 text-sm text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                <option value="">Selecciona una orden</option>
                @foreach($ordenes as $orden)
                    <option value="{{ $orden->id }}">#{{ $orden->order_number }} — {{ $orden->product->name ?? 'Sin producto' }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-xs font-bold text-stone-400 uppercase tracking-wide block mb-1">Importancia</label>
            <select name="importance" required class="w-full border border-amber-200 bg-white rounded-xl px-3 py-2 text-sm text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                <option value="baja">Baja</option>
                <option value="media" selected>Media</option>
                <option value="alta">Alta</option>
            </select>
        </div>

        <div>
            <label class="text-xs font-bold text-stone-400 uppercase tracking-wide block mb-1">Título</label>
            <input type="text" name="title" required maxlength="255" placeholder="Ej. Falla de maquinaria"
                class="w-full border border-amber-200 bg-white rounded-xl px-3 py-2 text-sm text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
        </div>

        <div>
            <label class="text-xs font-bold text-stone-400 uppercase tracking-wide block mb-1">Descripción</label>
            <textarea name="description" required rows="4" placeholder="Describe la incidencia..."
                class="w-full border border-amber-200 bg-white rounded-xl px-3 py-2 text-sm text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"></textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition-colors text-sm cursor-pointer">
                Enviar Reporte
            </button>
            <a href="{{ route('operario.incidencias') }}" class="text-stone-500 hover:text-stone-800 font-medium py-2.5 px-5 rounded-xl transition-colors text-sm flex items-center justify-center">
                Cancelar
            </a>
        </div>
    </form>
</div>