<?php
session_start();
include 'db_config.php';

$clienteId = (int) ($_GET['id'] ?? 0);

if ($clienteId <= 0) {
    header('Location: clientes.php');
    exit;
}

// Buscar dados do cliente
$sql_cliente = "SELECT u.*, e.cep, e.rua, e.numero, e.bairro, e.cidade, e.estado
                FROM usuarios u
                LEFT JOIN enderecos e ON u.id = e.usuario_id
                WHERE u.id = ?";

$stmt = $conn->prepare($sql_cliente);
$stmt->bind_param("i", $clienteId);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();
$stmt->close();

if (!$cliente) {
    header('Location: clientes.php');
    exit;
}

// Calcular estatísticas
$sql_stats = "SELECT 
    COUNT(*) as total_pedidos,
    SUM(valor_total) as total_compras
    FROM pedidos 
    WHERE usuario_id = ? AND status != 'Cancelado'";

$stmt = $conn->prepare($sql_stats);
$stmt->bind_param("i", $clienteId);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();
$stmt->close();

$total_pedidos = $stats['total_pedidos'] ?? 0;
$total_compras = $stats['total_compras'] ?? 0;
$ticket_medio = $total_pedidos > 0 ? $total_compras / $total_pedidos : 0;

// Buscar histórico de pedidos
$sql_historico = "SELECT id, data_pedido, valor_total, status
    FROM pedidos
    WHERE usuario_id = ?
    ORDER BY data_pedido DESC
    LIMIT 10";

$stmt = $conn->prepare($sql_historico);
$stmt->bind_param("i", $clienteId);
$stmt->execute();
$result = $stmt->get_result();
$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $badge_map = [
        'Novo' => 'badge-new',
        'Em Preparação' => 'badge-preparing',
        'Enviado' => 'badge-sent',
        'Entregue' => 'badge-delivered',
        'Cancelado' => 'badge-cancelled'
    ];
    $row['badge'] = $badge_map[$row['status']] ?? 'badge-new';
    $pedidos[] = $row;
}
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Cliente</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>

    <main class="main-content">
        <div class="dashboard-header">
            <div>
                <a href="clientes.php"
                    style="color: var(--text-light); text-decoration: none; margin-bottom: 0.5rem; display: block;">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <h1><?php echo htmlspecialchars($cliente['nome']); ?></h1>
            </div>
        </div>

        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total em Compras</h3>
                    <p class="stat-value">R$ <?php echo number_format($total_compras, 2, ',', '.'); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total de Pedidos</h3>
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

        <div class="dashboard-grid">
            <div class="chart-card">
                <h2>Informações do Cliente</h2>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div><strong>Email:</strong> <?php echo htmlspecialchars($cliente['email']); ?></div>
                    <div><strong>Telefone:</strong> <?php echo htmlspecialchars($cliente['celular'] ?? 'Não informado'); ?></div>
                    <div><strong>Data de Nascimento:</strong> <?php echo $cliente['dataNascimento'] ? date('d/m/Y', strtotime($cliente['dataNascimento'])) : 'Não informado'; ?></div>
                    <div><strong>Data de Cadastro:</strong> <?php echo date('d/m/Y', strtotime($cliente['data_cadastro'])); ?></div>
                    <?php if ($cliente['cep']): ?>
                        <div><strong>Endereço:</strong> 
                            <?php echo htmlspecialchars($cliente['rua']); ?>, 
                            <?php echo htmlspecialchars($cliente['numero']); ?> - 
                            <?php echo htmlspecialchars($cliente['bairro']); ?> - 
                            <?php echo htmlspecialchars($cliente['cidade']); ?>/<?php echo htmlspecialchars($cliente['estado']); ?>
                        </div>
                        <div><strong>CEP:</strong> <?php echo htmlspecialchars($cliente['cep']); ?></div>
                    <?php else: ?>
                        <div><strong>Endereço:</strong> Não cadastrado</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="recent-orders">
            <h2>Histórico de Compras</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID do Pedido</th>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pedidos)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem;">
                                    Nenhum pedido encontrado para este cliente.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td>#<?php echo $pedido['id']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                                    <td>R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></td>
                                    <td><span class="badge <?php echo $pedido['badge']; ?>"><?php echo $pedido['status']; ?></span></td>
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
</body>

</html>