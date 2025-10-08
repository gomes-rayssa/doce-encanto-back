document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("login-form");
  const emailInput = document.getElementById("email");
  const senhaInput = document.getElementById("senha");

  // Área para outras notificações, se quiser usar além do toast
    const toast = document.createElement("div");
  toast.className = "toast";
  document.body.appendChild(toast);

  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Validação básica
    if (!emailInput.value.trim() || !senhaInput.value.trim()) {
      showToast("Preencha todos os campos!", "error");
      return;
    }

    // ✅ Simula um login bem-sucedido
    // Em uma aplicação real, aqui você faria uma chamada para uma API
    showToast("Login realizado com sucesso!", "success", () => {
      // Salva o estado de login no localStorage
      localStorage.setItem('isLoggedIn', 'true');
      // Redireciona para a página inicial
      window.location.href = "../index.html";
    });
  });

  // Função para exibir o toast com cores diferentes
  function showToast(message, type, callback = null) {
    toast.textContent = message;
    toast.classList.add("show", type);

    setTimeout(() => {
      toast.classList.remove("show", type);
      if (callback) {
        callback();
      }
    }, 2500);
  }
});

// Funcionalidade de toggle de senha
  const togglePasswordButtons = document.querySelectorAll('.toggle-password');

  togglePasswordButtons.forEach(button => {
    button.addEventListener('click', function () {
      const targetId = this.getAttribute('data-target');
      const targetInput = document.getElementById(targetId);

      if (targetInput.type === 'password') {
        targetInput.type = 'text';
        this.src = '../assets/logos/olho-x.png'; // Ícone para ocultar
        this.alt = 'Ocultar Senha';
      } else {
        targetInput.type = 'password';
        this.src = '../assets/logos/olho.png'; // Ícone para mostrar
        this.alt = 'Mostrar Senha';
      }
    });
  });