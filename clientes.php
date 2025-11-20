<?php
session_start();
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
            <input type="search" placeholder="Buscar cliente..." style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; width: 300px;">
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
                    <tbody>
                        <tr>
                            <td>#C001</td>
                            <td>Maria Silva</td>
                            <td>maria@email.com</td>
                            <td>(11) 98765-4321</td>
                            <td>15/01/2024</td>
                            <td>R$ 1.234,50</td>
                            <td>
                                <a href="cliente-detalhe.php?id=1" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#C002</td>
                            <td>João Santos</td>
                            <td>joao@email.com</td>
                            <td>(11) 98765-1234</td>
                            <td>20/02/2024</td>
                            <td>R$ 856,00</td>
                            <td>
                                <a href="cliente-detalhe.php?id=2" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>#C003</td>
                            <td>Ana Costa</td>
                            <td>ana@email.com</td>
                            <td>(11) 98765-5678</td>
                            <td>10/03/2024</td>
                            <td>R$ 2.145,80</td>
                            <td>
                                <a href="cliente-detalhe.php?id=3" class="btn-icon"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
