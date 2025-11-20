// scripts/produtos.js

const productModal = document.getElementById('productModal');
let currentProductId = null; // Para rastrear se estamos editando ou adicionando

function showAdminNotification(message, type = "success") {
    // Implementação de notificação simples para o admin
    alert(`${type.toUpperCase()}: ${message}`);
    // Em um projeto real, você usaria uma implementação de toast/snackbar mais sofisticada
}

async function sendAdminAction(action, data) {
    // Cria FormData para suportar envio de dados e arquivos (se necessário)
    const formData = new FormData();
    for (const key in data) {
        formData.append(key, data[key]);
    }
    
    // Adicionar a ação ao FormData
    formData.append('action', action);

    try {
        const response = await fetch("processa_admin.php", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();
        
        if (result.success) {
            showAdminNotification(result.message, "success");
            productModal.classList.remove('active'); // Fecha o modal se estiver aberto
            setTimeout(() => {
                window.location.reload(); 
            }, 500);
        } else {
            showAdminNotification(result.message, "error");
        }
    } catch (error) {
        console.error("Erro no fetch:", error);
        showAdminNotification("Erro de conexão com o servidor.", "error");
    }
}


function openProductModal(isEdit = false, id = null) {
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('productForm');
    
    // Limpar form e variáveis de estado
    form.reset();
    document.getElementById('imagePreview').innerHTML = '<span style="color: var(--text-light);">Nenhuma imagem selecionada</span>';
    currentProductId = id;

    if (isEdit && id) {
        modalTitle.textContent = 'Editar Produto #' + id;
        // NOTA: Em um projeto real, você faria um FETCH para carregar os dados do produto pelo ID aqui.
    } else {
        modalTitle.textContent = 'Novo Produto';
    }
    
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

// Lógica de Submissão do Formulário de Produto
document.getElementById('productForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    const action = currentProductId ? 'edit_product' : 'add_product';
    
    formData.append('action', action);
    if (currentProductId) {
        formData.append('id', currentProductId);
    }
    
    // Adicionar a imagem se estiver presente no input file
    const imageInput = document.querySelector('input[name="imagem"]');
    if (imageInput && imageInput.files.length > 0) {
        // O FormData(this) já deve incluir o arquivo, mas adicionamos novamente para garantir o nome 'imagem'
        formData.append('imagem', imageInput.files[0]);
    }

    fetch("processa_admin.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAdminNotification(result.message, "success");
            closeProductModal();
            setTimeout(() => {
                window.location.reload(); 
            }, 500);
        } else {
            showAdminNotification(result.message, "error");
        }
    })
    .catch(error => {
        console.error("Erro no fetch:", error);
        showAdminNotification("Erro de conexão com o servidor.", "error");
    });
});


// Funções de Ação (para disparar o modal/backend)
function editProduct(id) {
    openProductModal(true, id);
}

function deleteProduct(id) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        // Envia a exclusão via FormData simples
        sendAdminAction('delete_product', { id: id });
    }
}