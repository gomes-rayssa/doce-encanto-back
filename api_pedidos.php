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
        listarPedidos($conn);
        break;
    case 'detalhes':
        obterDetalhesPedido($conn);
        break;
    case 'atualizar_status':
        atualizarStatus($conn);
        break;
    case 'atualizar_entregador':
        atualizarEntregador($conn);
        break;
    case 'enviar_nota_fiscal':
        enviarNotaFiscal($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Ação inválida']);
        break;
}

function listarPedidos($conn) {
    $sql = "SELECT p.id, p.data_pedido, p.status, p.valor_total, p.status_pagamento,
                   u.nome as cliente_nome, u.email as cliente_email
            FROM pedidos p
            LEFT JOIN usuarios u ON p.usuario_id = u.id
            ORDER BY p.data_pedido DESC";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $pedidos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $pedidos[] = $row;
        }
        echo json_encode(['success' => true, 'pedidos' => $pedidos]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao listar pedidos']);
    }
}

function obterDetalhesPedido($conn) {
    $id = $_GET['id'] ?? 0;
    
    // Informações do pedido
    $sql_pedido = "SELECT p.*, u.nome as cliente_nome, u.email as cliente_email, u.celular as cliente_celular,
                          e.cep, e.rua, e.numero, e.bairro, e.cidade, e.estado,
                          ent.nome as entregador_nome, ent.veiculo as entregador_veiculo
                   FROM pedidos p
                   LEFT JOIN usuarios u ON p.usuario_id = u.id
                   LEFT JOIN enderecos e ON p.endereco_entrega_id = e.id
                   LEFT JOIN entregadores ent ON p.entregador_id = ent.id
                   WHERE p.id = ?";
    
    $stmt = mysqli_prepare($conn, $sql_pedido);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($pedido = mysqli_fetch_assoc($result)) {
        // Itens do pedido
        $sql_itens = "SELECT ip.*, pr.nome as produto_nome, pr.categoria
                      FROM itens_pedido ip
                      LEFT JOIN produtos pr ON ip.produto_id = pr.id
                      WHERE ip.pedido_id = ?";
        
        $stmt_itens = mysqli_prepare($conn, $sql_itens);
        mysqli_stmt_bind_param($stmt_itens, "i", $id);
        mysqli_stmt_execute($stmt_itens);
        $result_itens = mysqli_stmt_get_result($stmt_itens);
        
        $itens = [];
        while ($item = mysqli_fetch_assoc($result_itens)) {
            $itens[] = $item;
        }
        
        // Histórico de status
        $sql_historico = "SELECT h.*, u.nome as alterado_por_nome
                          FROM historico_status_pedido h
                          LEFT JOIN usuarios u ON h.alterado_por = u.id
                          WHERE h.pedido_id = ?
                          ORDER BY h.data_alteracao DESC";
        
        $stmt_historico = mysqli_prepare($conn, $sql_historico);
        mysqli_stmt_bind_param($stmt_historico, "i", $id);
        mysqli_stmt_execute($stmt_historico);
        $result_historico = mysqli_stmt_get_result($stmt_historico);
        
        $historico = [];
        while ($hist = mysqli_fetch_assoc($result_historico)) {
            $historico[] = $hist;
        }
        
        $pedido['itens'] = $itens;
        $pedido['historico'] = $historico;
        
        echo json_encode(['success' => true, 'pedido' => $pedido]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Pedido não encontrado']);
    }
}

function atualizarStatus($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $pedido_id = $data['pedido_id'] ?? 0;
    $novo_status = $data['status'] ?? '';
    
    if (empty($pedido_id) || empty($novo_status)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        return;
    }
    
    // Buscar status anterior
    $sql_status = "SELECT status FROM pedidos WHERE id = ?";
    $stmt_status = mysqli_prepare($conn, $sql_status);
    mysqli_stmt_bind_param($stmt_status, "i", $pedido_id);
    mysqli_stmt_execute($stmt_status);
    $result_status = mysqli_stmt_get_result($stmt_status);
    $row_status = mysqli_fetch_assoc($result_status);
    $status_anterior = $row_status['status'] ?? null;
    
    // Atualizar status
    $sql = "UPDATE pedidos SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $novo_status, $pedido_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Registrar no histórico
        $sql_hist = "INSERT INTO historico_status_pedido (pedido_id, status_anterior, status_novo, alterado_por) 
                     VALUES (?, ?, ?, ?)";
        $stmt_hist = mysqli_prepare($conn, $sql_hist);
        $admin_id = $_SESSION['usuario_id'];
        mysqli_stmt_bind_param($stmt_hist, "issi", $pedido_id, $status_anterior, $novo_status, $admin_id);
        mysqli_stmt_execute($stmt_hist);
        
        // Log da ação
        registrarLog($conn, $admin_id, 'Atualizou status do pedido', 'pedidos', $pedido_id, "De '$status_anterior' para '$novo_status'");
        
        echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status']);
    }
}

function atualizarEntregador($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $pedido_id = $data['pedido_id'] ?? 0;
    $entregador_id = $data['entregador_id'] ?? null;
    
    if (empty($pedido_id)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        return;
    }
    
    $sql = "UPDATE pedidos SET entregador_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $entregador_id, $pedido_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Log da ação
        registrarLog($conn, $_SESSION['usuario_id'], 'Atribuiu entregador ao pedido', 'pedidos', $pedido_id, "Entregador ID: $entregador_id");
        
        echo json_encode(['success' => true, 'message' => 'Entregador atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar entregador']);
    }
}

function enviarNotaFiscal($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $pedido_id = $data['pedido_id'] ?? 0;
    
    if (empty($pedido_id)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        return;
    }
    
    // Marcar nota fiscal como enviada
    $sql = "UPDATE pedidos SET nota_fiscal_enviada = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $pedido_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Log da ação
        registrarLog($conn, $_SESSION['usuario_id'], 'Enviou nota fiscal', 'pedidos', $pedido_id, "Pedido ID: $pedido_id");
        
        // Aqui você pode adicionar lógica para enviar email com a nota fiscal
        
        echo json_encode(['success' => true, 'message' => 'Nota fiscal enviada com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao enviar nota fiscal']);
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
