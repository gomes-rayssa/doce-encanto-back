<?php
session_start();
// Simulação de autenticação - em produção, use um sistema real
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_email'] = 'admin@doces.com';
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
                <select id="period-filter">
                    <option value="today">Hoje</option>
                    <option value="week">Esta Semana</option>
                    <option value="month" selected>Este Mês</option>
                    <option value="year">Este Ano</option>
                </select>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3>Total de Vendas</h3>
                    <p class="stat-value">R$ 45.890,00</p>
                    <span class="stat-change positive">+12% vs mês anterior</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3>Pedidos</h3>
                    <p class="stat-value">328</p>
                    <span class="stat-change positive">+8% vs mês anterior</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Clientes</h3>
                    <p class="stat-value">1.245</p>
                    <span class="stat-change positive">+15% vs mês anterior</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>Ticket Médio</h3>
                    <p class="stat-value">R$ 139,90</p>
                    <span class="stat-change negative">-3% vs mês anterior</span>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="chart-card">
                <h2>Vendas por Período</h2>
                <canvas id="salesChart"></canvas>
            </div>

            <div class="chart-card">
                <h2>Produtos Mais Vendidos</h2>
                <div class="top-products">
                    <div class="product-item">
                        <span class="rank">1</span>
                        <span class="product-name">Brigadeiro Gourmet</span>
                        <span class="product-sales">234 vendas</span>
                    </div>
                    <div class="product-item">
                        <span class="rank">2</span>
                        <span class="product-name">Beijinho</span>
                        <span class="product-sales">198 vendas</span>
                    </div>
                    <div class="product-item">
                        <span class="rank">3</span>
                        <span class="product-name">Trufas Sortidas</span>
                        <span class="product-sales">167 vendas</span>
                    </div>
                    <div class="product-item">
                        <span class="rank">4</span>
                        <span class="product-name">Cajuzinho</span>
                        <span class="product-sales">143 vendas</span>
                    </div>
                    <div class="product-item">
                        <span class="rank">5</span>
                        <span class="product-name">Brigadeiro de Colher</span>
                        <span class="product-sales">129 vendas</span>
                    </div>
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
                        <tr>
                            <td>#1234</td>
                            <td>Maria Silva</td>
                            <td>20/11/2024 14:30</td>
                            <td>R$ 156,90</td>
                            <td><span class="badge badge-new">Novo</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1234" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#1233</td>
                            <td>João Santos</td>
                            <td>20/11/2024 13:15</td>
                            <td>R$ 89,90</td>
                            <td><span class="badge badge-preparing">Em Preparação</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1233" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#1232</td>
                            <td>Ana Costa</td>
                            <td>20/11/2024 12:45</td>
                            <td>R$ 234,50</td>
                            <td><span class="badge badge-sent">Enviado</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1232" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="scripts/dashboard.js"></script>
</body>
</html>
