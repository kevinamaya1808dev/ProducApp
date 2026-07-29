@props(['routeGuardar' => '#', 'ordenId' => null])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Registro Rápido</h3>

    @if($ordenId)
        <form action="{{ $routeGuardar }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="production_order_id" value="{{ $ordenId }}">
            <button type="submit" name="cantidad" value="1" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 rounded-xl shadow-sm transition-colors text-xl flex flex-col items-center cursor-pointer">
                <span>+1</span>
                <span class="text-xs font-normal text-blue-200">Unidad</span>
            </button>
            <button type="submit" name="cantidad" value="5" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-5 rounded-xl shadow-sm transition-colors text-xl flex flex-col items-center cursor-pointer">
                <span>+5</span>
                <span class="text-xs font-normal text-slate-400">Lote Pequeño</span>
            </button>
        </form>
    @else
        <p class="text-sm text-slate-400 text-center py-6">No tienes una orden activa para registrar producción.</p>
    @endif
</div>