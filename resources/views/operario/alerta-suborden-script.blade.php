{{--
    Script de alerta de suborden.
    NOTA: Este bloque estaba duplicado exactamente en dos archivos:
      - resources/views/operario/registro.blade.php
      - resources/views/components/operario/modals/alert.blade.php
    Se unifica aquí; ambas vistas deben hacer @include de este partial
    y eliminar sus bloques <script> propios.
--}}
<script>
(() => {
    const url = @json($routeEstadoSuborden ?? null);
    if (!url) return;

    let avisado = false;

    const verificar = async () => {
        if (document.hidden) return;
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, cache: 'no-store' });
            if (!res.ok) return;
            const d = await res.json();

            if (d.alerta_cercana && !avisado) {
                avisado = true;
                document.getElementById('alertaSubordenTexto').textContent =
                    `Quedan ${d.restantes} pieza(s) para completar esta suborden. Coordínense para terminar.`;

                document.getElementById('alertaSubordenColegas').innerHTML = d.colegas.length
                    ? d.colegas.map(c => `
                        <div class="flex justify-between text-xs bg-slate-50 dark:bg-stone-800 rounded-lg px-3 py-2">
                            <span class="font-medium text-slate-700 dark:text-stone-300">${c.nombre}</span>
                            <span class="text-slate-400">${c.estacion} · ${c.aportadas} pzas</span>
                        </div>`).join('')
                    : '<p class="text-xs text-slate-400 italic">No hay más operarios asignados a esta suborden.</p>';

                const modal = document.getElementById('alertaSubordenModal');
                modal.classList.replace('hidden', 'flex');
            }

            if (!d.alerta_cercana) avisado = false;
        } catch { /* silencioso */ }
    };

    verificar();
    setInterval(verificar, 5000);
    document.addEventListener('visibilitychange', () => { if (!document.hidden) verificar(); });
})();
</script>