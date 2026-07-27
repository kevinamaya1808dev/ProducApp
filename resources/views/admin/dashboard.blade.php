@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Encabezado del Dashboard -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Resumen de Producción</h1>
            <p class="text-xs text-slate-500 mt-1">
                Turno Matutino &middot; Planta Monterrey &middot; Actualizado hace 2 min
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition-colors">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filtrar
            </button>
            <button type="button" class="inline-flex items-center gap-2 px-3.5 py-2 bg-blue-600 border border-transparent rounded-lg text-xs font-semibold text-white hover:bg-blue-700 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Exportar
            </button>
        </div>
    </div>

    <!-- Tarjetas de Métricas KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tarjeta 1: Lotes Activos -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Lotes Activos</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-3">24</div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">
                ↑ +3 vs ayer
            </span>
        </div>

        <!-- Tarjeta 2: Piezas Terminadas Hoy -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Piezas Terminadas Hoy</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-3">1,847</div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                ↑ +12% meta diaria
            </span>
        </div>

        <!-- Tarjeta 3: Rendimiento Global -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Rendimiento Global</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-3">91.4%</div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">
                ↓ -0.8% vs semana
            </span>
        </div>

        <!-- Tarjeta 4: Cuellos de Botella -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Cuellos de Botella</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-3">3</div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                ↓ +2 críticos
            </span>
        </div>
    </div>

    <!-- Sección Principal: Tabla de Órdenes y Panel de Actividad -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        <!-- Tabla de Órdenes de Producción en Curso -->
        <div class="xl:col-span-3 bg-white border border-slate-200/80 rounded-xl shadow-sm p-6">
            <!-- Encabezado y Filtros de Tabla -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Órdenes de Producción en Curso</h2>
                    <p class="text-xs text-slate-500">7 órdenes</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative w-full sm:w-48">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" placeholder="Buscar..." class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg text-xs font-semibold">
                        <button type="button" class="px-3 py-1 rounded-md bg-blue-600 text-white shadow-sm">Todos</button>
                        <button type="button" class="px-3 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-colors">En Progreso</button>
                        <button type="button" class="px-3 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-colors">Completada</button>
                        <button type="button" class="px-3 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-colors">Iniciando</button>
                        <button type="button" class="px-3 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-colors">Revisión</button>
                    </div>
                </div>
            </div>

            <!-- Estructura de la Tabla -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="pb-3 pr-4">ID de Orden</th>
                            <th class="pb-3 px-4">Producto</th>
                            <th class="pb-3 px-4">Progreso</th>
                            <th class="pb-3 px-4">Fecha Límite</th>
                            <th class="pb-3 px-4">Operario</th>
                            <th class="pb-3 pl-4 text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        <!-- Fila 1 -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 pr-4 font-semibold text-blue-600 whitespace-nowrap">ORD-2024-0091</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-800">Chamarra de Mezclilla ...</p>
                                <p class="text-[11px] text-slate-400">Outerwear</p>
                            </td>
                            <td class="py-3.5 px-4 w-40">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: 72%"></div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-500 w-8 text-right">72%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-600 whitespace-nowrap">25 jul 2026</td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 font-bold text-[10px] flex items-center justify-center">RL</span>
                                    <span class="font-medium text-slate-700">R. López</span>
                                </div>
                            </td>
                            <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    En Progreso
                                </span>
                            </td>
                        </tr>

                        <!-- Fila 2 -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 pr-4 font-semibold text-blue-600 whitespace-nowrap">ORD-2024-0090</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-800">Pantalón Cargo Slim M...</p>
                                <p class="text-[11px] text-slate-400">Bottoms</p>
                            </td>
                            <td class="py-3.5 px-4 w-40">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-amber-500 h-2 rounded-full" style="width: 45%"></div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-500 w-8 text-right">45%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-red-600 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1">⚠ 23 jul 2026</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 font-bold text-[10px] flex items-center justify-center">MG</span>
                                    <span class="font-medium text-slate-700">M. García</span>
                                </div>
                            </td>
                            <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    En Progreso
                                </span>
                            </td>
                        </tr>

                        <!-- Fila 3 -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 pr-4 font-semibold text-blue-600 whitespace-nowrap">ORD-2024-0089</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-800">Blusa Lino Temporada ...</p>
                                <p class="text-[11px] text-slate-400">Tops</p>
                            </td>
                            <td class="py-3.5 px-4 w-40">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-500 w-8 text-right">100%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-600 whitespace-nowrap">20 jul 2026</td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 font-bold text-[10px] flex items-center justify-center">AT</span>
                                    <span class="font-medium text-slate-700">A. Torres</span>
                                </div>
                            </td>
                            <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Completada
                                </span>
                            </td>
                        </tr>

                        <!-- Fila 4 -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 pr-4 font-semibold text-blue-600 whitespace-nowrap">ORD-2024-0088</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-800">Sudadera Hoodie Over...</p>
                                <p class="text-[11px] text-slate-400">Outerwear</p>
                            </td>
                            <td class="py-3.5 px-4 w-40">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: 18%"></div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-500 w-8 text-right">18%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-600 whitespace-nowrap">30 jul 2026</td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 font-bold text-[10px] flex items-center justify-center">LR</span>
                                    <span class="font-medium text-slate-700">L. Ramírez</span>
                                </div>
                            </td>
                            <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Iniciando
                                </span>
                            </td>
                        </tr>

                        <!-- Fila 5 -->
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 pr-4 font-semibold text-blue-600 whitespace-nowrap">ORD-2024-0087</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-800">Vestido Floral Cut & Sew</p>
                                <p class="text-[11px] text-slate-400">Dresses</p>
                            </td>
                            <td class="py-3.5 px-4 w-40">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: 61%"></div>
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-500 w-8 text-right">61%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-red-600 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1">⚠ 24 jul 2026</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 font-bold text-[10px] flex items-center justify-center">CM</span>
                                    <span class="font-medium text-slate-700">C. Morales</span>
                                </div>
                            </td>
                            <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    En Progreso
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panel Lateral de Actividad Reciente -->
        <div class="xl:col-span-1 bg-white border border-slate-200/80 rounded-xl shadow-sm p-6 flex flex-col">
            <div class="mb-6">
                <h2 class="text-base font-bold text-slate-900">Actividad Reciente</h2>
                <p class="text-xs text-slate-500">Últimos eventos del turno</p>
            </div>

            <div class="space-y-6 relative before:absolute before:inset-0 before:left-2 before:w-0.5 before:bg-slate-100">
                <!-- Evento 1 -->
                <div class="relative pl-6">
                    <span class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-white border-2 border-blue-600 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                    </span>
                    <p class="text-xs font-semibold text-slate-800 leading-snug">
                        ORD-2024-0090 entró a revisión de calidad
                    </p>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">10:42 am</span>
                </div>

                <!-- Evento 2 -->
                <div class="relative pl-6">
                    <span class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-white border-2 border-amber-500 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                    </span>
                    <p class="text-xs font-semibold text-slate-800 leading-snug">
                        Cuello de botella detectado en Estación 6
                    </p>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">10:15 am</span>
                </div>

                <!-- Evento 3 -->
                <div class="relative pl-6">
                    <span class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-white border-2 border-emerald-500 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                    </span>
                    <p class="text-xs font-semibold text-slate-800 leading-snug">
                        ORD-2024-0089 marcada como Completada
                    </p>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">09:58 am</span>
                </div>

                <!-- Evento 4 -->
                <div class="relative pl-6">
                    <span class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-white border-2 border-blue-600 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                    </span>
                    <p class="text-xs font-semibold text-slate-800 leading-snug">
                        Nuevo operario asignado: P. Salinas
                    </p>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">09:30 am</span>
                </div>

                <!-- Evento 5 -->
                <div class="relative pl-6">
                    <span class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-white border-2 border-red-500 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                    </span>
                    <p class="text-xs font-semibold text-slate-800 leading-snug">
                        Paro no planificado en Estación 3
                    </p>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">08:55 am</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection