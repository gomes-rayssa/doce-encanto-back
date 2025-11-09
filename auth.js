// Dropdown usuário
document.addEventListener("DOMContentLoaded", function () {
  // Seleciona os itens do dropdown
  const loginItem = document.querySelector('.dropdown-menu a[href*="login.html"]');
  const cadastroItem = document.querySelector('.dropdown-menu a[href*="cadastro.html"]');
  const minhaContaItem = document.querySelector('.dropdown-menu a[href*="usuario.html"]');

  // Verifica se há um usuário logado
  const currentUser = JSON.parse(localStorage.getItem("doceEncanto_currentUser"));

  if (currentUser) {
    // Usuário logado → mostra apenas "Minha Conta"
    if (loginItem) loginItem.style.display = "none";
    if (cadastroItem) cadastroItem.style.display = "none";
    if (minhaContaItem) minhaContaItem.style.display = "block";
  } else {
    // Usuário não logado → mostra apenas Login e Cadastro
    if (loginItem) loginItem.style.display = "block";
    if (cadastroItem) cadastroItem.style.display = "block";
    if (minhaContaItem) minhaContaItem.style.display = "none";
  }
});




// ACESSIBILIDADE
document.addEventListener("DOMContentLoaded", function () {
  const menuBtn = document.getElementById("accessibility-btn");
  const menu = document.getElementById("accessibility-menu");
  const contrastBtn = document.getElementById("contrast-toggle");
  const increaseBtn = document.getElementById("increase-font");
  const decreaseBtn = document.getElementById("decrease-font");

  let fontSize = 100;

  // Alternar menu
  menuBtn.addEventListener("click", () => {
    const isOpen = menu.style.display === "block";
    menu.style.display = isOpen ? "none" : "block";
    menu.setAttribute("aria-hidden", isOpen);
  });

  // Clique fora fecha menu
  document.addEventListener("click", (e) => {
    if (!menu.contains(e.target) && e.target !== menuBtn) {
      menu.style.display = "none";
    }
  });

  // Alternar contraste
  contrastBtn.addEventListener("click", () => {
    document.body.classList.toggle("high-contrast");
  });

  // Aumentar fonte
  increaseBtn.addEventListener("click", () => {
    fontSize += 10;
    document.body.style.fontSize = fontSize + "%";
  });

  // Diminuir fonte
  decreaseBtn.addEventListener("click", () => {
    if (fontSize > 70) {
      fontSize -= 10;
      document.body.style.fontSize = fontSize + "%";
    }
  });

  // ====== Navegação por teclado ======
  const focusableElements = document.querySelectorAll("a, button, input, select, textarea");
  let currentIndex = 0;

  document.addEventListener("keydown", (e) => {
    // Alt + → vai para o próximo item
    if (e.altKey && e.key === "ArrowRight") {
      e.preventDefault();
      currentIndex = (currentIndex + 1) % focusableElements.length;
      focusableElements[currentIndex].focus();
    }

    // Alt + ← volta para o item anterior
    if (e.altKey && e.key === "ArrowLeft") {
      e.preventDefault();
      currentIndex = (currentIndex - 1 + focusableElements.length) % focusableElements.length;
      focusableElements[currentIndex].focus();
    }
  });
});