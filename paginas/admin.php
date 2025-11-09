<?php 
  include '../header.php'; 

  // ==================================================================
  // VERIFICAÇÃO DE SEGURANÇA OBRIGATÓRIA
  // Se 'is_admin' não estiver definido ou não for true, expulsa o usuário.
  // ==================================================================
  if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
      header('Location: ../index.php'); // Redireciona para a home
      exit; // Para a execução do script
  }

  // --- Carregar Dados Relevantes (Simulação) ---

  // 1. Dados de Usuários (do nosso "banco" na sessão)
  $lista_usuarios = $_SESSION['lista_usuarios'] ?? [];

  // 2. Dados de Produtos (simulando a leitura dos arrays das outras páginas)
  // (Em um sistema real, viria tudo do banco de dados com "SELECT * FROM produtos")
  $produtos_destaque = [
      ['id' => 'bolo_chocolate', 'nome' => 'Bolo de Chocolate', 'preco' => 100.00, 'categoria' => 'bolo'],
      ['id' => 'bolo_red_velvet', 'nome' => 'Bolo Red Velvet', 'preco' => 120.00, 'categoria' => 'bolo'],
      ['id' => 'bolo_pistache', 'nome' => 'Bolo de Pistache', 'preco' => 150.00, 'categoria' => 'bolo'],
      ['id' => 'doce_pistache', 'nome' => 'Doce de Pistache', 'preco' => 12.00, 'categoria' => 'doce gourmet'],
  ];
  $doces_pagina = [
      ['id' => 'doce_brigadeiro', 'nome' => 'Brigadeiro', 'preco' => 8.00, 'categoria' => 'tradicionais'],
      ['id' => 'trufa_cafe', 'nome' => 'Trufa de Café', 'preco' => 18.00, 'categoria' => 'trufas'],
      // (etc... vamos usar só os destaques por simplicidade)
  ];
  // Vamos usar apenas os 'produtos_destaque' como exemplo
  $todos_produtos = $produtos_destaque; 

  // --- Estatísticas ---
  $total_usuarios = count($lista_usuarios);
  $total_produtos = count($todos_produtos);
?>

<link rel="stylesheet" href="admin.css" />

<main class="admin-container">
    <div class="container">
        
        <header class="admin-header">
            <h1>Painel Administrativo</h1>
            <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_data']['nome']); ?>!</p>
        </header>

        <section class="stat-cards-container">
            <div class="stat-card">
                <h3>Total de Usuários</h3>
                <p class="stat-number"><?php echo $total_usuarios; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total de Produtos (Demo)</h3>
                <p class="stat-number"><?php echo $total_produtos; ?></p>
            </div>
            <div class="stat-card">
                <h3>Pedidos (Demo)</h3>
                <p class="stat-number">0</p>
            </div>
            <div class="stat-card">
                <h3>Visão Geral</h3>
                <p class="stat-number">OK</p>
            </div>
        </section>

        <section class="admin-section">
            <h2>Gerenciamento de Usuários</h2>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Permissão</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <?php if (isset($usuario['isAdmin']) && $usuario['isAdmin']): ?>
                                    <span class="badge admin">Admin</span>
                                <?php else: ?>
                                    <span class="badge user">Usuário</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline">Editar</button>
                                <button class="btn btn-sm btn-danger">Excluir</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <section class="admin-section">
            <h2>Gerenciamento de Produtos (Demo)</h2>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID do Produto</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Categoria</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($todos_produtos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['id']); ?></td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline">Editar</button>
                                <button class="btn btn-sm btn-danger">Excluir</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</main>

<?php 
  include '../footer.php'; 
?>