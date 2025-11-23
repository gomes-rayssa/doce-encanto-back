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
  <header class="modern-header" role="banner">
    <nav class="modern-nav" role="navigation" aria-label="Navegação principal">
      <div class="nav-container">
        <div class="nav-logo">
          <a href="index.php" class="logo-link" aria-label="Doce Encanto - Página inicial">
            <i class="fas fa-birthday-cake" aria-hidden="true"></i>
            <span class="logo-text">Doce Encanto</span>
          </a>
        </div>

        <ul class="nav-menu" role="menubar">
          <li class="nav-item" role="none">
            <a href="index.php" class="nav-link" role="menuitem" aria-label="Ir para página inicial">
              <i class="fas fa-home" aria-hidden="true"></i>
              <span>Início</span>
            </a>
          </li>
          <li class="nav-item dropdown" role="none">
            <a href="#" class="nav-link dropdown-toggle" role="menuitem" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-store" aria-hidden="true"></i>
              <span>Produtos</span>
              <i class="fas fa-chevron-down" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu" role="menu" aria-label="Submenu de produtos">
              <a href="bolos.php" class="dropdown-item" role="menuitem">
                <i class="fas fa-birthday-cake" aria-hidden="true"></i>
                <span>Bolos</span>
              </a>
              <a href="doces.php" class="dropdown-item" role="menuitem">
                <i class="fas fa-candy-cane" aria-hidden="true"></i>
                <span>Doces</span>
              </a>
            </div>
          </li>
        </ul>

        <div class="nav-actions">
          <li class="nav-item dropdown" style="list-style: none;" role="none">
            <a href="#" class="nav-link dropdown-toggle" title="Minha Conta" role="menuitem" aria-haspopup="true"
              aria-expanded="false" aria-label="Menu da conta">
              <i class="fas fa-user" aria-hidden="true"></i>
              <i class="fas fa-chevron-down" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu" role="menu" aria-label="Submenu da conta">
              <?php if (isset($_SESSION['usuario_logado'])): ?>
                <a href="usuario.php" class="dropdown-item" role="menuitem">
                  <i class="fas fa-id-card" aria-hidden="true"></i>
                  <span>Minha Conta</span>
                </a>
                <a href="logout.php" class="dropdown-item" role="menuitem">
                  <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                  <span>Sair</span>
                </a>
              <?php else: ?>
                <a href="login.php" class="dropdown-item" role="menuitem">
                  <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                  <span>Login</span>
                </a>
                <a href="cadastro.php" class="dropdown-item" role="menuitem">
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
          <a href="carrinho.php" class="nav-action cart-action" title="Carrinho de compras"
            aria-label="Carrinho com <?php echo $total_itens_carrinho; ?> itens">
            <i class="fas fa-shopping-bag" aria-hidden="true"></i>
            <span class="cart-count" aria-live="polite"><?php echo $total_itens_carrinho; ?></span>
          </a>

          <button class="mobile-menu-toggle" aria-label="Abrir menu de navegação" aria-expanded="false"
            aria-controls="mobile-menu">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
          </button>
        </div>
      </div>
    </nav>
  </header>

  <main id="main-content" role="main">