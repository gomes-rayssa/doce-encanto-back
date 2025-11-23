<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_email'] = 'admin@doceencanto.com';
    $_SESSION['is_admin'] = true;
}

$mes_atual = date('Y-m-01 00:00:00');
$mes_fim = date('Y-m-t 23:59:59');

$sql_vendas = "SELECT SUM(valor_total) as total FROM pedidos WHERE data_pedido BETWEEN ? AND ? AND status != 'Cancelado'";
$stmt = $conn->prepare($sql_vendas);
$stmt->bind_param("ss", $mes_atual, $mes_fim);
$stmt->execute();
$result = $stmt->get_result();
$total_vendas = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();

$sql_pedidos = "SELECT COUNT(*) as total FROM pedidos WHERE data_pedido BETWEEN ? AND ?";
$stmt = $conn->prepare($sql_pedidos);
$stmt->bind_param("ss", $mes_atual, $mes_fim);
$stmt->execute();
$result = $stmt->get_result();
$total_pedidos = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();

$sql_clientes = "SELECT COUNT(*) as total FROM usuarios WHERE isAdmin = 0";
$result = $conn->query($sql_clientes);
$total_clientes = $result->fetch_assoc()['total'] ?? 0;

$ticket_medio = $total_pedidos > 0 ? $total_vendas / $total_pedidos : 0;

$sql_produtos = "SELECT p.nome, SUM(ip.quantidade) as total_vendido 
                 FROM itens_pedido ip
                 JOIN produtos p ON ip.produto_id = p.id
                 JOIN pedidos ped ON ip.pedido_id = ped.id
                 WHERE ped.data_pedido BETWEEN ? AND ? AND ped.status != 'Cancelado'
                 GROUP BY p.id
                 ORDER BY total_vendido DESC
                 LIMIT 5";
$stmt = $conn->prepare($sql_produtos);
$stmt->bind_param("ss", $mes_atual, $mes_fim);
$stmt->execute();
$result = $stmt->get_result();
$produtos_mais_vendidos = [];
while ($row = $result->fetch_assoc()) {
    $produtos_mais_vendidos[] = $row;
}
$stmt->close();

$sql_recentes = "SELECT p.id, p.data_pedido, p.valor_total, p.status, u.nome as cliente_nome
                 FROM pedidos p
                 LEFT JOIN usuarios u ON p.usuario_id = u.id
                 ORDER BY p.data_pedido DESC
                 LIMIT 5";
$result = $conn->query($sql_recentes);
$pedidos_recentes = [];
while ($row = $result->fetch_assoc()) {
    $pedidos_recentes[] = $row;
}

$conn->close();

function getBadgeClass($status)
{
    $map = [
        'Novo' => 'badge-new',
        'Em Preparação' => 'badge-preparing',
        'Enviado' => 'badge-sent',
        'Entregue' => 'badge-delivered',
        'Cancelado' => 'badge-cancelled'
    ];
    return $map[$status] ?? 'badge-new';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>

    <main class="main-content">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <div class="date-filter">
                <span style="color: var(--text-light); font-size: 0.9rem;">
                    <?php echo date('F Y'); ?>
                </span>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3>Total de Vendas</h3>
                    <p class="stat-value">R$ <?php echo number_format($total_vendas, 2, ',', '.'); ?></p>
                    <span class="stat-change positive">Este mês</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3>Pedidos</h3>
                    <p class="stat-value"><?php echo $total_pedidos; ?></p>
                    <span class="stat-change positive">Este mês</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Clientes</h3>
                    <p class="stat-value"><?php echo $total_clientes; ?></p>
                    <span class="stat-change positive">Total cadastrado</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>Ticket Médio</h3>
                    <p class="stat-value">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></p>
                    <span class="stat-change">Este mês</span>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="chart-card">
                <h2>Produtos Mais Vendidos</h2>
                <div class="top-products">
                    <?php if (empty($produtos_mais_vendidos)): ?>
                        <p style="text-align: center; color: var(--text-light); padding: 2rem;">
                            Nenhuma venda registrada este mês
                        </p>
                    <?php else: ?>
                        <?php foreach ($produtos_mais_vendidos as $index => $produto): ?>
                            <div class="product-item">
                                <span class="rank"><?php echo $index + 1; ?></span>
                                <span class="product-name"><?php echo htmlspecialchars($produto['nome']); ?></span>
                                <span class="product-sales"><?php echo $produto['total_vendido']; ?> vendas</span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="recent-orders">
            <div class="section-header">
                <h2>Pedidos Recentes</h2>
                <a href="pedidos.php" class="btn-secondary">Ver Todos</a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pedidos_recentes)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem;">
                                    Nenhum pedido encontrado
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pedidos_recentes as $pedido): ?>
                                <tr>
                                    <td>#<?php echo $pedido['id']; ?></td>
                                    <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                                    <td>R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></td>
                                    <td><span
                                            class="badge <?php echo getBadgeClass($pedido['status']); ?>"><?php echo $pedido['status']; ?></span>
                                    </td>
                                    <td>
                                        <a href="pedido-detalhe.php?id=<?php echo $pedido['id']; ?>" class="btn-icon">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>