import './bootstrap';

/**
 * Script global de actualización automática inteligente (Polling con detección de cambios)
 * Sincroniza las vistas de administrador y operario automáticamente solo cuando detecta modificaciones.
 *
 * Mejoras respecto a la versión anterior:
 *  - Evita peticiones solapadas (si una tarda más que el intervalo, no se acumulan).
 *  - Pausa el polling cuando la pestaña no está visible (ahorra red/batería).
 *  - Backoff exponencial ante errores de red, en vez de martillar el servidor.
 *  - Cancela peticiones colgadas con AbortController + timeout.
 *  - Deshabilita caché del navegador explícitamente (cache: 'no-store').
 *  - Limpia el intervalo al salir de la página (evita fugas de memoria/timers zombis).
 *  - No recarga si el usuario está escribiendo en un formulario (evita perder datos).
 */
document.addEventListener("DOMContentLoaded", () => {
    const INTERVALO_BASE = 4000;       // 4s en condiciones normales
    const INTERVALO_MAXIMO = 60000;    // tope de backoff: 60s
    const TIMEOUT_PETICION = 8000;     // aborta si el servidor tarda más de 8s

    const requiereActualizacion =
        window.location.pathname.includes('/operario') ||
        window.location.pathname.includes('/admin');

    if (!requiereActualizacion) return;

    let ultimaFirma = null;
    let peticionEnCurso = false;
    let intervaloActual = INTERVALO_BASE;
    let timerId = null;

    // Extrae una firma del contenido relevante de la página (tablas/listados)
    const obtenerFirmaContenido = (htmlText) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlText, 'text/html');
        const contenidoClave =
            doc.querySelector('table') || doc.querySelector('.grid') || doc.body;
        // Normaliza espacios para evitar falsos positivos por saltos de línea/espacios
        return contenidoClave
            ? contenidoClave.innerText.trim().replace(/\s+/g, ' ')
            : '';
    };

    // Evita interrumpir al usuario si está escribiendo en un input/textarea/select
    const usuarioEstaEscribiendo = () => {
        const activo = document.activeElement;
        if (!activo) return false;
        const tag = activo.tagName;
        return tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || activo.isContentEditable;
    };

    const fetchConTimeout = (url, opciones = {}) => {
        const controller = new AbortController();
        const id = setTimeout(() => controller.abort(), TIMEOUT_PETICION);
        return fetch(url, { ...opciones, signal: controller.signal })
            .finally(() => clearTimeout(id));
    };

    const verificarCambios = async () => {
        if (peticionEnCurso || document.hidden) return;
        peticionEnCurso = true;

        try {
            const respuesta = await fetchConTimeout(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store',
                credentials: 'same-origin',
            });

            if (respuesta.ok) {
                const htmlActual = await respuesta.text();
                const nuevaFirma = obtenerFirmaContenido(htmlActual);

                if (ultimaFirma !== null && ultimaFirma !== nuevaFirma) {
                    if (usuarioEstaEscribiendo()) {
                        // No interrumpimos: reintentamos en el próximo ciclo
                        console.log('Cambio detectado, pero el usuario está escribiendo. Se pospone la recarga.');
                    } else {
                        console.log('Cambio detectado en órdenes, recetas o perfiles. Actualizando vista automáticamente...');
                        window.location.reload();
                        return;
                    }
                } else {
                    ultimaFirma = nuevaFirma;
                }

                // Éxito: restablecemos el intervalo a su valor base
                intervaloActual = INTERVALO_BASE;
            } else {
                throw new Error(`Respuesta no válida: ${respuesta.status}`);
            }
        } catch (error) {
            console.error('Error al sincronizar los datos automáticamente:', error);
            // Backoff exponencial (hasta el máximo) para no saturar un servidor caído
            intervaloActual = Math.min(intervaloActual * 2, INTERVALO_MAXIMO);
        } finally {
            peticionEnCurso = false;
            programarSiguienteVerificacion();
        }
    };

    const programarSiguienteVerificacion = () => {
        clearTimeout(timerId);
        timerId = setTimeout(verificarCambios, intervaloActual);
    };

    // Firma inicial al cargar la página
    fetchConTimeout(window.location.href, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        cache: 'no-store',
        credentials: 'same-origin',
    })
        .then((res) => res.text())
        .then((html) => {
            ultimaFirma = obtenerFirmaContenido(html);
        })
        .catch(() => {})
        .finally(() => {
            programarSiguienteVerificacion();
        });

    // Al volver a la pestaña, verificamos de inmediato en vez de esperar el intervalo completo
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            clearTimeout(timerId);
            verificarCambios();
        }
    });

    // Limpieza al salir de la página
    window.addEventListener('beforeunload', () => {
        clearTimeout(timerId);
    });
});