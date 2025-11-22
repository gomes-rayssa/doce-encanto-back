<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

$pedidoId = (int) ($_GET['id'] ?? 0);

if ($pedidoId <= 0) {
    header('Location: pedidos.php');
    exit;
}

// Buscar dados do pedido
$sql_pedido = "SELECT p.*, u.nome as cliente_nome, u.email as cliente_email, u.celular as cliente_celular,
               e.nome as entregador_nome, e.veiculo_tipo, e.placa
               FROM pedidos p
               LEFT JOIN usuarios u ON p.usuario_id = u.id
               LEFT JOIN equipe e ON p.entregador_id = e.id
               WHERE p.id = ?";

$stmt = $conn->prepare($sql_pedido);
$stmt->bind_param("i", $pedidoId);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();
$stmt->close();

if (!$pedido) {
    header('Location: pedidos.php');
    exit;
}

// Buscar itens do pedido
$sql_itens = "SELECT ip.*, pr.nome as produto_nome
              FROM itens_pedido ip
              LEFT JOIN produtos pr ON ip.produto_id = pr.id
              WHERE ip.pedido_id = ?";

$stmt_itens = $conn->prepare($sql_itens);
$stmt_itens->bind_param("i", $pedidoId);
$stmt_itens->execute();
$result_itens = $stmt_itens->get_result();
$itens = [];
while ($row = $result_itens->fetch_assoc()) {
    $itens[] = $row;
}
$stmt_itens->close();

// Buscar endereço de entrega
$sql_endereco = "SELECT * FROM enderecos WHERE usuario_id = ? LIMIT 1";
$stmt_end = $conn->prepare($sql_endereco);
$stmt_end->bind_param("i", $pedido['usuario_id']);
$stmt_end->execute();
$result_end = $stmt_end->get_result();
$endereco = $result_end->fetch_assoc();
$stmt_end->close();

// Buscar entregadores disponíveis
$sql_entregadores = "SELECT id, nome, veiculo_tipo, placa FROM equipe WHERE tipo = 'entregador' ORDER BY nome";
$result_entregadores = $conn->query($sql_entregadores);
$entregadores = [];
while ($row = $result_entregadores->fetch_assoc()) {
    $entregadores[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido #<?php echo $pedidoId; ?></title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="accessibility.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>

    <main class="main-content">
        <div class="dashboard-header">
            <div>
                <a href="pedidos.php"
                    style="color: var(--text-light); text-decoration: none; margin-bottom: 0.5rem; display: block;">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <h1>Pedido #<?php echo $pedidoId; ?></h1>
                <p style="color: var(--text-light); font-size: 0.875rem;">
                    Cliente: <?php echo htmlspecialchars($pedido['cliente_nome']); ?> | 
                    Data: <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?>
                </p>
            </div>
        </div>

        <div class="dashboard-grid" style="grid-template-columns: 2fr 1fr;">
            <div class="chart-card">
                <h2>Itens do Pedido</h2>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itens as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['produto_nome']); ?></td>
                                    <td><?php echo $item['quantidade']; ?></td>
                                    <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                                    <td>R$ <?php echo number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
                                <td style="font-weight: bold; color: var(--primary-color);">
                                    R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="chart-card">
                <h2>Status do Pedido</h2>
                <div class="form-group">
                    <label for="orderStatus">Alterar Status</label>
                    <select id="orderStatus" onchange="updateStatus(<?php echo $pedidoId; ?>)">
                        <option value="Novo" <?php echo $pedido['status'] === 'Novo' ? 'selected' : ''; ?>>Novo</option>
                        <option value="Em Preparação" <?php echo $pedido['status'] === 'Em Preparação' ? 'selected' : ''; ?>>Em Preparação</option>
                        <option value="Enviado" <?php echo $pedido['status'] === 'Enviado' ? 'selected' : ''; ?>>Enviado</option>
                        <option value="Entregue" <?php echo $pedido['status'] === 'Entregue' ? 'selected' : ''; ?>>Entregue</option>
                        <option value="Cancelado" <?php echo $pedido['status'] === 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                <div style="margin-top: 1.5rem;">
                    <h3 style="font-size: 1rem; margin-bottom: 1rem;">Status Atual</h3>
                    <div style="border-left: 2px solid var(--border-color); padding-left: 1rem;">
                        <div style="margin-bottom: 1rem;">
                            <div style="font-weight: 600; color: var(--text-dark);">
                                <?php echo htmlspecialchars($pedido['status']); ?>
                            </div>
                            <div style="font-size: 0.875rem; color: var(--text-light);">
                                <?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <?php if ($endereco): ?>
            <div class="chart-card">
                <h2>Endereço de Entrega</h2>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <div><strong>CEP:</strong> <?php echo htmlspecialchars($endereco['cep']); ?></div>
                    <div><strong>Rua:</strong> <?php echo htmlspecialchars($endereco['rua']); ?>, <?php echo htmlspecialchars($endereco['numero']); ?></div>
                    <div><strong>Bairro:</strong> <?php echo htmlspecialchars($endereco['bairro']); ?></div>
                    <div><strong>Cidade:</strong> <?php echo htmlspecialchars($endereco['cidade']); ?> - <?php echo htmlspecialchars($endereco['estado']); ?></div>
                    <?php if ($pedido['cliente_celular']): ?>
                    <div style="margin-top: 1rem;">
                        <strong>Contato:</strong> <?php echo htmlspecialchars($pedido['cliente_celular']); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="chart-card">
                <h2>Dados do Entregador</h2>
                <div class="form-group">
                    <label for="entregadorSelect">Selecionar Entregador</label>
                    <select id="entregadorSelect" onchange="updateEntregador(<?php echo $pedidoId; ?>)">
                        <option value="">Selecione um entregador</option>
                        <?php foreach ($entregadores as $entregador): ?>
                            <option value="<?php echo $entregador['id']; ?>" 
                                    <?php echo ($pedido['entregador_id'] == $entregador['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($entregador['nome']); ?> - 
                                <?php echo htmlspecialchars($entregador['veiculo_tipo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($pedido['entregador_nome']): ?>
                <div id="deliveryInfo" style="margin-top: 1rem;">
                    <div><strong>Nome:</strong> <?php echo htmlspecialchars($pedido['entregador_nome']); ?></div>
                    <div><strong>Veículo:</strong> <?php echo htmlspecialchars($pedido['veiculo_tipo']); ?>
                        <?php if ($pedido['placa']): ?>
                            - <?php echo htmlspecialchars($pedido['placa']); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="scripts/pedido-detalhe.js"></script>
</body>

</html>
