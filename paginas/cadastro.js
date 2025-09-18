// validações
document.addEventListener("DOMContentLoaded", function () {
  const senha = document.getElementById("senha");
  const erroForca = document.getElementById("erroForca");
  const form = document.querySelector("form");
  const confirmarSenha = document.getElementById("confirmarSenha");
  const mensagemErro = document.getElementById("mensagemErro");
  const email = document.getElementById("email");
  const erroEmail = document.getElementById("erroEmail");

  const notificationArea = document.createElement('div');
  notificationArea.id = 'notification-area'; 
  document.body.appendChild(notificationArea);

  
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

  const regexSenhaForte =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.,;:_])[A-Za-z\d@$!%*?&#.,;:_]{8,}$/;

  function validarForcaSenha() {
    if (!regexSenhaForte.test(senha.value)) {
      erroForca.textContent =
        "Senha fraca: use no mínimo 8 caracteres, com maiúsculas, minúsculas, número e símbolo.";
      erroForca.style.display = "block";
      return false;
    } else {
      erroForca.textContent = "";
      erroForca.style.display = "none";
      return true;
    }
  }

  function validarSenhasIguais() {
    if (senha.value !== confirmarSenha.value) {
      mensagemErro.textContent = "As senhas não coincidem.";
      mensagemErro.style.display = "block";
      return false;
    } else {
      mensagemErro.textContent = "";
      mensagemErro.style.display = "none";
      return true;
    }
  }

  function validarEmail() {
    if (!email.value.includes("@")) {
      erroEmail.textContent = "O e-mail precisa conter '@'.";
      erroEmail.style.display = "block";
      return false;
    } else {
      erroEmail.textContent = "";
      erroEmail.style.display = "none";
      return true;
    }
  }

  // validações em tempo real
  senha.addEventListener("input", validarForcaSenha);
  confirmarSenha.addEventListener("input", validarSenhasIguais);
  email.addEventListener("input", validarEmail);

  
  function loadFormData() {
    const storedData = localStorage.getItem("cadastroFormData");
    if (storedData) {
      const formData = JSON.parse(storedData);
      document.getElementById("nome").value = formData.nome || "";
      document.getElementById("email").value = formData.email || "";
      document.getElementById("dob").value = formData.dob || "";
      document.getElementById("cep").value = formData.cep || "";
      document.getElementById("rua").value = formData.rua || "";
      document.getElementById("bairro").value = formData.bairro || "";
      document.getElementById("cidade").value = formData.cidade || "";
      document.getElementById("uf").value = formData.uf || "";
    }
  }

  
  loadFormData();

  // submit form
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const senhaValida = validarForcaSenha();
    const senhasIguais = validarSenhasIguais();
    const emailValido = validarEmail();

    if (!senhaValida || !senhasIguais || !emailValido) {
      showNotification("Por favor, corrija os erros no formulário.", "error");
      return;
    }

    try {
      const userData = {
        nome: document.getElementById("nome").value,
        email: email.value,
        dob: document.getElementById("dob").value,
        cep: document.getElementById("cep").value,
        rua: document.getElementById("rua").value,
        bairro: document.getElementById("bairro").value,
        cidade: document.getElementById("cidade").value,
        uf: document.getElementById("uf").value,
        senha: senha.value,
      };

      // localStorage
      localStorage.setItem("cadastroFormData", JSON.stringify(userData));

      console.log("Dados do usuário para registro:", userData);

      // not
      showNotification("Cadastro realizado com sucesso! Redirecionando para o login...", "success");
      setTimeout(() => {
        window.location.href = "login.html"; 
      }, 1500); 

    } catch (error) {
      showNotification("Erro no cadastro: " + error.message, "error");
    }
  });
});

// data
function formatDate(event) {
  let input = event.target;
  let value = input.value.replace(/\D/g, "");
  if (value.length <= 2) {
    input.value = value;
  } else if (value.length <= 4) {
    input.value = value.substring(0, 2) + "/" + value.substring(2);
  } else {
    input.value =
      value.substring(0, 2) +
      "/" +
      value.substring(2, 4) +
      "/" +
      value.substring(4, 8);
  }
}

// viacep
function limpa_formulário_cep() {
  document.getElementById("rua").value = "";
  document.getElementById("bairro").value = "";
  document.getElementById("cidade").value = "";
  document.getElementById("uf").value = "";
}

function meu_callback(conteudo) {
  if (!("erro" in conteudo)) {
    document.getElementById("rua").value = conteudo.logradouro;
    document.getElementById("bairro").value = conteudo.bairro;
    document.getElementById("cidade").value = conteudo.localidade;
    document.getElementById("uf").value = conteudo.uf;
  } else {
    limpa_formulário_cep();
    showNotification("CEP não encontrado.", "error");
  }
}

function pesquisacep(valor) {
  var cep = valor.replace(/\D/g, "");

  if (cep != "") {
    var validacep = /^[0-9]{8}$/;

    if (validacep.test(cep)) {
      document.getElementById("rua").value = "...";
      document.getElementById("bairro").value = "...";
      document.getElementById("cidade").value = "...";
      document.getElementById("uf").value = "...";

      var script = document.createElement("script");

      script.src =
        "https://viacep.com.br/ws/" + cep + "/json/?callback=meu_callback";

      document.body.appendChild(script);
    } else {
      limpa_formulário_cep();
      showNotification("Formato de CEP inválido.", "error");
    }
  } else {
    limpa_formulário_cep();
  }
}