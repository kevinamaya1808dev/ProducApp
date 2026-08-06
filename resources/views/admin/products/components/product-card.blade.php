@php
    use Illuminate\Support\Facades\Storage;

    $themes = [
        ['bg' => 'bg-orange-100/50 dark:bg-orange-950/30', 'text' => 'text-orange-200 dark:text-orange-900/50', 'badge' => 'bg-orange-50 dark:bg-orange-950/50 text-orange-700 dark:text-orange-400 border border-orange-200/60 dark:border-orange-900/50'],
        ['bg' => 'bg-purple-100/50 dark:bg-purple-950/30', 'text' => 'text-purple-200 dark:text-purple-900/50', 'badge' => 'bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-400 border border-purple-200/60 dark:border-purple-900/50'],
        ['bg' => 'bg-sky-100/50 dark:bg-sky-950/30',      'text' => 'text-sky-200 dark:text-sky-900/50',      'badge' => 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-400 border border-sky-200/60 dark:border-sky-900/50'],
        ['bg' => 'bg-pink-100/50 dark:bg-pink-950/30',    'text' => 'text-pink-200 dark:text-pink-900/50',    'badge' => 'bg-pink-50 dark:bg-pink-950/50 text-pink-700 dark:text-pink-400 border border-pink-200/60 dark:border-pink-900/50'],
        ['bg' => 'bg-emerald-100/50 dark:bg-emerald-950/30','text' => 'text-emerald-200 dark:text-emerald-900/50','badge' => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-900/50'],
    ];
    $theme = $themes[$product->id % count($themes)];

    $words = explode(' ', trim($product->name));
    $siglas = count($words) > 1
        ? strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1))
        : strtoupper(mb_substr($product->name, 0, 2));

    $isActive = $product->stock > 0;
@endphp

<div class="bg-white dark:bg-stone-900 rounded-2xl border border-slate-200/80 dark:border-stone-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col relative group cursor-pointer">
    @can('manage-products')
    <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 z-10">
        <button type="button"
            data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-code="{{ $product->code }}" data-category_id="{{ $product->category_id }}" data-stock="{{ $product->stock }}" data-unit_cost="{{ $product->unit_cost }}" data-description="{{ $product->description }}" data-image="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : '' }}"
            onclick="openEditModal(this)"
            class="p-1.5 bg-white/90 dark:bg-stone-900/90 backdrop-blur text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-950/40 shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        </button>
        <button type="button" onclick="openDeleteModal({{ $product->id }})" class="p-1.5 bg-white/90 dark:bg-stone-900/90 backdrop-blur text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-950/40 shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
    </div>
    @endcan

    <div class="h-32 {{ $theme['bg'] }} flex items-center justify-center overflow-hidden relative">
        @if($product->image && Storage::disk('public')->exists($product->image))
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <span class="font-black text-6xl {{ $theme['text'] }} select-none tracking-tighter">{{ $siglas }}</span>
        @endif
    </div>

    <div class="p-5 flex-1 flex flex-col">
        <div class="flex justify-between items-start gap-3">
            <div class="min-w-0">
                <h3 class="font-bold text-slate-800 dark:text-stone-200 text-sm truncate" title="{{ $product->name }}">
                    {{ $product->name }}
                </h3>
                <p class="text-[10px] font-mono text-slate-400 dark:text-stone-500 mt-0.5">
                    {{ $product->code }}
                </p>
            </div>
            @if($isActive)
                <span class="shrink-0 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border border-emerald-200 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40">Activo</span>
            @else
                <span class="shrink-0 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40">Sin stock</span>
            @endif
        </div>

        <div class="mt-3">
            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $theme['badge'] }}">
                {{ $product->category->name ?? 'Sin categoría' }}
            </span>
        </div>

        <div class="mt-auto pt-5">
            <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 dark:border-stone-800 text-center">
                <div>
                    <p class="text-[17px] font-bold text-slate-800 dark:text-stone-200">0</p>
                    <p class="text-[9px] font-bold text-slate-400 dark:text-stone-500 uppercase tracking-wider mt-0.5">Comp.</p>
                </div>
                <div>
                    <p class="text-[17px] font-bold text-slate-800 dark:text-stone-200">0</p>
                    <p class="text-[9px] font-bold text-slate-400 dark:text-stone-500 uppercase tracking-wider mt-0.5">Órdenes</p>
                </div>
                <div>
                    <p class="text-[17px] font-bold {{ !$isActive ? 'text-red-500 dark:text-red-400' : 'text-slate-800 dark:text-stone-200' }}">{{ $product->stock }}</p>
                    <p class="text-[9px] font-bold text-slate-400 dark:text-stone-500 uppercase tracking-wider mt-0.5">Stock</p>
                </div>
            </div>
        </div>
    </div>
</div>