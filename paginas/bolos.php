<?php 
  include '../header.php'; 
?>
<link rel="stylesheet" href="../style.css" />
<link rel="stylesheet" href="loja.css" />

  <main class="shop-page">
    <div class="container">
      <div class="page-header">
        <div class="breadcrumb">
          <a href="../index.php">Início</a>
          <i class="fas fa-chevron-right"></i>
          <span>Bolos</span>
        </div>
        <h1 class="page-title">
          <i class="fas fa-birthday-cake"></i>
          Nossos Bolos Artesanais
        </h1>
        <p class="page-subtitle">
          Bolos únicos feitos com amor e ingredientes selecionados para tornar
          seus momentos ainda mais especiais
        </p>
      </div>
      <div class="shop-layout">
        <div class="product-card" data-category="tradicionais" data-price="100" data-name="Bolo de Chocolate">
          <div class="product-image">
            <img src="../assets/bolos/bolo-chocolate.png" alt="Bolo de Chocolate" />
            <div class="product-badge bestseller">Bestseller</div>
            <div class="product-actions">
              <button class="quick-view" title="Visualização Rápida">
                <i class="fas fa-eye"></i>
              </button>
              <button class="add-to-cart"
                onclick="adicionarItem('bolo_chocolate', 'Bolo de Chocolate', 100, '../assets/bolos/bolo-chocolate.png', 'bolo tradicional')"
                title="Adicionar ao Carrinho">
                <i class="fas fa-shopping-cart"></i>
              </button>
              </div>
          </div>
          </div>
        </div>
    </div>
  </main>

<?php 
  include '../footer.php'; 
?>
<script src="shop.js"></script>