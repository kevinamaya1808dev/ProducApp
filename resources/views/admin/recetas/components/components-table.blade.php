@php
    $sinTipoClasses = 'bg-slate-100 dark:bg-stone-800 text-slate-500 dark:text-stone-400 border-slate-200 dark:border-stone-700';
@endphp

<div class="p-6">
    <div class="overflow-x-auto border border-slate-200/80 dark:border-stone-800 rounded-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 dark:border-stone-800 text-[11px] font-bold text-slate-400 dark:text-stone-500 uppercase tracking-wider bg-slate-50/50 dark:bg-stone-900/50">
                    <th class="py-3 px-4 w-12">#</th>
                    <th class="py-3 px-4">Componente</th>
                    <th class="py-3 px-4">Tipo</th>
                    <th class="py-3 px-4">Cantidad</th>
                    <th class="py-3 px-4">Unidad</th>
                    <th class="py-3 px-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-stone-800 text-sm">
                @forelse($recipe->components as $component)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-stone-800/40 transition-colors group">
                        <td class="py-3 px-4 text-xs font-mono text-slate-400 dark:text-stone-500 font-semibold">{{ sprintf('%02d', $loop->iteration) }}</td>
                        <td class="py-3 px-4 font-semibold text-slate-800 dark:text-stone-200">{{ $component->name }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $component->componentType?->badgeClasses() ?? $sinTipoClasses }}">
                                {{ $component->componentType->name ?? 'Sin tipo' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800 dark:text-stone-200">{{ rtrim(rtrim(number_format($component->pivot->quantity, 2), '0'), '.') }}</td>
                        <td class="py-3 px-4 text-slate-500 dark:text-stone-400">{{ $component->base_unit }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="openModal('editComponentModal-{{ $component->id }}')" class="p-1.5 text-slate-400 dark:text-stone-500 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-950/40 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button type="button" onclick="openModal('deleteComponentModal-{{ $component->id }}')" class="p-1.5 text-slate-400 dark:text-stone-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-slate-400 dark:text-stone-500 text-sm">No hay componentes asignados a esta receta.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($recipe->components->isNotEmpty())
            <div class="flex items-center justify-between px-4 py-3 bg-slate-50/50 dark:bg-stone-900/50 border-t border-slate-100 dark:border-stone-800 flex-wrap gap-2">
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach($recipe->components->groupBy(fn($c) => $c->componentType->name ?? 'Sin tipo') as $tipo => $items)
                        @php $badge = $items->first()->componentType?->badgeClasses() ?? $sinTipoClasses; @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badge }}">
                            {{ $tipo }} <span class="opacity-70">{{ $items->count() }}</span>
                        </span>
                    @endforeach
                </div>
                <span class="text-xs text-slate-400 dark:text-stone-500">{{ $recipe->components->count() }} componentes</span>
            </div>
        @endif
    </div>
</div>