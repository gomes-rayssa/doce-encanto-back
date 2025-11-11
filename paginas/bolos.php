<?php
include 'header.php';

$bolos = [
  [
    'id' => 'bolo_chocolate',
    'nome' => 'Bolo de Chocolate',
    'descricao' => 'Bolo de massa de chocolate, recheio de trufado de chocolate ao leite',
    'preco' => 100.00,
    'imagem' => '../assets/bolos/bolo-chocolate.png',
    'categoria' => 'tradicionais'
  ],
  [
    'id' => 'bolo_red_velvet',
    'nome' => 'Bolo Red Velvet',
    'descricao' => 'Bolo de massa red velvet, recheios de Leite Moça®',
    'preco' => 120.00,
    'imagem' => '../assets/bolos/bolo-red velvet.png',
    'categoria' => 'tradicionais'
  ],
  [
    'id' => 'bolo_pistache',
    'nome' => 'Bolo de Pistache',
    'descricao' => 'Bolo de massa branca, recheios de trufado e aerado de pistache',
    'preco' => 150.00,
    'imagem' => '../assets/bolos/bolo-pistache.png',
    'categoria' => 'gourmet'
  ]
];
?>

<link rel="stylesheet" href="style.css" />
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
      <aside class="sidebar">
        <div class="sidebar-header">
          <h3>
            <i class="fas fa-filter"></i>
            Categorias
          </h3>
        </div>

        <nav class="sidebar-nav">
          <ul class="category-menu">
            <li class="category-item">
              <a href="#todos" class="category-link active" data-category="todos">
                <i class="fas fa-th-large"></i>
                <span>Todos os Bolos</span>
                <span class="count"><?php echo count($bolos); ?></span>
              </a>
            </li>
            <li class="category-item">
              <a href="#tradicionais" class="category-link" data-category="tradicionais">
                <i class="fas fa-birthday-cake"></i>
                <span>Bolos Tradicionais</span>
                <span class="count">2</span>
              </a>
            </li>
            <li class="category-item">
              <a href="#gourmet" class="category-link" data-category="gourmet">
                <i class="fas fa-star"></i>
                <span>Bolos Gourmet</span>
                <span class="count">1</span>
              </a>
            </li>
          </ul>
        </nav>

        <div class="sidebar-section">
          <h4>Ordenar por</h4>
          <select class="sort-select" id="sortSelect">
            <option value="name">Nome A-Z</option>
            <option value="price-low">Menor Preço</option>
            <option value="price-high">Maior Preço</option>
          </select>
        </div>
      </aside>

      <section class="products-area">
        <div class="products-header">
          <div class="results-info">
            <span id="resultsCount">Mostrando <?php echo count($bolos); ?> produtos</span>
          </div>
          <div class="view-toggle">
            <button class="view-btn active" data-view="grid" title="Visualização em Grade">
              <i class="fas fa-th"></i>
            </button>
            <button class="view-btn" data-view="list" title="Visualização em Lista">
              <i class="fas fa-list"></i>
            </button>
          </div>
        </div>

        <div class="products-grid" id="productsGrid">
          <?php foreach ($bolos as $bolo): ?>
            <div class="product-card" data-category="<?php echo $bolo['categoria']; ?>"
              data-price="<?php echo $bolo['preco']; ?>" data-name="<?php echo htmlspecialchars($bolo['nome']); ?>">
              <div class="product-image">
                <img src="<?php echo htmlspecialchars($bolo['imagem']); ?>"
                  alt="<?php echo htmlspecialchars($bolo['nome']); ?>" />
                <div class="product-actions">
                  <button class="quick-view" title="Visualização Rápida">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button class="add-to-cart" onclick="adicionarItem(
                    '<?php echo $bolo['id']; ?>',
                    '<?php echo htmlspecialchars($bolo['nome'], ENT_QUOTES); ?>',
                    <?php echo $bolo['preco']; ?>,
                    '<?php echo htmlspecialchars($bolo['imagem'], ENT_QUOTES); ?>',
                    '<?php echo htmlspecialchars($bolo['categoria'], ENT_QUOTES); ?>'
                  )" title="Adicionar ao Carrinho">
                    <i class="fas fa-shopping-cart"></i>
                  </button>
                </div>
              </div>
              <div class="product-info">
                <h3 class="product-name"><?php echo htmlspecialchars($bolo['nome']); ?></h3>
                <p class="product-description"><?php echo htmlspecialchars($bolo['descricao']); ?></p>
                <div class="product-rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span>(4.9)</span>
                </div>
                <div class="product-price">R$ <?php echo number_format($bolo['preco'], 2, ',', '.'); ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    </div>
  </div>
</main>

<?php
include 'footer.php';
?>
<script src="cart.js"></script>
<script src="shop.js"></script>