document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("login-form");
  const emailInput = document.getElementById("email");
  const senhaInput = document.getElementById("senha");

  const toast = document.createElement("div");
  toast.className = "toast";
  document.body.appendChild(toast);

  // =======================
  // Redirecionar se já estiver logado
  // =======================
  if (localStorage.getItem("isLoggedIn") === "true") {
    window.location.href = "../paginas/usuario.html";
    return;
  }

  // =======================
  // Evento do formulário
  // =======================
  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    if (!emailInput.value.trim() || !senhaInput.value.trim()) {
      showToast("Preencha todos os campos!", "error");
      return;
    }

    // Puxar dados do usuário do localStorage
    const storedUser = JSON.parse(localStorage.getItem("userData"));

    if (storedUser && storedUser.email === emailInput.value.trim()) {
      // Simula login bem-sucedido
      showToast("Login realizado com sucesso!", "success", () => {
        localStorage.setItem("isLoggedIn", "true");
        window.location.href = "../paginas/usuario.html";
      });
    } else {
      showToast("Email não cadastrado!", "error");
    }
  });

  // =======================
  // Função toast
  // =======================
  function showToast(message, type, callback = null) {
    toast.textContent = message;
    toast.classList.add("show", type);

    setTimeout(() => {
      toast.classList.remove("show", type);
      if (callback) callback();
    }, 2500);
  }

  // =======================
  // Toggle de senha
  // =======================
  const togglePasswordButtons = document.querySelectorAll(".toggle-password");
  togglePasswordButtons.forEach(button => {
    button.addEventListener("click", function () {
      const targetId = this.getAttribute("data-target");
      const targetInput = document.getElementById(targetId);

      if (targetInput.type === "password") {
        targetInput.type = "text";
        this.src = "../assets/logos/olho-x.png";
        this.alt = "Ocultar Senha";
      } else {
        targetInput.type = "password";
        this.src = "../assets/logos/olho.png";
        this.alt = "Mostrar Senha";
      }
    });
  });
});
