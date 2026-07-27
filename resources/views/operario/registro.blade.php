@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Registro de Producción</h1>
    <p class="text-slate-500 text-sm">Registra las unidades producidas en tu tarea activa</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Controles Izquierda -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Info Tarea -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-blue-600 p-4">
                <h3 class="text-xs font-bold text-blue-200 uppercase tracking-wide mb-1">Tarea Activa</h3>
                <h2 class="text-white font-bold text-lg">Costura de Mangas</h2>
                <p class="text-blue-100 text-xs">Chamarra de Mezclilla Mod. A</p>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Avance Total</span>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-slate-800">21</span>
                        <span class="text-slate-400 text-sm">/100</span>
                    </div>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mb-1">
                    <div class="bg-orange-400 h-2 rounded-full" style="width: 21%"></div>
                </div>
                <div class="text-right text-xs font-bold text-blue-600">21%</div>
            </div>
        </div>

        <!-- Registro Rápido -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Registro Rápido</h3>
            <div class="space-y-3">
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 rounded-xl shadow-sm transition-colors text-xl flex flex-col items-center">
                    <span>+1</span>
                    <span class="text-xs font-normal text-blue-200">Unidad</span>
                </button>
                <button class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-5 rounded-xl shadow-sm transition-colors text-xl flex flex-col items-center">
                    <span>+5</span>
                    <span class="text-xs font-normal text-slate-400">Lote Pequeño</span>
                </button>
            </div>
        </div>

        <!-- Entrada Manual -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Entrada Manual</h3>
            <div class="flex space-x-3">
                <input type="number" placeholder="Cantidad" class="flex-grow bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <button class="bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold px-6 rounded-xl transition-colors">
                    OK
                </button>
            </div>
        </div>

    </div>

    <!-- Tabla Historial (Derecha) -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-slate-800">Historial de Registros</h3>
                <p class="text-sm text-slate-500">Turno actual · 4 entradas</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wide">
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Hora</th>
                            <th class="py-3 px-4">Cantidad</th>
                            <th class="py-3 px-4">Tipo</th>
                            <th class="py-3 px-4">Nota</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr class="border-b border-slate-50 hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-400 font-medium">004</td>
                            <td class="py-3 px-4 font-medium text-slate-700">10:50</td>
                            <td class="py-3 px-4 font-bold text-slate-800">+5</td>
                            <td class="py-3 px-4">
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md text-xs font-medium">+Lote</span>
                            </td>
                            <td class="py-3 px-4 text-slate-400">—</td>
                        </tr>
                        <tr class="border-b border-slate-50 hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-400 font-medium">003</td>
                            <td class="py-3 px-4 font-medium text-slate-700">10:35</td>
                            <td class="py-3 px-4 font-bold text-slate-800">+1</td>
                            <td class="py-3 px-4">
                                <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-md text-xs font-medium border border-blue-100">+1 Unidad</span>
                            </td>
                            <td class="py-3 px-4 text-slate-400">—</td>
                        </tr>
                        <tr class="border-b border-slate-50 hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-400 font-medium">002</td>
                            <td class="py-3 px-4 font-medium text-slate-700">10:12</td>
                            <td class="py-3 px-4 font-bold text-slate-800">+10</td>
                            <td class="py-3 px-4">
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md text-xs font-medium">+Lote</span>
                            </td>
                            <td class="py-3 px-4 text-slate-500">Lote validado con supervisor</td>
                        </tr>
                        <tr class="border-b border-slate-50 hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-400 font-medium">001</td>
                            <td class="py-3 px-4 font-medium text-slate-700">09:58</td>
                            <td class="py-3 px-4 font-bold text-slate-800">+5</td>
                            <td class="py-3 px-4">
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md text-xs font-medium">+Lote</span>
                            </td>
                            <td class="py-3 px-4 text-slate-400">—</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection