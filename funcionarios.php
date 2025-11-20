<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Funcionários</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="dashboard-header">
            <h1>Funcionários</h1>
            <button class="btn-primary" onclick="openEmployeeModal()">
                <i class="fas fa-plus"></i> Novo Funcionário
            </button>
        </div>

        <div class="chart-card" style="margin-bottom: 2rem;">
            <h2>Entregadores</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Veículo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#E001</td>
                            <td>Carlos Silva</td>
                            <td>carlos@email.com</td>
                            <td>(11) 91234-5678</td>
                            <td>Moto - ABC-1234</td>
                            <td>
                                <a href="#" onclick="editEmployee(1)" class="btn-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="deleteEmployee(1)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#E002</td>
                            <td>José Alves</td>
                            <td>jose@email.com</td>
                            <td>(11) 91234-8765</td>
                            <td>Carro - XYZ-5678</td>
                            <td>
                                <a href="#" onclick="editEmployee(2)" class="btn-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="deleteEmployee(2)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#E003</td>
                            <td>Ana Paula</td>
                            <td>ana.p@email.com</td>
                            <td>(11) 91234-4321</td>
                            <td>Bicicleta</td>
                            <td>
                                <a href="#" onclick="editEmployee(3)" class="btn-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="deleteEmployee(3)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="chart-card">
            <h2>Funcionários</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Função</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#F001</td>
                            <td>Mariana Santos</td>
                            <td>mariana@email.com</td>
                            <td>(11) 92345-6789</td>
                            <td>Atendente</td>
                            <td>
                                <a href="#" onclick="editStaff(1)" class="btn-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="deleteStaff(1)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#F002</td>
                            <td>Roberto Costa</td>
                            <td>roberto@email.com</td>
                            <td>(11) 92345-9876</td>
                            <td>Cozinha</td>
                            <td>
                                <a href="#" onclick="editStaff(2)" class="btn-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="deleteStaff(2)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#F003</td>
                            <td>Paula Lima</td>
                            <td>paula@email.com</td>
                            <td>(11) 92345-1234</td>
                            <td>Caixa</td>
                            <td>
                                <a href="#" onclick="editStaff(3)" class="btn-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="deleteStaff(3)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Funcionário -->
    <div id="employeeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Novo Funcionário</h2>
                <button class="modal-close" onclick="closeEmployeeModal()">&times;</button>
            </div>
            <form id="employeeForm">
                <div class="form-group">
                    <label>Tipo de Funcionário *</label>
                    <select name="tipo" id="employeeType" onchange="toggleVehicleField()" required>
                        <option value="">Selecione</option>
                        <option value="entregador">Entregador</option>
                        <option value="funcionario">Funcionário</option>
                    </select>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nome Completo *</label>
                        <input type="text" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Telefone *</label>
                        <input type="tel" name="telefone" required>
                    </div>
                    <div class="form-group" id="funcaoField" style="display: none;">
                        <label>Função *</label>
                        <select name="funcao">
                            <option value="">Selecione</option>
                            <option value="atendente">Atendente</option>
                            <option value="cozinha">Cozinha</option>
                            <option value="caixa">Caixa</option>
                        </select>
                    </div>
                    <div class="form-group" id="veiculoField" style="display: none;">
                        <label>Tipo de Veículo *</label>
                        <select name="veiculo_tipo">
                            <option value="">Selecione</option>
                            <option value="bicicleta">Bicicleta</option>
                            <option value="moto">Moto</option>
                            <option value="carro">Carro</option>
                        </select>
                    </div>
                    <div class="form-group" id="placaField" style="display: none;">
                        <label>Placa do Veículo</label>
                        <input type="text" name="placa" placeholder="ABC-1234">
                    </div>
                </div>
                <div class="form-group">
                    <label>Endereço Completo</label>
                    <textarea name="endereco"></textarea>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="button" class="btn-secondary" onclick="closeEmployeeModal()">Cancelar</button>
                    <button type="submit" class="btn-primary">Salvar Funcionário</button>
                </div>
            </form>
        </div>
    </div>

    <script src="scripts/funcionarios.js"></script>
</body>
</html>
