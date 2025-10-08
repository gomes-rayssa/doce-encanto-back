document.addEventListener('DOMContentLoaded', function () {
  // Elementos do DOM
  const form = document.getElementById('registration-form');
  const step1 = document.getElementById('step1');
  const step2 = document.getElementById('step2');
  const step1Indicator = document.getElementById('step1-indicator');
  const step2Indicator = document.getElementById('step2-indicator');
  const nextBtn = document.getElementById('next-step1');
  const prevBtn = document.getElementById('prev-step2');
  const submitBtn = document.getElementById('submit-form');

  // Campos do formulário
  const campos = {
    nome: document.getElementById('nome'),
    email: document.getElementById('email'),
    dataNascimento: document.getElementById('data-nascimento'),
    senha: document.getElementById('senha'),
    confirmarSenha: document.getElementById('confirmar-senha'),
    cep: document.getElementById('cep'),
    rua: document.getElementById('rua'),
    numero: document.getElementById('numero'),
    bairro: document.getElementById('bairro'),
    cidade: document.getElementById('cidade'),
    estado: document.getElementById('estado')
  };

  // Variável para controlar a etapa atual
  let etapaAtual = 1;

  // Funções de validação
  function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }

  function validarSenha(senha) {
    // Senha forte: mínimo 8 caracteres, pelo menos 1 maiúscula, 1 minúscula e 1 símbolo
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
    const hoje = new Date();
    const nascimento = new Date(dataNascimento);
    const idade = hoje.getFullYear() - nascimento.getFullYear();
    const mesAtual = hoje.getMonth();
    const mesNascimento = nascimento.getMonth();

    if (mesAtual < mesNascimento || (mesAtual === mesNascimento && hoje.getDate() < nascimento.getDate())) {
      idade--;
    }

    return idade >= 18;
  }

  // Função para mostrar erro
  function mostrarErro(campo, mensagem) {
    const formGroup = campo.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message');

    formGroup.classList.add('error');
    formGroup.classList.remove('success');
    errorElement.textContent = mensagem;
  }

  // Função para mostrar sucesso
  function mostrarSucesso(campo) {
    const formGroup = campo.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message');

    formGroup.classList.add('success');
    formGroup.classList.remove('error');
    errorElement.textContent = '';
  }

  // Função para limpar validação
  function limparValidacao(campo) {
    const formGroup = campo.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message');

    formGroup.classList.remove('error', 'success');
    errorElement.textContent = '';
  }

  // Validação em tempo real
  Object.keys(campos).forEach(key => {
    const campo = campos[key];

    campo.addEventListener('blur', function () {
      validarCampo(key, campo.value);
    });

    campo.addEventListener('input', function () {
      if (campo.closest('.form-group').classList.contains('error')) {
        validarCampo(key, campo.value);
      }
    });
  });

  // Função para validar campo individual
  function validarCampo(nomeCampo, valor) {
    const campo = campos[nomeCampo];

    switch (nomeCampo) {
      case 'nome':
        if (!valor.trim()) {
          mostrarErro(campo, 'Nome é obrigatório');
          return false;
        } else if (valor.trim().length < 2) {
          mostrarErro(campo, 'Nome deve ter pelo menos 2 caracteres');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      case 'email':
        if (!valor.trim()) {
          mostrarErro(campo, 'E-mail é obrigatório');
          return false;
        } else if (!validarEmail(valor)) {
          mostrarErro(campo, 'E-mail inválido');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      case 'dataNascimento':
        if (!valor) {
          mostrarErro(campo, 'Data de nascimento é obrigatória');
          return false;
        } else if (!validarIdade(valor)) {
          mostrarErro(campo, 'Você deve ter pelo menos 18 anos');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      case 'senha':
        if (!valor) {
          mostrarErro(campo, 'Senha é obrigatória');
          return false;
        } else if (!validarSenha(valor)) {
          mostrarErro(campo, 'Senha deve ter pelo menos 8 caracteres, incluindo maiúscula, minúscula e símbolo');
          return false;
        } else {
          mostrarSucesso(campo);
          // Revalidar confirmação de senha se já foi preenchida
          if (campos.confirmarSenha.value) {
            validarCampo('confirmarSenha', campos.confirmarSenha.value);
          }
          return true;
        }

      case 'confirmarSenha':
        if (!valor) {
          mostrarErro(campo, 'Confirmação de senha é obrigatória');
          return false;
        } else if (valor !== campos.senha.value) {
          mostrarErro(campo, 'Senhas não coincidem');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      case 'cep':
        if (!valor.trim()) {
          mostrarErro(campo, 'CEP é obrigatório');
          return false;
        } else if (!validarCEP(valor)) {
          mostrarErro(campo, 'CEP inválido');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      case 'rua':
        if (!valor.trim()) {
          mostrarErro(campo, 'Rua é obrigatória');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      case 'numero': // <-- Adicione este novo case
        if (!valor.trim()) {
          mostrarErro(campo, 'Número é obrigatório');
          return false;
        } else if (isNaN(valor)) { // Valida se é um número
          mostrarErro(campo, 'O número deve conter apenas dígitos');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      case 'bairro':
        if (!valor.trim()) {
          mostrarErro(campo, 'Bairro é obrigatório');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      case 'cidade':
        if (!valor.trim()) {
          mostrarErro(campo, 'Cidade é obrigatória');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      case 'estado':
        if (!valor) {
          mostrarErro(campo, 'Estado é obrigatório');
          return false;
        } else {
          mostrarSucesso(campo);
          return true;
        }

      default:
        return true;
    }
  }

  // Função para validar etapa
  function validarEtapa(etapa) {
    let valido = true;

    if (etapa === 1) {
      const camposEtapa1 = ['nome', 'email', 'dataNascimento', 'senha', 'confirmarSenha'];
      camposEtapa1.forEach(campo => {
        if (!validarCampo(campo, campos[campo].value)) {
          valido = false;
        }
      });
    } else if (etapa === 2) {
      const camposEtapa2 = ['cep', 'rua', 'numero', 'bairro', 'cidade', 'estado'];
      camposEtapa2.forEach(campo => {
        if (!validarCampo(campo, campos[campo].value)) {
          valido = false;
        }
      });
    }

    return valido;
  }

  // Função para ir para próxima etapa
  function proximaEtapa() {
    if (validarEtapa(1)) {
      etapaAtual = 2;
      step1.classList.remove('active');
      step2.classList.add('active');
      step1Indicator.classList.remove('active');
      step1Indicator.classList.add('completed');
      step2Indicator.classList.add('active');
    }
  }

  // Função para voltar etapa
  function etapaAnterior() {
    etapaAtual = 1;
    step2.classList.remove('active');
    step1.classList.add('active');
    step2Indicator.classList.remove('active');
    step1Indicator.classList.remove('completed');
    step1Indicator.classList.add('active');
  }

  // Função para buscar CEP
  async function buscarCEP(cep) {
    try {
      const cepLimpo = cep.replace(/\D/g, '');
      if (cepLimpo.length !== 8) return;

      // Adicionar classe de loading
      campos.cep.classList.add('loading');

      const response = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
      const data = await response.json();

      if (!data.erro) {
        campos.rua.value = data.logradouro || '';
        campos.bairro.value = data.bairro || '';
        campos.cidade.value = data.localidade || '';
        campos.estado.value = data.uf || '';

        // Validar campos preenchidos automaticamente
        if (data.logradouro) validarCampo('rua', data.logradouro);
        if (data.bairro) validarCampo('bairro', data.bairro);
        if (data.localidade) validarCampo('cidade', data.localidade);
        if (data.uf) validarCampo('estado', data.uf);
      }
    } catch (error) {
      console.error('Erro ao buscar CEP:', error);
    } finally {
      // Remover classe de loading
      campos.cep.classList.remove('loading');
    }
  }

  // Máscara para CEP
  campos.cep.addEventListener('input', function (e) {
    let valor = e.target.value.replace(/\D/g, '');
    if (valor.length > 5) {
      valor = valor.substring(0, 5) + '-' + valor.substring(5, 8);
    }
    e.target.value = valor;

    // Buscar CEP automaticamente quando completo
    if (valor.length === 9) {
      buscarCEP(valor);
    }
  });

  // Event listeners para botões
  nextBtn.addEventListener('click', proximaEtapa);
  prevBtn.addEventListener('click', etapaAnterior);

  // Submissão do formulário
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (validarEtapa(2)) {
      submitBtn.textContent = 'Enviando...';
      submitBtn.disabled = true;

      setTimeout(() => {
        // ✅ Notificação
        showToast('Cadastro realizado com sucesso!', 'login.html');

        // Reset do formulário (opcional, pois vamos sair da página)
        form.reset();
        etapaAnterior();

        Object.keys(campos).forEach(key => {
          limparValidacao(campos[key]);
        });

        submitBtn.textContent = 'Finalizar Cadastro';
        submitBtn.disabled = false;
      }, 2000);
    }

    function showToast(message, redirectUrl) {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      toast.className = 'toast';
      toast.textContent = message;
      container.appendChild(toast);

      // Faz aparecer
      setTimeout(() => toast.classList.add('show'), 50);

      // Some após 2,5 segundos e redireciona
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
        if (redirectUrl) window.location.href = redirectUrl;
      }, 2500);
    }

  });

  // Navegação por teclado
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && e.target.tagName !== 'BUTTON' && e.target.type !== 'submit') {
      e.preventDefault();
      if (etapaAtual === 1) {
        proximaEtapa();
      }
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

  // Inicialização
  console.log('Formulário de cadastro inicializado');
});


