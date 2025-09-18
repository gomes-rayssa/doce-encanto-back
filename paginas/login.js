document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");
  const emailInput = document.getElementById("email");
  const senhaInput = document.getElementById("senha");

  
  const notificationArea = document.createElement('div');
  notificationArea.id = 'notification-area';
  document.body.appendChild(notificationArea);

  // exibir a notificação
  function showNotification(message, type = 'info') {
    notificationArea.textContent = message;
    notificationArea.className = 'notification ' + type; 
    notificationArea.style.display = 'block'; 

   
    setTimeout(() => {
      notificationArea.style.display = 'none';
      notificationArea.textContent = '';
      notificationArea.className = ''; 
    }, 3000); 
  }

  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      event.preventDefault(); 

      const emailDigitado = emailInput.value;
      const senhaDigitada = senhaInput.value;

      const storedUserData = localStorage.getItem("cadastroFormData");

      if (storedUserData) {
        const userData = JSON.parse(storedUserData);

        if (emailDigitado === userData.email && senhaDigitada === userData.senha) {
          showNotification("Login bem-sucedido! Redirecionando...", "success");
          setTimeout(() => {
            window.location.href = "usuario.html"; 
          }, 1500); 
        } else {
          showNotification("Email ou senha incorretos. Verifique suas credenciais.", "error");
        }
      } else {
        showNotification("Nenhum usuário cadastrado. Por favor, cadastre-se primeiro.", "info");
      }
    });
  }
});