{{-- Lista de solo lectura: permisos especiales (no siguen el esquema módulo + acción) --}}
<div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 mb-8">
    <div class="px-4 py-3 border-b border-slate-200 dark:border-stone-800">
        <h2 class="text-sm font-bold text-slate-700 dark:text-stone-200">Permisos Especiales</h2>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 dark:bg-stone-800/50 border-b border-slate-200 dark:border-stone-800">
                <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide">Permiso</th>
                <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide">Slug</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-stone-800">
            @forelse($special as $perm)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-stone-800/40 transition-colors">
                    <td class="px-4 py-3 font-medium text-slate-700 dark:text-stone-200">{{ $perm->name }}</td>
                    <td class="px-4 py-3">
                        <code class="text-xs font-mono text-slate-500 dark:text-stone-400">{{ $perm->slug }}</code>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="px-4 py-6 text-center text-sm text-slate-400 dark:text-stone-600">
                        No hay permisos especiales todavía.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>