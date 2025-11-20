<?php
session_start();
// Inclusão da verificação de admin logado deve ser adicionada
include 'db_config.php';

$entregadores = [];
$funcionarios = [];

// Busca entregadores
$sql_entregadores = "SELECT id, nome, email, telefone, veiculo_tipo, placa FROM equipe WHERE tipo = 'entregador' ORDER BY id";
if ($result = $conn->query($sql_entregadores)) {
    while ($row = $result->fetch_assoc()) {
        $row['veiculo_info'] = $row['veiculo_tipo'] . ($row['placa'] ? ' - ' . $row['placa'] : '');
        $entregadores[] = $row;
    }
    $result->free();
}

// Busca funcionários
$sql_funcionarios = "SELECT id, nome, email, telefone, funcao FROM equipe WHERE tipo = 'funcionario' ORDER BY id";
if ($result = $conn->query($sql_funcionarios)) {
    while ($row = $result->fetch_assoc()) {
        $funcionarios[] = $row;
    }
    $result->free();
}

$conn->close();
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
                        <?php foreach ($entregadores as $e): ?>
                        <tr>
                            <td>#E<?php echo htmlspecialchars(str_pad($e['id'], 3, '0', STR_PAD_LEFT)); ?></td>
                            <td><?php echo htmlspecialchars($e['nome']); ?></td>
                            <td><?php echo htmlspecialchars($e['email']); ?></td>
                            <td><?php echo htmlspecialchars($e['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($e['veiculo_info']); ?></td>
                            <td>
                                <a href="#" onclick="editEmployee(<?php echo $e['id']; ?>)" class="btn-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="deleteEmployee(<?php echo $e['id']; ?>)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
                        <?php foreach ($funcionarios as $f): ?>
                        <tr>
                            <td>#F<?php echo htmlspecialchars(str_pad($f['id'], 3, '0', STR_PAD_LEFT)); ?></td>
                            <td><?php echo htmlspecialchars($f['nome']); ?></td>
                            <td><?php echo htmlspecialchars($f['email']); ?></td>
                            <td><?php echo htmlspecialchars($f['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($f['funcao']); ?></td>
                            <td>
                                <a href="#" onclick="editStaff(<?php echo $f['id']; ?>)" class="btn-icon"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="deleteStaff(<?php echo $f['id']; ?>)" class="btn-icon"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

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

    </main>

    <script src="scripts/funcionarios.js"></script>
</body>
</html>