const productModal = document.getElementById('productModal');

function openProductModal(isEdit = false) {
    document.getElementById('modalTitle').textContent = isEdit ? 'Editar Produto' : 'Novo Produto';
    document.getElementById('productForm').reset();
    document.getElementById('imagePreview').innerHTML = '<span style="color: var(--text-light);">Nenhuma imagem selecionada</span>';
    productModal.classList.add('active');
}

function closeProductModal() {
    productModal.classList.remove('active');
}

function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Preview do Produto';
            preview.appendChild(img);
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '<span style="color: var(--text-light);">Nenhuma imagem selecionada</span>';
    }
}

// Funções de Ação (apenas log/confirmação)
function editProduct(id) {
    console.log('Editar produto: ' + id);
    openProductModal(true);
}

function deleteProduct(id) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        console.log('Excluir produto: ' + id);
    }
}