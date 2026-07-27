@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Mis Tareas</h1>
    <p class="text-slate-500 text-sm">Tareas asignadas a tu turno · 3 órdenes</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Lista de Tareas (Sidebar) -->
    <div class="lg:col-span-4 space-y-4">
        
        <!-- Tarea 1 (Activa) -->
        <div class="bg-blue-50 rounded-xl border-2 border-blue-400 p-4 cursor-pointer">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full flex items-center">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full mr-1.5"></span> En Progreso
                </span>
                <span class="text-xs font-bold text-red-500">Alta</span>
            </div>
            <div class="mb-3">
                <span class="text-xs font-bold text-blue-600">#8892</span>
                <h4 class="font-bold text-slate-800">Costura de Mangas</h4>
                <p class="text-xs text-slate-500">Chamarra de Mezclilla Mod. A</p>
            </div>
            <div>
                <div class="w-full bg-slate-200 rounded-full h-1.5 mb-1"><div class="bg-blue-600 h-1.5 rounded-full" style="width: 45%"></div></div>
                <div class="flex justify-between text-[10px] text-slate-500 font-medium">
                    <span>45/100 pzas</span>
                    <span>Límite: 25 jul 2026</span>
                </div>
            </div>
        </div>

        <!-- Tarea 2 -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 hover:border-slate-300 cursor-pointer shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full flex items-center">
                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span> Pendiente
                </span>
                <span class="text-xs font-bold text-amber-500">Media</span>
            </div>
            <div class="mb-3">
                <span class="text-xs font-bold text-blue-600">#8897</span>
                <h4 class="font-bold text-slate-800">Ensamble de Cuerpo</h4>
                <p class="text-xs text-slate-500">Chamarra de Mezclilla Mod. B</p>
            </div>
            <div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 mb-1"><div class="bg-blue-600 h-1.5 rounded-full" style="width: 0%"></div></div>
                <div class="flex justify-between text-[10px] text-slate-500 font-medium">
                    <span>0/80 pzas</span>
                    <span>Límite: 28 jul 2026</span>
                </div>
            </div>
        </div>

        <!-- Tarea 3 -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 hover:border-slate-300 cursor-pointer shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full flex items-center">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-1.5"></span> En Espera
                </span>
                <span class="text-xs font-bold text-slate-400">Baja</span>
            </div>
            <div class="mb-3">
                <span class="text-xs font-bold text-blue-600">#8889</span>
                <h4 class="font-bold text-slate-800">Pegado de Bolsillos</h4>
                <p class="text-xs text-slate-500">Sudadera Hoodie Oversize</p>
            </div>
            <div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 mb-1"><div class="bg-blue-600 h-1.5 rounded-full" style="width: 30%"></div></div>
                <div class="flex justify-between text-[10px] text-slate-500 font-medium">
                    <span>18/60 pzas</span>
                    <span>Límite: 30 jul 2026</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Detalle de Tarea -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
            <!-- Header Detalle -->
            <div class="bg-blue-600 p-6 flex justify-between items-start">
                <div>
                    <div class="flex space-x-2 mb-2 text-xs font-bold">
                        <span class="bg-blue-500 text-white px-2 py-0.5 rounded">ORD-2024-0091</span>
                        <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded">#8892</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-1">Costura de Mangas</h2>
                    <p class="text-blue-100 text-sm">Chamarra de Mezclilla Mod. A</p>
                </div>
                <div class="flex flex-col items-end space-y-2">
                    <span class="bg-white text-red-500 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Alta prioridad</span>
                    <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center">
                        <span class="w-1.5 h-1.5 bg-white rounded-full mr-1.5"></span> En Progreso
                    </span>
                </div>
            </div>

            <!-- Body Detalle -->
            <div class="p-6">
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <!-- Progreso -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Progreso</h4>
                        <div class="flex items-end space-x-1 mb-2">
                            <span class="text-4xl font-bold text-slate-800">45</span>
                            <span class="text-slate-400 font-medium pb-1">/100 pzas</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 45%"></div>
                        </div>
                        <div class="text-xs font-bold text-blue-600">45% completado</div>
                    </div>
                    
                    <!-- Detalles -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Detalles</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex justify-between border-b border-slate-50 pb-1">
                                <span class="text-slate-500">Estación</span>
                                <span class="font-medium text-slate-800">Estación 4</span>
                            </li>
                            <li class="flex justify-between border-b border-slate-50 pb-1">
                                <span class="text-slate-500">Fecha Límite</span>
                                <span class="font-medium text-slate-800">25 jul 2026</span>
                            </li>
                            <li class="flex justify-between border-b border-slate-50 pb-1">
                                <span class="text-slate-500">Inicio</span>
                                <span class="font-medium text-slate-800">09:58</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-slate-500">Restante</span>
                                <span class="font-medium text-slate-800">55 pzas</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Nota Supervisor -->
                <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 mb-8">
                    <h5 class="text-xs font-bold text-yellow-700 uppercase tracking-wide mb-1">Nota del Supervisor</h5>
                    <p class="text-yellow-800 text-sm">Verificar costuras laterales antes de pasar a acabados.</p>
                </div>

                <!-- Botones Accion -->
                <div class="grid grid-cols-2 gap-4">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors text-center">
                        Registrar Unidades
                    </button>
                    <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-4 rounded-xl transition-colors text-center">
                        Pausar Tarea
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection