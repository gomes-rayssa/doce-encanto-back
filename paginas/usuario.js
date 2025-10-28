document.addEventListener('DOMContentLoaded', function () {
  // Campos do perfil
  const campos = {
    nome: document.getElementById('nome-completo'),
    email: document.getElementById('email'),
    dataNascimento: document.getElementById('data-nascimento'),
    cep: document.getElementById('cep'),
    rua: document.getElementById('rua'),
    numero: document.getElementById('numero'),
    bairro: document.getElementById('bairro'),
    cidade: document.getElementById('cidade'),
    estado: document.getElementById('estado')
  };

  const editBtn = document.getElementById('edit-btn');
  const saveBtn = document.getElementById('save-btn');
  const cancelBtn = document.getElementById('cancel-btn');
  const editActions = document.getElementById('edit-actions');

  // Buscar usuário do localStorage (último cadastrado)
  let usuarios = JSON.parse(localStorage.getItem('doceEncanto_users')) || [];
  let usuarioAtual = usuarios[usuarios.length - 1] || null;

  function preencherCampos() {
    if (!usuarioAtual) return;
    campos.nome.value = usuarioAtual.nome;
    campos.email.value = usuarioAtual.email;
    campos.dataNascimento.value = usuarioAtual.dataNascimento;
    campos.cep.value = usuarioAtual.endereco.cep;
    campos.rua.value = usuarioAtual.endereco.rua;
    campos.numero.value = usuarioAtual.endereco.numero;
    campos.bairro.value = usuarioAtual.endereco.bairro;
    campos.cidade.value = usuarioAtual.endereco.cidade;
    campos.estado.value = usuarioAtual.endereco.estado;
  }

  function setReadonly(valor) {
    Object.values(campos).forEach(input => {
      input.readOnly = valor;
    });
  }

  // Inicializar
  preencherCampos();
  setReadonly(true);

  // Botão Editar
  editBtn.addEventListener('click', () => {
    setReadonly(false);
    editActions.classList.remove('hidden');
    editBtn.classList.add('hidden');
  });

  // Botão Cancelar
  cancelBtn.addEventListener('click', () => {
    preencherCampos(); // volta aos valores originais
    setReadonly(true);
    editActions.classList.add('hidden');
    editBtn.classList.remove('hidden');
  });

  // Botão Salvar
  saveBtn.addEventListener('click', () => {
    if (!usuarioAtual) return;

    // Atualiza o objeto no localStorage
    usuarioAtual.nome = campos.nome.value;
    usuarioAtual.email = campos.email.value;
    usuarioAtual.dataNascimento = campos.dataNascimento.value;
    usuarioAtual.endereco.cep = campos.cep.value;
    usuarioAtual.endereco.rua = campos.rua.value;
    usuarioAtual.endereco.numero = campos.numero.value;
    usuarioAtual.endereco.bairro = campos.bairro.value;
    usuarioAtual.endereco.cidade = campos.cidade.value;
    usuarioAtual.endereco.estado = campos.estado.value;

    usuarios[usuarios.length - 1] = usuarioAtual;
    localStorage.setItem('doceEncanto_users', JSON.stringify(usuarios));

    setReadonly(true);
    editActions.classList.add('hidden');
    editBtn.classList.remove('hidden');

    alert('Informações salvas com sucesso!');
  });

  // Logout (opcional)
  const logoutBtn = document.getElementById('logout-btn');
  logoutBtn.addEventListener('click', () => {
    alert('Você saiu da conta!');
    window.location.href = '../index.html';
  });
});
