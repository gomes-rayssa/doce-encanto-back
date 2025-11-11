document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("login-form");
  const emailInput = document.getElementById("email");
  const senhaInput = document.getElementById("senha");
  const loginButton = document.getElementById("login-button");

  const toast = document.createElement("div");
  toast.className = "toast";
  document.body.appendChild(toast);

  function showToast(message, type, callback = null) {
    toast.textContent = message;
    toast.style.backgroundColor = type === "error" ? "#dc3545" : "#28a745";
    toast.classList.add("show");

    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => {
        if (callback) callback();
      }, 500);
    }, 2000);
  }

  loginForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const email = emailInput.value.trim();
    const senha = senhaInput.value.trim();

    if (!email || !senha) {
      showToast("Preencha todos os campos!", "error");
      return;
    }

    loginButton.disabled = true;
    loginButton.textContent = "Entrando...";

    try {
      const response = await fetch("processa_login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email: email, senha: senha }),
      });

      const data = await response.json();

      if (data.success) {
        showToast(data.message, "success", () => {
          window.location.href = data.redirect || "usuario.php";
        });
      } else {
        showToast(data.message || "Erro desconhecido", "error");
        loginButton.disabled = false;
        loginButton.textContent = "Entrar";
      }
    } catch (error) {
      console.error("Erro no fetch:", error);
      showToast("Erro de conexão. Tente novamente.", "error");
      loginButton.disabled = false;
      loginButton.textContent = "Entrar";
    }
  });

  const togglePasswordButtons = document.querySelectorAll(".toggle-password");
  togglePasswordButtons.forEach((button) => {
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
