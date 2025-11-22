document.addEventListener("DOMContentLoaded", function () {
  console.log("=== CADASTRO.JS CARREGADO ===");
  
  const form = document.getElementById("registration-form");
  const step1 = document.getElementById("step1");
  const step2 = document.getElementById("step2");
  const step1Indicator = document.getElementById("step1-indicator");
  const step2Indicator = document.getElementById("step2-indicator");
  const nextBtn = document.getElementById("next-step1");
  const prevBtn = document.getElementById("prev-step2");
  const submitBtn = document.getElementById("submit-form");

  console.log("Elementos encontrados:", {
    form: !!form,
    step1: !!step1,
    step2: !!step2,
    nextBtn: !!nextBtn,
    prevBtn: !!prevBtn,
    submitBtn: !!submitBtn
  });

  const campos = {
    nome: document.getElementById("nome"),
    email: document.getElementById("email"),
    celular: document.getElementById("celular"),
    dataNascimento: document.getElementById("data-nascimento"),
    senha: document.getElementById("senha"),
    confirmarSenha: document.getElementById("confirmar-senha"),
    cep: document.getElementById("cep"),
    rua: document.getElementById("rua"),
    numero: document.getElementById("numero"),
    bairro: document.getElementById("bairro"),
    cidade: document.getElementById("cidade"),
    estado: document.getElementById("estado"),
  };

  let etapaAtual = 1;

  // Funções de validação
  function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }

  function validarSenha(senha) {
    const temTamanhoMinimo = senha.length >= 8;
    const temMaiuscula = /[A-Z]/.test(senha);
    const temMinuscula = /[a-z]/.test(senha);
    const temSimbolo = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(senha);
    return temTamanhoMinimo && temMaiuscula && temMinuscula && temSimbolo;
  }

  function validarCEP(cep) {
    const regex = /^\d{5}-?\d{3}$/;
    return regex.test(cep);
  }

  function validarIdade(dataNascimento) {
    if (!dataNascimento) return false;
    const hoje = new Date();
    const nascimento = new Date(dataNascimento);
    let idade = hoje.getFullYear() - nascimento.getFullYear();
    const mesAtual = hoje.getMonth();
    const mesNascimento = nascimento.getMonth();
    if (
      mesAtual < mesNascimento ||
      (mesAtual === mesNascimento && hoje.getDate() < nascimento.getDate())
    ) {
      idade--;
    }
    return idade >= 18;
  }

  function validarCelular(valor) {
    const apenasNumeros = valor.replace(/\D/g, "");
    return apenasNumeros.length === 10 || apenasNumeros.length === 11;
  }

  function aplicarMascaraCelular(valor) {
    const digitos = valor.replace(/\D/g, "");
    if (digitos.length <= 10) {
      return digitos.replace(/^(\d{0,2})(\d{0,4})(\d{0,4}).*/, (m, d1, d2, d3) =>
        !d1 ? "" : `(${d1}${d2 ? ") " + d2 : ""}${d3 ? "-" + d3 : ""}`
      );
    } else {
      return digitos.replace(/^(\d{0,2})(\d{0,5})(\d{0,4}).*/, (m, d1, d2, d3) =>
        !d1 ? "" : `(${d1}${d2 ? ") " + d2 : ""}${d3 ? "-" + d3 : ""}`
      );
    }
  }

  // Aplicar máscara de celular
  if (campos.celular) {
    campos.celular.addEventListener("input", (e) => {
      e.target.value = aplicarMascaraCelular(e.target.value);
    });
  }

  function mostrarErro(campo, mensagem) {
    if (!campo) return;
    const formGroup = campo.closest(".form-group");
    if (!formGroup) return;
    const errorElement = formGroup.querySelector(".error-message");
    formGroup.classList.add("error");
    formGroup.classList.remove("success");
    if (errorElement) {
      errorElement.textContent = mensagem;
    }
  }

  function mostrarSucesso(campo) {
    if (!campo) return;
    const formGroup = campo.closest(".form-group");
    if (!formGroup) return;
    const errorElement = formGroup.querySelector(".error-message");
    formGroup.classList.add("success");
    formGroup.classList.remove("error");
    if (errorElement) {
      errorElement.textContent = "";
    }
  }

  function limparValidacao(campo) {
    if (!campo) return;
    const formGroup = campo.closest(".form-group");
    if (!formGroup) return;
    const errorElement = formGroup.querySelector(".error-message");
    formGroup.classList.remove("error", "success");
    if (errorElement) {
      errorElement.textContent = "";
    }
  }

  function validarCampo(nomeCampo, valor) {
    const campo = campos[nomeCampo];
    if (!campo) return true;

    switch (nomeCampo) {
      case "nome":
        if (!valor || !valor.trim()) {
          mostrarErro(campo, "Nome é obrigatório");
          return false;
        }
        if (valor.trim().length < 2) {
          mostrarErro(campo, "Nome deve ter pelo menos 2 caracteres");
          return false;
        }
        mostrarSucesso(campo);
        return true;

      case "email":
        if (!valor || !valor.trim()) {
          mostrarErro(campo, "E-mail é obrigatório");
          return false;
        }
        if (!validarEmail(valor)) {
          mostrarErro(campo, "E-mail inválido");
          return false;
        }
        mostrarSucesso(campo);
        return true;

      case "celular":
        if (!valor || !valor.trim()) {
          mostrarErro(campo, "Celular é obrigatório");
          return false;
        }
        if (!validarCelular(valor)) {
          mostrarErro(campo, "Número de celular inválido");
          return false;
        }
        mostrarSucesso(campo);
        return true;

      case "dataNascimento":
        if (!valor) {
          mostrarErro(campo, "Data de nascimento é obrigatória");
          return false;
        }
        if (!validarIdade(valor)) {
          mostrarErro(campo, "Você deve ter pelo menos 18 anos");
          return false;
        }
        mostrarSucesso(campo);
        return true;

      case "senha":
        if (!valor) {
          mostrarErro(campo, "Senha é obrigatória");
          return false;
        }
        if (!validarSenha(valor)) {
          mostrarErro(
            campo,
            "Senha deve ter pelo menos 8 caracteres, incluindo maiúscula, minúscula e símbolo"
          );
          return false;
        }
        mostrarSucesso(campo);
        return true;

      case "confirmarSenha":
        if (!valor) {
          mostrarErro(campo, "Confirmação de senha é obrigatória");
          return false;
        }
        if (valor !== campos.senha.value) {
          mostrarErro(campo, "Senhas não coincidem");
          return false;
        }
        mostrarSucesso(campo);
        return true;

      case "cep":
        if (!valor || !valor.trim()) {
          mostrarErro(campo, "CEP é obrigatório");
          return false;
        }
        if (!validarCEP(valor)) {
          mostrarErro(campo, "CEP inválido");
          return false;
        }
        mostrarSucesso(campo);
        return true;

      case "numero":
        if (!valor || !valor.trim()) {
          mostrarErro(campo, "Número é obrigatório");
          return false;
        }
        mostrarSucesso(campo);
        return true;

      case "rua":
      case "bairro":
      case "cidade":
        if (!valor || !valor.trim()) {
          const label = nomeCampo.charAt(0).toUpperCase() + nomeCampo.slice(1);
          mostrarErro(campo, `${label} é obrigatório`);
          return false;
        }
        mostrarSucesso(campo);
        return true;

      case "estado":
        if (!valor) {
          mostrarErro(campo, "Estado é obrigatório");
          return false;
        }
        mostrarSucesso(campo);
        return true;

      default:
        return true;
    }
  }

  function validarEtapa(etapa) {
    console.log(`=== Validando Etapa ${etapa} ===`);
    let valido = true;
    
    const camposEtapa = etapa === 1
      ? ["nome", "email", "celular", "dataNascimento", "senha", "confirmarSenha"]
      : ["cep", "rua", "numero", "bairro", "cidade", "estado"];

    camposEtapa.forEach((nomeCampo) => {
      const campo = campos[nomeCampo];
      if (!campo) {
        console.warn(`Campo ${nomeCampo} não encontrado`);
        return;
      }
      
      const valor = campo.value;
      console.log(`Validando ${nomeCampo}:`, valor);
      
      const resultado = validarCampo(nomeCampo, valor);
      console.log(`${nomeCampo} válido:`, resultado);
      
      if (!resultado) {
        valido = false;
      }
    });

    console.log(`Etapa ${etapa} válida:`, valido);
    return valido;
  }

  function proximaEtapa() {
    console.log("=== BOTÃO PRÓXIMO CLICADO ===");
    
    if (!validarEtapa(1)) {
      console.log("Validação falhou - não avançando");
      
      // Focar no primeiro campo com erro
      const camposEtapa1 = ["nome", "email", "celular", "dataNascimento", "senha", "confirmarSenha"];
      for (const nomeCampo of camposEtapa1) {
        const campo = campos[nomeCampo];
        if (campo) {
          const formGroup = campo.closest(".form-group");
          if (formGroup && formGroup.classList.contains("error")) {
            campo.focus();
            break;
          }
        }
      }
      return;
    }

    console.log("Validação passou - avançando para etapa 2");
    
    etapaAtual = 2;
    
    if (step1) step1.classList.remove("active");
    if (step2) step2.classList.add("active");
    if (step1Indicator) {
      step1Indicator.classList.remove("active");
      step1Indicator.classList.add("completed");
    }
    if (step2Indicator) step2Indicator.classList.add("active");

    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  function etapaAnterior() {
    console.log("=== BOTÃO VOLTAR CLICADO ===");
    
    etapaAtual = 1;
    
    if (step2) step2.classList.remove("active");
    if (step1) step1.classList.add("active");
    if (step2Indicator) step2Indicator.classList.remove("active");
    if (step1Indicator) {
      step1Indicator.classList.remove("completed");
      step1Indicator.classList.add("active");
    }

    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  async function buscarCEP(cep) {
    try {
      const cepLimpo = cep.replace(/\D/g, "");
      if (cepLimpo.length !== 8) return;

      campos.cep.classList.add("loading");

      const response = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
      const data = await response.json();

      if (!data.erro) {
        if (campos.rua) campos.rua.value = data.logradouro || "";
        if (campos.bairro) campos.bairro.value = data.bairro || "";
        if (campos.cidade) campos.cidade.value = data.localidade || "";
        if (campos.estado) campos.estado.value = data.uf || "";

        if (data.logradouro) validarCampo("rua", data.logradouro);
        if (data.bairro) validarCampo("bairro", data.bairro);
        if (data.localidade) validarCampo("cidade", data.localidade);
        if (data.uf) validarCampo("estado", data.uf);
      }
    } catch (error) {
      console.error("Erro ao buscar CEP:", error);
    } finally {
      campos.cep.classList.remove("loading");
    }
  }

  // Máscara e busca de CEP
  if (campos.cep) {
    campos.cep.addEventListener("input", function (e) {
      let valor = e.target.value.replace(/\D/g, "");
      if (valor.length > 5) {
        valor = valor.substring(0, 5) + "-" + valor.substring(5, 8);
      }
      e.target.value = valor;

      if (valor.length === 9) {
        buscarCEP(valor);
      }
    });
  }

  // Event Listeners dos botões
  if (nextBtn) {
    console.log("Adicionando listener ao botão PRÓXIMO");
    nextBtn.addEventListener("click", function(e) {
      e.preventDefault();
      e.stopPropagation();
      console.log("Evento de clique capturado");
      proximaEtapa();
    });
  } else {
    console.error("Botão PRÓXIMO não encontrado!");
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", function(e) {
      e.preventDefault();
      e.stopPropagation();
      etapaAnterior();
    });
  }

  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      console.log("=== FORMULÁRIO SUBMETIDO ===");

      if (!validarEtapa(2)) {
        console.log("Validação da etapa 2 falhou");
        return;
      }

      if (submitBtn) {
        submitBtn.textContent = "Enviando...";
        submitBtn.disabled = true;
      }

      const novoUsuario = {
        nome: campos.nome.value,
        email: campos.email.value,
        celular: campos.celular.value,
        dataNascimento: campos.dataNascimento.value,
        senha: campos.senha.value,
        endereco: {
          cep: campos.cep.value,
          rua: campos.rua.value,
          numero: campos.numero.value,
          bairro: campos.bairro.value,
          cidade: campos.cidade.value,
          estado: campos.estado.value,
        },
      };

      try {
        const response = await fetch("processa_cadastro.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(novoUsuario),
        });

        const data = await response.json();

        if (data.success) {
          showToast("Cadastro realizado com sucesso!", "login.php", "success");
          form.reset();
          etapaAnterior();
          Object.keys(campos).forEach((key) => limparValidacao(campos[key]));
        } else {
          showToast(data.message || "Erro ao cadastrar", null, "error");
        }
      } catch (error) {
        console.error("Erro no fetch:", error);
        showToast("Erro de conexão.", null, "error");
      } finally {
        if (submitBtn) {
          submitBtn.textContent = "Finalizar Cadastro";
          submitBtn.disabled = false;
        }
      }
    });
  }

  function showToast(message, redirectUrl = null, type = "success") {
    const container = document.getElementById("toast-container");
    if (!container) return;

    const toast = document.createElement("div");
    toast.className = "toast";
    toast.textContent = message;

    if (type === "error") {
      toast.style.backgroundColor = "#dc3545";
    } else {
      toast.style.backgroundColor = "#4caf50";
    }

    container.appendChild(toast);

    setTimeout(() => toast.classList.add("show"), 50);
    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 400);
      if (redirectUrl && type === "success") {
        window.location.href = redirectUrl;
      }
    }, 2500);
  }

  // Toggle senha
  const togglePasswordButtons = document.querySelectorAll(".toggle-password");
  togglePasswordButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("data-target");
      const targetInput = document.getElementById(targetId);
      if (targetInput) {
        if (targetInput.type === "password") {
          targetInput.type = "text";
          this.src = "assets/logos/olho-x.png";
        } else {
          targetInput.type = "password";
          this.src = "assets/logos/olho.png";
        }
      }
    });
  });

  console.log("=== INICIALIZAÇÃO COMPLETA ===");
});