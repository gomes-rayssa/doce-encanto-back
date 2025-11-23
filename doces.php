<?php
include 'header.php';
include 'db_config.php';

$produtos = [];
$sql = "SELECT id, nome, descricao, preco, imagem_url, categoria FROM produtos WHERE (categoria LIKE '%doce%' OR categoria LIKE '%chocolate%' OR categoria LIKE '%brigadeiro%' OR categoria LIKE '%beijinho%' OR categoria LIKE '%trufa%') AND estoque > 0 ORDER BY nome";

if ($result = $conn->query($sql)) {
  while ($row = $result->fetch_assoc()) {
    $cat_display = '';
    // Lógica de subcategoria:
    // 1. Trufas Premium (prioridade)
    if (str_contains($row['nome'], 'Trufa') || str_contains($row['nome'], 'Premium')) {
      $cat_display = 'trufas';
    // 2. Doces Gourmet (preço > R$ 4.49)
    } elseif ((float) $row['preco'] > 4.49) {
      $cat_display = 'gourmet';
    // 3. Doces Tradicionais (default)
    } else {
      $cat_display = 'tradicionais';
    }

    $produtos[] = [
      'id' => $row['id'],
      'nome' => $row['nome'],
      'descricao' => $row['descricao'],
      'preco' => (float) $row['preco'],
      'imagem' => htmlspecialchars($row['imagem_url']),
      'categoria_display' => $cat_display,
    ];
  }
  $result->free();
}

$conn->close();

$count_todos = count($produtos);
$count_tradicionais = count(array_filter($produtos, fn($p) => $p['categoria_display'] == 'tradicionais'));
$count_trufas = count(array_filter($produtos, fn($p) => $p['categoria_display'] == 'trufas'));
$count_gourmet = count(array_filter($produtos, fn($p) => $p['categoria_display'] == 'gourmet'));
?>

<link rel="stylesheet" href="style.css" />
<link rel="stylesheet" href="loja.css" />

<main class="shop-page">
  <div class="container">
    <div class="page-header">
      <div class="breadcrumb">
        <a href="index.php">Início</a>
        <i class="fas fa-chevron-right"></i>
        <span>Doces</span>
      </div>
      <h1 class="page-title">
        <i class="fas fa-candy-cane"></i>
        Nossos Doces Artesanais
      </h1>
      <p class="page-subtitle">
        Pequenos prazeres da vida feitos com ingredientes premium e muito
        carinho para adoçar seus momentos especiais
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
                <span>Todos os Doces</span>
                <span class="count"><?php echo $count_todos; ?></span>
              </a>
            </li>
            <li class="category-item">
              <a href="#tradicionais" class="category-link" data-category="tradicionais">
                <i class="fas fa-candy-cane"></i>
                <span>Doces Tradicionais</span>
                <span class="count"><?php echo $count_tradicionais; ?></span>
              </a>
            </li>
            <li class="category-item">
              <a href="#gourmet" class="category-link" data-category="gourmet">
                <i class="fas fa-star"></i>
                <span>Doces Gourmet</span>
                <span class="count"><?php echo $count_gourmet; ?></span>
              </a>
            </li>
            <li class="category-item">
              <a href="#trufas" class="category-link" data-category="trufas">
                <i class="fas fa-gem"></i>
                <span>Trufas Premium</span>
                <span class="count"><?php echo $count_trufas; ?></span>
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
            <span id="resultsCount">Mostrando <?php echo $count_todos; ?> produtos</span>
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
          <?php if (empty($produtos)): ?>
            <p>Nenhum doce encontrado no catálogo. Verifique a tabela `produtos`.</p>
          <?php else: ?>
            <?php foreach ($produtos as $doce): ?>
              <div class="product-card" data-category="<?php echo $doce['categoria_display']; ?>"
                data-price="<?php echo $doce['preco']; ?>" data-name="<?php echo htmlspecialchars($doce['nome']); ?>">
                <div class="product-image">
                  <img src="<?php echo htmlspecialchars($doce['imagem']); ?>"
                    alt="<?php echo htmlspecialchars($doce['nome']); ?>" />
                  <div class="product-actions">
                    <button class="quick-view" title="Visualização Rápida">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button class="add-to-cart"
                      onclick="adicionarItem('<?php echo $doce['id']; ?>', '<?php echo htmlspecialchars($doce['nome'], ENT_QUOTES); ?>', <?php echo $doce['preco']; ?>, '<?php echo htmlspecialchars($doce['imagem'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($doce['categoria_display'], ENT_QUOTES); ?>')"
                      title="Adicionar ao Carrinho">
                      <i class="fas fa-shopping-cart"></i>
                    </button>
                    <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                      <i class="fas fa-heart"></i>
                    </button>
                  </div>
                </div>
                <div class="product-info">
                  <h3 class="product-name"><?php echo htmlspecialchars($doce['nome']); ?></h3>
                  <p class="product-description"><?php echo htmlspecialchars($doce['descricao']); ?></p>
                  <div class="product-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <span>(4.9)</span>
                  </div>
                  <div class="product-price">R$ <?php echo number_format($doce['preco'], 2, ',', '.'); ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</main>

<?php
include 'footer.php';
?>

<script src="script.js"></script>
<script src="shop.js"></script>