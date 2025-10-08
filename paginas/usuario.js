// Estado da aplicação
let isEditing = false;
let showDeleteConfirm = false;

// Dados do usuário
let userData = {
    nomeCompleto: 'Maria Silva Santos',
    email: 'maria.silva@email.com',
    dataNascimento: '1990-05-15',
    cep: '01234-567',
    rua: 'Rua das Flores',
    numero: '123',
    bairro: 'Centro',
    cidade: 'São Paulo',
    estado: 'SP'
};

// Dados temporários para edição
let tempUserData = { ...userData };

// Elementos DOM
const elements = {
    // Botões principais
    editBtn: document.getElementById('edit-btn'),
    saveBtn: document.getElementById('save-btn'),
    cancelBtn: document.getElementById('cancel-btn'),
    logoutBtn: document.getElementById('logout-btn'),
    deleteBtn: document.getElementById('delete-btn'),
    confirmDeleteBtn: document.getElementById('confirm-delete-btn'),
    cancelDeleteBtn: document.getElementById('cancel-delete-btn'),
    
    // Containers
    editActions: document.getElementById('edit-actions'),
    deleteConfirm: document.getElementById('delete-confirm'),
    deleteWarning: document.getElementById('delete-warning'),
    notification: document.getElementById('notification'),
    notificationMessage: document.getElementById('notification-message'),
    
    // Campos de input
    nomeCompleto: document.getElementById('nome-completo'),
    email: document.getElementById('email'),
    dataNascimento: document.getElementById('data-nascimento'),
    cep: document.getElementById('cep'),
    rua: document.getElementById('rua'),
    numero: document.getElementById('numero'),
    bairro: document.getElementById('bairro'),
    cidade: document.getElementById('cidade'),
    estado: document.getElementById('estado'),
    
    // Displays
    nomeDisplay: document.getElementById('nome-display'),
    emailDisplay: document.getElementById('email-display'),
    dataDisplay: document.getElementById('data-display'),
    cepDisplay: document.getElementById('cep-display'),
    ruaDisplay: document.getElementById('rua-display'),
    numeroDisplay: document.getElementById('numero-display'),
    bairroDisplay: document.getElementById('bairro-display'),
    cidadeDisplay: document.getElementById('cidade-display'),
    estadoDisplay: document.getElementById('estado-display')
};

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    initializeData();
    bindEvents();
    setupFormValidation();
});

// Inicializar dados na interface
function initializeData() {
    // Preencher campos de input
    elements.nomeCompleto.value = userData.nomeCompleto;
    elements.email.value = userData.email;
    elements.dataNascimento.value = userData.dataNascimento;
    elements.cep.value = userData.cep;
    elements.rua.value = userData.rua;
    elements.numero.value = userData.numero;
    elements.bairro.value = userData.bairro;
    elements.cidade.value = userData.cidade;
    elements.estado.value = userData.estado;
    
    // Preencher displays
    elements.nomeDisplay.textContent = userData.nomeCompleto;
    elements.emailDisplay.textContent = userData.email;
    elements.dataDisplay.textContent = formatDate(userData.dataNascimento);
    elements.cepDisplay.textContent = userData.cep;
    elements.ruaDisplay.textContent = userData.rua;
    elements.numeroDisplay.textContent = userData.numero;
    elements.bairroDisplay.textContent = userData.bairro;
    elements.cidadeDisplay.textContent = userData.cidade;
    elements.estadoDisplay.textContent = userData.estado;
}

// Vincular eventos
function bindEvents() {
    // Botões de edição
    elements.editBtn.addEventListener('click', handleEdit);
    elements.saveBtn.addEventListener('click', handleSave);
    elements.cancelBtn.addEventListener('click', handleCancel);
    
    // Botões de ação
    elements.logoutBtn.addEventListener('click', handleLogout);
    elements.deleteBtn.addEventListener('click', handleDeleteAccount);
    elements.confirmDeleteBtn.addEventListener('click', handleConfirmDelete);
    elements.cancelDeleteBtn.addEventListener('click', handleCancelDelete);
    
    // Eventos de input para dados temporários
    Object.keys(userData).forEach(key => {
        const element = elements[key] || elements[key.replace(/([A-Z])/g, '-$1').toLowerCase()];
        if (element && element.addEventListener) {
            element.addEventListener('input', (e) => {
                tempUserData[key] = e.target.value;
            });
        }
    });
}

// Configurar validação de formulário
function setupFormValidation() {
    // Máscara para CEP
    elements.cep.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
            tempUserData.cep = value;
        }
    });
    
    // Limitar estado a 2 caracteres
    elements.estado.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase().slice(0, 2);
        tempUserData.estado = e.target.value;
    });
    
    // Validação de email
    elements.email.addEventListener('blur', function(e) {
        const email = e.target.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            showNotification('Por favor, insira um email válido', 'error');
            e.target.focus();
        }
    });
}

// Funções de manipulação de estado
function handleEdit() {
    isEditing = true;
    tempUserData = { ...userData };
    updateEditState();
}

function handleSave() {
    if (validateForm()) {
        userData = { ...tempUserData };
        isEditing = false;
        updateEditState();
        updateDisplays();
        showNotification('Perfil atualizado com sucesso!', 'success');
    }
}

function handleCancel() {
    isEditing = false;
    tempUserData = { ...userData };
    updateEditState();
    resetInputs();
}

function handleLogout() {
    showNotification('Logout realizado com sucesso!', 'success');
    
    // Simular redirecionamento após 2 segundos
    setTimeout(() => {
        if (confirm('Você será redirecionado para a página de login. Continuar?')) {
            window.location.href = '/login';
        }
    }, 2000);
}

function handleDeleteAccount() {
    showDeleteConfirm = true;
    updateDeleteState();
}

function handleConfirmDelete() {
    showNotification('Conta excluída com sucesso!', 'success');
    showDeleteConfirm = false;
    updateDeleteState();
    
    // Simular redirecionamento após 2 segundos
    setTimeout(() => {
        if (confirm('Sua conta foi excluída. Você será redirecionado para a página inicial. Continuar?')) {
            window.location.href = '/';
        }
    }, 2000);
}

function handleCancelDelete() {
    showDeleteConfirm = false;
    updateDeleteState();
}

// Funções de atualização da interface
function updateEditState() {
    const inputs = document.querySelectorAll('.form-input');
    const displays = document.querySelectorAll('.form-display');
    
    if (isEditing) {
        // Mostrar inputs, esconder displays
        inputs.forEach(input => {
            input.style.display = 'block';
            input.removeAttribute('readonly');
        });
        displays.forEach(display => {
            display.style.display = 'none';
        });
        
        // Mostrar botões de edição, esconder botão editar
        elements.editBtn.style.display = 'none';
        elements.editActions.classList.remove('hidden');
    } else {
        // Mostrar displays, esconder inputs
        inputs.forEach(input => {
            input.style.display = 'none';
            input.setAttribute('readonly', true);
        });
        displays.forEach(display => {
            display.style.display = 'block';
        });
        
        // Mostrar botão editar, esconder botões de edição
        elements.editBtn.style.display = 'inline-flex';
        elements.editActions.classList.add('hidden');
    }
}

function updateDeleteState() {
    if (showDeleteConfirm) {
        elements.deleteBtn.style.display = 'none';
        elements.deleteConfirm.classList.remove('hidden');
        elements.deleteWarning.classList.remove('hidden');
    } else {
        elements.deleteBtn.style.display = 'inline-flex';
        elements.deleteConfirm.classList.add('hidden');
        elements.deleteWarning.classList.add('hidden');
    }
}

function updateDisplays() {
    elements.nomeDisplay.textContent = userData.nomeCompleto;
    elements.emailDisplay.textContent = userData.email;
    elements.dataDisplay.textContent = formatDate(userData.dataNascimento);
    elements.cepDisplay.textContent = userData.cep;
    elements.ruaDisplay.textContent = userData.rua;
    elements.numeroDisplay.textContent = userData.numero;
    elements.bairroDisplay.textContent = userData.bairro;
    elements.cidadeDisplay.textContent = userData.cidade;
    elements.estadoDisplay.textContent = userData.estado;
}

function resetInputs() {
    elements.nomeCompleto.value = userData.nomeCompleto;
    elements.email.value = userData.email;
    elements.dataNascimento.value = userData.dataNascimento;
    elements.cep.value = userData.cep;
    elements.rua.value = userData.rua;
    elements.numero.value = userData.numero;
    elements.bairro.value = userData.bairro;
    elements.cidade.value = userData.cidade;
    elements.estado.value = userData.estado;
}

// Funções de validação
function validateForm() {
    const errors = [];
    
    // Validar nome
    if (!tempUserData.nomeCompleto || tempUserData.nomeCompleto.trim().length < 2) {
        errors.push('Nome completo deve ter pelo menos 2 caracteres');
    }
    
    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!tempUserData.email || !emailRegex.test(tempUserData.email)) {
        errors.push('Email deve ter um formato válido');
    }
    
    // Validar data de nascimento
    if (!tempUserData.dataNascimento) {
        errors.push('Data de nascimento é obrigatória');
    } else {
        const birthDate = new Date(tempUserData.dataNascimento);
        const today = new Date();
        const age = today.getFullYear() - birthDate.getFullYear();
        
        if (age < 13 || age > 120) {
            errors.push('Data de nascimento deve ser válida (idade entre 13 e 120 anos)');
        }
    }
    
    // Validar CEP
    const cepRegex = /^\d{5}-?\d{3}$/;
    if (!tempUserData.cep || !cepRegex.test(tempUserData.cep)) {
        errors.push('CEP deve ter o formato 00000-000');
    }
    
    // Validar campos obrigatórios
    const requiredFields = ['rua', 'numero', 'bairro', 'cidade', 'estado'];
    requiredFields.forEach(field => {
        if (!tempUserData[field] || tempUserData[field].trim().length === 0) {
            errors.push(`${getFieldLabel(field)} é obrigatório`);
        }
    });
    
    // Validar estado (2 caracteres)
    if (tempUserData.estado && tempUserData.estado.length !== 2) {
        errors.push('Estado deve ter exatamente 2 caracteres');
    }
    
    if (errors.length > 0) {
        showNotification(errors[0], 'error');
        return false;
    }
    
    return true;
}

// Funções utilitárias
function formatDate(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
}

function getFieldLabel(field) {
    const labels = {
        nomeCompleto: 'Nome Completo',
        email: 'Email',
        dataNascimento: 'Data de Nascimento',
        cep: 'CEP',
        rua: 'Rua',
        numero: 'Número',
        bairro: 'Bairro',
        cidade: 'Cidade',
        estado: 'Estado'
    };
    
    return labels[field] || field;
}

function showNotification(message, type = 'info') {
    elements.notificationMessage.textContent = message;
    elements.notification.className = `notification ${type}`;
    elements.notification.classList.add('show');
    
    // Auto-hide após 3 segundos
    setTimeout(() => {
        elements.notification.classList.remove('show');
    }, 3000);
}

// Funcionalidades adicionais
function animateCard(element) {
    element.style.transform = 'scale(0.98)';
    setTimeout(() => {
        element.style.transform = 'scale(1)';
    }, 100);
}

// Adicionar animações aos cards quando clicados
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Só animar se não for um botão ou input
            if (!e.target.closest('button, input, .btn')) {
                animateCard(this);
            }
        });
    });
});

// Funcionalidade de busca de CEP (simulada)
elements.cep.addEventListener('blur', function(e) {
    const cep = e.target.value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        // Simular busca de CEP
        setTimeout(() => {
            // Dados simulados baseados no CEP
            const cepData = {
                '01234567': {
                    rua: 'Rua das Flores',
                    bairro: 'Centro',
                    cidade: 'São Paulo',
                    estado: 'SP'
                }
            };
            
            if (cepData[cep]) {
                const data = cepData[cep];
                elements.rua.value = data.rua;
                elements.bairro.value = data.bairro;
                elements.cidade.value = data.cidade;
                elements.estado.value = data.estado;
                
                // Atualizar dados temporários
                tempUserData.rua = data.rua;
                tempUserData.bairro = data.bairro;
                tempUserData.cidade = data.cidade;
                tempUserData.estado = data.estado;
                
                showNotification('Endereço preenchido automaticamente!', 'success');
            }
        }, 500);
    }
});

// Atalhos de teclado
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S para salvar
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        if (isEditing) {
            handleSave();
        }
    }
    
    // Escape para cancelar
    if (e.key === 'Escape') {
        if (isEditing) {
            handleCancel();
        }
        if (showDeleteConfirm) {
            handleCancelDelete();
        }
    }
});

// Inicializar estado da interface
updateEditState();
updateDeleteState();