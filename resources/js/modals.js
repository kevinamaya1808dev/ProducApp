// Previsualización de nueva imagen seleccionada
window.previewEditImage = function(event) {
    const reader = new FileReader();
    const file = event.target.files[0];
    
    const previewImage = document.getElementById('edit_image_preview');
    const placeholder = document.getElementById('image_placeholder');
    const removeBtn = document.getElementById('remove_image_btn');

    if (file) {
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.classList.remove('hidden'); // Muestra la imagen
            placeholder.classList.add('hidden');     // Oculta el icono +
            removeBtn.classList.remove('hidden');    // Muestra la X
        }
        reader.readAsDataURL(file);
    }
};

// Remover previsualización
window.clearEditImage = function(event) {
    if (event) event.stopPropagation(); // Evita que se abra el explorador de archivos al dar click en la X

    const input = document.getElementById('edit_image_input');
    const previewImage = document.getElementById('edit_image_preview');
    const placeholder = document.getElementById('image_placeholder');
    const removeBtn = document.getElementById('remove_image_btn');

    input.value = ''; // Limpia el archivo
    previewImage.src = '';
    
    previewImage.classList.add('hidden');
    placeholder.classList.remove('hidden');
    removeBtn.classList.add('hidden');
};

// Actualizar también la función de abrir modal para que cargue la imagen existente si existe
window.openEditModal = function(button) {
    // ... (tus otras asignaciones de variables) ...
    const imageUrl = button.getAttribute('data-image');
    
    // Configuración de la imagen
    const previewImage = document.getElementById('edit_image_preview');
    const placeholder = document.getElementById('image_placeholder');
    const removeBtn = document.getElementById('remove_image_btn');

    if (imageUrl && imageUrl.trim() !== '') {
        previewImage.src = imageUrl;
        previewImage.classList.remove('hidden');
        placeholder.classList.add('hidden');
        removeBtn.classList.remove('hidden');
    } else {
        window.clearEditImage();
    }
    
    // ... (resto de tu lógica para abrir el modal) ...
};

/**
 * Control global de modales y previsualización de imágenes
 */

window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
};

// Función específica para abrir el modal de edición y cargar sus datos y previsualización
window.openEditModal = function(button) {
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const code = button.getAttribute('data-code');
    const categoryId = button.getAttribute('data-category_id');
    const stock = button.getAttribute('data-stock');
    const unitCost = button.getAttribute('data-unit_cost');
    const description = button.getAttribute('data-description');
    const imageUrl = button.getAttribute('data-image');

    // Asignar la ruta de actualización dinámicamente al formulario (ej. /products/{id})
    const form = document.getElementById('edit_product_form');
    if (form) {
        form.action = `/products/${id}`;
    }

    // Rellenar los campos del formulario
    document.getElementById('edit_name').value = name || '';
    document.getElementById('edit_code').value = code || '';
    document.getElementById('edit_category_id').value = categoryId || '';
    document.getElementById('edit_stock').value = stock || '';
    document.getElementById('edit_unit_cost').value = unitCost || '';
    document.getElementById('edit_description').value = description || '';

    // Manejar la previsualización de la imagen
    const previewContainer = document.getElementById('edit_image_preview_container');
    const previewImage = document.getElementById('edit_image_preview');
    const fileInput = document.getElementById('edit_image_input');

    if (fileInput) fileInput.value = ''; // Limpiar input file por seguridad

    if (imageUrl && imageUrl.trim() !== '') {
        previewImage.src = imageUrl;
        previewContainer.classList.remove('hidden');
    } else {
        previewImage.src = '';
        previewContainer.classList.add('hidden');
    }

    // Mostrar el modal
    window.openModal('modal-edit');
};

// Previsualización de nueva imagen seleccionada
window.previewEditImage = function(event) {
    const reader = new FileReader();
    const file = event.target.files[0];
    const previewContainer = document.getElementById('edit_image_preview_container');
    const previewImage = document.getElementById('edit_image_preview');

    if (file) {
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        window.clearEditImage();
    }
};

// Remover previsualización con el botón del tache
window.clearEditImage = function() {
    const input = document.getElementById('edit_image_input');
    const previewContainer = document.getElementById('edit_image_preview_container');
    const previewImage = document.getElementById('edit_image_preview');

    if (input) input.value = '';
    if (previewImage) previewImage.src = '';
    if (previewContainer) previewContainer.classList.add('hidden');
};