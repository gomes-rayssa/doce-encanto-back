const adminModal = document.getElementById('adminModal');
const promotionModal = document.getElementById('promotionModal');

function openAdminModal() {
    document.getElementById('adminForm').reset();
    adminModal.classList.add('active');
}

function closeAdminModal() {
    adminModal.classList.remove('active');
}

function deleteAdmin(id) {
    if (confirm('Tem certeza que deseja remover este administrador?')) {
        console.log('Remover administrador: ' + id);
    }
}

function openPromotionModal(isEdit = false) {
    document.querySelector('#promotionModal .modal-header h2').textContent = isEdit ? 'Editar Promoção' : 'Nova Promoção';
    document.getElementById('promotionForm').reset();
    promotionModal.classList.add('active');
}

function closePromotionModal() {
    promotionModal.classList.remove('active');
}

function editPromotion(id) {
    console.log('Editar promoção: ' + id);
    openPromotionModal(true);
}

function deletePromotion(id) {
    if (confirm('Tem certeza que deseja excluir esta promoção?')) {
        console.log('Excluir promoção: ' + id);
    }
}

// Simulação de submissão de formulário
document.getElementById('siteConfigForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Configurações do Site salvas!');
    console.log('Configurações do Site salvas.');
});

document.getElementById('adminForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Novo Administrador criado!');
    closeAdminModal();
});

document.getElementById('promotionForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Nova Promoção criada!');
    closePromotionModal();
});