// scripts/configuracoes.js

const adminModal = document.getElementById('adminModal');
const promotionModal = document.getElementById('promotionModal');
let currentPromotionId = null; // Para rastrear se estamos editando ou adicionando

function showAdminNotification(message, type = "success") {
    // Implementação de notificação simples para o admin
    alert(`${type.toUpperCase()}: ${message}`);
}

async function sendJsonAction(action, data) {
    // Para ações que requerem JSON (como promoções, exclusão de admin/promoção e atualização de config)
    const payload = { ...data, action: action };
    
    try {
        const response = await fetch("processa_admin.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();
        
        if (result.success) {
            showAdminNotification(result.message, "success");
            // Fecha modais se estiverem abertos
            adminModal.classList.remove('active');
            promotionModal.classList.remove('active');
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

function openAdminModal() {
    document.getElementById('adminForm').reset();
    adminModal.classList.add('active');
}

function closeAdminModal() {
    adminModal.classList.remove('active');
}

function deleteAdmin(id) {
    if (confirm('Tem certeza que deseja remover o status de administrador deste usuário?')) {
        sendJsonAction('delete_admin', { id: id });
    }
}

function openPromotionModal(isEdit = false, id = null) {
    const modalTitle = document.querySelector('#promotionModal .modal-header h2');
    modalTitle.textContent = isEdit ? 'Editar Promoção #' + id : 'Nova Promoção';
    document.getElementById('promotionForm').reset();
    currentPromotionId = id;
    
    if (isEdit && id) {
        // NOTA: Em um projeto real, faria-se um FETCH para carregar os dados aqui.
    }
    
    promotionModal.classList.add('active');
}

function closePromotionModal() {
    promotionModal.classList.remove('active');
}

function editPromotion(id) {
    openPromotionModal(true, id);
}

function deletePromotion(id) {
    if (confirm('Tem certeza que deseja excluir esta promoção?')) {
        sendJsonAction('delete_promotion', { id: id });
    }
}

// --- Lógica de Submissão de Formulário ---

// 1. Configurações do Site
document.getElementById('siteConfigForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    const payload = {};
    
    // Converte FormData para objeto simples para enviar como JSON
    formData.forEach((value, key) => {
        payload[key] = value;
    });

    // O backend processa o payload como um array de chave-valor para UPDATE na tabela configuracoes
    sendJsonAction('update_site_config', payload);
});

// 2. Novo Administrador
document.getElementById('adminForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    
    const emailInput = document.querySelector('input[name="admin_email"]');
    const email = emailInput.value;
    
    if (!email) {
        showAdminNotification("Preencha o e-mail do administrador.", "error");
        return;
    }
    
    sendJsonAction('add_admin', { admin_email: email });
});

// 3. Promoção
document.getElementById('promotionForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    const action = currentPromotionId ? 'edit_promotion' : 'add_promotion';
    
    // Converte FormData para objeto simples para enviar como JSON
    const payload = {};
    formData.forEach((value, key) => {
        payload[key] = value;
    });
    
    if (currentPromotionId) {
        payload.id = currentPromotionId;
    }

    sendJsonAction(action, payload);
});

// Sobrescreve as funções placeholder com as novas
window.openAdminModal = openAdminModal;
window.closeAdminModal = closeAdminModal;
window.deleteAdmin = deleteAdmin;
window.openPromotionModal = openPromotionModal;
window.closePromotionModal = closePromotionModal;
window.editPromotion = editPromotion;
window.deletePromotion = deletePromotion;