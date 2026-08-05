<div id="toastContainer" class="fixed top-20 right-4 z-[9999] flex flex-col gap-3 w-full max-w-sm pointer-events-none"></div>

<template id="toastTemplate">
    <div class="toast-item pointer-events-auto flex items-start gap-3 w-full bg-white dark:bg-stone-900 border rounded-xl shadow-lg shadow-stone-900/10 dark:shadow-black/40 p-4 pr-3 overflow-hidden relative opacity-0 translate-x-6 transition-all duration-300 ease-out">
        <!-- Barra de color lateral -->
        <div class="toast-accent absolute left-0 top-0 h-full w-1"></div>

        <!-- Icono -->
        <div class="toast-icon-wrap shrink-0 w-9 h-9 rounded-lg flex items-center justify-center mt-0.5">
            <svg class="toast-icon-success w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            <svg class="toast-icon-error w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            <svg class="toast-icon-warning w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m0 3.75h.007v.008H12v-.008zM10.29 3.86L1.82 18a1.5 1.5 0 001.3 2.25h17.76a1.5 1.5 0 001.3-2.25L13.71 3.86a1.5 1.5 0 00-2.42 0z"></path></svg>
            <svg class="toast-icon-info w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <!-- Contenido -->
        <div class="flex-1 min-w-0 pt-0.5">
            <p class="toast-title text-sm font-bold text-stone-900 dark:text-stone-100 leading-tight"></p>
            <p class="toast-message text-xs text-stone-500 dark:text-stone-400 mt-0.5 leading-snug"></p>
        </div>

        <!-- Botón cerrar -->
        <button type="button" class="toast-close shrink-0 w-6 h-6 flex items-center justify-center text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-md transition-colors cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Barra de progreso -->
        <div class="toast-progress absolute bottom-0 left-0 h-0.5 w-full origin-left"></div>
    </div>
</template>

@push('scripts')
<script>
(function () {
    const container = document.getElementById('toastContainer');
    const template = document.getElementById('toastTemplate');

    const THEMES = {
        success: { border: 'border-emerald-200 dark:border-emerald-900/50', accent: 'bg-emerald-500', iconBg: 'bg-emerald-50 dark:bg-emerald-950/50', iconColor: 'text-emerald-600 dark:text-emerald-400', title: 'Éxito', progress: 'bg-emerald-500' },
        error:   { border: 'border-red-200 dark:border-red-900/50', accent: 'bg-red-500', iconBg: 'bg-red-50 dark:bg-red-950/50', iconColor: 'text-red-600 dark:text-red-400', title: 'Error', progress: 'bg-red-500' },
        warning: { border: 'border-amber-200 dark:border-amber-900/50', accent: 'bg-amber-500', iconBg: 'bg-amber-50 dark:bg-amber-950/50', iconColor: 'text-amber-600 dark:text-amber-400', title: 'Atención', progress: 'bg-amber-500' },
        info:    { border: 'border-orange-200 dark:border-orange-900/50', accent: 'bg-orange-500', iconBg: 'bg-orange-50 dark:bg-orange-950/50', iconColor: 'text-orange-600 dark:text-orange-400', title: 'Información', progress: 'bg-orange-500' },
    };

    function showToast(message, type = 'info', options = {}) {
        type = THEMES[type] ? type : 'info';
        const theme = THEMES[type];
        const duration = options.duration ?? 5000;
        const title = options.title ?? theme.title;

        const node = template.content.firstElementChild.cloneNode(true);
node.classList.add(...theme.border.split(' '));
node.querySelector('.toast-accent').classList.add(...theme.accent.split(' '));
node.querySelector('.toast-icon-wrap').classList.add(...theme.iconBg.split(' '), ...theme.iconColor.split(' '));
node.querySelector(`.toast-icon-${type}`).classList.remove('hidden');
node.querySelector('.toast-title').textContent = title;
node.querySelector('.toast-message').textContent = message;

const progressBar = node.querySelector('.toast-progress');
progressBar.classList.add(...theme.progress.split(' '));
        container.appendChild(node);

        // Animación de entrada
        requestAnimationFrame(() => {
            node.classList.remove('opacity-0', 'translate-x-6');
        });

        // Barra de progreso animada
        progressBar.style.transition = `transform ${duration}ms linear`;
        requestAnimationFrame(() => {
            progressBar.style.transform = 'scaleX(1)';
            progressBar.style.transformOrigin = 'left';
            requestAnimationFrame(() => {
                progressBar.style.transform = 'scaleX(0)';
            });
        });

        function remove() {
            node.classList.add('opacity-0', 'translate-x-6');
            node.style.maxHeight = node.offsetHeight + 'px';
            requestAnimationFrame(() => {
                node.style.maxHeight = '0px';
                node.style.marginBottom = '0px';
                node.style.paddingTop = '0px';
                node.style.paddingBottom = '0px';
            });
            setTimeout(() => node.remove(), 300);
        }

        const timer = setTimeout(remove, duration);

        node.querySelector('.toast-close').addEventListener('click', () => {
            clearTimeout(timer);
            remove();
        });

        // Pausar al pasar el mouse
        node.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            progressBar.style.transition = 'none';
        });
    }

    // Exponer función global
    window.notify = showToast;

    // Auto-mostrar mensajes flash de sesión (Laravel)
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            window.notify(@json(session('success')), 'success');
        @endif
        @if (session('error'))
            window.notify(@json(session('error')), 'error');
        @endif
        @if (session('warning'))
            window.notify(@json(session('warning')), 'warning');
        @endif
        @if (session('info'))
            window.notify(@json(session('info')), 'info');
        @endif

        {{-- Soporte para errores de validación de formularios --}}
        @if ($errors->any())
            window.notify(@json($errors->first()), 'error', { title: 'Error de validación' });
        @endif
    });
})();
</script>
@endpush