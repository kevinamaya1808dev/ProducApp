import './bootstrap';

/**
 * Script global de actualización automática (Polling)
 * Compatible con servidores locales y en la nube mediante rutas relativas.
 */
document.addEventListener("DOMContentLoaded", () => {
    // Configura el tiempo en milisegundos (Ejemplo: cada 10,000 ms = 10 segundos)
    const INTERVALO_ACTUALIZACION = 1000;

    // Detectar si estamos en una sección que requiere actualización en tiempo real (ej. operario o admin)
    const requiereActualizacion = window.location.pathname.includes('/operario') || 
                                   window.location.pathname.includes('/admin');

    if (requiereActualizacion) {
        setInterval(async () => {
            try {
                // Usamos la URL actual de forma relativa para que funcione en la nube y local por igual
                const respuesta = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (respuesta.ok) {
                    // O opción A: Recargar la vista de forma transparente para refrescar contadores y tablas
                    // Descomenta la siguiente línea si prefieres recarga completa automática:
                    // window.location.reload();
                    
                    console.log("Verificación de sincronización de datos exitosa.");
                }
            } catch (error) {
                console.error("Error al sincronizar los datos automáticamente:", error);
            }
        }, INTERVALO_ACTUALIZACION);
    }
});