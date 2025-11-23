<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

$periodo = $_GET['periodo'] ?? 'month';

$data_inicio = '';
$data_fim = date('Y-m-d 23:59:59');

switch ($periodo) {
    case 'today':
        $data_inicio = date('Y-m-d 00:00:00');
        break;
    case 'week':
        $data_inicio = date('Y-m-d 00:00:00', strtotime('-7 days'));
        break;
    case 'month':
        $data_inicio = date('Y-m-01 00:00:00');
        break;
    case 'year':
        $data_inicio = date('Y-01-01 00:00:00');
        break;
    default:
        $data_inicio = date('Y-m-01 00:00:00');
}

$sql_vendas = "SELECT 
                COUNT(*) as total_pedidos,
                SUM(valor_total) as total_vendas
               FROM pedidos 
               WHERE data_pedido BETWEEN ? AND ?
               AND status != 'Cancelado'";

$stmt = $conn->prepare($sql_vendas);
$stmt->bind_param("ss", $data_inicio, $data_fim);
$stmt->execute();
$result = $stmt->get_result();
$vendas = $result->fetch_assoc();
$stmt->close();

$total_vendas = $vendas['total_vendas'] ?? 0;
$total_pedidos = $vendas['total_pedidos'] ?? 0;
$ticket_medio = $total_pedidos > 0 ? $total_vendas / $total_pedidos : 0;

$sql_ranking = "SELECT 
                    pr.nome as produto_nome,
                    pr.categoria,
                    SUM(ip.quantidade) as quantidade_vendida,
                    SUM(ip.quantidade * ip.preco_unitario) as receita_total
                FROM itens_pedido ip
                INNER JOIN produtos pr ON ip.produto_id = pr.id
                INNER JOIN pedidos p ON ip.pedido_id = p.id
                WHERE p.data_pedido BETWEEN ? AND ?
                AND p.status != 'Cancelado'
                GROUP BY ip.produto_id
                ORDER BY quantidade_vendida DESC
                LIMIT 10";

$stmt_ranking = $conn->prepare($sql_ranking);
$stmt_ranking->bind_param("ss", $data_inicio, $data_fim);
$stmt_ranking->execute();
$result_ranking = $stmt_ranking->get_result();
$ranking = [];
while ($row = $result_ranking->fetch_assoc()) {
    $ranking[] = $row;
}
$stmt_ranking->close();

$sql_estoque = "SELECT nome, categoria, estoque 
                FROM produtos 
                WHERE estoque < 10
                ORDER BY estoque ASC";

$result_estoque = $conn->query($sql_estoque);
$baixo_estoque = [];
while ($row = $result_estoque->fetch_assoc()) {
    $baixo_estoque[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios e Estatísticas</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>

    <main class="main-content">
        <div class="dashboard-header">
            <h1>Relatórios e Estatísticas</h1>
            <select id="period-filter-reports" onchange="changePeriod(this.value)">
                <option value="today" <?php echo $periodo === 'today' ? 'selected' : ''; ?>>Hoje</option>
                <option value="week" <?php echo $periodo === 'week' ? 'selected' : ''; ?>>Esta Semana</option>
                <option value="month" <?php echo $periodo === 'month' ? 'selected' : ''; ?>>Este Mês</option>
                <option value="year" <?php echo $periodo === 'year' ? 'selected' : ''; ?>>Este Ano</option>
            </select>
        </div>

        <div class="chart-card" style="margin-bottom: 2rem;">
            <h2>Relatório de Vendas</h2>
            <div class="stats-grid" style="margin-top: 1rem;">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Total de Vendas</h3>
                        <p class="stat-value">R$ <?php echo number_format($total_vendas, 2, ',', '.'); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Número de Pedidos</h3>
                        <p class="stat-value"><?php echo $total_pedidos; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Ticket Médio</h3>
                        <p class="stat-value">R$ <?php echo number_format($ticket_medio, 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="chart-card" style="margin-bottom: 2rem;">
            <h2>Ranking de Produtos Mais Vendidos</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Posição</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Quantidade Vendida</th>
                            <th>Receita Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ranking)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem;">
                                    Nenhuma venda registrada no período selecionado.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ranking as $index => $produto): ?>
                                <tr>
                                    <td><span class="rank"><?php echo $index + 1; ?></span></td>
                                    <td><?php echo htmlspecialchars($produto['produto_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                                    <td><?php echo $produto['quantidade_vendida']; ?> unidades</td>
                                    <td>R$ <?php echo number_format($produto['receita_total'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="chart-card">
            <h2>Relatório de Estoque - Produtos com Baixo Estoque</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Quantidade Atual</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($baixo_estoque)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem;">
                                    Todos os produtos estão com estoque adequado.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($baixo_estoque as $produto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                                    <td><?php echo $produto['estoque']; ?></td>
                                    <td>
                                        <?php if ($produto['estoque'] == 0): ?>
                                            <span class="badge badge-out-of-stock">Esgotado</span>
                                        <?php else: ?>
                                            <span class="badge badge-pending">Baixo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function changePeriod(periodo) {
            window.location.href = 'relatorios.php?periodo=' + periodo;
        }
    </script>
</body>

</html>