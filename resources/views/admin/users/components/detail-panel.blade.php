<div id="userPanel" class="w-full max-w-[380px] shrink-0 bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-2xl shadow-sm hidden lg:flex flex-col relative" style="display:none;" role="region" aria-label="Perfil del operario">

    <div class="p-4 flex justify-between items-center bg-white dark:bg-stone-900 rounded-t-2xl">
        <h3 class="font-bold text-slate-900 dark:text-stone-100 text-lg">Perfil del Operario</h3>
        <button type="button" onclick="closePanel()" aria-label="Cerrar panel de perfil" class="text-slate-400 dark:text-stone-500 hover:text-slate-600 dark:hover:text-stone-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div class="p-5 flex-1 overflow-y-auto">
        <div class="text-center mb-6">
            <div id="panelInitials" class="w-20 h-20 rounded-full bg-orange-600 dark:bg-orange-700 text-white font-bold text-2xl flex items-center justify-center mx-auto mb-3 shadow-md shadow-orange-500/20"></div>
            <h2 id="panelName" class="text-xl font-bold text-slate-900 dark:text-stone-100"></h2>
            <span id="panelRole" class="block text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/50 border border-orange-200/80 dark:border-orange-900/50 px-3 py-1 rounded-full text-xs font-semibold w-max mx-auto mt-2"></span>

            <div class="flex items-center justify-center gap-2 mt-3">
                <span id="panelStatus" class="px-3 py-1 text-xs font-semibold rounded-full border"></span>
                <span id="panelTurno" class="px-3 py-1 text-slate-600 dark:text-stone-300 bg-slate-100 dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-xs font-semibold rounded-full"></span>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex justify-between items-end mb-2">
                <span class="text-xs font-bold text-slate-400 dark:text-stone-500 tracking-wider uppercase">Eficiencia</span>
                <span id="panelEficienciaTexto" class="text-base font-bold text-slate-800 dark:text-stone-200">0%</span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-stone-800 rounded-full h-2.5 overflow-hidden">
                <div id="panelEficienciaBarra" class="bg-orange-600 dark:bg-orange-500 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
        </div>

        <ul class="space-y-4 text-sm mb-6">
            <li class="flex justify-between items-center border-b border-slate-100 dark:border-stone-800 pb-2">
                <span class="text-slate-400 dark:text-stone-500 font-semibold text-xs uppercase tracking-wider">Estación</span>
                <span id="panelEstacion" class="text-slate-800 dark:text-stone-200 font-bold"></span>
            </li>
            <li class="flex justify-between items-center border-b border-slate-100 dark:border-stone-800 pb-2">
                <span class="text-slate-400 dark:text-stone-500 font-semibold text-xs uppercase tracking-wider">Órdenes</span>
                <span id="panelOrdenes" class="text-slate-800 dark:text-stone-200 font-bold">0</span>
            </li>
            <li class="flex justify-between items-center border-b border-slate-100 dark:border-stone-800 pb-2">
                <span class="text-slate-400 dark:text-stone-500 font-semibold text-xs uppercase tracking-wider">Orden Actual</span>
                <span id="panelOrdenActual" class="text-slate-800 dark:text-stone-200 font-bold text-right truncate max-w-[180px]">Ninguna</span>
            </li>
            <li class="flex justify-between items-center border-b border-slate-100 dark:border-stone-800 pb-2">
                <span class="text-slate-400 dark:text-stone-500 font-semibold text-xs uppercase tracking-wider">Correo</span>
                <span id="panelEmail" class="text-slate-800 dark:text-stone-200 font-bold truncate max-w-[180px]"></span>
            </li>
            <li class="flex justify-between items-center">
                <span class="text-slate-400 dark:text-stone-500 font-semibold text-xs uppercase tracking-wider">Alta</span>
                <span id="panelAlta" class="text-slate-800 dark:text-stone-200 font-bold"></span>
            </li>
        </ul>

        <div class="mt-2">
            <h4 class="text-xs font-bold text-slate-400 dark:text-stone-500 uppercase tracking-wider mb-2">Habilidades</h4>
            <div id="panelSkillsContainer" class="flex flex-wrap gap-2"></div>
            <p id="panelNoSkills" class="text-xs text-slate-400 dark:text-stone-500" style="display: none;">Aún no se han registrado habilidades.</p>
        </div>
    </div>

    <div class="p-5 border-t border-slate-100 dark:border-stone-800 flex flex-col gap-3">
        @can('manage-users')
        <button type="button"
                onclick="triggerEditModal()"
                class="w-full bg-amber-50 dark:bg-amber-950/40 hover:bg-amber-100 dark:hover:bg-amber-900/50 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 font-semibold text-sm py-2.5 rounded-xl transition-colors shadow-sm cursor-pointer">
            Editar Registro
        </button>
        <form id="statusFormPanel" method="POST" class="w-full">
    @csrf
    @method('PUT')
    <input type="hidden" name="name" id="statusFormName">
    <input type="hidden" name="email" id="statusFormEmail">
    <input type="hidden" name="role_id" id="statusFormRole">
    <input type="hidden" name="active" id="statusFormActive">
    
    <!-- Añadimos el id en el botón o dentro de un span -->
    <button type="button" onclick="toggleStatusFromPanel()" class="w-full bg-orange-50 dark:bg-orange-950/40 hover:bg-orange-100 dark:hover:bg-orange-900/50 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-900/50 font-semibold text-sm py-2.5 rounded-xl transition-colors shadow-sm cursor-pointer">
        <span id="statusButtonText">Dar de baja / Alta</span>
    </button>
</form>
      <button type="button"
        id="deleteUserBtn"
        onclick="openDeleteModal()"
        class="w-full bg-red-50 dark:bg-red-950/40 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/50 font-semibold text-sm py-2.5 rounded-xl transition-colors shadow-sm cursor-pointer">
    Eliminar Registro
</button>
        @else
        <div class="text-center text-xs text-slate-400 dark:text-stone-500 py-1 w-full">
            Modo visualización (Sin privilegios)
        </div>
        @endcan
    </div>
</div>