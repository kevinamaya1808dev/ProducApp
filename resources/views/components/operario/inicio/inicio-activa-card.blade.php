@props(['ordenActiva', 'piezasOrdenActiva'])

@if($ordenActiva)
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-blue-600 p-4 flex justify-between items-center">
        <h2 class="text-white font-bold text-sm tracking-wide uppercase">Tarea Activa</h2>
        <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Alta Prioridad</span>
    </div>
    
    <div class="p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xs font-semibold text-slate-400 uppercase">Orden</span>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">#{{ $ordenActiva->order_number }}</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $ordenActiva->product->name ?? 'Producto sin nombre' }}</h3>
                <p class="text-slate-500 text-sm">{{ $ordenActiva->product->description ?? '' }}</p>
            </div>

            <!-- Asignar / editar estación de la orden activa -->
            <div class="text-right">
                @if($ordenActiva->estacion)
                    <div class="text-sm text-slate-400 font-medium mb-1">Estación {{ $ordenActiva->estacion }}</div>
                @else
                    <div class="text-sm text-orange-500 font-medium mb-1">Sin estación asignada</div>
                @endif
                <form action="{{ route('operario.estacion.actualizar', $ordenActiva->id) }}" method="POST" class="flex items-center gap-1">
                    @csrf
                    @method('PATCH')
                    <input type="text" name="estacion" value="{{ $ordenActiva->estacion }}" placeholder="Ej. 4"
                        class="w-16 text-xs border border-slate-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-700">Guardar</button>
                </form>
            </div>
        </div>

        <!-- Barra de Avance Dinámica -->
        <div class="mb-8">
            <div class="flex justify-between items-end mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Avance de Hoy</span>
                <div class="text-right">
                    <span class="text-3xl font-bold text-blue-600">{{ $piezasOrdenActiva }}</span>
                    <span class="text-slate-400 font-medium">/{{ $ordenActiva->quantity }} unidades</span>
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-3 mb-2">
                @php
                    $porcentajeOrden = $ordenActiva->quantity > 0
                        ? min(($piezasOrdenActiva / $ordenActiva->quantity) * 100, 100)
                        : 0;
                @endphp
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $porcentajeOrden }}%"></div>
            </div>
            <div class="flex justify-between text-xs font-bold">
                <span class="text-slate-400">0%</span>
                <span class="text-blue-600">{{ round($porcentajeOrden) }}% completado</span>
                <span class="text-slate-400">100%</span>
            </div>
        </div>

        <!-- Registro Rápido -->
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wide block mb-3">Registro Rápido</span>
            <form action="{{ route('operario.registro.guardar') }}" method="POST" class="grid grid-cols-3 gap-4">
                @csrf
                <input type="hidden" name="production_order_id" value="{{ $ordenActiva->id }}">
                
                <button type="submit" name="cantidad" value="1" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-sm transition-colors text-lg cursor-pointer">
                    +1 Unidad
                </button>
                
                <button type="submit" name="cantidad" value="5" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 rounded-xl shadow-sm transition-colors text-lg cursor-pointer">
                    +5 Lote Pequeño
                </button>
                
                <a href="{{ route('operario.incidencias') }}" class="bg-orange-50 hover:bg-orange-100 text-orange-600 border border-orange-200 font-bold py-4 rounded-xl shadow-sm transition-colors text-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Reportar
                </a>
            </form>
        </div>
    </div>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center text-slate-400">
    No tienes ninguna orden de producción activa asignada por ahora.
</div>
@endif