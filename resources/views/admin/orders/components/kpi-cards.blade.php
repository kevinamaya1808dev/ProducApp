<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
        <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Total Órdenes</p>
        <p class="text-3xl font-black text-slate-900">{{ $orders->total() }}</p>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
        <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">En Progreso</p>
        <p class="text-3xl font-black text-orange-600">{{ $orders->where('status', 'in_progress')->count() }}</p>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
        <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Completadas</p>
        <p class="text-3xl font-black text-emerald-500">{{ $orders->where('status', 'completed')->count() }}</p>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
        <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Urgentes (Alta)</p>
        <p class="text-3xl font-black text-red-600">{{ $orders->where('priority', 'high')->where('status', '!=', 'completed')->count() }}</p>
    </div>
</div>