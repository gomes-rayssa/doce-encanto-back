<aside class="sidebar">
    <nav>
        <ul>
            <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
            <li><a href="admin.php" class="<?php echo ($current_page == 'admin.php') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="pedidos.php" class="<?php echo ($current_page == 'pedidos.php' || $current_page == 'pedido-detalhe.php') ? 'active' : ''; ?>"><i class="fas fa-box"></i> Pedidos</a></li>
            <li><a href="produtos.php" class="<?php echo ($current_page == 'produtos.php') ? 'active' : ''; ?>"><i class="fas fa-candy-cane"></i> Produtos</a></li>
            <li><a href="clientes.php" class="<?php echo ($current_page == 'clientes.php' || $current_page == 'cliente-detalhe.php') ? 'active' : ''; ?>"><i class="fas fa-users"></i> Clientes</a></li>
            <li><a href="funcionarios.php" class="<?php echo ($current_page == 'funcionarios.php') ? 'active' : ''; ?>"><i class="fas fa-user-tie"></i> Funcionários</a></li>
            <li><a href="relatorios.php" class="<?php echo ($current_page == 'relatorios.php') ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i> Relatórios</a></li>
            <li><a href="configuracoes.php" class="<?php echo ($current_page == 'configuracoes.php') ? 'active' : ''; ?>"><i class="fas fa-cogs"></i> Configurações</a></li>
        </ul>
    </nav>
</aside>