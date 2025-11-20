const employeeModal = document.getElementById('employeeModal');

function openEmployeeModal(isEdit = false) {
    document.getElementById('modalTitle').textContent = isEdit ? 'Editar Funcionário' : 'Novo Funcionário';
    document.getElementById('employeeForm').reset();
    document.getElementById('funcaoField').style.display = 'none';
    document.getElementById('veiculoField').style.display = 'none';
    document.getElementById('placaField').style.display = 'none';
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

// Funções de Ação (apenas log/confirmação)
function editEmployee(id) {
    console.log('Editar entregador: ' + id);
    openEmployeeModal(true);
    document.getElementById('employeeType').value = 'entregador';
    toggleVehicleField();
}

function deleteEmployee(id) {
    if (confirm('Tem certeza que deseja excluir este entregador?')) {
        console.log('Excluir entregador: ' + id);
    }
}

function editStaff(id) {
    console.log('Editar funcionário: ' + id);
    openEmployeeModal(true);
    document.getElementById('employeeType').value = 'funcionario';
    toggleVehicleField();
}

function deleteStaff(id) {
    if (confirm('Tem certeza que deseja excluir este funcionário?')) {
        console.log('Excluir funcionário: ' + id);
    }
}