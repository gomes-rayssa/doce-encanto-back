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

// NOVO: Função para buscar dados do funcionário
async function fetchEmployeeData(id) {
    try {
        const response = await fetch("processa_admin.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ action: 'fetch_employee', id: id }),
        });
        const result = await response.json();
        if (result.success && result.data) {
            return result.data;
        } else {
            showAdminNotification(result.message || 'Erro ao buscar dados do funcionário.', "error");
            return null;
        }
    } catch (error) {
        console.error("Erro no fetch:", error);
        showAdminNotification("Erro de conexão ao buscar dados do funcionário.", "error");
        return null;
    }
}

async function openEmployeeModal(isEdit = false, id = null) {
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('employeeForm');
    
    // Limpar form e variáveis de estado
    form.reset();
    currentEmployeeId = id;
    document.getElementById('funcaoField').style.display = 'none';
    document.getElementById('veiculoField').style.display = 'none';
    document.getElementById('placaField').style.display = 'none';


    if (isEdit && id) {
        modalTitle.textContent = 'Carregando Funcionário #' + id;
        const employeeData = await fetchEmployeeData(id); // CHAMA FETCH AQUI

        if (employeeData) {
            modalTitle.textContent = 'Editar Funcionário #' + id;
            // Preencher campos comuns
            form.elements['nome'].value = employeeData.nome;
            form.elements['email'].value = employeeData.email;
            form.elements['telefone'].value = employeeData.telefone;
            form.elements['endereco'].value = employeeData.endereco;
            
            // Tipo de Funcionário
            form.elements['tipo'].value = employeeData.tipo;
            
            // Chamar toggle para exibir campos corretos
            toggleVehicleField(); 

            // Preencher campos específicos após o toggle
            if (employeeData.tipo === 'funcionario') {
                // Preenche a função se for funcionário interno
                form.elements['funcao'].value = employeeData.funcao;
            } else if (employeeData.tipo === 'entregador') {
                // Preenche veículo e placa se for entregador
                form.elements['veiculo_tipo'].value = employeeData.veiculo_tipo;
                form.elements['placa'].value = employeeData.placa;
            }

        } else {
            // Se falhar, fechar e notificar
            closeEmployeeModal();
            return;
        }
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

// Lógica de Submissão do Formulário de Funcionário (MANTIDA)
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


// Funções de Ação para Entregadores (Atualizada para usar a nova lógica)
function editEmployee(id) {
    openEmployeeModal(true, id);
}

function deleteEmployee(id) {
    if (confirm('Tem certeza que deseja excluir este entregador?')) {
        sendAdminAction('delete_employee', { id: id });
    }
}

// Funções de Ação para Funcionários Internos (Atualizada para usar a nova lógica)
function editStaff(id) {
    openEmployeeModal(true, id);
}

function deleteStaff(id) {
    if (confirm('Tem certeza que deseja excluir este funcionário?')) {
        sendAdminAction('delete_employee', { id: id });
    }
}