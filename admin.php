<?php
include 'header.php';
include 'db_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: index.php');
    exit;
}
?>

<link rel="stylesheet" href="admin.css" />

<main class="admin-main">
    <div class="admin-container">
        
        <!-- Header do Admin -->
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> Painel Administrativo</h1>
            <p>Bem-vindo, <strong><?php echo htmlspecialchars($_SESSION['usuario_data']['nome']); ?></strong>!</p>
        </div>

        <!-- Dashboard Cards -->
        <section id="dashboard-section" class="admin-section active">
            <h2><i class="fas fa-chart-line"></i> Dashboard</h2>
            <div class="dashboard-cards" id="dashboard-cards">
                <div class="dashboard-card">
                    <div class="card-icon"><i class="fas fa-users"></i></div>
                    <div class="card-content">
                        <h3>Total de Clientes</h3>
                        <p class="card-number" id="total-clientes">0</p>
                    </div>
                </div>
                <div class="dashboard-card">
                    <div class="card-icon"><i class="fas fa-box"></i></div>
                    <div class="card-content">
                        <h3>Total de Produtos</h3>
                        <p class="card-number" id="total-produtos">0</p>
                    </div>
                </div>
                <div class="dashboard-card">
                    <div class="card-icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="card-content">
                        <h3>Pedidos Hoje</h3>
                        <p class="card-number" id="pedidos-hoje">0</p>
                    </div>
                </div>
                <div class="dashboard-card">
                    <div class="card-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="card-content">
                        <h3>Vendas Hoje</h3>
                        <p class="card-number" id="vendas-hoje">R$ 0,00</p>
                    </div>
                </div>
                <div class="dashboard-card alert">
                    <div class="card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="card-content">
                        <h3>Estoque Baixo</h3>
                        <p class="card-number" id="estoque-baixo">0</p>
                    </div>
                </div>
                <div class="dashboard-card">
                    <div class="card-icon"><i class="fas fa-clock"></i></div>
                    <div class="card-content">
                        <h3>Pedidos Pendentes</h3>
                        <p class="card-number" id="pedidos-pendentes">0</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Menu de Navegação -->
        <nav class="admin-nav">
            <button class="admin-nav-btn active" data-section="dashboard"><i class="fas fa-chart-line"></i> Dashboard</button>
            <button class="admin-nav-btn" data-section="produtos"><i class="fas fa-box"></i> Produtos</button>
            <button class="admin-nav-btn" data-section="pedidos"><i class="fas fa-shopping-cart"></i> Pedidos</button>
            <button class="admin-nav-btn" data-section="clientes"><i class="fas fa-users"></i> Clientes</button>
            <button class="admin-nav-btn" data-section="funcionarios"><i class="fas fa-user-tie"></i> Funcionários</button>
            <button class="admin-nav-btn" data-section="relatorios"><i class="fas fa-chart-bar"></i> Relatórios</button>
            <button class="admin-nav-btn" data-section="configuracoes"><i class="fas fa-cog"></i> Configurações</button>
        </nav>

        <!-- Seção de Produtos -->
        <section id="produtos-section" class="admin-section">
            <div class="section-header">
                <h2><i class="fas fa-box"></i> Gerenciamento de Produtos</h2>
                <button class="btn btn-primary" onclick="abrirModalProduto()"><i class="fas fa-plus"></i> Novo Produto</button>
            </div>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="produtos-tbody"></tbody>
                </table>
            </div>
        </section>

        <!-- Seção de Pedidos -->
        <section id="pedidos-section" class="admin-section">
            <div class="section-header">
                <h2><i class="fas fa-shopping-cart"></i> Gerenciamento de Pedidos</h2>
            </div>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Pagamento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="pedidos-tbody"></tbody>
                </table>
            </div>
        </section>

        <!-- Seção de Clientes -->
        <section id="clientes-section" class="admin-section">
            <div class="section-header">
                <h2><i class="fas fa-users"></i> Gerenciamento de Clientes</h2>
            </div>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Celular</th>
                            <th>Data Cadastro</th>
                            <th>Total Compras</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="clientes-tbody"></tbody>
                </table>
            </div>
        </section>

        <!-- Seção de Funcionários -->
        <section id="funcionarios-section" class="admin-section">
            <div class="section-header">
                <h2><i class="fas fa-user-tie"></i> Gerenciamento de Funcionários</h2>
                <div>
                    <button class="btn btn-secondary" onclick="toggleTipoFuncionario('funcionario')">Funcionários</button>
                    <button class="btn btn-secondary" onclick="toggleTipoFuncionario('entregador')">Entregadores</button>
                </div>
            </div>
            
            <div id="funcionarios-container">
                <button class="btn btn-primary" onclick="abrirModalFuncionario('funcionario')"><i class="fas fa-plus"></i> Novo Funcionário</button>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Celular</th>
                                <th>Função</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="funcionarios-tbody"></tbody>
                    </table>
                </div>
            </div>

            <div id="entregadores-container" style="display: none;">
                <button class="btn btn-primary" onclick="abrirModalFuncionario('entregador')"><i class="fas fa-plus"></i> Novo Entregador</button>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Celular</th>
                                <th>Veículo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="entregadores-tbody"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Seção de Relatórios -->
        <section id="relatorios-section" class="admin-section">
            <div class="section-header">
                <h2><i class="fas fa-chart-bar"></i> Relatórios e Estatísticas</h2>
            </div>
            
            <div class="relatorio-filters">
                <label>Período:
                    <select id="periodo-vendas" onchange="carregarRelatorioVendas()">
                        <option value="dia">Hoje</option>
                        <option value="semana">Última Semana</option>
                        <option value="mes" selected>Último Mês</option>
                        <option value="ano">Último Ano</option>
                    </select>
                </label>
            </div>

            <div class="relatorio-cards">
                <div class="relatorio-card">
                    <h3>Total de Vendas</h3>
                    <p class="relatorio-number" id="rel-total-vendas">R$ 0,00</p>
                </div>
                <div class="relatorio-card">
                    <h3>Total de Pedidos</h3>
                    <p class="relatorio-number" id="rel-total-pedidos">0</p>
                </div>
                <div class="relatorio-card">
                    <h3>Ticket Médio</h3>
                    <p class="relatorio-number" id="rel-ticket-medio">R$ 0,00</p>
                </div>
            </div>

            <div class="relatorio-section">
                <h3>Produtos Mais Vendidos</h3>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Posição</th>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Quantidade Vendida</th>
                                <th>Receita Total</th>
                            </tr>
                        </thead>
                        <tbody id="produtos-mais-vendidos-tbody"></tbody>
                    </table>
                </div>
            </div>

            <div class="relatorio-section">
                <h3>Produtos com Estoque Baixo</h3>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Estoque Atual</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="estoque-baixo-tbody"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Seção de Configurações -->
        <section id="configuracoes-section" class="admin-section">
            <div class="section-header">
                <h2><i class="fas fa-cog"></i> Configurações</h2>
            </div>
            
            <div class="config-section">
                <h3>Administradores</h3>
                <button class="btn btn-primary" onclick="abrirModalAdmin()"><i class="fas fa-plus"></i> Novo Administrador</button>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Data Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="admins-tbody"></tbody>
                    </table>
                </div>
            </div>
        </section>

    </div>
</main>

<!-- Modais -->
<div id="modal-produto" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="fecharModal('modal-produto')">&times;</span>
        <h2 id="modal-produto-titulo">Novo Produto</h2>
        <form id="form-produto" onsubmit="salvarProduto(event)">
            <input type="hidden" id="produto-id">
            <div class="form-group">
                <label>Nome *</label>
                <input type="text" id="produto-nome" required>
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea id="produto-descricao" rows="3"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Preço *</label>
                    <input type="number" id="produto-preco" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Estoque *</label>
                    <input type="number" id="produto-estoque" required>
                </div>
            </div>
            <div class="form-group">
                <label>Categoria</label>
                <input type="text" id="produto-categoria">
            </div>
            <div class="form-group">
                <label>URL da Imagem</label>
                <input type="text" id="produto-imagem">
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="fecharModal('modal-produto')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-pedido" class="modal">
    <div class="modal-content modal-large">
        <span class="modal-close" onclick="fecharModal('modal-pedido')">&times;</span>
        <h2>Detalhes do Pedido #<span id="pedido-id-display"></span></h2>
        <div id="pedido-detalhes-content"></div>
    </div>
</div>

<div id="modal-cliente" class="modal">
    <div class="modal-content modal-large">
        <span class="modal-close" onclick="fecharModal('modal-cliente')">&times;</span>
        <h2>Detalhes do Cliente</h2>
        <div id="cliente-detalhes-content"></div>
    </div>
</div>

<div id="modal-funcionario" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="fecharModal('modal-funcionario')">&times;</span>
        <h2 id="modal-funcionario-titulo">Novo Funcionário</h2>
        <form id="form-funcionario" onsubmit="salvarFuncionario(event)">
            <input type="hidden" id="funcionario-id">
            <input type="hidden" id="funcionario-tipo">
            <div class="form-group">
                <label>Nome *</label>
                <input type="text" id="funcionario-nome" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="funcionario-email" required>
                </div>
                <div class="form-group">
                    <label>Celular *</label>
                    <input type="text" id="funcionario-celular" required>
                </div>
            </div>
            <div id="funcionario-funcao-group" class="form-group">
                <label>Função *</label>
                <select id="funcionario-funcao">
                    <option value="atendente">Atendente</option>
                    <option value="cozinha">Cozinha</option>
                    <option value="caixa">Caixa</option>
                </select>
            </div>
            <div id="funcionario-veiculo-group" class="form-group" style="display: none;">
                <label>Veículo *</label>
                <select id="funcionario-veiculo">
                    <option value="bicicleta">Bicicleta</option>
                    <option value="moto">Moto</option>
                    <option value="carro">Carro</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>CEP</label>
                    <input type="text" id="funcionario-cep">
                </div>
                <div class="form-group">
                    <label>Rua</label>
                    <input type="text" id="funcionario-rua">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Número</label>
                    <input type="text" id="funcionario-numero">
                </div>
                <div class="form-group">
                    <label>Bairro</label>
                    <input type="text" id="funcionario-bairro">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Cidade</label>
                    <input type="text" id="funcionario-cidade">
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <input type="text" id="funcionario-estado" maxlength="2">
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="fecharModal('modal-funcionario')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-admin" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="fecharModal('modal-admin')">&times;</span>
        <h2>Novo Administrador</h2>
        <form id="form-admin" onsubmit="salvarAdmin(event)">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" id="admin-nome" value="Administrador">
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" id="admin-email" required>
            </div>
            <p class="info-text">Senha padrão: <strong>Doce2025@</strong></p>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="fecharModal('modal-admin')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar</button>
            </div>
        </form>
    </div>
</div>

<script src="admin.js"></script>

<?php
include 'footer.php';
?>
