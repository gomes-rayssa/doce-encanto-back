<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

// Verificar se é administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Acesso negado']);
    exit;
}

$acao = $_GET['acao'] ?? '';
$tipo = $_GET['tipo'] ?? 'funcionario'; // 'funcionario' ou 'entregador'

switch ($acao) {
    case 'listar':
        listarFuncionarios($conn, $tipo);
        break;
    case 'obter':
        obterFuncionario($conn, $tipo);
        break;
    case 'criar':
        criarFuncionario($conn, $tipo);
        break;
    case 'editar':
        editarFuncionario($conn, $tipo);
        break;
    case 'deletar':
        deletarFuncionario($conn, $tipo);
        break;
    case 'toggle_ativo':
        toggleAtivo($conn, $tipo);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Ação inválida']);
        break;
}

function listarFuncionarios($conn, $tipo) {
    $tabela = ($tipo === 'entregador') ? 'entregadores' : 'funcionarios';
    $sql = "SELECT * FROM $tabela ORDER BY ativo DESC, nome ASC";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $funcionarios = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $funcionarios[] = $row;
        }
        echo json_encode(['success' => true, 'funcionarios' => $funcionarios]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao listar']);
    }
}

function obterFuncionario($conn, $tipo) {
    $id = $_GET['id'] ?? 0;
    $tabela = ($tipo === 'entregador') ? 'entregadores' : 'funcionarios';
    
    $sql = "SELECT * FROM $tabela WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($funcionario = mysqli_fetch_assoc($result)) {
        echo json_encode(['success' => true, 'funcionario' => $funcionario]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Não encontrado']);
    }
}

function criarFuncionario($conn, $tipo) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $nome = $data['nome'] ?? '';
    $celular = $data['celular'] ?? '';
    $email = $data['email'] ?? '';
    $cep = $data['cep'] ?? '';
    $rua = $data['rua'] ?? '';
    $numero = $data['numero'] ?? '';
    $bairro = $data['bairro'] ?? '';
    $cidade = $data['cidade'] ?? '';
    $estado = $data['estado'] ?? '';
    
    if (empty($nome) || empty($celular) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Nome, celular e email são obrigatórios']);
        return;
    }
    
    if ($tipo === 'entregador') {
        $veiculo = $data['veiculo'] ?? '';
        if (empty($veiculo)) {
            echo json_encode(['success' => false, 'message' => 'Veículo é obrigatório para entregadores']);
            return;
        }
        
        $sql = "INSERT INTO entregadores (nome, celular, email, cep, rua, numero, bairro, cidade, estado, veiculo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $nome, $celular, $email, $cep, $rua, $numero, $bairro, $cidade, $estado, $veiculo);
    } else {
        $funcao = $data['funcao'] ?? '';
        if (empty($funcao)) {
            echo json_encode(['success' => false, 'message' => 'Função é obrigatória']);
            return;
        }
        
        $sql = "INSERT INTO funcionarios (nome, celular, email, cep, rua, numero, bairro, cidade, estado, funcao) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $nome, $celular, $email, $cep, $rua, $numero, $bairro, $cidade, $estado, $funcao);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $id = mysqli_insert_id($conn);
        
        // Log da ação
        $tabela = ($tipo === 'entregador') ? 'entregadores' : 'funcionarios';
        registrarLog($conn, $_SESSION['usuario_id'], "Criou $tipo", $tabela, $id, "Nome: $nome");
        
        echo json_encode(['success' => true, 'message' => ucfirst($tipo) . ' criado com sucesso', 'id' => $id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao criar']);
    }
}

function editarFuncionario($conn, $tipo) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = $data['id'] ?? 0;
    $nome = $data['nome'] ?? '';
    $celular = $data['celular'] ?? '';
    $email = $data['email'] ?? '';
    $cep = $data['cep'] ?? '';
    $rua = $data['rua'] ?? '';
    $numero = $data['numero'] ?? '';
    $bairro = $data['bairro'] ?? '';
    $cidade = $data['cidade'] ?? '';
    $estado = $data['estado'] ?? '';
    
    if (empty($id) || empty($nome) || empty($celular) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        return;
    }
    
    if ($tipo === 'entregador') {
        $veiculo = $data['veiculo'] ?? '';
        if (empty($veiculo)) {
            echo json_encode(['success' => false, 'message' => 'Veículo é obrigatório']);
            return;
        }
        
        $sql = "UPDATE entregadores SET nome = ?, celular = ?, email = ?, cep = ?, rua = ?, 
                numero = ?, bairro = ?, cidade = ?, estado = ?, veiculo = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssssi", $nome, $celular, $email, $cep, $rua, $numero, $bairro, $cidade, $estado, $veiculo, $id);
    } else {
        $funcao = $data['funcao'] ?? '';
        if (empty($funcao)) {
            echo json_encode(['success' => false, 'message' => 'Função é obrigatória']);
            return;
        }
        
        $sql = "UPDATE funcionarios SET nome = ?, celular = ?, email = ?, cep = ?, rua = ?, 
                numero = ?, bairro = ?, cidade = ?, estado = ?, funcao = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssssi", $nome, $celular, $email, $cep, $rua, $numero, $bairro, $cidade, $estado, $funcao, $id);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        // Log da ação
        $tabela = ($tipo === 'entregador') ? 'entregadores' : 'funcionarios';
        registrarLog($conn, $_SESSION['usuario_id'], "Editou $tipo", $tabela, $id, "Nome: $nome");
        
        echo json_encode(['success' => true, 'message' => ucfirst($tipo) . ' atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar']);
    }
}

function deletarFuncionario($conn, $tipo) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $tabela = ($tipo === 'entregador') ? 'entregadores' : 'funcionarios';
    $sql = "DELETE FROM $tabela WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Log da ação
        registrarLog($conn, $_SESSION['usuario_id'], "Deletou $tipo", $tabela, $id, "ID: $id");
        
        echo json_encode(['success' => true, 'message' => ucfirst($tipo) . ' deletado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao deletar']);
    }
}

function toggleAtivo($conn, $tipo) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $tabela = ($tipo === 'entregador') ? 'entregadores' : 'funcionarios';
    $sql = "UPDATE $tabela SET ativo = NOT ativo WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Status atualizado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status']);
    }
}

function registrarLog($conn, $admin_id, $acao, $tabela, $registro_id, $detalhes) {
    $sql = "INSERT INTO log_admin (admin_id, acao, tabela, registro_id, detalhes) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issis", $admin_id, $acao, $tabela, $registro_id, $detalhes);
    mysqli_stmt_execute($stmt);
}

mysqli_close($conn);
?>
