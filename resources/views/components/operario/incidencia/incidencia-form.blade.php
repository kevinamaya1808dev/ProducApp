@props(['ordenes'])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <h3 class="text-lg font-bold text-slate-800 mb-4">Reportar Incidencia</h3>

    <form action="{{ route('operario.incidencias.guardar') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="text-xs font-bold text-slate-400 uppercase tracking-wide block mb-1">Orden relacionada</label>
            <select name="production_order_id" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="">Selecciona una orden</option>
                @foreach($ordenes as $orden)
                    <option value="{{ $orden->id }}">#{{ $orden->order_number }} — {{ $orden->product->name ?? 'Sin producto' }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-xs font-bold text-slate-400 uppercase tracking-wide block mb-1">Título</label>
            <input type="text" name="title" required maxlength="255" placeholder="Ej. Falla de maquinaria"
                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>

        <div>
            <label class="text-xs font-bold text-slate-400 uppercase tracking-wide block mb-1">Descripción</label>
            <textarea name="description" required rows="4" placeholder="Describe la incidencia..."
                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"></textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition-colors">
                Enviar Reporte
            </button>
            <a href="{{ route('operario.incidencias') }}" class="text-slate-500 hover:text-slate-700 font-medium py-2.5 px-5 rounded-xl transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>