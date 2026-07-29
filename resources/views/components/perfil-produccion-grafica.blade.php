@props(['rangoFechas' => 'Semana del 18 al 22 jul 2026'])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Producción Semanal</h3>
            <p class="text-sm text-slate-500">{{ $rangoFechas }}</p>
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