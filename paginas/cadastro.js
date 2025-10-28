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

  let etapaAtual = 1;

  // --- Funções de validação (mantidas) ---
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
    const hoje = new Date();
    const nascimento = new Date(dataNascimento);
    let idade = hoje.getFullYear() - nascimento.getFullYear();
    const mesAtual = hoje.getMonth();
    const mesNascimento = nascimento.getMonth();
    if (mesAtual < mesNascimento || (mesAtual === mesNascimento && hoje.getDate() < nascimento.getDate())) {
      idade--;
    }
    return idade >= 18;
  }

  // --- Validação visual ---
  function mostrarErro(campo, mensagem) {
    const formGroup = campo.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message');
    formGroup.classList.add('error');
    formGroup.classList.remove('success');
    errorElement.textContent = mensagem;
  }

  function mostrarSucesso(campo) {
    const formGroup = campo.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message');
    formGroup.classList.add('success');
    formGroup.classList.remove('error');
    errorElement.textContent = '';
  }

  function limparValidacao(campo) {
    const formGroup = campo.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message');
    formGroup.classList.remove('error', 'success');
    errorElement.textContent = '';
  }

  // --- Validação em tempo real ---
  Object.keys(campos).forEach(key => {
    const campo = campos[key];
    campo.addEventListener('blur', () => validarCampo(key, campo.value));
    campo.addEventListener('input', () => {
      if (campo.closest('.form-group').classList.contains('error')) {
        validarCampo(key, campo.value);
      }
    });
  });

  function validarCampo(nomeCampo, valor) {
    const campo = campos[nomeCampo];
    switch (nomeCampo) {
      case 'nome':
        if (!valor.trim()) return mostrarErro(campo, 'Nome é obrigatório'), false;
        if (valor.trim().length < 2) return mostrarErro(campo, 'Nome deve ter pelo menos 2 caracteres'), false;
        mostrarSucesso(campo); return true;

      case 'email':
        if (!valor.trim()) return mostrarErro(campo, 'E-mail é obrigatório'), false;
        if (!validarEmail(valor)) return mostrarErro(campo, 'E-mail inválido'), false;
        mostrarSucesso(campo); return true;

      case 'dataNascimento':
        if (!valor) return mostrarErro(campo, 'Data de nascimento é obrigatória'), false;
        if (!validarIdade(valor)) return mostrarErro(campo, 'Você deve ter pelo menos 18 anos'), false;
        mostrarSucesso(campo); return true;

      case 'senha':
        if (!valor) return mostrarErro(campo, 'Senha é obrigatória'), false;
        if (!validarSenha(valor)) return mostrarErro(campo, 'Senha deve ter pelo menos 8 caracteres, incluindo maiúscula, minúscula e símbolo'), false;
        mostrarSucesso(campo);
        if (campos.confirmarSenha.value) validarCampo('confirmarSenha', campos.confirmarSenha.value);
        return true;

      case 'confirmarSenha':
        if (!valor) return mostrarErro(campo, 'Confirmação de senha é obrigatória'), false;
        if (valor !== campos.senha.value) return mostrarErro(campo, 'Senhas não coincidem'), false;
        mostrarSucesso(campo); return true;

      case 'cep':
        if (!valor.trim()) return mostrarErro(campo, 'CEP é obrigatório'), false;
        if (!validarCEP(valor)) return mostrarErro(campo, 'CEP inválido'), false;
        mostrarSucesso(campo); return true;

      case 'numero':
        if (!valor.trim()) return mostrarErro(campo, 'Número é obrigatório'), false;
        if (isNaN(valor)) return mostrarErro(campo, 'O número deve conter apenas dígitos'), false;
        mostrarSucesso(campo); return true;

      case 'rua':
      case 'bairro':
      case 'cidade':
        if (!valor.trim()) return mostrarErro(campo, `${nomeCampo.charAt(0).toUpperCase() + nomeCampo.slice(1)} é obrigatório`), false;
        mostrarSucesso(campo); return true;

      case 'estado':
        if (!valor) return mostrarErro(campo, 'Estado é obrigatório'), false;
        mostrarSucesso(campo); return true;

      default:
        return true;
    }
  }

  function validarEtapa(etapa) {
    let valido = true;
    const camposEtapa = etapa === 1
      ? ['nome', 'email', 'dataNascimento', 'senha', 'confirmarSenha']
      : ['cep', 'rua', 'numero', 'bairro', 'cidade', 'estado'];

    camposEtapa.forEach(campo => {
      if (!validarCampo(campo, campos[campo].value)) valido = false;
    });
    return valido;
  }

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

  function etapaAnterior() {
    etapaAtual = 1;
    step2.classList.remove('active');
    step1.classList.add('active');
    step2Indicator.classList.remove('active');
    step1Indicator.classList.remove('completed');
    step1Indicator.classList.add('active');
  }

  async function buscarCEP(cep) {
    try {
      const cepLimpo = cep.replace(/\D/g, '');
      if (cepLimpo.length !== 8) return;
      campos.cep.classList.add('loading');
      const response = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
      const data = await response.json();
      if (!data.erro) {
        campos.rua.value = data.logradouro || '';
        campos.bairro.value = data.bairro || '';
        campos.cidade.value = data.localidade || '';
        campos.estado.value = data.uf || '';
        if (data.logradouro) validarCampo('rua', data.logradouro);
        if (data.bairro) validarCampo('bairro', data.bairro);
        if (data.localidade) validarCampo('cidade', data.localidade);
        if (data.uf) validarCampo('estado', data.uf);
      }
    } catch (error) {
      console.error('Erro ao buscar CEP:', error);
    } finally {
      campos.cep.classList.remove('loading');
    }
  }

  campos.cep.addEventListener('input', function (e) {
    let valor = e.target.value.replace(/\D/g, '');
    if (valor.length > 5) valor = valor.substring(0, 5) + '-' + valor.substring(5, 8);
    e.target.value = valor;
    if (valor.length === 9) buscarCEP(valor);
  });

  nextBtn.addEventListener('click', proximaEtapa);
  prevBtn.addEventListener('click', etapaAnterior);

  // --- Submissão ---
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (validarEtapa(2)) {
      submitBtn.textContent = 'Enviando...';
      submitBtn.disabled = true;

      setTimeout(() => {
        // ✅ Salvar dados no localStorage
        const novoUsuario = {
          nome: campos.nome.value,
          email: campos.email.value,
          dataNascimento: campos.dataNascimento.value,
          senha: campos.senha.value,
          endereco: {
            cep: campos.cep.value,
            rua: campos.rua.value,
            numero: campos.numero.value,
            bairro: campos.bairro.value,
            cidade: campos.cidade.value,
            estado: campos.estado.value
          }
        };

        const usuarios = JSON.parse(localStorage.getItem('doceEncanto_users')) || [];
        usuarios.push(novoUsuario);
        localStorage.setItem('doceEncanto_users', JSON.stringify(usuarios));

        // ✅ Notificação e redirecionamento
        showToast('Cadastro realizado com sucesso!', 'login.html');

        // Reset visual
        form.reset();
        etapaAnterior();
        Object.keys(campos).forEach(key => limparValidacao(campos[key]));
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

      setTimeout(() => toast.classList.add('show'), 50);
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
        if (redirectUrl) window.location.href = redirectUrl;
      }, 2500);
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && e.target.tagName !== 'BUTTON' && e.target.type !== 'submit') {
      e.preventDefault();
      if (etapaAtual === 1) proximaEtapa();
    }
  });

  const togglePasswordButtons = document.querySelectorAll('.toggle-password');
  togglePasswordButtons.forEach(button => {
    button.addEventListener('click', function () {
      const targetId = this.getAttribute('data-target');
      const targetInput = document.getElementById(targetId);
      if (targetInput.type === 'password') {
        targetInput.type = 'text';
        this.src = '../assets/logos/olho-x.png';
      } else {
        targetInput.type = 'password';
        this.src = '../assets/logos/olho.png';
      }
    });
  });

  console.log('Formulário de cadastro inicializado');
});
