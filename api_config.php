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

switch ($acao) {
    case 'listar_admins':
        listarAdmins($conn);
        break;
    case 'criar_admin':
        criarAdmin($conn);
        break;
    case 'remover_admin':
        removerAdmin($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Ação inválida']);
        break;
}

function listarAdmins($conn) {
    $sql = "SELECT id, nome, email, data_cadastro FROM usuarios WHERE isAdmin = 1 ORDER BY nome ASC";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $admins = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $admins[] = $row;
        }
        echo json_encode(['success' => true, 'admins' => $admins]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao listar administradores']);
    }
}

function criarAdmin($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $email = $data['email'] ?? '';
    $nome = $data['nome'] ?? 'Administrador';
    
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email é obrigatório']);
        return;
    }
    
    // Verificar se email já existe
    $sql_check = "SELECT id FROM usuarios WHERE email = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Email já cadastrado']);
        return;
    }
    
    // Senha padrão: Doce2025@
    $senha_padrao = 'Doce2025@';
    $senha_hash = password_hash($senha_padrao, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO usuarios (nome, email, senha_hash, isAdmin) VALUES (?, ?, ?, 1)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $senha_hash);
    
    if (mysqli_stmt_execute($stmt)) {
        $admin_id = mysqli_insert_id($conn);
        
        // Log da ação
        registrarLog($conn, $_SESSION['usuario_id'], 'Criou novo administrador', 'usuarios', $admin_id, "Email: $email");
        
        echo json_encode(['success' => true, 'message' => 'Administrador criado com sucesso', 'id' => $admin_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao criar administrador']);
    }
}

function removerAdmin($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    // Não permitir remover a si mesmo
    if ($id == $_SESSION['usuario_id']) {
        echo json_encode(['success' => false, 'message' => 'Você não pode remover seu próprio acesso']);
        return;
    }
    
    // Verificar se há pelo menos 2 admins
    $sql_count = "SELECT COUNT(*) as total FROM usuarios WHERE isAdmin = 1";
    $result_count = mysqli_query($conn, $sql_count);
    $row_count = mysqli_fetch_assoc($result_count);
    
    if ($row_count['total'] <= 1) {
        echo json_encode(['success' => false, 'message' => 'Não é possível remover o único administrador']);
        return;
    }
    
    $sql = "UPDATE usuarios SET isAdmin = 0 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Log da ação
        registrarLog($conn, $_SESSION['usuario_id'], 'Removeu permissão de administrador', 'usuarios', $id, "ID: $id");
        
        echo json_encode(['success' => true, 'message' => 'Permissão de administrador removida']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover administrador']);
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
