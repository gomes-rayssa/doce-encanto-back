<?php
include 'header.php';

$produtos_destaque = [
  [
    'id' => 'bolo_chocolate',
    'nome' => 'Bolo de Chocolate',
    'descricao' => 'Bolo de massa de chocolate, recheio de trufado de chocolate ao leite, cobertura de mousse de chocolate, raspas de chocolate ao leite e cerejas.',
    'preco' => 100.00,
    'imagem' => '../assets/bolos/bolo-chocolate.png',
    'categoria' => 'bolo',
    'badge' => 'Bestseller'
  ],
  [
    'id' => 'bolo_red_velvet',
    'nome' => 'Bolo Red Velvet',
    'descricao' => 'Bolo de massa red velvet, recheios de Leite Moça®, mousse de cream cheese, cobertura de mousse branca, Leite Moça®, massa red velvet e cerejas decorativas.',
    'preco' => 120.00,
    'imagem' => '../assets/bolos/bolo-red velvet.png',
    'categoria' => 'bolo',
    'badge' => 'Novo'
  ],
  [
    'id' => 'bolo_pistache',
    'nome' => 'Bolo de Pistache',
    'descricao' => 'Bolo de massa branca, recheios de trufado e aerado de pistache, cobertura de trufado de pistache e pistache granulado.',
    'preco' => 150.00,
    'imagem' => '../assets/bolos/bolo-pistache.png',
    'categoria' => 'bolo',
    'badge' => 'Premium'
  ],
  [
    'id' => 'doce_pistache',
    'nome' => 'Doce de Pistache',
    'descricao' => 'Brigadeiro feito com chocolate branco belga e pistache.',
    'preco' => 12.00,
    'imagem' => '../assets/doces-gourmet/pistache.png',
    'categoria' => 'doce gourmet',
    'badge' => ''
  ]
];
?>

<section class="hero-section">
  <div class="hero-background">
    <div class="hero-overlay"></div>
  </div>
  <div class="hero-content">
    <div class="hero-text">
      <h1 class="hero-title">
        <span class="title-line">Confeitaria</span>
        <span class="title-line highlight">Gourmet</span>
      </h1>
      <p class="hero-subtitle">
        Sabores únicos que despertam emoções e criam memórias inesquecíveis
      </p>
      <div class="hero-buttons">
        <a href="paginas/bolos.php" class="btn btn-primary">
          <i class="fas fa-birthday-cake"></i>
          Ver Bolos
        </a>
        <a href="paginas/doces.php" class="btn btn-secondary">
          <i class="fas fa-candy-cane"></i>
          Ver Doces
        </a>
      </div>
    </div>
    <div class="hero-stats">
      <div class="stat-item">
        <span class="stat-number">1000+</span>
        <span class="stat-label">Clientes Felizes</span>
      </div>
      <div class="stat-item">
        <span class="stat-number">30+</span>
        <span class="stat-label">Sabores Únicos</span>
      </div>
      <div class="stat-item">
        <span class="stat-number">5</span>
        <span class="stat-label">Anos de Experiência</span>
      </div>
    </div>
  </div>
</section>

<section class="categories-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Nossas Especialidades</h2>
      <p class="section-subtitle">
        Descubra nossos produtos artesanais feitos com amor e ingredientes
        selecionados
      </p>
    </div>
    <div class="categories-grid">
      <div class="category-card" data-category="bolos">
        <div class="category-image">
          <img src="../assets/bolos/bolo-chocolate.png" alt="Bolos" />
          <div class="category-overlay">
            <i class="fas fa-birthday-cake"></i>
          </div>
        </div>
        <div class="category-content">
          <h3>Bolos</h3>
          <p>Bolos únicos para momentos especiais</p>
          <a href="paginas/bolos.php" class="category-link">
            Ver Coleção <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <div class="category-card" data-category="doces">
        <div class="category-image">
          <img src="../assets/doces/brigadeiro.png" alt="Doces" />
          <div class="category-overlay">
            <i class="fas fa-candy-cane"></i>
          </div>
        </div>
        <div class="category-content">
          <h3>Doces</h3>
          <p>Pequenos prazeres da vida</p>
          <a href="paginas/doces.php" class="category-link">
            Ver Coleção <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <div class="category-card" data-category="trufas">
        <div class="category-image">
          <img src="../assets/doces-trufas/brigadeiro.png" alt="Trufas" />
          <div class="category-overlay">
            <i class="fas fa-gem"></i>
          </div>
        </div>
        <div class="category-content">
          <h3>Trufas</h3>
          <p>Sofisticação em cada mordida</p>
          <a href="paginas/doces.php#trufas" class="category-link">
            Ver Coleção <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="featured-products">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Mais Vendidos</h2>
      <p class="section-subtitle">Os favoritos dos nossos clientes</p>
    </div>
    <div class="products-grid">

      <?php foreach ($produtos_destaque as $produto): ?>

        <div class="product-card">
          <div class="product-image">
            <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
              alt="<?php echo htmlspecialchars($produto['nome']); ?>" />

            <?php if (!empty($produto['badge'])): ?>
              <div class="product-badge <?php echo strtolower(htmlspecialchars($produto['badge'])); ?>">
                <?php echo htmlspecialchars($produto['badge']); ?>
              </div>
            <?php endif; ?>

            <div class="product-actions">
              <button class="quick-view" title="Visualização Rápida">
                <i class="fas fa-eye"></i>
              </button>

              <button class="add-to-cart" onclick="adicionarItem(
                  '<?php echo $produto['id']; ?>',
                  '<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES); ?>',
                  <?php echo $produto['preco']; ?>,
                  '<?php echo htmlspecialchars($produto['imagem'], ENT_QUOTES); ?>',
                  '<?php echo htmlspecialchars($produto['categoria'], ENT_QUOTES); ?>'
                )" title="Adicionar ao Carrinho">
                <i class="fas fa-shopping-cart"></i>
              </button>
            </div>
          </div>
          <div class="product-info">
            <h3 class="product-name"><?php echo htmlspecialchars($produto['nome']); ?></h3>
            <p class="product-description"><?php echo htmlspecialchars($produto['descricao']); ?></p>
            <div class="product-rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <span>(4.9)</span>
            </div>
            <div class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
          </div>
        </div>

      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="newsletter-section">
  <div class="container">
    <div class="newsletter-content">
      <div class="newsletter-text">
        <h2>Fique por dentro das novidades!</h2>
        <p>
          Receba ofertas exclusivas, novos sabores e promoções especiais
          diretamente no seu email
        </p>
      </div>
      <form id="newsletter-form" class="newsletter-form">
        <div class="form-group">
          <input type="text" id="newsletter-nome" placeholder="Seu nome" required />
          <input type="email" id="newsletter-email" placeholder="seu@email.com" required />
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i>
            Inscrever-se
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<?php
include 'footer.php';
?>