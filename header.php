<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
    content="Doce Encanto - Confeitaria Gourmet com bolos e doces artesanais de alta qualidade" />
  <meta name="keywords" content="confeitaria, bolos, doces, gourmet, artesanal" />
  <title>Doce Encanto - Confeitaria Gourmet</title>

  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="sobre.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

  <style>
    @import url("https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@300;400;500;700&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
  </style>
</head>

<body>
  <a href="#main-content" class="skip-to-main">Pular para o conteúdo principal</a>
	  <header class="modern-header">
    <nav class="modern-nav">
      <div class="nav-container">
        <div class="nav-logo">
          <a href="index.php" class="logo-link">
            <i class="fas fa-birthday-cake" aria-hidden="true"></i>
            <span class="logo-text">Doce Encanto</span>
          </a>
        </div>

        <ul class="nav-menu">
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="fas fa-home" aria-hidden="true"></i>
              <span>Início</span>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle">
              <i class="fas fa-store" aria-hidden="true"></i>
              <span>Produtos</span>
              <i class="fas fa-chevron-down" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu">
              <a href="bolos.php" class="dropdown-item">
                <i class="fas fa-birthday-cake" aria-hidden="true"></i>
                <span>Bolos</span>
              </a>
              <a href="doces.php" class="dropdown-item">
                <i class="fas fa-candy-cane" aria-hidden="true"></i>
                <span>Doces</span>
              </a>
            </div>
          </li>
        </ul>

        <div class="nav-actions">
          <li class="nav-item dropdown" style="list-style: none;">
            <a href="#" class="nav-link dropdown-toggle" title="Minha Conta">
              <i class="fas fa-user" aria-hidden="true"></i>
              <i class="fas fa-chevron-down" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu">
              <?php if (isset($_SESSION['usuario_logado'])): ?>
                <a href="usuario.php" class="dropdown-item">
                  <i class="fas fa-id-card" aria-hidden="true"></i>
                  <span>Minha Conta</span>
                </a>
                <a href="logout.php" class="dropdown-item">
                  <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                  <span>Sair</span>
                </a>
              <?php else: ?>
                <a href="login.php" class="dropdown-item">
                  <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                  <span>Login</span>
                </a>
                <a href="cadastro.php" class="dropdown-item">
                  <i class="fas fa-user-plus" aria-hidden="true"></i>
                  <span>Cadastro</span>
                </a>
              <?php endif; ?>
            </div>
          </li>

          <?php
          $total_itens_carrinho = 0;
          if (!empty($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
              $total_itens_carrinho += $item['quantidade'];
            }
          }
          ?>
          <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
          <a href="admin.php" class="nav-action" title="Voltar para Área Administrativa" 
             aria-label="Voltar para área administrativa"
             style="background-color: var(--primary-color); color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; font-weight: 600;">
            <i class="fas fa-user-shield" aria-hidden="true"></i>
            <span style="display: none;">Admin</span>
          </a>
          <?php endif; ?>
          
          <a href="carrinho.php" class="nav-action cart-action" title="Carrinho de compras">
            <i class="fas fa-shopping-bag" aria-hidden="true"></i>
            <span class="cart-count"><?php echo $total_itens_carrinho; ?></span>
          </a>

          <button class="mobile-menu-toggle">
            <span></span>
            <span></span>
            <span></span>
          </button>
        </div>
      </div>
    </nav>
  </header>

	  <main id="main-content">