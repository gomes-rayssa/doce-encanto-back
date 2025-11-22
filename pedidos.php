<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Buscar todos os pedidos com informações do cliente
$sql = "SELECT p.*, u.nome as cliente_nome, u.email as cliente_email
        FROM pedidos p
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.data_pedido DESC";

$result = $conn->query($sql);
$pedidos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;
    }
}

$conn->close();

// Função para mapear status para classe de badge
function getBadgeClass($status) {
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
    <title>Gerenciamento de Pedidos</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="accessibility.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>

    <main class="main-content">
        <div class="dashboard-header">
            <h1>Pedidos</h1>
            <div style="display: flex; gap: 1rem;">
                <select id="status-filter" onchange="filterByStatus()">
                    <option value="">Todos os Status</option>
                    <option value="Novo">Novo</option>
                    <option value="Em Preparação">Em Preparação</option>
                    <option value="Enviado">Enviado</option>
                    <option value="Entregue">Entregue</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>
        </div>

        <div class="recent-orders">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID do Pedido</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="pedidos-tbody">
                        <?php if (empty($pedidos)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem;">
                                    Nenhum pedido encontrado.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr data-status="<?php echo htmlspecialchars($pedido['status']); ?>">
                                    <td>#<?php echo $pedido['id']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
                                    <td>R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge <?php echo getBadgeClass($pedido['status']); ?>">
                                            <?php echo htmlspecialchars($pedido['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="pedido-detalhe.php?id=<?php echo $pedido['id']; ?>" 
                                           class="btn-icon" 
                                           title="Ver detalhes do pedido">
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

    <script>
        function filterByStatus() {
            const filter = document.getElementById('status-filter').value;
            const rows = document.querySelectorAll('#pedidos-tbody tr[data-status]');
            
            rows.forEach(row => {
                if (filter === '' || row.dataset.status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>
