@props(['historial' => []])

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-6 space-y-4 relative">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-stone-800 dark:text-stone-100">Historial de Órdenes</h3>
            <p class="text-sm text-stone-500 dark:text-stone-400">Órdenes completadas recientes y vigentes</p>
        </div>
        
        <div class="flex items-center gap-2">
            <input type="text" id="buscarHistorial" placeholder="Buscar orden o producto..." onkeyup="filtrarHistorial()" class="w-full sm:w-64 bg-stone-50 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 text-xs rounded-xl px-3 py-2 focus:outline-none focus:border-orange-500">
            
            <button onclick="toggleModalFiltros(true)" class="p-2.5 bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 dark:hover:bg-stone-700 text-stone-600 dark:text-stone-300 rounded-xl transition-colors cursor-pointer" title="Filtros avanzados">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="tablaHistorial">
            <thead>
                <tr class="border-b border-stone-100 dark:border-stone-800 text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide">
                    <th class="py-3 px-2">Orden</th>
                    <th class="py-3 px-2">Producto</th>
                    <th class="py-3 px-2">Fecha</th>
                    <th class="py-3 px-2">Unidades</th>
                    <th class="py-3 px-2">Eficiencia</th>
                </tr>
            </thead>
            <tbody class="text-sm" id="cuerpoHistorial">
                @forelse($historial as $orden)
                    <tr class="fila-historial border-b border-stone-50 dark:border-stone-800/60 hover:bg-stone-50/50 dark:hover:bg-stone-800/40 transition-colors" 
                        data-orden="{{ $orden['orden'] }}"
                        data-producto="{{ strtolower($orden['producto']) }}"
                        data-fecha="{{ strtolower($orden['fecha']) }}"
                        data-eficiencia="{{ $orden['eficiencia'] }}">
                        <td class="py-4 px-2 font-bold text-xs">
                            <span class="bg-orange-50 dark:bg-orange-950/50 text-orange-700 dark:text-orange-400 border border-orange-100 dark:border-orange-900/50 px-2 py-1 rounded-md">#{{ $orden['orden'] }}</span>
                        </td>
                        <td class="py-4 px-2 font-semibold text-stone-700 dark:text-stone-200">{{ $orden['producto'] }}</td>
                        <td class="py-4 px-2 text-stone-500 dark:text-stone-400 text-xs">{{ $orden['fecha'] }}</td>
                        <td class="py-4 px-2 font-bold text-stone-800 dark:text-stone-100">{{ $orden['unidades'] }}</td>
                        <td class="py-4 px-2">
                            @php
                                $colorBarra = $orden['eficiencia'] >= 95 ? 'bg-emerald-500' : ($orden['eficiencia'] >= 80 ? 'bg-orange-500' : 'bg-amber-400');
                                $colorTexto = $orden['eficiencia'] >= 95 ? 'text-emerald-600 dark:text-emerald-400' : ($orden['eficiencia'] >= 80 ? 'text-orange-600 dark:text-orange-400' : 'text-amber-600 dark:text-amber-400');
                            @endphp
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-stone-100 dark:bg-stone-800 rounded-full h-1.5 overflow-hidden">
                                    <div class="{{ $colorBarra }} h-1.5 rounded-full" style="width: {{ min($orden['eficiencia'], 100) }}%"></div>
                                </div>
                                <span class="font-bold {{ $colorTexto }} text-xs">{{ $orden['eficiencia'] }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 px-2 text-center text-stone-400 dark:text-stone-500 text-sm">Aún no tienes órdenes completadas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<x-operario.modal-filtros />

<script>
    function toggleModalFiltros(show) {
        document.getElementById('modalFiltros').classList.toggle('hidden', !show);
    }

    function filtrarHistorial() {
        let input = document.getElementById('buscarHistorial').value.toLowerCase();
        document.querySelectorAll('.fila-historial').forEach(fila => {
            fila.style.display = fila.innerText.toLowerCase().includes(input) ? '' : 'none';
        });
    }

    function aplicarFiltrosModal() {
        let mes = document.getElementById('filtroMes').value.toLowerCase();
        let ordenamiento = document.getElementById('ordenamientoSelect').value;
        let tbody = document.getElementById('cuerpoHistorial');
        let filas = Array.from(document.querySelectorAll('.fila-historial'));

        filas.forEach(fila => {
            let fecha = fila.getAttribute('data-fecha');
            fila.style.display = (!mes || fecha.includes(mes)) ? '' : 'none';
        });

        filas.sort((a, b) => {
            if (ordenamiento === 'az') return a.dataset.producto.localeCompare(b.dataset.producto);
            if (ordenamiento === 'za') return b.dataset.producto.localeCompare(a.dataset.producto);
            if (ordenamiento === 'reciente') return b.dataset.orden.localeCompare(a.dataset.orden);
            if (ordenamiento === 'antigua') return a.dataset.orden.localeCompare(b.dataset.orden);
            return 0;
        });

        filas.forEach(fila => tbody.appendChild(fila));
        toggleModalFiltros(false);
    }

    function limpiarFiltrosModal() {
        document.getElementById('filtroMes').value = '';
        document.getElementById('ordenamientoSelect').value = 'reciente';
        document.querySelectorAll('.fila-historial').forEach(f => f.style.display = '');
        toggleModalFiltros(false);
    }
</script>