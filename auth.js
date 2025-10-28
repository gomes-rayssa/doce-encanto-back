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
