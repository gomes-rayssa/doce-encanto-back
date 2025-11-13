<?php
include 'header.php';
include 'db_config.php';
?>

<link rel="stylesheet" href="style.css" />
<link rel="stylesheet" href="loja.css" />

<main class="shop-page">
  <div class="container">
    <div class="page-header">
      <div class="breadcrumb">
        <a href="../index.html">Início</a>
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
                <span class="count">18</span>
              </a>
            </li>
            <li class="category-item">
              <a href="#tradicionais" class="category-link" data-category="tradicionais">
                <i class="fas fa-candy-cane"></i>
                <span>Doces Tradicionais</span>
                <span class="count">6</span>
              </a>
            </li>
            <li class="category-item">
              <a href="#trufas" class="category-link" data-category="trufas">
                <i class="fas fa-gem"></i>
                <span>Trufas Premium</span>
                <span class="count">6</span>
              </a>
            </li>
            <li class="category-item">
              <a href="#gourmet" class="category-link" data-category="gourmet">
                <i class="fas fa-star"></i>
                <span>Doces Gourmet</span>
                <span class="count">6</span>
              </a>
            </li>
          </ul>
        </nav>

        <div class="sidebar-section">
          <h4>Preço</h4>
          <div class="price-filter">
            <div class="price-range">
              <input type="range" id="priceRange" min="5" max="50" value="50" class="range-slider" />
              <div class="price-display">
                <span>R$ 5</span>
                <span id="maxPrice">R$ 50</span>
              </div>
            </div>
          </div>
        </div>

        <div class="sidebar-section">
          <h4>Ordenar por</h4>
          <select class="sort-select" id="sortSelect">
            <option value="name">Nome A-Z</option>
            <option value="price-low">Menor Preço</option>
            <option value="price-high">Maior Preço</option>
            <option value="popular">Mais Popular</option>
          </select>
        </div>
      </aside>

      <section class="products-area">
        <div class="products-header">
          <div class="results-info">
            <span id="resultsCount">Mostrando 18 produtos</span>
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
          <div class="product-card" data-category="tradicionais" data-price="8" data-name="Brigadeiro">
            <div class="product-image">
              <img src="../assets/doces/brigadeiro.png" alt="Brigadeiro" />
              <div class="product-badge bestseller">Bestseller</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Brigadeiro', 8, '../assets/doces/brigadeiro.png', 'doce tradicional')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Brigadeiro</h3>
              <p class="product-description">
                Clássico brasileiro com chocolate nobre e granulado especial
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.9)</span>
              </div>
              <div class="product-price">R$ 8,00</div>
            </div>
          </div>

          <div class="product-card" data-category="tradicionais" data-price="10" data-name="Cajuzinho">
            <div class="product-image">
              <img src="../assets/doces/cajuzinho.png" alt="Cajuzinho" />
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Cajuzinho', 10, '../assets/doces/cajuzinho.png', 'doce tradicional')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Cajuzinho</h3>
              <p class="product-description">
                Doce de amendoim com formato de caju e sabor irresistível
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.7)</span>
              </div>
              <div class="product-price">R$ 10,00</div>
            </div>
          </div>

          <div class="product-card" data-category="tradicionais" data-price="12" data-name="Casadinho">
            <div class="product-image">
              <img src="../assets/doces/casadinho.png" alt="Casadinho" />
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Casadinho', 12, '../assets/doces/casadinho.png', 'doce tradicional')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Casadinho</h3>
              <p class="product-description">
                Perfeita combinação de chocolate e coco em harmonia
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.8)</span>
              </div>
              <div class="product-price">R$ 12,00</div>
            </div>
          </div>

          <div class="product-card" data-category="tradicionais" data-price="9" data-name="Beijinho">
            <div class="product-image">
              <img src="../assets/doces/beijnho.png" alt="Beijinho" />
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Beijinho', 9, '../assets/doces/beijnho.png', 'doce tradicional')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Beijinho</h3>
              <p class="product-description">
                Doce de coco cremoso com cravo especial no centro
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.6)</span>
              </div>
              <div class="product-price">R$ 9,00</div>
            </div>
          </div>

          <div class="product-card" data-category="tradicionais" data-price="11" data-name="Bicho de Pé">
            <div class="product-image">
              <img src="../assets/doces-finos/camafeu nozes.png" alt="Bicho de Pé" />
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Bicho de Pé', 11, '../assets/doces-finos/camafeu nozes.png', 'doce tradicional')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Bicho de Pé</h3>
              <p class="product-description">
                Doce de amendoim com leite condensado e coco ralado
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.5)</span>
              </div>
              <div class="product-price">R$ 11,00</div>
            </div>
          </div>

          <div class="product-card" data-category="tradicionais" data-price="13" data-name="Morango com Chocolate">
            <div class="product-image">
              <img src="../assets/doces/morango.png" alt="Morango com Chocolate" />
              <div class="product-badge new">Novo</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Morango com Chocolate', 13, '../assets/doces/morango.png', 'doce tradicional')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Morango com Chocolate</h3>
              <p class="product-description">
                Morango fresco coberto com chocolate belga derretido
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.9)</span>
              </div>
              <div class="product-price">R$ 13,00</div>
            </div>
          </div>

          <!-- Trufas Premium -->
          <div class="product-card" data-category="trufas" data-price="15" data-name="Trufa de Brigadeiro">
            <div class="product-image">
              <img src="../assets/doces-trufas/brigadeiro.png" alt="Trufa de Brigadeiro" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Trufa de Brigadeiro', 15, '../assets/doces-trufas/brigadeiro.png', 'trufa')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Trufa de Brigadeiro</h3>
              <p class="product-description">
                Trufa artesanal com recheio cremoso de brigadeiro
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(5.0)</span>
              </div>
              <div class="product-price">R$ 15,00</div>
            </div>
          </div>

          <div class="product-card" data-category="trufas" data-price="18" data-name="Trufa de Café">
            <div class="product-image">
              <img src="../assets/doces-trufas/cafe.png" alt="Trufa de Café" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Trufa de Café', 18, '../assets/doces-trufas/cafe.png', 'trufa')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Trufa de Café</h3>
              <p class="product-description">
                Sofisticada trufa com café expresso e chocolate 70%
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.9)</span>
              </div>
              <div class="product-price">R$ 18,00</div>
            </div>
          </div>

          <div class="product-card" data-category="trufas" data-price="20" data-name="Trufa de Cereja">
            <div class="product-image">
              <img src="../assets/doces-trufas/cereja.png" alt="Trufa de Cereja" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Trufa de Cereja', 20, '../assets/doces-trufas/cereja.png', 'trufa')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Trufa de Cereja</h3>
              <p class="product-description">
                Trufa gourmet com cereja importada e licor especial
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.8)</span>
              </div>
              <div class="product-price">R$ 20,00</div>
            </div>
          </div>

          <div class="product-card" data-category="trufas" data-price="16" data-name="Trufa de Coco">
            <div class="product-image">
              <img src="../assets/doces-trufas/coco.png" alt="Trufa de Coco" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Trufa de Coco', 16, '../assets/doces-trufas/coco.png', 'trufa')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Trufa de Coco</h3>
              <p class="product-description">
                Trufa tropical com coco fresco e chocolate branco
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.7)</span>
              </div>
              <div class="product-price">R$ 16,00</div>
            </div>
          </div>

          <div class="product-card" data-category="trufas" data-price="22" data-name="Trufa de Maracujá">
            <div class="product-image">
              <img src="../assets/doces-trufas/maracuja.png" alt="Trufa de Maracujá" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Trufa de Maracujá', 22, '../assets/doces-trufas/maracuja.png', 'trufa')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Trufa de Maracujá</h3>
              <p class="product-description">
                Trufa cítrica com polpa de maracujá e chocolate branco
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.9)</span>
              </div>
              <div class="product-price">R$ 22,00</div>
            </div>
          </div>

          <div class="product-card" data-category="trufas" data-price="25" data-name="Trufa Mix Premium">
            <div class="product-image">
              <img src="../assets/doces-trufas/mix.png" alt="Trufa Mix Premium" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Trufa Mix Premium', 25, '../assets/doces-trufas/mix.png', 'trufa')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Trufa Mix Premium</h3>
              <p class="product-description">
                Caixa com 6 trufas variadas dos sabores mais especiais
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(5.0)</span>
              </div>
              <div class="product-price">R$ 25,00</div>
            </div>
          </div>

          <!-- Doces Gourmet -->
          <div class="product-card" data-category="gourmet" data-price="35" data-name="Doce Ao Leite Premium">
            <div class="product-image">
              <img src="../assets/doces-gourmet/ao leite.png" alt="Doce Ao Leite Premium" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Doce Ao Leite Premium', 35, '../assets/doces-gourmet/ao leite.png', 'doce gourmet')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Doce Ao Leite Premium</h3>
              <p class="product-description">
                Chocolate ao leite belga com recheio cremoso especial
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.8)</span>
              </div>
              <div class="product-price">R$ 35,00</div>
            </div>
          </div>

          <div class="product-card" data-category="gourmet" data-price="40" data-name="Doce de Café Gourmet">
            <div class="product-image">
              <img src="../assets/doces-gourmet/cafe.png" alt="Doce de Café Gourmet" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Doce de Café Gourmet', 40, '../assets/doces-gourmet/cafe.png', 'doce gourmet')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Doce de Café Gourmet</h3>
              <p class="product-description">
                Café especial com chocolate 70% cacau e notas amadeiradas
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.9)</span>
              </div>
              <div class="product-price">R$ 40,00</div>
            </div>
          </div>

          <div class="product-card" data-category="gourmet" data-price="45" data-name="Doce de Pistache">
            <div class="product-image">
              <img src="../assets/doces-gourmet/pistache.png" alt="Doce de Pistache" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Doce de Pistache', 45, '../assets/doces-gourmet/pistache.png', 'doce gourmet')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Doce de Pistache</h3>
              <p class="product-description">
                Pistaches sicilianos com chocolate branco e toque de sal
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(5.0)</span>
              </div>
              <div class="product-price">R$ 45,00</div>
            </div>
          </div>

          <div class="product-card" data-category="gourmet" data-price="38" data-name="Doce Frutas Vermelhas">
            <div class="product-image">
              <img src="../assets/doces-gourmet/frutas vermelhas.png" alt="Doce Frutas Vermelhas" />
              <div class="product-badge new">Novo</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Doce Frutas Vermelhas', 38, '../assets/doces-gourmet/frutas vermelhas.png', 'doce gourmet')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Doce Frutas Vermelhas</h3>
              <p class="product-description">
                Mix de frutas vermelhas com chocolate ruby especial
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.7)</span>
              </div>
              <div class="product-price">R$ 38,00</div>
            </div>
          </div>

          <div class="product-card" data-category="gourmet" data-price="42" data-name="Doce Ninho com Nutella">
            <div class="product-image">
              <img src="../assets/doces-gourmet/ninho nutella.png" alt="Doce Ninho com Nutella" />
              <div class="product-badge bestseller">Bestseller</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Doce Ninho com Nutella', 42, '../assets/doces-gourmet/ninho nutella.png', 'doce gourmet')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Doce Ninho com Nutella</h3>
              <p class="product-description">
                Combinação irresistível de leite ninho e nutella original
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.9)</span>
              </div>
              <div class="product-price">R$ 42,00</div>
            </div>
          </div>

          <div class="product-card" data-category="gourmet" data-price="50" data-name="Doce Churros Gourmet">
            <div class="product-image">
              <img src="../assets/doces-gourmet/churros.png" alt="Doce Churros Gourmet" />
              <div class="product-badge premium">Premium</div>
              <div class="product-actions">
                <button class="quick-view" title="Visualização Rápida">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="add-to-cart"
                  onclick="adicionarAoCarrinho('Doce Churros Gourmet', 50, '../assets/doces-gourmet/churros.png', 'doce gourmet')"
                  title="Adicionar ao Carrinho">
                  <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="add-to-wishlist" title="Adicionar aos Favoritos">
                  <i class="fas fa-heart"></i>
                </button>
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Doce Churros Gourmet</h3>
              <p class="product-description">
                Churros artesanal com doce de leite argentino e canela
              </p>
              <div class="product-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span>(4.8)</span>
              </div>
              <div class="product-price">R$ 50,00</div>
            </div>
          </div>
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
</body>

</html>