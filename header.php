<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Doce Encanto - Confeitaria Gourmet</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@300;400;500;700&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
  </style>
</head>

<body>
  <header class="modern-header">
    <nav class="modern-nav">
      <div class="nav-container">
        <div class="nav-logo">
          <a href="index.php" class="logo-link">
            <i class="fas fa-birthday-cake"></i>
            <span class="logo-text">Doce Encanto</span>
          </a>
        </div>

        <ul class="nav-menu">
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="fas fa-home"></i>
              <span>Início</span>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle">
              <i class="fas fa-store"></i>
              <span>Produtos</span>
              <i class="fas fa-chevron-down"></i>
            </a>
            <div class="dropdown-menu">
              <a href="bolos.php" class="dropdown-item">
                <i class="fas fa-birthday-cake"></i>
                <span>Bolos</span>
              </a>
              <a href="doces.php" class="dropdown-item">
                <i class="fas fa-candy-cane"></i>
                <span>Doces</span>
              </a>
            </div>
          </li>
          <li class="nav-item">
            <a href="sobre.php" class="nav-link">
              <i class="fas fa-info-circle"></i>
              <span>Sobre</span>
            </a>
          </li>
        </ul>

        <div class="nav-actions">

          <li class="nav-item dropdown" style="list-style: none;">
            <a href="#" class="nav-link dropdown-toggle" title="Minha Conta">
              <i class="fas fa-user"></i>
              <i class="fas fa-chevron-down"></i>
            </a>
            <div class="dropdown-menu">

              <?php if (isset($_SESSION['usuario_logado'])): ?>
                <a href="usuario.php" class="dropdown-item">
                  <i class="fas fa-id-card"></i>
                  <span>Minha Conta</span>
                </a>
                <a href="logout.php" class="dropdown-item">
                  <i class="fas fa-sign-out-alt"></i>
                  <span>Sair</span>
                </a>
              <?php else: ?>
                <a href="login.php" class="dropdown-item">
                  <i class="fas fa-sign-in-alt"></i>
                  <span>Login</span>
                </a>
                <a href="cadastro.php" class="dropdown-item">
                  <i class="fas fa-user-plus"></i>
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
          <a href="carrinho.php" class="nav-action cart-action" title="Carrinho">
            <i class="fas fa-shopping-bag"></i>
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