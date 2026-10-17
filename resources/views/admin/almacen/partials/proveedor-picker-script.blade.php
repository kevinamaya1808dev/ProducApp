{{--
    Lógica (Alpine) del selector de proveedor de los modales de Almacén:
    Registrar material, Editar material y Agregar stock.

    Se incluye UNA vez en admin/almacen/index. Los datos viajan como JSON (JSON), nunca
    interpolados dentro de cadenas JavaScript, para que un nombre con comillas no rompa la
    página ni permita inyectar código. (Los datos se serializan con la directiva JSON de Blade.)
--}}
@php
    $listaProveedores = $proveedores->map(function ($p) {
        return $p->only(['id', 'nombre', 'tipo', 'contacto_nombre', 'telefono', 'email']);
    })->values();
@endphp
<script>
window.PROVEEDORES = @json($listaProveedores);

window.proveedorPicker = (cfg = {}) => ({
    required: !!cfg.required,
    allowNew: !!cfg.allowNew,
    url: cfg.url || '',
    csrf: cfg.csrf || '',

    proveedores: (window.PROVEEDORES || []).slice(),
    source: 'registrado',      // 'registrado' (directorio) | 'manual' (temporal)
    tipo: 'empresa',           // filtro de la lista: 'empresa' | 'particular'
    selectedId: '',
    manualText: '',
    open: false,
    q: '',
    creating: false,
    mas: false,
    saving: false,
    errores: {},
    errorGeneral: '',
    nuevo: { nombre: '', contacto_nombre: '', telefono: '', email: '', direccion: '' },

    // ---------- datos derivados ----------
    get delTipo() { return this.proveedores.filter(p => p.tipo === this.tipo); },
    get lista() {
        const q = this.q.trim().toLowerCase();
        return this.delTipo.filter(p => !q || `${p.nombre} ${p.contacto_nombre ?? ''}`.toLowerCase().includes(q));
    },
    get seleccionado() { return this.proveedores.find(p => String(p.id) === String(this.selectedId)) || null; },
    get etiqueta() { return this.seleccionado ? this.seleccionado.nombre : 'Selecciona un proveedor...'; },
    get valido() {
        if (!this.required) return true;
        return this.source === 'registrado' ? !!this.selectedId : this.manualText.trim() !== '';
    },
    conteo(t) { return this.proveedores.filter(p => p.tipo === t).length; },

    // ---------- estado ----------
    reset() {
        this.source = 'registrado'; this.tipo = 'empresa'; this.selectedId = ''; this.manualText = '';
        this.open = false; this.q = ''; this.cerrarNuevo();
    },
    // Precarga el proveedor actual de un material (modal Editar).
    cargar(m) {
        this.reset();
        m = m || {};
        if (m.proveedor_id) {
            const p = this.proveedores.find(x => String(x.id) === String(m.proveedor_id));
            this.selectedId = String(m.proveedor_id);
            this.tipo = (p && p.tipo) || m.proveedor_tipo || 'empresa';
        } else if (m.proveedor) {
            this.source = 'manual';
            this.manualText = m.proveedor;
        }
    },
    setSource(s) { this.source = s; this.open = false; this.cerrarNuevo(); },
    setTipo(t) {
        this.tipo = t; this.q = ''; this.cerrarNuevo();
        if (this.seleccionado && this.seleccionado.tipo !== t) this.selectedId = '';
    },
    elegir(p) { this.selectedId = String(p.id); this.open = false; this.q = ''; },
    quitar() { this.selectedId = ''; },

    // ---------- alta rápida de proveedor (sin salir del modal) ----------
    abrirNuevo() { this.open = false; this.creating = true; this.errores = {}; this.errorGeneral = ''; },
    cerrarNuevo() {
        this.creating = false; this.mas = false; this.saving = false; this.errores = {}; this.errorGeneral = '';
        this.nuevo = { nombre: '', contacto_nombre: '', telefono: '', email: '', direccion: '' };
    },
    alta(p) {
        if (!this.proveedores.some(x => x.id === p.id)) this.proveedores.push(p);
    },
    async guardarNuevo() {
        if (this.saving || !this.nuevo.nombre.trim()) return;
        this.saving = true; this.errores = {}; this.errorGeneral = '';
        try {
            const res = await fetch(this.url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ ...this.nuevo, tipo: this.tipo }),
            });
            const data = await res.json().catch(() => ({}));
            if (res.status === 422) {
                this.errores = Object.fromEntries(Object.entries(data.errors || {}).map(([k, v]) => [k, v[0]]));
                return;
            }
            if (!res.ok) {
                this.errorGeneral = res.status === 403 ? 'No tienes permiso para registrar proveedores.' : 'No se pudo guardar el proveedor. Inténtalo de nuevo.';
                return;
            }
            const p = data.proveedor;
            if (!window.PROVEEDORES.some(x => x.id === p.id)) window.PROVEEDORES.push(p);
            this.alta(p);
            window.dispatchEvent(new CustomEvent('proveedor-creado', { detail: p }));   // los demás modales lo reciben
            this.elegir(p);
            this.cerrarNuevo();
        } catch (e) {
            this.errorGeneral = 'Error de conexión. Revisa tu internet e inténtalo de nuevo.';
        } finally {
            this.saving = false;
        }
    },
});
</script>
