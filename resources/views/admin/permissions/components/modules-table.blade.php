{{-- Tabla de solo lectura: qué permisos existen por módulo y acción --}}
<div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 mb-8">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 dark:bg-stone-800/50 border-b border-slate-200 dark:border-stone-800">
                <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide w-1/4">Módulo</th>
                @foreach($actions as $key => $label)
                    <th class="text-center px-3 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-stone-800">
            @forelse($modules as $moduleSlug => $modulePermissions)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-stone-800/40 transition-colors align-top">
                    <td class="px-4 py-3 font-medium text-slate-700 dark:text-stone-200">
                        {{ \Illuminate\Support\Str::headline($moduleSlug) }}
                    </td>

                    @foreach($actions as $actionKey => $actionLabel)
                        @php
                            $perm = $modulePermissions->firstWhere('action', $actionKey);
                        @endphp
                        <td class="text-center px-3 py-3" title="{{ $perm->name ?? '' }}">
                            @if($perm)
                                <svg class="w-4 h-4 mx-auto text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <span class="text-slate-200 dark:text-stone-700">—</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($actions) + 1 }}" class="px-4 py-6 text-center text-sm text-slate-400 dark:text-stone-600">
                        No hay permisos registrados todavía.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>