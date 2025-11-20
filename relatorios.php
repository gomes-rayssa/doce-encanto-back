<?php
session_start();
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
            <select id="period-filter-reports">
                <option value="today">Hoje</option>
                <option value="week">Esta Semana</option>
                <option value="month" selected>Este Mês</option>
                <option value="year">Este Ano</option>
                <option value="custom">Período Personalizado</option>
            </select>
        </div>

        <!-- Relatório de Vendas -->
        <div class="chart-card" style="margin-bottom: 2rem;">
            <h2>Relatório de Vendas</h2>
            <div class="stats-grid" style="margin-top: 1rem;">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Total de Vendas</h3>
                        <p class="stat-value">R$ 45.890,00</p>
                        <span class="stat-change positive">+12% vs período anterior</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Número de Pedidos</h3>
                        <p class="stat-value">328</p>
                        <span class="stat-change positive">+8% vs período anterior</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Ticket Médio</h3>
                        <p class="stat-value">R$ 139,90</p>
                        <span class="stat-change negative">-3% vs período anterior</span>
                    </div>
                </div>
            </div>
            <div style="margin-top: 2rem;">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Produtos Mais Vendidos -->
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
                        <tr>
                            <td><span class="rank">1</span></td>
                            <td>Brigadeiro Gourmet</td>
                            <td>Chocolates</td>
                            <td>234 unidades</td>
                            <td>R$ 819,00</td>
                        </tr>
                        <tr>
                            <td><span class="rank">2</span></td>
                            <td>Beijinho</td>
                            <td>Chocolates</td>
                            <td>198 unidades</td>
                            <td>R$ 594,00</td>
                        </tr>
                        <tr>
                            <td><span class="rank">3</span></td>
                            <td>Trufas Sortidas</td>
                            <td>Chocolates</td>
                            <td>167 caixas</td>
                            <td>R$ 7.515,00</td>
                        </tr>
                        <tr>
                            <td><span class="rank">4</span></td>
                            <td>Cajuzinho</td>
                            <td>Chocolates</td>
                            <td>143 unidades</td>
                            <td>R$ 500,50</td>
                        </tr>
                        <tr>
                            <td><span class="rank">5</span></td>
                            <td>Brigadeiro de Colher</td>
                            <td>Chocolates</td>
                            <td>129 potes</td>
                            <td>R$ 1.806,00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Relatório de Estoque -->
        <div class="chart-card">
            <h2>Relatório de Estoque - Produtos com Baixo Estoque</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Quantidade Atual</th>
                            <th>Estoque Mínimo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Trufas Sortidas</td>
                            <td>Chocolates</td>
                            <td>0</td>
                            <td>20</td>
                            <td><span class="badge badge-out-of-stock">Esgotado</span></td>
                        </tr>
                        <tr>
                            <td>Bolo de Chocolate</td>
                            <td>Bolos</td>
                            <td>5</td>
                            <td>15</td>
                            <td><span class="badge badge-pending">Baixo</span></td>
                        </tr>
                        <tr>
                            <td>Torta de Limão</td>
                            <td>Tortas</td>
                            <td>8</td>
                            <td>20</td>
                            <td><span class="badge badge-pending">Baixo</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="scripts/relatorios.js"></script>
</body>
</html>
