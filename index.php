<?php
include 'header.php';

$produtos_destaque = [
  [
    'id' => 'bolo_chocolate',
    'nome' => 'Bolo de Chocolate',
    'descricao' => 'Bolo de massa de chocolate, recheio de trufado de chocolate ao leite, cobertura de mousse de chocolate, raspas de chocolate ao leite e cerejas.',
    'preco' => 100.00,
    'imagem' => 'assets/bolos/bolo-chocolate.png',
    'categoria' => 'bolo',
    'badge' => 'Bestseller'
  ],
  [
    'id' => 'bolo_red_velvet',
    'nome' => 'Bolo Red Velvet',
    'descricao' => 'Bolo de massa red velvet, recheios de Leite Moça®, mousse de cream cheese, cobertura de mousse branca, Leite Moça®, massa red velvet e cerejas decorativas.',
    'preco' => 120.00,
    'imagem' => 'assets/bolos/bolo-red velvet.png',
    'categoria' => 'bolo',
    'badge' => 'Novo'
  ],
  [
    'id' => 'bolo_pistache',
    'nome' => 'Bolo de Pistache',
    'descricao' => 'Bolo de massa branca, recheios de trufado e aerado de pistache, cobertura de trufado de pistache e pistache granulado.',
    'preco' => 150.00,
    'imagem' => 'assets/bolos/bolo-pistache.png',
    'categoria' => 'bolo',
    'badge' => 'Premium'
  ],
  [
    'id' => 'doce_pistache',
    'nome' => 'Doce de Pistache',
    'descricao' => 'Brigadeiro feito com chocolate branco belga e pistache.',
    'preco' => 12.00,
    'imagem' => 'assets/doces-gourmet/pistache.png',
    'categoria' => 'doce gourmet',
    'badge' => ''
  ]
];
?>

<main id="main-content">
  <section class="hero-section" aria-labelledby="hero-title">
    <div class="hero-background">
      <div class="hero-overlay"></div>
    </div>
    <div class="hero-content">
      <div class="hero-text">
        <h1 class="hero-title" id="hero-title">
          <span class="title-line">Confeitaria</span>
          <span class="title-line highlight">Gourmet</span>
        </h1>
        <p class="hero-subtitle">
          Sabores únicos que despertam emoções e criam memórias inesquecíveis
        </p>
        <div class="hero-buttons">
          <a href="bolos.php" class="btn btn-primary">
            <i class="fas fa-birthday-cake" aria-hidden="true"></i>
            Ver Bolos
          </a>
          <a href="doces.php" class="btn btn-secondary">
            <i class="fas fa-candy-cane" aria-hidden="true"></i>
            Ver Doces
          </a>
        </div>
      </div>
      </div>
    </div>
  </section>

  <section class="categories-section" aria-labelledby="categories-title">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" id="categories-title">Nossas Especialidades</h2>
        <p class="section-subtitle">
          Descubra nossos produtos artesanais feitos com amor e ingredientes
          selecionados
        </p>
      </div>
      <div class="categories-grid">
        <div class="category-card" data-category="bolos">
          <div class="category-image">
            <img src="assets/bolos/bolo-chocolate.png" alt="Bolo de chocolate - Categoria Bolos" />
            <div class="category-overlay" aria-hidden="true">
              <i class="fas fa-birthday-cake"></i>
            </div>
          </div>
          <div class="category-content">
            <h3>Bolos</h3>
            <p>Bolos únicos para momentos especiais</p>
            <a href="paginas/bolos.php" class="category-link">
              Ver Coleção <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <div class="category-card" data-category="doces">
          <div class="category-image">
            <img src="assets/doces/brigadeiro.png" alt="Brigadeiro - Categoria Doces" />
            <div class="category-overlay" aria-hidden="true">
              <i class="fas fa-candy-cane"></i>
            </div>
          </div>
          <div class="category-content">
            <h3>Doces</h3>
            <p>Pequenos prazeres da vida</p>
            <a href="paginas/doces.php" class="category-link">
              Ver Coleção <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <div class="category-card" data-category="trufas">
          <div class="category-image">
            <img src="assets/doces-trufas/brigadeiro.png" alt="Trufa de brigadeiro - Categoria Trufas" />
            <div class="category-overlay" aria-hidden="true">
              <i class="fas fa-gem"></i>
            </div>
          </div>
          <div class="category-content">
            <h3>Trufas</h3>
            <p>Sofisticação em cada mordida</p>
            <a href="paginas/doces.php#trufas" class="category-link">
              Ver Coleção <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="featured-products" aria-labelledby="featured-title">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" id="featured-title">Mais Vendidos</h2>
        <p class="section-subtitle">Os favoritos dos nossos clientes</p>
      </div>
      <div class="products-grid">

        <?php foreach ($produtos_destaque as $produto): ?>

          <article class="product-card">
            <div class="product-image">
              <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                alt="<?php echo htmlspecialchars($produto['nome']); ?> - <?php echo htmlspecialchars($produto['descricao']); ?>" />

              <?php if (!empty($produto['badge'])): ?>
                <div class="product-badge <?php echo strtolower(htmlspecialchars($produto['badge'])); ?>"
                  aria-label="<?php echo htmlspecialchars($produto['badge']); ?>">
                  <?php echo htmlspecialchars($produto['badge']); ?>
                </div>
              <?php endif; ?>

              <div class="product-actions">
                <button class="quick-view"
                  aria-label="Visualização rápida de <?php echo htmlspecialchars($produto['nome']); ?>">
                  <i class="fas fa-eye" aria-hidden="true"></i>
                </button>

                <button class="add-to-cart"
                  aria-label="Adicionar <?php echo htmlspecialchars($produto['nome']); ?> ao carrinho" onclick="adicionarItem(
                  '<?php echo $produto['id']; ?>',
                  '<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES); ?>',
                  <?php echo $produto['preco']; ?>,
                  '<?php echo htmlspecialchars($produto['imagem'], ENT_QUOTES); ?>',
                  '<?php echo htmlspecialchars($produto['categoria'], ENT_QUOTES); ?>'
                )">
                  <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name"><?php echo htmlspecialchars($produto['nome']); ?></h3>
              <p class="product-description"><?php echo htmlspecialchars($produto['descricao']); ?></p>
              <div class="product-rating" role="img" aria-label="Avaliação: 4.9 de 5 estrelas">
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <span>(4.9)</span>
              </div>
              <div class="product-price"
                aria-label="Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>">
                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
              </div>
            </div>
          </article>

        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="sobre-section" aria-labelledby="sobre-title">
    <div class="container">
      <div class="sobre-content">
        <div class="sobre-text">
          <h2 id="sobre-title">Sobre nós</h2>
          <p>Bem-vindo(a) à Doce Encanto, onde cada criação é feita com paixão para adoçar a sua vida! Desde a nossa
            fundação em 2020, temos o prazer de levar sabor e alegria para as mesas e corações dos nossos clientes.
            Aqui na Doce Encanto, somos apaixonados por bolos e doces. Acreditamos que momentos especiais merecem ser
            celebrados com algo realmente delicioso, e é por isso que cada receita é preparada com ingredientes de alta
            qualidade e um toque artesanal que você sente em cada mordida.
            Seja para um aniversário inesquecível, um evento especial ou simplesmente para tornar o seu dia um pouco
            mais
            doce, a Doce Encanto está aqui para te encantar. Venha descobrir a magia dos nossos sabores!</p>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
include 'footer.php';
?>