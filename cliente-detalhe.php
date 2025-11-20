<?php
session_start();
$clienteId = $_GET['id'] ?? '1';
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
                <a href="clientes.php" style="color: var(--text-light); text-decoration: none; margin-bottom: 0.5rem; display: block;">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <h1>Maria Silva</h1>
            </div>
        </div>

        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total em Compras</h3>
                    <p class="stat-value">R$ 1.234,50</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total de Pedidos</h3>
                    <p class="stat-value">12</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Ticket Médio</h3>
                    <p class="stat-value">R$ 102,87</p>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="chart-card">
                <h2>Informações do Cliente</h2>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div><strong>Email:</strong> maria@email.com</div>
                    <div><strong>Telefone:</strong> (11) 98765-4321</div>
                    <div><strong>CPF:</strong> 123.456.789-00</div>
                    <div><strong>Data de Cadastro:</strong> 15/01/2024</div>
                    <div><strong>Endereço:</strong> Rua das Flores, 123 - Centro - São Paulo/SP</div>
                    <div><strong>CEP:</strong> 12345-678</div>
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
                        <tr>
                            <td>#1234</td>
                            <td>20/11/2024</td>
                            <td>R$ 156,90</td>
                            <td><span class="badge badge-new">Novo</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1234" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#1180</td>
                            <td>15/11/2024</td>
                            <td>R$ 98,50</td>
                            <td><span class="badge badge-delivered">Entregue</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1180" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#1120</td>
                            <td>08/11/2024</td>
                            <td>R$ 234,50</td>
                            <td><span class="badge badge-delivered">Entregue</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1120" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
