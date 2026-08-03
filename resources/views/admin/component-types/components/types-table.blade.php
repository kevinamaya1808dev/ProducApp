<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                <th class="py-3 px-5">Tipo</th>
                <th class="py-3 px-5">Componentes</th>
                <th class="py-3 px-5 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            @forelse($componentTypes as $type)
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-3 px-5">
                        <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $type->badgeClasses() }}">{{ $type->name }}</span>
                    </td>
                    <td class="py-3 px-5 text-slate-500">{{ $type->components_count }}</td>
                    <td class="py-3 px-5">
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" onclick="openModal('editTypeModal-{{ $type->id }}')" class="p-1.5 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button type="button" onclick="openModal('deleteTypeModal-{{ $type->id }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="py-8 text-center text-slate-400 text-sm">Aún no hay tipos de componente registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>