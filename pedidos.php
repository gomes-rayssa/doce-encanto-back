<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Pedidos</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'components/header-adm.php'; ?>
    <?php include 'components/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="dashboard-header">
            <h1>Pedidos</h1>
            <div style="display: flex; gap: 1rem;">
                <select id="status-filter">
                    <option value="">Todos os Status</option>
                    <option value="novo">Novo</option>
                    <option value="preparacao">Em Preparação</option>
                    <option value="enviado">Enviado</option>
                    <option value="entregue">Entregue</option>
                    <option value="cancelado">Cancelado</option>
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
                    <tbody>
                        <tr>
                            <td>#1234</td>
                            <td>20/11/2024 14:30</td>
                            <td>Maria Silva</td>
                            <td>R$ 156,90</td>
                            <td><span class="badge badge-new">Novo</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1234" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#1233</td>
                            <td>20/11/2024 13:15</td>
                            <td>João Santos</td>
                            <td>R$ 89,90</td>
                            <td><span class="badge badge-preparing">Em Preparação</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1233" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#1232</td>
                            <td>20/11/2024 12:45</td>
                            <td>Ana Costa</td>
                            <td>R$ 234,50</td>
                            <td><span class="badge badge-sent">Enviado</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1232" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#1231</td>
                            <td>19/11/2024 18:20</td>
                            <td>Pedro Oliveira</td>
                            <td>R$ 178,00</td>
                            <td><span class="badge badge-delivered">Entregue</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1231" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#1230</td>
                            <td>19/11/2024 16:45</td>
                            <td>Carla Mendes</td>
                            <td>R$ 95,00</td>
                            <td><span class="badge badge-cancelled">Cancelado</span></td>
                            <td>
                                <a href="pedido-detalhe.php?id=1230" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
