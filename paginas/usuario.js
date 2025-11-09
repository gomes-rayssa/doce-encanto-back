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

    // Elementos de Informações Pessoais
    const editBtn = document.getElementById('edit-btn');
    const saveBtn = document.getElementById('save-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const editActions = document.getElementById('edit-actions');
    const logoutBtn = document.getElementById('logout-btn');

    // Elementos de Endereço (ATUALIZADOS)
    const editAddressBtn = document.getElementById('edit-address-btn');
    const addressEditActions = document.getElementById('address-edit-actions');
    const saveAddressBtn = document.getElementById('save-address-btn');
    const cancelAddressBtn = document.getElementById('cancel-address-btn');

    // Elementos da Zona de Perigo
    const deleteBtn = document.getElementById('delete-btn');

    // Elementos do Pop-up Personalizado (Confirmação)
    const customPopup = document.getElementById('custom-popup');
    const popupMessage = document.getElementById('popup-message');

    // Buscar usuário do localStorage
    let usuarios = JSON.parse(localStorage.getItem('doceEncanto_users')) || [];
    let usuarioAtual = JSON.parse(localStorage.getItem("doceEncanto_currentUser")) || usuarios[usuarios.length - 1] || null;

    // --- Funções Auxiliares ---

    // Função para mostrar notificação personalizada (Toast)
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        
        notificationMessage.textContent = message;
        notification.classList.remove('hidden', 'success', 'error');
        notification.classList.add(type);
        
        notification.offsetHeight; // Força reflow/reinicialização
        notification.classList.add('show'); 

        // Oculta após 3 segundos
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 500);
        }, 3000);
    }
    
    function preencherCampos() {
        if (!usuarioAtual) return;
        campos.nome.value = usuarioAtual.nome || '';
        campos.email.value = usuarioAtual.email || '';
        campos.dataNascimento.value = usuarioAtual.dataNascimento || '';
        campos.cep.value = usuarioAtual.endereco.cep || '';
        campos.rua.value = usuarioAtual.endereco.rua || '';
        campos.numero.value = usuarioAtual.endereco.numero || '';
        campos.bairro.value = usuarioAtual.endereco.bairro || '';
        campos.cidade.value = usuarioAtual.endereco.cidade || '';
        campos.estado.value = usuarioAtual.endereco.estado || '';
    }

    function setReadonly(inputs, valor) {
        inputs.forEach(input => {
            input.readOnly = valor;
        });
    }

    /**
     * Exibe o pop-up personalizado e configura a ação de "Sim".
     * @param {string} message - A mensagem a ser exibida no pop-up.
     * @param {function} callbackYes - Função a ser executada ao clicar em "Sim".
     */
    function showPopup(message, callbackYes) {
        popupMessage.textContent = message;
        customPopup.classList.remove('hidden');

        // Clona e substitui os botões para remover listeners antigos
        const oldYesBtn = document.getElementById('popup-yes-btn');
        const oldNoBtn = document.getElementById('popup-no-btn');
        
        const newYesBtn = oldYesBtn.cloneNode(true);
        const newNoBtn = oldNoBtn.cloneNode(true);

        oldYesBtn.replaceWith(newYesBtn);
        oldNoBtn.replaceWith(newNoBtn);

        newYesBtn.addEventListener('click', () => {
            customPopup.classList.add('hidden');
            callbackYes();
        }, { once: true });

        newNoBtn.addEventListener('click', () => {
            customPopup.classList.add('hidden');
        }, { once: true });
    }

    // --- Lógica Inicial ---
    
    preencherCampos();
    setReadonly(Object.values(campos), true);

    // --- Event Listeners ---

    // 1. Informações Pessoais: Botão Editar
    editBtn.addEventListener('click', () => {
        const personalFields = [campos.nome, campos.email, campos.dataNascimento];
        setReadonly(personalFields, false);
        editActions.classList.remove('hidden');
        editBtn.classList.add('hidden');
        logoutBtn.classList.add('hidden'); 
        editAddressBtn.classList.add('hidden');
    });

    // 1.1 Informações Pessoais: Botão Cancelar
    cancelBtn.addEventListener('click', () => {
        const personalFields = [campos.nome, campos.email, campos.dataNascimento];
        preencherCampos(); 
        setReadonly(personalFields, true);
        editActions.classList.add('hidden');
        editBtn.classList.remove('hidden');
        logoutBtn.classList.remove('hidden');
        editAddressBtn.classList.remove('hidden');
    });

    // 1.2 Informações Pessoais: Botão Salvar
    saveBtn.addEventListener('click', () => {
        if (!usuarioAtual) return;

        // Atualiza campos pessoais
        usuarioAtual.nome = campos.nome.value;
        usuarioAtual.email = campos.email.value;
        usuarioAtual.dataNascimento = campos.dataNascimento.value;

        // Atualiza no localStorage
        usuarios[usuarios.length - 1] = usuarioAtual;
        localStorage.setItem('doceEncanto_users', JSON.stringify(usuarios));
        localStorage.setItem("doceEncanto_currentUser", JSON.stringify(usuarioAtual));
        
        const personalFields = [campos.nome, campos.email, campos.dataNascimento];
        setReadonly(personalFields, true);
        editActions.classList.add('hidden');
        editBtn.classList.remove('hidden');
        logoutBtn.classList.remove('hidden');
        editAddressBtn.classList.remove('hidden');

        showNotification('Informações pessoais salvas com sucesso!', 'success');
    });

    // 2. Botão de Editar Endereço (AGORA MUDA PARA SALVAR/CANCELAR)
    editAddressBtn.addEventListener('click', () => {
        const addressFields = [campos.cep, campos.rua, campos.numero, campos.bairro, campos.cidade, campos.estado];
        
        // Ativa o modo de edição
        setReadonly(addressFields, false);
        editAddressBtn.classList.add('hidden');
        addressEditActions.classList.remove('hidden');

        // Oculta botões relacionados a outros cards
        editBtn.classList.add('hidden'); 
        logoutBtn.classList.add('hidden');
    });

    // 2.1 Botão Cancelar Endereço
    cancelAddressBtn.addEventListener('click', () => {
        const addressFields = [campos.cep, campos.rua, campos.numero, campos.bairro, campos.cidade, campos.estado];
        preencherCampos(); // Volta aos valores originais
        setReadonly(addressFields, true);
        
        editAddressBtn.classList.remove('hidden');
        addressEditActions.classList.add('hidden');

        // Mostra botões relacionados a outros cards
        editBtn.classList.remove('hidden'); 
        logoutBtn.classList.remove('hidden');
    });

    // 2.2 Botão Salvar Endereço
    saveAddressBtn.addEventListener('click', () => {
        const addressFields = [campos.cep, campos.rua, campos.numero, campos.bairro, campos.cidade, campos.estado];
        
        if (!usuarioAtual) return;

        // Atualiza dados do endereço
        usuarioAtual.endereco.cep = campos.cep.value;
        usuarioAtual.endereco.rua = campos.rua.value;
        usuarioAtual.endereco.numero = campos.numero.value;
        usuarioAtual.endereco.bairro = campos.bairro.value;
        usuarioAtual.endereco.cidade = campos.cidade.value;
        usuarioAtual.endereco.estado = campos.estado.value;

        // Atualiza no localStorage
        usuarios[usuarios.length - 1] = usuarioAtual;
        localStorage.setItem('doceEncanto_users', JSON.stringify(usuarios));
        localStorage.setItem("doceEncanto_currentUser", JSON.stringify(usuarioAtual));

        setReadonly(addressFields, true);
        editAddressBtn.classList.remove('hidden');
        addressEditActions.classList.add('hidden');

        // Mostra botões relacionados a outros cards
        editBtn.classList.remove('hidden'); 
        logoutBtn.classList.remove('hidden');

        showNotification('Endereço salvo com sucesso!', 'success');
    });

    // 3. Logout (Com Pop-up e Notificação Personalizada)
    logoutBtn.addEventListener('click', () => {
        showPopup("Deseja sair da conta?", () => {
            // Ação de Sim (Logout)
            localStorage.removeItem('doceEncanto_currentUser');
            
            showNotification('Você saiu da conta. Redirecionando...', 'success');
            
            // Redireciona APÓS o toast aparecer
            setTimeout(() => {
                window.location.href = '../index.html'; 
            }, 3500);
        });
    });
    
    // 4. Apagar Conta (Funcional e com Pop-up e Notificação Personalizada)
    deleteBtn.addEventListener('click', () => {
        showPopup("Deseja apagar sua conta permanentemente? Esta ação é irreversível.", () => {
            // Ação de Sim (Excluir Conta)
            if (!usuarioAtual) return;

            // Encontra e remove o usuário do array principal
            const indexToRemove = usuarios.findIndex(u => u.email === usuarioAtual.email);
            
            if (indexToRemove !== -1) {
                 usuarios.splice(indexToRemove, 1);
                 localStorage.setItem('doceEncanto_users', JSON.stringify(usuarios));
            }
            
            // Remove o status de usuário logado
            localStorage.removeItem('doceEncanto_currentUser');

            showNotification('Sua conta foi apagada permanentemente. Sentiremos sua falta!', 'error');
            
            // Redireciona APÓS o toast aparecer
            setTimeout(() => {
                window.location.href = '../index.html';
            }, 3500); 
        });
    });


    // --- Lógica de Visibilidade do Menu ---
    const loginItem = document.querySelector('.dropdown-menu a[href*="login.html"]');
    const cadastroItem = document.querySelector('.dropdown-menu a[href*="cadastro.html"]');
    const minhaContaItem = document.querySelector('.dropdown-menu a[href*="usuario.html"]');

    const currentUserStatus = localStorage.getItem("doceEncanto_currentUser");

    if (currentUserStatus) {
        if (loginItem) loginItem.style.display = "none";
        if (cadastroItem) cadastroItem.style.display = "none";
        if (minhaContaItem) minhaContaItem.style.display = "block";
    } else {
        if (loginItem) loginItem.style.display = "block";
        if (cadastroItem) cadastroItem.style.display = "block";
        if (minhaContaItem) minhaContaItem.style.display = "none";
    }
});

