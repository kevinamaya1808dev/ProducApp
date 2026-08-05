<div id="usersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 flex-1 min-w-0 transition-all duration-300">
    @forelse($users as $user)
        @include('admin.users.components.user-card', ['user' => $user])
    @empty
        <p id="emptyState" class="text-sm text-slate-400 dark:text-stone-500 col-span-full text-center py-10">No hay operarios gestionables todavía.</p>
    @endforelse
    <p id="noResults" class="text-sm text-slate-400 dark:text-stone-500 col-span-full text-center py-10" style="display:none;">No se encontraron usuarios.</p>
</div>