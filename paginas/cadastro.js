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
    celular: document.getElementById('celular'),
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

  // --- Funções de validação ---
  function validarEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function validarSenha(senha) {
    const temTamanhoMinimo = senha.length >= 8;
    const temMaiuscula = /[A-Z]/.test(senha);
    const temMinuscula = /[a-z]/.test(senha);
    const temSimbolo = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(senha);
    return temTamanhoMinimo && temMaiuscula && temMinuscula && temSimbolo;
  }

  function validarCEP(cep) {
    return /^\d{5}-?\d{3}$/.test(cep);
  }

  function validarCelular(celular) {
    const numeros = celular.replace(/\D/g, '');
    return /^[1-9]{2}9\d{8}$/.test(numeros); // Ex: 21998765432
  }

  function validarIdade(dataNascimento) {
    const hoje = new Date();
    const nascimento = new Date(dataNascimento);
    let idade = hoje.getFullYear() - nascimento.getFullYear();
    const mes = hoje.getMonth() - nascimento.getMonth();
    if (mes < 0 || (mes === 0 && hoje.getDate() < nascimento.getDate())) idade--;
    return idade >= 18;
  }

  // --- Máscara de celular ---
  campos.celular.addEventListener('input', function (e) {
    let valor = e.target.value.replace(/\D/g, '');
    if (valor.length > 11) valor = valor.substring(0, 11);

    if (valor.length > 6) {
      e.target.value = `(${valor.substring(0, 2)}) ${valor.substring(2, 7)}-${valor.substring(7)}`;
    } else if (valor.length > 2) {
      e.target.value = `(${valor.substring(0, 2)}) ${valor.substring(2)}`;
    } else {
      e.target.value = valor;
    }
  });

  // --- Validação visual ---
  function mostrarErro(campo, mensagem) {
    const grupo = campo.closest('.form-group');
    const erro = grupo.querySelector('.error-message');
    grupo.classList.add('error');
    grupo.classList.remove('success');
    erro.textContent = mensagem;
  }

  function mostrarSucesso(campo) {
    const grupo = campo.closest('.form-group');
    const erro = grupo.querySelector('.error-message');
    grupo.classList.add('success');
    grupo.classList.remove('error');
    erro.textContent = '';
  }

  function limparValidacao(campo) {
    const grupo = campo.closest('.form-group');
    const erro = grupo.querySelector('.error-message');
    grupo.classList.remove('error', 'success');
    erro.textContent = '';
  }

  // --- Validação por campo ---
  Object.keys(campos).forEach(key => {
    const campo = campos[key];
    campo.addEventListener('blur', () => validarCampo(key, campo.value));
    campo.addEventListener('input', () => {
      if (campo.closest('.form-group').classList.contains('error')) {
        validarCampo(key, campo.value);
      }
    });
  });

  function validarCampo(nome, valor) {
    const campo = campos[nome];
    switch (nome) {
      case 'nome':
        if (!valor.trim()) return mostrarErro(campo, 'Nome é obrigatório'), false;
        if (valor.trim().length < 2) return mostrarErro(campo, 'Nome muito curto'), false;
        mostrarSucesso(campo); return true;

      case 'email':
        if (!valor.trim()) return mostrarErro(campo, 'E-mail é obrigatório'), false;
        if (!validarEmail(valor)) return mostrarErro(campo, 'E-mail inválido'), false;
        mostrarSucesso(campo); return true;

      case 'celular':
        if (!valor.trim()) return mostrarErro(campo, 'Celular é obrigatório'), false;
        if (!validarCelular(valor)) return mostrarErro(campo, 'Número inválido. Use o formato (99) 99999-9999'), false;
        mostrarSucesso(campo); return true;

      case 'dataNascimento':
        if (!valor) return mostrarErro(campo, 'Data é obrigatória'), false;
        if (!validarIdade(valor)) return mostrarErro(campo, 'Você deve ter pelo menos 18 anos'), false;
        mostrarSucesso(campo); return true;

      case 'senha':
        if (!valor) return mostrarErro(campo, 'Senha é obrigatória'), false;
        if (!validarSenha(valor)) return mostrarErro(campo, 'Senha deve conter 8+ caracteres, maiúscula, minúscula e símbolo'), false;
        mostrarSucesso(campo); return true;

      case 'confirmarSenha':
        if (!valor) return mostrarErro(campo, 'Confirmação obrigatória'), false;
        if (valor !== campos.senha.value) return mostrarErro(campo, 'Senhas não coincidem'), false;
        mostrarSucesso(campo); return true;

      case 'cep':
        if (!validarCEP(valor)) return mostrarErro(campo, 'CEP inválido'), false;
        mostrarSucesso(campo); return true;

      case 'numero':
        if (!valor.trim() || isNaN(valor)) return mostrarErro(campo, 'Número inválido'), false;
        mostrarSucesso(campo); return true;

      case 'rua':
      case 'bairro':
      case 'cidade':
        if (!valor.trim()) return mostrarErro(campo, `${nome} é obrigatório`), false;
        mostrarSucesso(campo); return true;

      case 'estado':
        if (!valor) return mostrarErro(campo, 'Estado é obrigatório'), false;
        mostrarSucesso(campo); return true;

      default: return true;
    }
  }

  // --- Etapas ---
  function validarEtapa(etapa) {
    const camposEtapa = etapa === 1
      ? ['nome', 'email', 'celular', 'dataNascimento', 'senha', 'confirmarSenha']
      : ['cep', 'rua', 'numero', 'bairro', 'cidade', 'estado'];

    return camposEtapa.every(campo => validarCampo(campo, campos[campo].value));
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

  nextBtn.addEventListener('click', proximaEtapa);
  prevBtn.addEventListener('click', etapaAnterior);

  // --- CEP automático ---
  async function buscarCEP(cep) {
    try {
      const cepLimpo = cep.replace(/\D/g, '');
      if (cepLimpo.length !== 8) return;
      const resp = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
      const data = await resp.json();
      if (!data.erro) {
        campos.rua.value = data.logradouro || '';
        campos.bairro.value = data.bairro || '';
        campos.cidade.value = data.localidade || '';
        campos.estado.value = data.uf || '';
      }
    } catch (err) {
      console.error('Erro ao buscar CEP', err);
    }
  }

  campos.cep.addEventListener('input', e => {
    let valor = e.target.value.replace(/\D/g, '');
    if (valor.length > 5) valor = valor.substring(0, 5) + '-' + valor.substring(5);
    e.target.value = valor;
    if (valor.length === 9) buscarCEP(valor);
  });

  // --- Submissão ---
  form.addEventListener('submit', e => {
    e.preventDefault();
    if (validarEtapa(2)) {
      submitBtn.textContent = 'Enviando...';
      submitBtn.disabled = true;

      setTimeout(() => {
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
            estado: campos.estado.value
          }
        };

        const usuarios = JSON.parse(localStorage.getItem('doceEncanto_users')) || [];
        usuarios.push(novoUsuario);
        localStorage.setItem('doceEncanto_users', JSON.stringify(usuarios));

        showToast('Cadastro realizado com sucesso!', 'login.html');
        form.reset();
        etapaAnterior();
        submitBtn.textContent = 'Finalizar Cadastro';
        submitBtn.disabled = false;
      }, 1500);
    }
  });

  function showToast(msg, redirect) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = msg;
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 50);
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 300);
      if (redirect) window.location.href = redirect;
    }, 2500);
  }

  console.log('Formulário de cadastro inicializado');
});

