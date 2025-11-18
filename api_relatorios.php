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
    case 'vendas':
        relatorioVendas($conn);
        break;
    case 'produtos_mais_vendidos':
        produtosMaisVendidos($conn);
        break;
    case 'estoque_baixo':
        estoqueBaixo($conn);
        break;
    case 'dashboard':
        dashboardGeral($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Ação inválida']);
        break;
}

function relatorioVendas($conn) {
    $periodo = $_GET['periodo'] ?? 'mes'; // dia, semana, mes, ano
    
    $data_inicio = '';
    $data_fim = date('Y-m-d 23:59:59');
    
    switch ($periodo) {
        case 'dia':
            $data_inicio = date('Y-m-d 00:00:00');
            break;
        case 'semana':
            $data_inicio = date('Y-m-d 00:00:00', strtotime('-7 days'));
            break;
        case 'mes':
            $data_inicio = date('Y-m-d 00:00:00', strtotime('-30 days'));
            break;
        case 'ano':
            $data_inicio = date('Y-m-d 00:00:00', strtotime('-365 days'));
            break;
    }
    
    // Total de vendas
    $sql_total = "SELECT COUNT(*) as total_pedidos, 
                         COALESCE(SUM(valor_total), 0) as valor_total,
                         COALESCE(AVG(valor_total), 0) as ticket_medio
                  FROM pedidos 
                  WHERE data_pedido BETWEEN ? AND ? 
                  AND status != 'cancelado'";
    
    $stmt = mysqli_prepare($conn, $sql_total);
    mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $totais = mysqli_fetch_assoc($result);
    
    // Vendas por dia (para gráfico)
    $sql_grafico = "SELECT DATE(data_pedido) as data, 
                           COUNT(*) as total_pedidos,
                           SUM(valor_total) as valor_total
                    FROM pedidos 
                    WHERE data_pedido BETWEEN ? AND ? 
                    AND status != 'cancelado'
                    GROUP BY DATE(data_pedido)
                    ORDER BY data ASC";
    
    $stmt_grafico = mysqli_prepare($conn, $sql_grafico);
    mysqli_stmt_bind_param($stmt_grafico, "ss", $data_inicio, $data_fim);
    mysqli_stmt_execute($stmt_grafico);
    $result_grafico = mysqli_stmt_get_result($stmt_grafico);
    
    $grafico = [];
    while ($row = mysqli_fetch_assoc($result_grafico)) {
        $grafico[] = $row;
    }
    
    echo json_encode([
        'success' => true, 
        'totais' => $totais,
        'grafico' => $grafico,
        'periodo' => $periodo
    ]);
}

function produtosMaisVendidos($conn) {
    $limite = $_GET['limite'] ?? 10;
    
    $sql = "SELECT p.id, p.nome, p.categoria, p.preco,
                   SUM(ip.quantidade) as total_vendido,
                   SUM(ip.quantidade * ip.preco_unitario) as receita_total
            FROM itens_pedido ip
            INNER JOIN produtos p ON ip.produto_id = p.id
            INNER JOIN pedidos ped ON ip.pedido_id = ped.id
            WHERE ped.status != 'cancelado'
            GROUP BY p.id
            ORDER BY total_vendido DESC
            LIMIT ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $limite);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $produtos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produtos[] = $row;
    }
    
    echo json_encode(['success' => true, 'produtos' => $produtos]);
}

function estoqueBaixo($conn) {
    $limite_estoque = $_GET['limite_estoque'] ?? 10;
    
    $sql = "SELECT id, nome, categoria, estoque, esgotado
            FROM produtos 
            WHERE estoque <= ? OR esgotado = 1
            ORDER BY estoque ASC, nome ASC";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $limite_estoque);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $produtos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produtos[] = $row;
    }
    
    echo json_encode(['success' => true, 'produtos' => $produtos]);
}

function dashboardGeral($conn) {
    // Total de clientes
    $sql_clientes = "SELECT COUNT(*) as total FROM usuarios WHERE isAdmin = 0";
    $result_clientes = mysqli_query($conn, $sql_clientes);
    $total_clientes = mysqli_fetch_assoc($result_clientes)['total'];
    
    // Total de produtos
    $sql_produtos = "SELECT COUNT(*) as total FROM produtos";
    $result_produtos = mysqli_query($conn, $sql_produtos);
    $total_produtos = mysqli_fetch_assoc($result_produtos)['total'];
    
    // Pedidos hoje
    $hoje = date('Y-m-d');
    $sql_pedidos_hoje = "SELECT COUNT(*) as total, COALESCE(SUM(valor_total), 0) as valor 
                         FROM pedidos 
                         WHERE DATE(data_pedido) = ? AND status != 'cancelado'";
    $stmt_pedidos = mysqli_prepare($conn, $sql_pedidos_hoje);
    mysqli_stmt_bind_param($stmt_pedidos, "s", $hoje);
    mysqli_stmt_execute($stmt_pedidos);
    $result_pedidos = mysqli_stmt_get_result($stmt_pedidos);
    $pedidos_hoje = mysqli_fetch_assoc($result_pedidos);
    
    // Pedidos pendentes
    $sql_pendentes = "SELECT COUNT(*) as total FROM pedidos WHERE status = 'novo'";
    $result_pendentes = mysqli_query($conn, $sql_pendentes);
    $pedidos_pendentes = mysqli_fetch_assoc($result_pendentes)['total'];
    
    // Produtos com estoque baixo
    $sql_estoque_baixo = "SELECT COUNT(*) as total FROM produtos WHERE estoque <= 5 OR esgotado = 1";
    $result_estoque = mysqli_query($conn, $sql_estoque_baixo);
    $produtos_estoque_baixo = mysqli_fetch_assoc($result_estoque)['total'];
    
    // Total de funcionários
    $sql_funcionarios = "SELECT COUNT(*) as total FROM funcionarios WHERE ativo = 1";
    $result_funcionarios = mysqli_query($conn, $sql_funcionarios);
    $total_funcionarios = mysqli_fetch_assoc($result_funcionarios)['total'];
    
    // Total de entregadores
    $sql_entregadores = "SELECT COUNT(*) as total FROM entregadores WHERE ativo = 1";
    $result_entregadores = mysqli_query($conn, $sql_entregadores);
    $total_entregadores = mysqli_fetch_assoc($result_entregadores)['total'];
    
    echo json_encode([
        'success' => true,
        'dashboard' => [
            'total_clientes' => $total_clientes,
            'total_produtos' => $total_produtos,
            'pedidos_hoje' => $pedidos_hoje['total'],
            'valor_vendas_hoje' => $pedidos_hoje['valor'],
            'pedidos_pendentes' => $pedidos_pendentes,
            'produtos_estoque_baixo' => $produtos_estoque_baixo,
            'total_funcionarios' => $total_funcionarios,
            'total_entregadores' => $total_entregadores
        ]
    ]);
}

mysqli_close($conn);
?>
