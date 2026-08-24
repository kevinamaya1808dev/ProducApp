@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-800 dark:text-stone-100">Registro de Producción</h1>
    <p class="text-stone-500 dark:text-stone-400 text-sm">Registra las unidades producidas en tu tarea activa</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Controles Izquierda -->
    <div class="lg:col-span-4 space-y-6">
        <x-operario.registro.registros-tarea-info :tarea="$tarea ?? null" />
        <x-operario.registro.registros-entrada-manual 
            :routeGuardar="$routeGuardar ?? '#'" 
            :ordenId="$ordenId ?? ($tarea->id ?? null)" 
            :subOrdenId="$subOrdenId ?? null"
            :orden="$tarea ?? null" 
        />
    </div>

    <!-- Tabla Historial (Derecha) -->
    <div class="lg:col-span-8">
        <x-operario.registro.registros-historial-tabla :registros="$registros ?? []" />
    </div>
</div>

@if($subOrdenId)
<div id="alertaSubordenModal" class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
        <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100">¡Ya casi terminan esta fase!</h3>
        <p id="alertaSubordenTexto" class="text-sm text-slate-500 dark:text-stone-400 mt-1"></p>
        <div id="alertaSubordenColegas" class="mt-4 space-y-1.5 text-left"></div>
        <button onclick="document.getElementById('alertaSubordenModal').classList.add('hidden')" class="mt-5 w-full py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-medium text-sm rounded-xl transition-colors">
            Entendido
        </button>
    </div>
</div>

<script>
(() => {
    const routeEstado = @json($routeEstadoSuborden);
    if (!routeEstado) return;

    let yaAvisado = false;

    const verificar = async () => {
        if (document.hidden) return;
        try {
            const res = await fetch(routeEstado, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store',
            });
            if (!res.ok) return;
            const data = await res.json();

            if (data.alerta_cercana && !yaAvisado) {
                yaAvisado = true;
                document.getElementById('alertaSubordenTexto').textContent =
                    `Quedan ${data.restantes} pieza(s) para completar esta suborden. Coordínense para terminar.`;

                const colegasEl = document.getElementById('alertaSubordenColegas');
                colegasEl.innerHTML = data.colegas.length
                    ? data.colegas.map(c => `
                        <div class="flex justify-between text-xs bg-slate-50 dark:bg-stone-800 rounded-lg px-3 py-2">
                            <span class="font-medium text-slate-700 dark:text-stone-300">${c.nombre}</span>
                            <span class="text-slate-400">${c.estacion} · ${c.aportadas} pzas</span>
                        </div>
                    `).join('')
                    : '<p class="text-xs text-slate-400 italic">No hay más operarios asignados a esta suborden.</p>';

                document.getElementById('alertaSubordenModal').classList.remove('hidden');
                document.getElementById('alertaSubordenModal').classList.add('flex');
            }

            if (!data.alerta_cercana) {
                yaAvisado = false;
            }
        } catch (e) {
            // Silencioso: no interrumpimos al operario por un fallo de red puntual
        }
    };

    verificar();
    setInterval(verificar, 5000);

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) verificar();
    });
})();
</script>
@endif
@endsection