@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Incidencias</h1>
        <p class="text-slate-500 text-sm">Reporta y da seguimiento a incidencias de tu turno</p>
    </div>
    <button class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition-colors flex items-center">
        <span class="mr-2">+</span> Reportar Incidencia
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Lista de Incidencias -->
    <div class="space-y-4">
        
        <!-- Item Alta -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 cursor-pointer hover:border-orange-300">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-400">INC-0044</span>
                    <span class="bg-orange-500 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase">Alta</span>
                </div>
                <span class="bg-orange-50 text-orange-700 border border-orange-200 text-xs font-bold px-3 py-1 rounded-full">En revisión</span>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Falla de maquinaria</h3>
            <p class="text-sm text-slate-500 mb-3">La máquina de coser presentó ruido anormal y detención automática.</p>
            <div class="flex items-center text-xs text-slate-400 font-medium space-x-4">
                <span>22 jul 2026 · 08:55</span>
                <span>ORD-2024-0091</span>
            </div>
        </div>

        <!-- Item Media -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 cursor-pointer hover:border-slate-300">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-400">INC-0039</span>
                    <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase">Media</span>
                </div>
                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full">Resuelta</span>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Material defectuoso</h3>
            <p class="text-sm text-slate-500 mb-3">Rollo de tela #TL-4421 presentó rasgaduras en metros 3-5.</p>
            <div class="flex items-center text-xs text-slate-400 font-medium space-x-4">
                <span>19 jul 2026 · 14:20</span>
                <span>ORD-2024-0088</span>
            </div>
        </div>

        <!-- Item Baja -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 cursor-pointer hover:border-slate-300">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-400">INC-0031</span>
                    <span class="bg-slate-400 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase">Baja</span>
                </div>
                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full">Resuelta</span>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Escasez de insumo</h3>
            <p class="text-sm text-slate-500 mb-3">Se agotó el stock de hilo índigo antes de completar el lote.</p>
            <div class="flex items-center text-xs text-slate-400 font-medium space-x-4">
                <span>15 jul 2026 · 11:10</span>
                <span>ORD-2024-0085</span>
            </div>
        </div>

    </div>

    <!-- Empty State / Selector -->
    <div>
        <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center h-64 text-center p-6">
            <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mb-4">
                <span class="text-3xl">📋</span>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1">Selecciona una incidencia</h3>
            <p class="text-sm text-slate-400">o reporta una nueva</p>
        </div>
    </div>
</div>
@endsection