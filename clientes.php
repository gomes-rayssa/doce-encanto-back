<?php
session_start();
include 'db_config.php';

$sql = "SELECT u.id, u.nome, u.email, u.celular, u.data_cadastro,
        (SELECT SUM(valor_total) FROM pedidos WHERE usuario_id = u.id AND status != 'Cancelado') as total_compras
        FROM usuarios u
        WHERE u.isAdmin = 0
        ORDER BY u.data_cadastro DESC";

$result = $conn->query($sql);
$clientes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Clientes</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>

    <main class="main-content">
        <div class="dashboard-header">
            <h1>Clientes</h1>
            <input type="search" id="searchCliente" placeholder="Buscar cliente..." onkeyup="filtrarClientes()"
                style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; width: 300px;">
        </div>

        <div class="recent-orders">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Data de Cadastro</th>
                            <th>Total de Compras</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="clientesTable">
                        <?php if (empty($clientes)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">
                                    Nenhum cliente cadastrado ainda.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td>#C<?php echo str_pad($cliente['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['celular'] ?? 'Não informado'); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($cliente['data_cadastro'])); ?></td>
                                    <td>R$ <?php echo number_format($cliente['total_compras'] ?? 0, 2, ',', '.'); ?></td>
                                    <td>
                                        <a href="cliente-detalhe.php?id=<?php echo $cliente['id']; ?>" class="btn-icon">
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
        function filtrarClientes() {
            const input = document.getElementById('searchCliente');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('clientesTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 0; i < tr.length; i++) {
                const tdNome = tr[i].getElementsByTagName('td')[1];
                const tdEmail = tr[i].getElementsByTagName('td')[2];

                if (tdNome || tdEmail) {
                    const txtNome = tdNome ? tdNome.textContent || tdNome.innerText : '';
                    const txtEmail = tdEmail ? tdEmail.textContent || tdEmail.innerText : '';

                    if (txtNome.toUpperCase().indexOf(filter) > -1 || txtEmail.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }
    </script>
</body>

</html>