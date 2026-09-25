import './bootstrap';

/**
 * Polling inteligente: sincroniza admin/operario solo cuando detecta cambios.
 * - Sin peticiones solapadas, pausa en pestaña oculta, backoff ante errores,
 *   AbortController con timeout, sin recargar mientras el usuario escribe.
 */
document.addEventListener('DOMContentLoaded', () => {
    const INTERVALO_BASE  = 4_000;
    const INTERVALO_MAX   = 60_000;
    const TIMEOUT         = 8_000;

    const ruta = window.location.pathname;
    if (!ruta.includes('/operario') && !ruta.includes('/admin')) return;

    let firma         = null;
    let enCurso       = false;
    let intervalo     = INTERVALO_BASE;
    let timer         = null;

    const escribiendo = () => {
        const el = document.activeElement;
        return el && ['INPUT', 'TEXTAREA', 'SELECT'].includes(el.tagName) || el?.isContentEditable;
    };

    const fetchTimeout = (url, opts = {}) => {
        const ctrl = new AbortController();
        const id   = setTimeout(() => ctrl.abort(), TIMEOUT);
        return fetch(url, { ...opts, signal: ctrl.signal }).finally(() => clearTimeout(id));
    };

    const firma_de = html => {
        const doc  = new DOMParser().parseFromString(html, 'text/html');
        const nodo = doc.querySelector('table') ?? doc.querySelector('.grid') ?? doc.body;
        return nodo.innerText.trim().replace(/\s+/g, ' ');
    };

    const opciones = { headers: { 'X-Requested-With': 'XMLHttpRequest' }, cache: 'no-store', credentials: 'same-origin' };

    const programar = () => { clearTimeout(timer); timer = setTimeout(verificar, intervalo); };

    const verificar = async () => {
        if (enCurso || document.hidden) return;
        enCurso = true;
        try {
            const res = await fetchTimeout(window.location.href, opciones);
            if (!res.ok) throw new Error(res.status);

            const nueva = firma_de(await res.text());
            if (firma !== null && firma !== nueva) {
                if (!escribiendo()) { window.location.reload(); return; }
            } else {
                firma = nueva;
            }
            intervalo = INTERVALO_BASE;
        } catch {
            intervalo = Math.min(intervalo * 2, INTERVALO_MAX);
        } finally {
            enCurso = false;
            programar();
        }
    };

    // Firma inicial
    fetchTimeout(window.location.href, opciones)
        .then(r => r.text()).then(h => { firma = firma_de(h); }).catch(() => {})
        .finally(programar);

    document.addEventListener('visibilitychange', () => { if (!document.hidden) { clearTimeout(timer); verificar(); } });
    window.addEventListener('beforeunload', () => clearTimeout(timer));
});