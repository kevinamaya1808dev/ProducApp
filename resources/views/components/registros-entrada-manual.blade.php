@props(['routeGuardar' => '#', 'ordenId' => null])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Entrada Manual</h3>

    @if($ordenId)
        <form action="{{ $routeGuardar }}" method="POST" class="flex space-x-3">
            @csrf
            <input type="hidden" name="production_order_id" value="{{ $ordenId }}">
            <input type="number" name="cantidad" placeholder="Cantidad" min="1" class="flex-grow bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
            <button type="submit" class="bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold px-6 rounded-xl transition-colors cursor-pointer">
                OK
            </button>
        </form>
    @else
        <p class="text-sm text-slate-400 text-center py-4">Sin orden activa asignada.</p>
    @endif
</div>