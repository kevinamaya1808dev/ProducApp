@php $isActive = isset($activeRecipe) && $activeRecipe->id === $recipe->id; @endphp
<a href="{{ route('recipes.index', ['recipe' => $recipe->id, 'search' => request('search')]) }}"
   class="block bg-white {{ $isActive ? 'border-l-4 border-orange-600 ring-1 ring-slate-900/5 shadow-sm' : 'hover:bg-slate-50/80 border border-slate-200/80' }} rounded-2xl p-4 transition-all">
    <div class="flex items-start justify-between gap-2">
        <h3 class="font-{{ $isActive ? 'bold text-slate-900' : 'semibold text-slate-800' }} text-sm leading-snug">{{ $recipe->name }}</h3>
        <span class="text-[11px] font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200 shrink-0">REC-00{{ $recipe->id }}</span>
    </div>
    <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">{{ $recipe->instructions ?? 'Sin instrucciones registradas.' }}</p>
    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-slate-100">
        <span>Producto: <strong class="text-slate-700 font-semibold">{{ $recipe->product->name ?? 'N/A' }}</strong></span>
        <span>Act. {{ $recipe->updated_at->format('d M Y') }}</span>
    </div>
</a>