{{--
    Selector de proveedor (marcado). Debe ir DENTRO de un <form> con x-data="proveedorPicker(...)".
    Variables: $label (texto), $required (bool), $allowNew (bool).
    Envía: provider_source (registrado|manual), proveedor_id o proveedor_manual.
--}}
@php
    $inp  = 'w-full px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500';
    $on   = "'bg-white dark:bg-stone-800 text-slate-800 dark:text-white shadow-sm'";
    $off  = "'text-slate-500 dark:text-stone-400'";
    $seg  = 'flex items-center gap-1 p-1 rounded-lg bg-slate-100 dark:bg-stone-950 border border-slate-200 dark:border-stone-800 w-fit';
    $btn  = 'px-3 py-1.5 rounded-md text-xs font-semibold transition-colors cursor-pointer';
@endphp
<div class="space-y-2" @keydown.escape="open = false">
    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">{{ $label ?? 'Proveedor' }} @if(!empty($required))<span class="text-red-500">*</span>@endif</label>

    {{-- Switch 1: proveedor del directorio vs. temporal --}}
    <div class="{{ $seg }}">
        <button type="button" data-role="source-registrado" @click="setSource('registrado')" :class="source === 'registrado' ? {{ $on }} : {{ $off }}" class="{{ $btn }}">Proveedor registrado</button>
        <button type="button" data-role="source-manual" @click="setSource('manual')" :class="source === 'manual' ? {{ $on }} : {{ $off }}" class="{{ $btn }}">Otro / temporal</button>
    </div>
    <input type="hidden" name="provider_source" :value="source">

    {{-- Proveedor registrado --}}
    <div x-show="source === 'registrado'" x-cloak class="space-y-2">
        {{-- Switch 2: empresas vs. particulares --}}
        <div class="{{ $seg }}">
            <button type="button" data-role="tipo-empresa" @click="setTipo('empresa')" :class="tipo === 'empresa' ? {{ $on }} : {{ $off }}" class="{{ $btn }} flex items-center gap-1.5">
                Empresas <span class="px-1.5 rounded-full text-[10px] bg-orange-500/10 text-orange-600 dark:text-orange-400" x-text="conteo('empresa')"></span>
            </button>
            <button type="button" data-role="tipo-particular" @click="setTipo('particular')" :class="tipo === 'particular' ? {{ $on }} : {{ $off }}" class="{{ $btn }} flex items-center gap-1.5">
                Particulares <span class="px-1.5 rounded-full text-[10px] bg-sky-500/10 text-sky-600 dark:text-sky-400" x-text="conteo('particular')"></span>
            </button>
        </div>

        <input type="hidden" name="proveedor_id" :value="selectedId" :disabled="source !== 'registrado'">

        {{-- Lista desplegable --}}
        <div class="relative">
            <button type="button" data-role="dropdown" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 cursor-pointer" :class="selectedId ? 'text-slate-800 dark:text-white' : 'text-slate-400 dark:text-stone-500'">
                <span x-text="etiqueta" class="truncate"></span>
                <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="open" x-cloak style="display: none;" class="absolute left-0 right-0 z-50 mt-1 max-h-56 overflow-auto bg-white dark:bg-stone-950 border border-slate-200 dark:border-stone-800 rounded-lg shadow-xl py-1">
                <div x-show="delTipo.length > 6" class="px-2 pb-1">
                    <input type="text" x-model="q" placeholder="Buscar..." class="{{ $inp }} !py-1.5 text-xs">
                </div>
                <template x-for="p in lista" :key="p.id">
                    <button type="button" data-role="opcion" @click="elegir(p)" class="w-full text-left px-3 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer">
                        <span class="min-w-0">
                            <span class="block truncate" x-text="p.nombre"></span>
                            <span class="block truncate text-[10px] text-slate-400 dark:text-stone-500" x-show="p.contacto_nombre" x-text="p.contacto_nombre"></span>
                        </span>
                        <svg x-show="String(selectedId) === String(p.id)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </template>
                <p x-show="lista.length === 0" class="px-3 py-2 text-xs text-slate-400 dark:text-stone-500" x-text="tipo === 'empresa' ? 'No hay empresas registradas.' : 'No hay proveedores particulares registrados.'"></p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if(!empty($allowNew))
                <button type="button" data-role="nuevo" x-show="!creating" @click="abrirNuevo()" class="text-xs font-semibold text-orange-600 dark:text-orange-400 hover:underline cursor-pointer">+ Nuevo proveedor</button>
            @endif
            @if(empty($required))
                <button type="button" data-role="quitar" x-show="selectedId" @click="quitar()" class="text-xs text-slate-400 dark:text-stone-500 hover:text-rose-500 cursor-pointer">Quitar proveedor</button>
            @endif
        </div>

        {{-- Alta rápida (no se envía con el formulario: los campos no tienen "name") --}}
        @if(!empty($allowNew))
            <div x-show="creating" x-cloak class="rounded-lg border border-orange-500/30 bg-orange-50/40 dark:bg-orange-500/5 p-3 space-y-2">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-slate-700 dark:text-stone-200" x-text="tipo === 'empresa' ? 'Nueva empresa' : 'Nuevo proveedor particular'"></p>
                    <button type="button" @click="cerrarNuevo()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer" title="Cancelar"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div>
                    <input type="text" data-field="nombre" x-model="nuevo.nombre" @keydown.enter.prevent="guardarNuevo()" :placeholder="tipo === 'empresa' ? 'Nombre de la empresa *' : 'Nombre completo *'" class="{{ $inp }}">
                    <p x-show="errores.nombre" class="text-[10px] text-rose-500 mt-0.5" x-text="errores.nombre"></p>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <input type="text" data-field="telefono" x-model="nuevo.telefono" @keydown.enter.prevent="guardarNuevo()" placeholder="Teléfono" class="{{ $inp }}">
                        <p x-show="errores.telefono" class="text-[10px] text-rose-500 mt-0.5" x-text="errores.telefono"></p>
                    </div>
                    <div>
                        <input type="email" data-field="email" x-model="nuevo.email" @keydown.enter.prevent="guardarNuevo()" placeholder="Correo" class="{{ $inp }}">
                        <p x-show="errores.email" class="text-[10px] text-rose-500 mt-0.5" x-text="errores.email"></p>
                    </div>
                </div>
                <button type="button" @click="mas = !mas" class="text-[11px] text-slate-500 dark:text-stone-400 hover:underline cursor-pointer" x-text="mas ? 'Ocultar datos adicionales' : 'Más datos (contacto y dirección)'"></button>
                <div x-show="mas" x-cloak class="space-y-2">
                    <input type="text" data-field="contacto_nombre" x-model="nuevo.contacto_nombre" @keydown.enter.prevent="guardarNuevo()" placeholder="Nombre de contacto" class="{{ $inp }}">
                    <input type="text" data-field="direccion" x-model="nuevo.direccion" @keydown.enter.prevent="guardarNuevo()" placeholder="Dirección" class="{{ $inp }}">
                </div>
                <p x-show="errorGeneral" class="text-[11px] text-rose-500" x-text="errorGeneral"></p>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="cerrarNuevo()" class="px-3 py-1.5 text-xs rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-slate-700 dark:text-stone-200 cursor-pointer">Cancelar</button>
                    <button type="button" data-role="guardar-nuevo" @click="guardarNuevo()" :disabled="saving || !nuevo.nombre.trim()" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-orange-600 hover:bg-orange-700 text-white disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer" x-text="saving ? 'Guardando...' : 'Guardar y seleccionar'"></button>
                </div>
            </div>
        @endif
    </div>

    {{-- Proveedor temporal (texto libre, no entra al directorio) --}}
    <div x-show="source === 'manual'" x-cloak>
        <input type="text" name="proveedor_manual" x-model="manualText" :disabled="source !== 'manual'" maxlength="255" placeholder="Ej. Juan Pérez (compra directa)" class="{{ $inp }}">
        <p class="text-[10px] text-slate-400 dark:text-stone-500 mt-1">Proveedor temporal: solo se guarda como texto, no se agrega al directorio.</p>
    </div>
</div>
