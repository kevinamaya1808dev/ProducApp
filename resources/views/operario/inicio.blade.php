@extends('layouts.operario')
@section('header_title', 'Inicio')

@section('content')
<div class="mb-6 text-slate-500 text-sm font-medium">
    Turno Matutino · Estación 4 · Martes 22 jul 2026
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Columna Izquierda -->
    <div class="lg:col-span-8 space-y-6">
        <!-- Tarjeta Tarea Activa -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-blue-600 p-4 flex justify-between items-center">
                <h2 class="text-white font-bold text-sm tracking-wide uppercase">Tarea Activa</h2>
                <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Alta Prioridad</span>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="text-xs font-semibold text-slate-400 uppercase">Lote</span>
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">#8892</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">Costura de Mangas</h3>
                        <p class="text-slate-500 text-sm">Chamarra de Mezclilla Mod. A</p>
                    </div>
                    <div class="text-sm text-slate-400 font-medium">Estación 4</div>
                </div>

                <!-- Barra de Avance -->
                <div class="mb-8">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Avance</span>
                        <div class="text-right">
                            <span class="text-3xl font-bold text-blue-600">45</span>
                            <span class="text-slate-400 font-medium">/100 unidades</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 mb-2">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: 45%"></div>
                    </div>
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-slate-400">0%</span>
                        <span class="text-blue-600">45% completado</span>
                        <span class="text-slate-400">100%</span>
                    </div>
                </div>

                <!-- Registro Rápido -->
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wide block mb-3">Registro Rápido</span>
                    <div class="grid grid-cols-3 gap-4">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-sm transition-colors text-lg">
                            +1 Unidad
                        </button>
                        <button class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 rounded-xl shadow-sm transition-colors text-lg">
                            +5 Lote Pequeño
                        </button>
                        <button class="bg-orange-50 hover:bg-orange-100 text-orange-600 border border-orange-200 font-bold py-4 rounded-xl shadow-sm transition-colors text-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Reportar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna Derecha -->
    <div class="lg:col-span-4 space-y-6">
        <!-- Mi Turno -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Mi Turno</h3>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-slate-600">Piezas hoy</span>
                        <span class="font-bold text-slate-800">45</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2"><div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div></div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-slate-600">Eficiencia</span>
                        <span class="font-bold text-slate-800">94%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2"><div class="bg-emerald-500 h-2 rounded-full" style="width: 94%"></div></div>
                </div>
                
                <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                    <span class="text-sm font-medium text-slate-600">Incidencias</span>
                    <span class="font-bold text-slate-800">0</span>
                </div>
            </div>
        </div>

        <!-- Acceso Rápido -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Acceso Rápido</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                    <span class="text-2xl mb-2">📋</span>
                    <span class="text-xs font-semibold text-slate-600">Mis Tareas</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                    <span class="text-2xl mb-2">✏️</span>
                    <span class="text-xs font-semibold text-slate-600">Registro</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                    <span class="text-2xl mb-2">⚠️</span>
                    <span class="text-xs font-semibold text-slate-600">Incidencias</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                    <span class="text-2xl mb-2">👤</span>
                    <span class="text-xs font-semibold text-slate-600">Mi Perfil</span>
                </a>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Actividad Reciente</h3>
            <div class="space-y-4 relative before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                <!-- Item -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-4 h-4 rounded-full border-2 border-white bg-emerald-500 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10"></div>
                    <div class="w-[calc(100%-2rem)] md:w-[calc(50%-1.5rem)] ml-4 md:ml-0">
                        <div class="flex flex-col">
                            <span class="text-sm text-slate-800 font-medium">+5 piezas registradas</span>
                            <span class="text-xs text-slate-400">10:50</span>
                        </div>
                    </div>
                </div>
                <!-- Item -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-4 h-4 rounded-full border-2 border-white bg-emerald-500 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10"></div>
                    <div class="w-[calc(100%-2rem)] md:w-[calc(50%-1.5rem)] ml-4 md:ml-0">
                        <div class="flex flex-col">
                            <span class="text-sm text-slate-800 font-medium">+1 pieza registrada</span>
                            <span class="text-xs text-slate-400">10:35</span>
                        </div>
                    </div>
                </div>
                <!-- Item -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-4 h-4 rounded-full border-2 border-white bg-blue-500 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10"></div>
                    <div class="w-[calc(100%-2rem)] md:w-[calc(50%-1.5rem)] ml-4 md:ml-0">
                        <div class="flex flex-col">
                            <span class="text-sm text-slate-800 font-medium">Tarea iniciada · ORD-2024-0091</span>
                            <span class="text-xs text-slate-400">09:58</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection