const menuToggle = document.querySelector('.menu-toggle');
const menu = document.querySelector('.menu');

// Adiciona funcionalidade de menu mobile se existir
if (menuToggle && menu) {
  menuToggle.addEventListener('click', () => {
    menu.classList.toggle('show');
  });
}

// Função para formatar preços
function formatPrice(price) {
  return `R$ ${parseFloat(price).toFixed(2).replace('.', ',')}`;
}

// Função para validar email
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Função para mostrar loading
function showLoading(element) {
  if (element) {
    element.disabled = true;
    element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
  }
}

// Função para esconder loading
function hideLoading(element, originalText) {
  if (element) {
    element.disabled = false;
    element.innerHTML = originalText;
  }
}

// Função para scroll suave
function smoothScrollTo(element) {
  if (element) {
    element.scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    });
  }
}

// Adiciona animações aos cards quando entram na viewport
function addScrollAnimations() {
  const cards = document.querySelectorAll('.card');
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, {
    threshold: 0.1
  });

  cards.forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
  });
}

// Inicializa animações quando o DOM carrega
document.addEventListener('DOMContentLoaded', () => {
  addScrollAnimations();
});
