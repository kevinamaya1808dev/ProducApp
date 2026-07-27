@extends('layouts.operario')
@section('header_title', 'Mi Perfil')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Mi Perfil</h1>
    <p class="text-slate-500 text-sm">Tus estadísticas, historial y certificaciones</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Columna Perfil (Izquierda) -->
    <div class="lg:col-span-3 space-y-6">
        
        <!-- Tarjeta Perfil -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-blue-600 h-24"></div>
            <div class="px-6 pb-6 relative">
                <div class="w-20 h-20 bg-blue-600 text-white border-4 border-white rounded-full flex items-center justify-center font-bold text-2xl shadow-sm absolute -top-10">
                    RL
                </div>
                <div class="flex justify-end pt-3 mb-2">
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-bold px-3 py-1 rounded-full">Activo</span>
                </div>
                <h2 class="text-xl font-bold text-slate-800">Roberto López</h2>
                <p class="text-slate-500 text-sm mb-6">Operario Senior</p>
                
                <ul class="space-y-3 text-sm">
                    <li class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400 font-medium">ID</span>
                        <span class="font-bold text-slate-800">OP-001</span>
                    </li>
                    <li class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400 font-medium">ESTACIÓN</span>
                        <span class="font-medium text-slate-800">Estación 4</span>
                    </li>
                    <li class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400 font-medium">TURNO</span>
                        <span class="font-medium text-slate-800">Matutino</span>
                    </li>
                    <li class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400 font-medium">PLANTA</span>
                        <span class="font-medium text-slate-800">Monterrey</span>
                    </li>
                    <li class="flex justify-between pt-1">
                        <span class="text-slate-400 font-medium">ALTA DESDE</span>
                        <span class="font-medium text-slate-800">Mar 2021</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Habilidades -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Habilidades</h3>
            <div class="flex flex-wrap gap-2">
                <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Costura industrial</span>
                <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Acabados</span>
                <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Control de calidad</span>
                <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Maquinaria overlock</span>
                <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Corte básico</span>
            </div>
        </div>

        <!-- Certificaciones -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Certificaciones</h3>
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Seguridad Industrial Nivel 2</h4>
                    <p class="text-xs text-slate-400">Mar 2025</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Columna Datos (Derecha) -->
    <div class="lg:col-span-9 space-y-6">
        
        <!-- Tarjetas Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Eficiencia Prom.</h3>
                <div class="text-4xl font-bold text-blue-600 mb-1">86%</div>
                <p class="text-xs text-slate-400">esta semana</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Órdenes Completas</h3>
                <div class="text-4xl font-bold text-emerald-600 mb-1">312</div>
                <p class="text-xs text-slate-400">total histórico</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Incidencias</h3>
                <div class="text-4xl font-bold text-slate-800 mb-1">3</div>
                <p class="text-xs text-slate-400">últimos 30 días</p>
            </div>
        </div>

        <!-- Gráfica Producción -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Producción Semanal</h3>
                    <p class="text-sm text-slate-500">Semana del 18 al 22 jul 2026</p>
                </div>
                <div class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 text-xs font-medium text-slate-500">
                    Meta: 100 pzas/día
                </div>
            </div>
            
            <!-- Bars simulation -->
            <div class="flex items-end justify-between h-48 pt-4 pb-2 border-b border-slate-100 px-4 md:px-10">
                <!-- Lunes -->
                <div class="flex flex-col items-center w-1/6">
                    <span class="text-xs font-bold text-slate-600 mb-2">98</span>
                    <div class="w-full bg-blue-500 rounded-t-md" style="height: 98%;"></div>
                </div>
                <!-- Martes -->
                <div class="flex flex-col items-center w-1/6">
                    <span class="text-xs font-bold text-slate-600 mb-2">100</span>
                    <div class="w-full bg-emerald-500 rounded-t-md relative" style="height: 100%;">
                        <div class="absolute -top-6 left-0 right-0 border-t-2 border-dashed border-slate-300"></div>
                    </div>
                </div>
                <!-- Miércoles -->
                <div class="flex flex-col items-center w-1/6">
                    <span class="text-xs font-bold text-slate-600 mb-2">87</span>
                    <div class="w-full bg-blue-500 rounded-t-md" style="height: 87%;"></div>
                </div>
                <!-- Jueves -->
                <div class="flex flex-col items-center w-1/6">
                    <span class="text-xs font-bold text-slate-600 mb-2">100</span>
                    <div class="w-full bg-emerald-500 rounded-t-md relative" style="height: 100%;">
                        <div class="absolute -top-6 left-0 right-0 border-t-2 border-dashed border-slate-300"></div>
                    </div>
                </div>
                <!-- Viernes -->
                <div class="flex flex-col items-center w-1/6">
                    <span class="text-xs font-bold text-slate-600 mb-2">45</span>
                    <div class="w-full bg-amber-400 rounded-t-md" style="height: 45%;"></div>
                </div>
            </div>
            <div class="flex justify-between px-4 md:px-10 mt-3 text-xs font-bold text-slate-400 uppercase">
                <div class="w-1/6 text-center">Lun</div>
                <div class="w-1/6 text-center text-emerald-600">Mar</div>
                <div class="w-1/6 text-center">Mié</div>
                <div class="w-1/6 text-center text-emerald-600">Jue</div>
                <div class="w-1/6 text-center text-amber-500">Vie</div>
            </div>
        </div>

        <!-- Historial de Órdenes -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-slate-800">Historial de Órdenes</h3>
                <p class="text-sm text-slate-500">Últimas 4 órdenes completadas</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wide">
                            <th class="py-3 px-2">Orden</th>
                            <th class="py-3 px-2">Producto</th>
                            <th class="py-3 px-2">Fecha</th>
                            <th class="py-3 px-2">Unidades</th>
                            <th class="py-3 px-2">Eficiencia</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr class="border-b border-slate-50">
                            <td class="py-4 px-2 font-bold text-blue-600 text-xs">
                                <span class="bg-blue-50 px-2 py-1 rounded">ORD-2024-0089</span>
                            </td>
                            <td class="py-4 px-2 font-medium text-slate-700">Blusa Lino Temporada 26</td>
                            <td class="py-4 px-2 text-slate-500">20 jul 2026</td>
                            <td class="py-4 px-2 font-bold text-slate-800">100</td>
                            <td class="py-4 px-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-16 bg-slate-100 rounded-full h-1.5"><div class="bg-emerald-500 h-1.5 rounded-full" style="width: 96%"></div></div>
                                    <span class="font-bold text-emerald-600 text-xs">96%</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-4 px-2 font-bold text-blue-600 text-xs">
                                <span class="bg-blue-50 px-2 py-1 rounded">ORD-2024-0085</span>
                            </td>
                            <td class="py-4 px-2 font-medium text-slate-700">Short Deportivo Dry-Fit</td>
                            <td class="py-4 px-2 text-slate-500">18 jul 2026</td>
                            <td class="py-4 px-2 font-bold text-slate-800">80</td>
                            <td class="py-4 px-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-16 bg-slate-100 rounded-full h-1.5"><div class="bg-blue-600 h-1.5 rounded-full" style="width: 92%"></div></div>
                                    <span class="font-bold text-blue-600 text-xs">92%</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection