document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("login-form");
    const emailInput = document.getElementById("email");
    const senhaInput = document.getElementById("senha");

    // Elemento Toast (usado para mensagens)
    const toast = document.createElement("div");
    toast.className = "toast";
    document.body.appendChild(toast);

    // =======================
    // Função toast
    // =======================
    function showToast(message, type, callback = null) {
        toast.textContent = message;
        toast.classList.add("show", type);

        setTimeout(() => {
            toast.classList.remove("show", type);
            // Chama o callback (se houver) após a remoção da classe 'show'
            setTimeout(() => {
                if (callback) callback();
            }, 500); // Pequeno atraso para permitir a animação do toast
        }, 2000); // Tempo que o toast fica visível
    }


    // =======================
    // Evento do formulário de LOGIN
    // =======================
    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const email = emailInput.value.trim();
        const senha = senhaInput.value.trim();

        if (!email || !senha) {
            showToast("Preencha todos os campos!", "error");
            return;
        }

        // Puxar a lista completa de usuários (Chave usada no Cadastro: doceEncanto_users)
        const usuarios = JSON.parse(localStorage.getItem("doceEncanto_users")) || [];

        // Procurar o usuário na lista pelo email E senha
        const usuarioEncontrado = usuarios.find(user => 
            user.email === email && user.senha === senha
        );

        if (usuarioEncontrado) {
            // Login bem-sucedido
            showToast("Login realizado com sucesso! Redirecionando...", "success", () => {
                // ❗ ESSENCIAL: Salva o usuário logado nesta chave
                localStorage.setItem("doceEncanto_currentUser", JSON.stringify(usuarioEncontrado));
                
                // Redireciona para a página de usuário
                window.location.href = '../paginas/usuario.html'; 
            });
        } else {
            // Se o usuário não foi encontrado
            
            // Verifica se o email existe (para mensagem mais específica)
            const emailExists = usuarios.some(user => user.email === email);

            if (emailExists) {
                showToast("Senha incorreta!", "error");
            } else {
                showToast("Email não cadastrado!", "error");
            }
        }
    });

    // =======================
    // Toggle de senha (mantido)
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