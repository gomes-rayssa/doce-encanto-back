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
  <link rel="stylesheet" href="acessibilidade-menu.css">

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

    <button id="accessibility-button" class="accessibility-button" aria-label="Abrir menu de acessibilidade"
      aria-expanded="false" title="Menu de Acessibilidade (Alt + A)">
      <i class="fas fa-universal-access" aria-hidden="true"></i>
    </button>

    <div id="accessibility-menu" class="accessibility-menu" role="dialog" aria-labelledby="accessibility-title"
      aria-modal="false">
      <div class="accessibility-header">
        <h2 id="accessibility-title" class="accessibility-title">
          <i class="fas fa-universal-access" aria-hidden="true"></i>
          Acessibilidade
        </h2>
        <button id="close-accessibility-menu" class="accessibility-close" aria-label="Fechar menu de acessibilidade"
          title="Fechar (ESC)">
          <i class="fas fa-times" aria-hidden="true"></i>
        </button>
      </div>

      <div class="accessibility-content">
        <div class="accessibility-group">
          <h3 class="accessibility-group-title">
            <i class="fas fa-text-height" aria-hidden="true"></i>
            Tamanho da Fonte
          </h3>
          <div class="accessibility-control">
            <div class="control-info">
              <div class="control-label">
                <span>Ajustar Tamanho</span>
              </div>
              <div class="control-description">Use Alt + / Alt - como atalho</div>
            </div>
            <div class="control-action">
              <button id="decrease-font" class="action-button" aria-label="Diminuir tamanho da fonte"
                title="Diminuir fonte (Alt + -)">
                <i class="fas fa-minus" aria-hidden="true"></i>
              </button>
              <button id="increase-font" class="action-button" aria-label="Aumentar tamanho da fonte"
                title="Aumentar fonte (Alt + +)">
                <i class="fas fa-plus" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="accessibility-group">
          <h3 class="accessibility-group-title">
            <i class="fas fa-align-justify" aria-hidden="true"></i>
            Espaçamento de Linha
          </h3>
          <div class="accessibility-control">
            <div class="control-info">
              <div class="control-label">
                <span>Ajustar Espaçamento</span>
              </div>
              <div class="control-description">Melhora a legibilidade do texto</div>
            </div>
            <div class="control-action">
              <button id="decrease-spacing" class="action-button" aria-label="Diminuir espaçamento entre linhas">
                <i class="fas fa-minus" aria-hidden="true"></i>
              </button>
              <button id="increase-spacing" class="action-button" aria-label="Aumentar espaçamento entre linhas">
                <i class="fas fa-plus" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="accessibility-group">
          <h3 class="accessibility-group-title">
            <i class="fas fa-adjust" aria-hidden="true"></i>
            Contraste e Cores
          </h3>

          <div class="accessibility-control">
            <div class="control-info">
              <div class="control-label">
                <i class="fas fa-circle-half-stroke" aria-hidden="true"></i>
                <span>Alto Contraste</span>
              </div>
              <div class="control-description">Aumenta o contraste das cores (Alt + C)</div>
            </div>
            <label class="toggle-switch" aria-label="Ativar alto contraste">
              <input type="checkbox" id="toggle-contrast" aria-label="Alto contraste">
              <span class="toggle-slider"></span>
            </label>
          </div>

          <div class="accessibility-control">
            <div class="control-info">
              <div class="control-label">
                <i class="fas fa-palette" aria-hidden="true"></i>
                <span>Escala de Cinza</span>
              </div>
              <div class="control-description">Remove todas as cores</div>
            </div>
            <label class="toggle-switch" aria-label="Ativar escala de cinza">
              <input type="checkbox" id="toggle-grayscale" aria-label="Escala de cinza">
              <span class="toggle-slider"></span>
            </label>
          </div>

          <div class="accessibility-control">
            <div class="control-info">
              <div class="control-label">
                <i class="fas fa-exchange-alt" aria-hidden="true"></i>
                <span>Inverter Cores</span>
              </div>
              <div class="control-description">Inverte as cores da página</div>
            </div>
            <label class="toggle-switch" aria-label="Ativar inversão de cores">
              <input type="checkbox" id="toggle-invert" aria-label="Inverter cores">
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        <div class="accessibility-group">
          <h3 class="accessibility-group-title">
            <i class="fas fa-mouse-pointer" aria-hidden="true"></i>
            Navegação
          </h3>

          <div class="accessibility-control">
            <div class="control-info">
              <div class="control-label">
                <i class="fas fa-mouse" aria-hidden="true"></i>
                <span>Cursor Grande</span>
              </div>
              <div class="control-description">Aumenta o tamanho do cursor</div>
            </div>
            <label class="toggle-switch" aria-label="Ativar cursor grande">
              <input type="checkbox" id="toggle-cursor" aria-label="Cursor grande">
              <span class="toggle-slider"></span>
            </label>
          </div>

          <div class="accessibility-control">
            <div class="control-info">
              <div class="control-label">
                <i class="fas fa-link" aria-hidden="true"></i>
                <span>Realçar Links</span>
              </div>
              <div class="control-description">Destaca todos os links da página</div>
            </div>
            <label class="toggle-switch" aria-label="Ativar realce de links">
              <input type="checkbox" id="toggle-links" aria-label="Realçar links">
              <span class="toggle-slider"></span>
            </label>
          </div>

          <div class="accessibility-control">
            <div class="control-info">
              <div class="control-label">
                <i class="fas fa-book-open" aria-hidden="true"></i>
                <span>Guia de Leitura</span>
              </div>
              <div class="control-description">Foca área de leitura atual</div>
            </div>
            <label class="toggle-switch" aria-label="Ativar guia de leitura">
              <input type="checkbox" id="toggle-reading-mask" aria-label="Guia de leitura">
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        <button id="reset-accessibility" class="reset-button">
          <i class="fas fa-redo" aria-hidden="true"></i>
          Resetar Todas as Configurações
        </button>

        <div
          style="margin-top: 1.5rem; padding: 1rem; background: #f3f4f6; border-radius: 12px; font-size: 0.85rem; color: #6b7280;">
          <strong style="display: block; margin-bottom: 0.5rem; color: #990053;">Atalhos de Teclado:</strong>
          <ul style="list-style: none; padding: 0; margin: 0; line-height: 1.8;">
            <li><kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #d1d5db;">Alt +
                A</kbd> Abrir menu</li>
            <li><kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #d1d5db;">Alt +
                +</kbd> Aumentar fonte</li>
            <li><kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #d1d5db;">Alt +
                -</kbd> Diminuir fonte</li>
            <li><kbd style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #d1d5db;">Alt +
                C</kbd> Alto contraste</li>
            <li><kbd
                style="background: white; padding: 2px 6px; border-radius: 4px; border: 1px solid #d1d5db;">ESC</kbd>
              Fechar menu</li>
          </ul>
        </div>
      </div>
    </div>

    <script src="acessibilidade-menu.js"></script>