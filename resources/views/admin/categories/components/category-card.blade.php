@php
    $isActive = isset($activeCategory) && $activeCategory->id === $category->id;
@endphp

<a href="{{ route('categories.index', ['category' => $category->id, 'search' => request('search')]) }}"
   class="block bg-white dark:bg-stone-900 {{ $isActive ? 'border-l-4 border-orange-600 ring-1 ring-slate-900/5 dark:ring-stone-100/10 shadow-sm' : 'hover:bg-slate-50/80 dark:hover:bg-stone-800/80 border border-slate-200/80 dark:border-stone-800' }} rounded-2xl p-4 transition-all">
    <div class="flex items-start justify-between gap-2">
        <h3 class="font-{{ $isActive ? 'bold text-slate-900 dark:text-stone-100' : 'semibold text-slate-800 dark:text-stone-200' }} text-sm leading-snug">
            {{ $category->name }}
        </h3>
        <span class="text-[11px] font-mono text-slate-400 dark:text-stone-400 bg-slate-100 dark:bg-stone-800 px-2 py-0.5 rounded-md border border-slate-200 dark:border-stone-700">
            {{ $category->slug }}
        </span>
    </div>
    <p class="text-xs text-slate-500 dark:text-stone-400 mt-1.5 line-clamp-2">
        {{ $category->description ?? 'Sin descripción registrada.' }}
    </p>
    <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-stone-500 mt-3 pt-3 border-t border-slate-100 dark:border-stone-800">
        <span>Última modificación</span>
        <span>{{ $category->updated_at->format('d M Y') }}</span>
    </div>
</a>