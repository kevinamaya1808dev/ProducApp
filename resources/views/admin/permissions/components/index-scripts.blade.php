<script>
    // ==================== Helper genérico de modales ====================
    function openModalEl(id) {
        const el = document.getElementById(id);
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeModalEl(id) {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    // ==================== Modo "Modificar" ====================
    let manageMode = false;

    function toggleManageMode() {
        manageMode = !manageMode;

        document.querySelectorAll('.manage-row-controls').forEach(el => {
            el.classList.toggle('hidden', !manageMode);
            el.classList.toggle('flex', manageMode);
        });

        const btn = document.getElementById('manageModeBtn');
        btn.textContent = manageMode ? 'Listo' : 'Modificar';
        btn.classList.toggle('bg-orange-600',    manageMode);
        btn.classList.toggle('text-white',        manageMode);
        btn.classList.toggle('border-orange-600', manageMode);
        btn.classList.toggle('bg-white',          !manageMode);
        btn.classList.toggle('dark:bg-stone-800', !manageMode);
        btn.classList.toggle('text-slate-600',    !manageMode);
        btn.classList.toggle('dark:text-stone-300', !manageMode);

        if (!manageMode) closeAllRowSelectors();
    }

    function closeAllRowSelectors() {
        document.querySelectorAll('.row-selector').forEach(sel => {
            sel.classList.add('hidden');
            sel.value      = '';
            sel.dataset.mode = '';
        });
    }

    function toggleRowSelector(moduleSlug, mode) {
        const select = document.getElementById('rowSelector-' + moduleSlug);
        if (!select) return;

        const alreadyOpen = !select.classList.contains('hidden') && select.dataset.mode === mode;
        closeAllRowSelectors();
        if (alreadyOpen) return;

        select.dataset.mode = mode;
        select.classList.remove('hidden');
        select.focus();
    }

    document.querySelectorAll('.row-selector').forEach(select => {
        select.addEventListener('change', function () {
            if (!this.value) return;
            const option = this.options[this.selectedIndex];
            const mode   = this.dataset.mode;

            if (mode === 'edit') {
                openEditModal(JSON.parse(option.dataset.permission));
            } else if (mode === 'delete') {
                openDeleteModal(this.value, option.dataset.name);
            }

            this.classList.add('hidden');
            this.value       = '';
            this.dataset.mode = '';
        });
    });

    // ==================== Modal Eliminar ====================
    function openDeleteModal(id, name) {
        document.getElementById('deletePermissionName').textContent = name;
        document.getElementById('deletePermissionForm').action      = `/admin/permissions/${id}`;
        openModalEl('deletePermissionModal');
    }

    function closeDeleteModal() {
        closeModalEl('deletePermissionModal');
    }

    // ==================== Modal Editar ====================
    function openEditModal(perm) {
        const form = document.getElementById('editPermissionForm');
        form.action = `/admin/permissions/${perm.id}`;

        document.getElementById('editName').value       = perm.name || '';
        document.getElementById('editIsSpecial').checked = !!perm.is_special;
        document.getElementById('editModuleInput').value = perm.is_special ? '' : (perm.module || '');
        document.getElementById('editAction').value      = perm.is_special ? '' : (perm.action || '');
        document.getElementById('editSlug').value        = perm.is_special ? (perm.slug || '') : '';

        document.getElementById('editModuleFields').classList.toggle('hidden', !!perm.is_special);
        document.getElementById('editSpecialFields').classList.toggle('hidden', !perm.is_special);

        document.getElementById('editDeleteBtn').onclick = () => {
            closeEditModal();
            openDeleteModal(perm.id, perm.name);
        };

        openModalEl('editPermissionModal');
    }

    function closeEditModal() {
        closeModalEl('editPermissionModal');
    }

    // ==================== Modal Crear ====================
    function headline(str) {
        if (!str) return 'Módulo';
        return str
            .replace(/[_\-]+/g, ' ')
            .trim()
            .split(/\s+/)
            .filter(Boolean)
            .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
            .join(' ');
    }

    function updateCreateNamePlaceholders() {
        const label = headline(document.getElementById('createModuleInput').value);
        document.querySelectorAll('.create-action-name-input').forEach(input => {
            input.placeholder = input.dataset.actionLabel + ' ' + label;
        });
    }

    function openCreateModal(module = null, action = null) {
        document.getElementById('createPermissionForm').reset();
        document.getElementById('createIsSpecial').checked = false;
        document.getElementById('createModuleFields').classList.remove('hidden');
        document.getElementById('createSpecialFields').classList.add('hidden');

        if (module) document.getElementById('createModuleInput').value = module;
        if (action) {
            const cb = document.querySelector('.create-action-checkbox[value="' + action + '"]');
            if (cb) cb.checked = true;
        }

        updateCreateNamePlaceholders();
        openModalEl('createPermissionModal');
    }

    function closeCreateModal() {
        closeModalEl('createPermissionModal');
    }

    function validateCreatePermissionForm() {
        const isSpecial = document.getElementById('createIsSpecial').checked;

        if (isSpecial) {
            const name = document.getElementById('createSpecialName').value.trim();
            const slug = document.getElementById('createSpecialSlug').value.trim();
            if (!name || !slug) {
                window.notify('Completa el nombre y el slug del permiso especial.', 'error');
                return false;
            }
            return true;
        }

        const module = document.getElementById('createModuleInput').value.trim();
        if (!module) {
            window.notify('Escribe el nombre del módulo.', 'error');
            return false;
        }

        const anyChecked = Array.from(document.querySelectorAll('.create-action-checkbox')).some(cb => cb.checked);
        if (!anyChecked) {
            window.notify('Selecciona al menos una acción para crear.', 'error');
            return false;
        }

        return true;
    }
</script>