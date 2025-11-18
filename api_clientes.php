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
        listarClientes($conn);
        break;
    case 'detalhes':
        obterDetalhesCliente($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Ação inválida']);
        break;
}

function listarClientes($conn) {
    $sql = "SELECT u.id, u.nome, u.email, u.celular, u.data_cadastro,
                   COUNT(DISTINCT p.id) as total_pedidos,
                   COALESCE(SUM(p.valor_total), 0) as valor_total_compras
            FROM usuarios u
            LEFT JOIN pedidos p ON u.id = p.usuario_id
            WHERE u.isAdmin = 0
            GROUP BY u.id
            ORDER BY u.data_cadastro DESC";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $clientes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $clientes[] = $row;
        }
        echo json_encode(['success' => true, 'clientes' => $clientes]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao listar clientes']);
    }
}

function obterDetalhesCliente($conn) {
    $id = $_GET['id'] ?? 0;
    
    // Informações do cliente
    $sql_cliente = "SELECT u.*, e.cep, e.rua, e.numero, e.bairro, e.cidade, e.estado
                    FROM usuarios u
                    LEFT JOIN enderecos e ON u.id = e.usuario_id
                    WHERE u.id = ?";
    
    $stmt = mysqli_prepare($conn, $sql_cliente);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($cliente = mysqli_fetch_assoc($result)) {
        // Histórico de compras
        $sql_compras = "SELECT p.id, p.data_pedido, p.status, p.valor_total, p.status_pagamento,
                               COUNT(ip.id) as total_itens
                        FROM pedidos p
                        LEFT JOIN itens_pedido ip ON p.id = ip.pedido_id
                        WHERE p.usuario_id = ?
                        GROUP BY p.id
                        ORDER BY p.data_pedido DESC";
        
        $stmt_compras = mysqli_prepare($conn, $sql_compras);
        mysqli_stmt_bind_param($stmt_compras, "i", $id);
        mysqli_stmt_execute($stmt_compras);
        $result_compras = mysqli_stmt_get_result($stmt_compras);
        
        $compras = [];
        while ($compra = mysqli_fetch_assoc($result_compras)) {
            $compras[] = $compra;
        }
        
        $cliente['historico_compras'] = $compras;
        
        echo json_encode(['success' => true, 'cliente' => $cliente]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cliente não encontrado']);
    }
}

mysqli_close($conn);
?>
