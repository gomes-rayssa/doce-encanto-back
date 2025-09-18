document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("login-form");
  const emailInput = document.getElementById("email");
  const senhaInput = document.getElementById("password");

  // Área para outras notificações, se quiser usar além do toast
  const notificationArea = document.createElement("div");
  notificationArea.id = "notification-area";
  document.body.appendChild(notificationArea);

  // Alternar visibilidade da senha
  window.togglePassword = function () {
    const pwd = document.getElementById("password");
    pwd.type = pwd.type === "password" ? "text" : "password";
  };

  // Exibir toast após submit
  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // aqui você pode validar o e-mail e a senha antes de mostrar o toast
    // exemplo simples de verificação vazia:
    if (!emailInput.value.trim() || !senhaInput.value.trim()) {
      showNotification("Preencha todos os campos!");
      return;
    }

    const toast = document.getElementById("toast");
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 3000);
  });

  // Função extra para mensagens personalizadas na div notification-area
  function showNotification(message) {
    const note = document.createElement("div");
    note.className = "toast show"; // reutiliza estilo do toast
    note.textContent = message;
    notificationArea.appendChild(note);

    setTimeout(() => {
      note.classList.remove("show");
      setTimeout(() => note.remove(), 400);
    }, 3000);
  }
});
