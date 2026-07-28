@props(['piezasHoy'])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Mi Turno</h3>
    
    <div class="space-y-4">
        <div>
            <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium text-slate-600">Piezas hoy</span>
                <span class="font-bold text-slate-800">{{ $piezasHoy ?? 0 }}</span>
            </div>
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