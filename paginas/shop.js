document.addEventListener("DOMContentLoaded", function () {
  const categoryLinks = document.querySelectorAll(".category-link");
  const productCards = document.querySelectorAll(".product-card");
  const viewButtons = document.querySelectorAll(".view-btn");
  const productsGrid = document.getElementById("productsGrid");
  const resultsCount = document.getElementById("resultsCount");
  const priceRange = document.getElementById("priceRange");
  const maxPriceDisplay = document.getElementById("maxPrice");
  const sortSelect = document.getElementById("sortSelect");

  let currentFilters = {
    category: "todos",
    maxPrice: 200,
    sortBy: "name",
    view: "grid",
  };

  init();

  function init() {
    setupCategoryFilter();
    setupPriceFilter();
    setupSortFilter();
    setupViewToggle();
    updateCartCount();
    filterProducts();
  }

  function setupCategoryFilter() {
    categoryLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();

        categoryLinks.forEach((l) => l.classList.remove("active"));

        this.classList.add("active");

        currentFilters.category = this.dataset.category;

        filterProducts();

        animateFilterChange();
      });
    });
  }

  function setupPriceFilter() {
    if (priceRange) {
      priceRange.addEventListener("input", function () {
        currentFilters.maxPrice = parseInt(this.value);
        maxPriceDisplay.textContent = `R$ ${this.value}`;

        clearTimeout(priceRange.timeout);
        priceRange.timeout = setTimeout(() => {
          filterProducts();
        }, 300);
      });
    }
  }

  function setupSortFilter() {
    if (sortSelect) {
      sortSelect.addEventListener("change", function () {
        currentFilters.sortBy = this.value;
        filterProducts();
      });
    }
  }

  function setupViewToggle() {
    viewButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        viewButtons.forEach((b) => b.classList.remove("active"));

        this.classList.add("active");

        const view = this.dataset.view;
        currentFilters.view = view;

        if (view === "list") {
          productsGrid.classList.add("list-view");
        } else {
          productsGrid.classList.remove("list-view");
        }
      });
    });
  }

  function filterProducts() {
    let visibleProducts = [];

    productCards.forEach((card) => {
      const category = card.dataset.category;
      const price = parseInt(card.dataset.price);

      const passesCategory =
        currentFilters.category === "todos" ||
        category === currentFilters.category;
      const passesPrice = price <= currentFilters.maxPrice;

      if (passesCategory && passesPrice) {
        card.style.display = "block";
        visibleProducts.push(card);
      } else {
        card.style.display = "none";
      }
    });

    sortProducts(visibleProducts);

    updateResultsCount(visibleProducts.length);

    updateCategoryCounts();
  }

  function sortProducts(products) {
    const sortedProducts = [...products].sort((a, b) => {
      const aName = a.dataset.name;
      const bName = b.dataset.name;
      const aPrice = parseInt(a.dataset.price);
      const bPrice = parseInt(b.dataset.price);

      switch (currentFilters.sortBy) {
        case "name":
          return aName.localeCompare(bName);
        case "price-low":
          return aPrice - bPrice;
        case "price-high":
          return bPrice - aPrice;
        case "popular":
          const aRating = parseFloat(
            a
              .querySelector(".product-rating span")
              .textContent.replace("(", "")
              .replace(")", "")
          );
          const bRating = parseFloat(
            b
              .querySelector(".product-rating span")
              .textContent.replace("(", "")
              .replace(")", "")
          );
          return bRating - aRating;
        default:
          return 0;
      }
    });

    sortedProducts.forEach((product) => {
      productsGrid.appendChild(product);
    });
  }

  function updateResultsCount(count) {
    if (resultsCount) {
      const text = count === 1 ? "produto" : "produtos";
      resultsCount.textContent = `Mostrando ${count} ${text}`;
    }
  }

  function updateCategoryCounts() {
    categoryLinks.forEach((link) => {
      const category = link.dataset.category;
      const countElement = link.querySelector(".count");

      if (countElement && category !== "todos") {
        const count = Array.from(productCards).filter((card) => {
          const cardCategory = card.dataset.category;
          const price = parseInt(card.dataset.price);
          return cardCategory === category && price <= currentFilters.maxPrice;
        }).length;

        countElement.textContent = count;
      }
    });

    const todosLink = document.querySelector('[data-category="todos"] .count');
    if (todosLink) {
      const totalCount = Array.from(productCards).filter((card) => {
        const price = parseInt(card.dataset.price);
        return price <= currentFilters.maxPrice;
      }).length;

      todosLink.textContent = totalCount;
    }
  }

  function animateFilterChange() {
    productsGrid.style.opacity = "0.7";
    productsGrid.style.transform = "translateY(10px)";

    setTimeout(() => {
      productsGrid.style.opacity = "1";
      productsGrid.style.transform = "translateY(0)";
    }, 150);
  }

  function updateCartCount() {
    const cartCount = document.querySelector(".cart-count");
    if (cartCount && typeof getCartItemCount === "function") {
      cartCount.textContent = getCartItemCount();
    }
  }

  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase();

      productCards.forEach((card) => {
        const productName = card.dataset.name.toLowerCase();
        const productDescription = card
          .querySelector(".product-description")
          .textContent.toLowerCase();

        if (
          productName.includes(searchTerm) ||
          productDescription.includes(searchTerm)
        ) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });

      const visibleCount = Array.from(productCards).filter(
        (card) => card.style.display !== "none"
      ).length;
      updateResultsCount(visibleCount);
    });
  }

  const wishlistButtons = document.querySelectorAll(".add-to-wishlist");
  wishlistButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      this.classList.toggle("active");

      if (this.classList.contains("active")) {
        this.style.background = "#e74c3c";
        this.style.color = "white";
        showNotification("Produto adicionado aos favoritos!", "success");
      } else {
        this.style.background = "";
        this.style.color = "";
        showNotification("Produto removido dos favoritos!", "info");
      }
    });
  });

  const quickViewButtons = document.querySelectorAll(".quick-view");
  quickViewButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      const productCard = this.closest(".product-card");
      const productName =
        productCard.querySelector(".product-name").textContent;
      const productImage = productCard.querySelector(".product-image img").src;
      const productPrice =
        productCard.querySelector(".product-price").textContent;
      const productDescription = productCard.querySelector(
        ".product-description"
      ).textContent;

      showQuickView({
        name: productName,
        image: productImage,
        price: productPrice,
        description: productDescription,
      });
    });
  });

  function showQuickView(product) {
    const modal = document.createElement("div");
    modal.className = "quick-view-modal";
    modal.innerHTML = `
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <button class="modal-close">&times;</button>
                <div class="modal-body">
                    <div class="modal-image">
                        <img src="${product.image}" alt="${product.name}">
                    </div>
                    <div class="modal-info">
                        <h3>${product.name}</h3>
                        <p>${product.description}</p>
                        <div class="modal-price">${product.price}</div>
                        <button class="btn btn-primary modal-add-cart">
                            <i class="fas fa-shopping-cart"></i>
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>
            </div>
        `;

    document.body.appendChild(modal);

    const closeBtn = modal.querySelector(".modal-close");
    const overlay = modal.querySelector(".modal-overlay");

    [closeBtn, overlay].forEach((element) => {
      element.addEventListener("click", () => {
        modal.remove();
      });
    });

    setTimeout(() => {
      modal.classList.add("active");
    }, 10);
  }

  function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
            <i class="fas fa-${
              type === "success" ? "check" : "info"
            }-circle"></i>
            <span>${message}</span>
        `;

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.classList.add("fade-out");
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  const images = document.querySelectorAll(".product-image img");
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src || img.src;
        img.classList.add("loaded");
        observer.unobserve(img);
      }
    });
  });

  images.forEach((img) => imageObserver.observe(img));

  const categoryAnchors = document.querySelectorAll('a[href^="#"]');
  categoryAnchors.forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  document.addEventListener("cartUpdated", updateCartCount);
});

const modalStyles = `
<style>
.quick-view-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.quick-view-modal.active {
    opacity: 1;
    visibility: visible;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 16px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    border: none;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 1;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: var(--primary-color);
    color: white;
}

.modal-body {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding: 2rem;
}

.modal-image {
    flex: 1;
}

.modal-image img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 12px;
}

.modal-info {
    flex: 1;
}

.modal-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.modal-info p {
    color: var(--text-light);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.modal-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 2rem;
}

.modal-add-cart {
    width: 100%;
    justify-content: center;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    z-index: 10001;
    transform: translateX(100%);
    animation: slideInRight 0.3s ease forwards;
}

.notification-success {
    border-left: 4px solid #28a745;
    color: #28a745;
}

.notification-info {
    border-left: 4px solid #17a2b8;
    color: #17a2b8;
}

.notification.fade-out {
    animation: slideOutRight 0.3s ease forwards;
}

@keyframes slideInRight {
    to {
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    to {
        transform: translateX(100%);
    }
}

@media (max-width: 768px) {
    .modal-body {
        flex-direction: column;
        padding: 1.5rem;
    }
    
    .modal-image {
        width: 100%;
    }
    
    .notification {
        right: 10px;
        left: 10px;
        transform: translateY(-100%);
        animation: slideInDown 0.3s ease forwards;
    }
    
    .notification.fade-out {
        animation: slideOutUp 0.3s ease forwards;
    }
    
    @keyframes slideInDown {
        to {
            transform: translateY(0);
        }
    }
    
    @keyframes slideOutUp {
        to {
            transform: translateY(-100%);
        }
    }
}
</style>
`;

document.head.insertAdjacentHTML("beforeend", modalStyles);
