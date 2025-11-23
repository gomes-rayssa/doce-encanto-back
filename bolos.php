<?php
include 'header.php';
include 'db_config.php';

$bolos = [];
$sql = "SELECT id, nome, descricao, preco, imagem_url, categoria 
        FROM produtos 
        WHERE categoria LIKE '%bolo%' AND estoque > 0
        ORDER BY nome";

$bolos_filtrados = [];
$count_tradicionais = 0;
$count_gourmet = 0;

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        // Lógica de subcategoria:
        // Se o preço for maior que R$ 64.99, é Gourmet. Caso contrário, é Tradicional.
        $subcategoria = ((float)$row['preco'] > 64.99) ? 'gourmet' : 'tradicionais';

        if ($subcategoria === 'gourmet') {
            $count_gourmet++;
        } else {
            $count_tradicionais++;
        }

        $bolos_filtrados[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
            'descricao' => $row['descricao'],
            'preco' => (float)$row['preco'],
            'imagem' => htmlspecialchars($row['imagem_url']), 
            'categoria' => htmlspecialchars($subcategoria), // Usar a subcategoria para o filtro JS
        ];
    }
    $result->free();
} else {
    echo "<h2>Erro na Consulta SQL: " . $conn->error . "</h2>";
}

$bolos = $bolos_filtrados;
$count_todos = count($bolos);
?>

<link rel="stylesheet" href="style.css" />
<link rel="stylesheet" href="loja.css" />

<main class="shop-page">
  <div class="container">
    <div class="page-header">
      <div class="breadcrumb">
        <a href="index.php">Início</a>
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
                <span class="count"><?php echo $count_todos; ?></span>
              </a>
            </li>
            <li class="category-item">
              <a href="#tradicionais" class="category-link" data-category="tradicionais">
                <i class="fas fa-birthday-cake"></i>
                <span>Bolos Tradicionais</span>
                <span class="count"><?php echo $count_tradicionais; ?></span>
              </a>
            </li>
            <li class="category-item">
              <a href="#gourmet" class="category-link" data-category="gourmet">
                <i class="fas fa-star"></i>
                <span>Bolos Gourmet</span>
                <span class="count"><?php echo $count_gourmet; ?></span>
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