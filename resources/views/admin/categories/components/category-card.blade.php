@php
    $isActive = isset($activeCategory) && $activeCategory->id === $category->id;
@endphp

<a href="{{ route('categories.index', ['category' => $category->id, 'search' => request('search')]) }}"
   class="block bg-white {{ $isActive ? 'border-l-4 border-orange-600 ring-1 ring-slate-900/5 shadow-sm' : 'hover:bg-slate-50/80 border border-slate-200/80' }} rounded-2xl p-4 transition-all">
    <div class="flex items-start justify-between gap-2">
        <h3 class="font-{{ $isActive ? 'bold text-slate-900' : 'semibold text-slate-800' }} text-sm leading-snug">
            {{ $category->name }}
        </h3>
        <span class="text-[11px] font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
            {{ $category->slug }}
        </span>
    </div>
    <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">
        {{ $category->description ?? 'Sin descripción registrada.' }}
    </p>
    <div class="flex items-center justify-between text-[11px] text-slate-400 mt-3 pt-3 border-t border-slate-100">
        <span>Última modificación</span>
        <span>{{ $category->updated_at->format('d M Y') }}</span>
    </div>
</a>