<div id="userPanel" class="w-full max-w-[380px] shrink-0 bg-white border border-slate-200 rounded-2xl shadow-sm hidden lg:flex flex-col relative" style="display:none;" role="region" aria-label="Perfil del operario">

    <div class="p-4 flex justify-between items-center bg-white rounded-t-2xl">
        <h3 class="font-bold text-slate-900 text-lg">Perfil del Operario</h3>
        <button type="button" onclick="closePanel()" aria-label="Cerrar panel de perfil" class="text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div class="p-5 flex-1 overflow-y-auto">
        <div class="text-center mb-6">
            <div id="panelInitials" class="w-20 h-20 rounded-full bg-orange-600 text-white font-bold text-2xl flex items-center justify-center mx-auto mb-3"></div>
            <h2 id="panelName" class="text-xl font-bold text-slate-900"></h2>
            <span id="panelRole" class="block text-orange-600 bg-orange-50 px-3 py-1 rounded-full text-xs font-semibold w-max mx-auto mt-2"></span>

            <div class="flex items-center justify-center gap-2 mt-3">
                <span id="panelStatus" class="px-3 py-1 text-xs font-semibold rounded-full border"></span>
                <span id="panelTurno" class="px-3 py-1 text-slate-600 bg-slate-100 border border-slate-200 text-xs font-semibold rounded-full"></span>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex justify-between items-end mb-2">
                <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Eficiencia</span>
                <span id="panelEficienciaTexto" class="text-base font-bold text-slate-800">0%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
                <div id="panelEficienciaBarra" class="bg-orange-600 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
        </div>

        <ul class="space-y-4 text-sm mb-6">
            <li class="flex justify-between items-center">
                <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Estación</span>
                <span id="panelEstacion" class="text-slate-800 font-bold"></span>
            </li>
            <li class="flex justify-between items-center">
                <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Órdenes</span>
                <span id="panelOrdenes" class="text-slate-800 font-bold">0</span>
            </li>
            <li class="flex justify-between items-center">
                <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Orden Actual</span>
                <span id="panelOrdenActual" class="text-slate-800 font-bold text-right truncate max-w-[180px]">Ninguna</span>
            </li>
            <li class="flex justify-between items-center">
                <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Correo</span>
                <span id="panelEmail" class="text-slate-800 font-bold truncate max-w-[180px]"></span>
            </li>
            <li class="flex justify-between items-center">
                <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Alta</span>
                <span id="panelAlta" class="text-slate-800 font-bold"></span>
            </li>
        </ul>

        <div class="mt-2">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Habilidades</h4>
            <div id="panelSkillsContainer" class="flex flex-wrap gap-2"></div>
            <p id="panelNoSkills" class="text-xs text-slate-400" style="display: none;">Aún no se han registrado habilidades.</p>
        </div>
    </div>

    <div class="p-5 border-t border-slate-100 flex flex-col gap-3">
        <!-- Candado actualizado con el slug correcto de la BD -->
        @can('manage-users')
        <button type="button"
                onclick="triggerEditModal()"
                class="w-full bg-amber-50 hover:bg-amber-100 text-amber-600 border border-amber-200 font-semibold text-sm py-2.5 rounded-xl transition-colors shadow-sm">
            Editar Registro
        </button>
        <form id="statusFormPanel" method="POST" class="w-full">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" id="statusFormName">
            <input type="hidden" name="email" id="statusFormEmail">
            <input type="hidden" name="role_id" id="statusFormRole">
            <input type="hidden" name="active" id="statusFormActive">
            <button type="button" onclick="toggleStatusFromPanel()" class="w-full bg-orange-50 hover:bg-orange-100 text-orange-600 border border-orange-200 font-semibold text-sm py-2.5 rounded-xl transition-colors shadow-sm">
                Dar de baja / Alta
            </button>
        </form>
        <button type="button" onclick="openDeleteModalFromPanel()" class="w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-semibold text-sm py-2.5 rounded-xl transition-colors shadow-sm">
            Eliminar Registro
        </button>
        @else
        <div class="text-center text-xs text-slate-400 py-1 w-full">
            Modo visualización (Sin privilegios)
        </div>
        @endcan
    </div>
</div>