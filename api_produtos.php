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
    case 'listar':
        listarProdutos($conn);
        break;
    case 'obter':
        obterProduto($conn);
        break;
    case 'criar':
        criarProduto($conn);
        break;
    case 'editar':
        editarProduto($conn);
        break;
    case 'deletar':
        deletarProduto($conn);
        break;
    case 'toggle_esgotado':
        toggleEsgotado($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Ação inválida']);
        break;
}

function listarProdutos($conn) {
    $sql = "SELECT * FROM produtos ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $produtos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $produtos[] = $row;
        }
        echo json_encode(['success' => true, 'produtos' => $produtos]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao listar produtos']);
    }
}

function obterProduto($conn) {
    $id = $_GET['id'] ?? 0;
    
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($produto = mysqli_fetch_assoc($result)) {
        echo json_encode(['success' => true, 'produto' => $produto]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Produto não encontrado']);
    }
}

function criarProduto($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $nome = $data['nome'] ?? '';
    $descricao = $data['descricao'] ?? '';
    $preco = $data['preco'] ?? 0;
    $categoria = $data['categoria'] ?? '';
    $imagem_url = $data['imagem_url'] ?? '';
    $estoque = $data['estoque'] ?? 0;
    
    if (empty($nome) || empty($preco)) {
        echo json_encode(['success' => false, 'message' => 'Nome e preço são obrigatórios']);
        return;
    }
    
    $sql = "INSERT INTO produtos (nome, descricao, preco, categoria, imagem_url, estoque, esgotado) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    $esgotado = ($estoque <= 0) ? 1 : 0;
    mysqli_stmt_bind_param($stmt, "ssdssii", $nome, $descricao, $preco, $categoria, $imagem_url, $estoque, $esgotado);
    
    if (mysqli_stmt_execute($stmt)) {
        $produto_id = mysqli_insert_id($conn);
        
        // Log da ação
        registrarLog($conn, $_SESSION['usuario_id'], 'Criou produto', 'produtos', $produto_id, "Produto: $nome");
        
        echo json_encode(['success' => true, 'message' => 'Produto criado com sucesso', 'id' => $produto_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao criar produto']);
    }
}

function editarProduto($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = $data['id'] ?? 0;
    $nome = $data['nome'] ?? '';
    $descricao = $data['descricao'] ?? '';
    $preco = $data['preco'] ?? 0;
    $categoria = $data['categoria'] ?? '';
    $imagem_url = $data['imagem_url'] ?? '';
    $estoque = $data['estoque'] ?? 0;
    
    if (empty($id) || empty($nome) || empty($preco)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        return;
    }
    
    $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, categoria = ?, 
            imagem_url = ?, estoque = ?, esgotado = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    $esgotado = ($estoque <= 0) ? 1 : 0;
    mysqli_stmt_bind_param($stmt, "ssdsssii", $nome, $descricao, $preco, $categoria, $imagem_url, $estoque, $esgotado, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Log da ação
        registrarLog($conn, $_SESSION['usuario_id'], 'Editou produto', 'produtos', $id, "Produto: $nome");
        
        echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar produto']);
    }
}

function deletarProduto($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    // Verificar se o produto está em algum pedido
    $sql_check = "SELECT COUNT(*) as total FROM itens_pedido WHERE produto_id = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    $row_check = mysqli_fetch_assoc($result_check);
    
    if ($row_check['total'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Não é possível deletar produto que já foi vendido']);
        return;
    }
    
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Log da ação
        registrarLog($conn, $_SESSION['usuario_id'], 'Deletou produto', 'produtos', $id, "ID: $id");
        
        echo json_encode(['success' => true, 'message' => 'Produto deletado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao deletar produto']);
    }
}

function toggleEsgotado($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $sql = "UPDATE produtos SET esgotado = NOT esgotado WHERE id = ?";
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
