// scripts/funcionarios.js

const employeeModal = document.getElementById('employeeModal');
let currentEmployeeId = null; // Para rastrear se estamos editando ou adicionando

function showAdminNotification(message, type = "success") {
    // Implementação de notificação simples para o admin
    alert(`${type.toUpperCase()}: ${message}`);
}

async function sendAdminAction(action, data) {
    // Cria FormData para enviar dados, mesmo que não haja arquivo
    const formData = new FormData();
    for (const key in data) {
        formData.append(key, data[key]);
    }
    
    formData.append('action', action);

    try {
        const response = await fetch("processa_admin.php", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();
        
        if (result.success) {
            showAdminNotification(result.message, "success");
            closeEmployeeModal();
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


function openEmployeeModal(isEdit = false, id = null) {
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('employeeForm');
    
    // Limpar form e variáveis de estado
    form.reset();
    currentEmployeeId = id;
    document.getElementById('funcaoField').style.display = 'none';
    document.getElementById('veiculoField').style.display = 'none';
    document.getElementById('placaField').style.display = 'none';


    if (isEdit && id) {
        modalTitle.textContent = 'Editar Funcionário #' + id;
        // NOTA: Em um projeto real, faria-se um FETCH para carregar os dados aqui.
    } else {
        modalTitle.textContent = 'Novo Funcionário';
    }

    employeeModal.classList.add('active');
}

function closeEmployeeModal() {
    employeeModal.classList.remove('active');
}

function toggleVehicleField() {
    const type = document.getElementById('employeeType').value;
    const funcaoField = document.getElementById('funcaoField');
    const veiculoField = document.getElementById('veiculoField');
    const placaField = document.getElementById('placaField');

    if (type === 'entregador') {
        funcaoField.style.display = 'none';
        veiculoField.style.display = 'flex';
        placaField.style.display = 'flex';
    } else if (type === 'funcionario') {
        funcaoField.style.display = 'flex';
        veiculoField.style.display = 'none';
        placaField.style.display = 'none';
    } else {
        funcaoField.style.display = 'none';
        veiculoField.style.display = 'none';
        placaField.style.display = 'none';
    }
}

// Lógica de Submissão do Formulário de Funcionário
document.getElementById('employeeForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    const action = currentEmployeeId ? 'edit_employee' : 'add_employee';
    
    formData.append('action', action);
    if (currentEmployeeId) {
        formData.append('id', currentEmployeeId);
    }
    
    fetch("processa_admin.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAdminNotification(result.message, "success");
            closeEmployeeModal();
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


// Funções de Ação para Entregadores
function editEmployee(id) {
    openEmployeeModal(true, id);
    // NOTA: O carregamento dos dados de tipo/veículo deve ser feito via AJAX aqui.
}

function deleteEmployee(id) {
    if (confirm('Tem certeza que deseja excluir este entregador?')) {
        sendAdminAction('delete_employee', { id: id });
    }
}

// Funções de Ação para Funcionários Internos
function editStaff(id) {
    openEmployeeModal(true, id);
    // NOTA: O carregamento dos dados de tipo/função deve ser feito via AJAX aqui.
}

function deleteStaff(id) {
    if (confirm('Tem certeza que deseja excluir este funcionário?')) {
        sendAdminAction('delete_employee', { id: id });
    }
}