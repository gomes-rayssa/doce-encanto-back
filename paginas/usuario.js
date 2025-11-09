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

    // Backup dos valores originais (carregados pelo PHP)
    const originalValues = {};
    Object.keys(campos).forEach(key => {
        originalValues[key] = campos[key].value;
    });

    // Elementos de Informações Pessoais
    const editBtn = document.getElementById('edit-btn');
    const saveBtn = document.getElementById('save-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const editActions = document.getElementById('edit-actions');
    const logoutBtn = document.getElementById('logout-btn');

    // Elementos de Endereço
    const editAddressBtn = document.getElementById('edit-address-btn');
    const addressEditActions = document.getElementById('address-edit-actions');
    const saveAddressBtn = document.getElementById('save-address-btn');
    const cancelAddressBtn = document.getElementById('cancel-address-btn');

    // Elementos da Zona de Perigo
    const deleteBtn = document.getElementById('delete-btn');

    // Elementos do Pop-up e Notificação
    const customPopup = document.getElementById('custom-popup');
    const popupMessage = document.getElementById('popup-message');
    const notification = document.getElementById('notification');
    const notificationMessage = document.getElementById('notification-message');

    // --- Funções Auxiliares ---

    function showNotification(message, type = 'success') {
        notificationMessage.textContent = message;
        notification.classList.remove('hidden', 'success', 'error');
        notification.classList.add(type);
        notification.offsetHeight; 
        notification.classList.add('show'); 

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.classList.add('hidden'), 500);
        }, 3000);
    }
    
    // Restaura os valores originais (pré-edição)
    function restaurarValores() {
        Object.keys(campos).forEach(key => {
            campos[key].value = originalValues[key];
        });
    }
    
    // Atualiza os valores de backup (pós-salvar)
    function atualizarBackup() {
         Object.keys(campos).forEach(key => {
            originalValues[key] = campos[key].value;
        });
    }

    function setReadonly(inputs, valor) {
        inputs.forEach(input => input.readOnly = valor);
    }

    function showPopup(message, callbackYes) {
        popupMessage.textContent = message;
        customPopup.classList.remove('hidden');

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
    // Removemos preencherCampos(), pois o PHP já fez isso.
    setReadonly(Object.values(campos), true);

    // --- Event Listeners ---

    // 1. Informações Pessoais: Editar
    editBtn.addEventListener('click', () => {
        const personalFields = [campos.nome, campos.email, campos.dataNascimento];
        setReadonly(personalFields, false);
        editActions.classList.remove('hidden');
        editBtn.classList.add('hidden');
        logoutBtn.classList.add('hidden'); 
        editAddressBtn.classList.add('hidden');
        campos.nome.focus();
    });

    // 1.1 Informações Pessoais: Cancelar
    cancelBtn.addEventListener('click', () => {
        const personalFields = [campos.nome, campos.email, campos.dataNascimento];
        restaurarValores(); // Restaura valores originais
        setReadonly(personalFields, true);
        editActions.classList.add('hidden');
        editBtn.classList.remove('hidden');
        logoutBtn.classList.remove('hidden');
        editAddressBtn.classList.remove('hidden');
    });

    // 1.2 Informações Pessoais: Salvar (MODIFICADO COM FETCH)
    saveBtn.addEventListener('click', async () => {
        const personalFields = [campos.nome, campos.email, campos.dataNascimento];
        
        const data = {
            action: 'save_personal',
            nome: campos.nome.value,
            email: campos.email.value, // Nota: Mudar email exigiria verificação
            dataNascimento: campos.dataNascimento.value
        };

        try {
            const response = await fetch('processa_perfil.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                setReadonly(personalFields, true);
                editActions.classList.add('hidden');
                editBtn.classList.remove('hidden');
                logoutBtn.classList.remove('hidden');
                editAddressBtn.classList.remove('hidden');
                atualizarBackup(); // Salva os novos valores como "originais"
            } else {
                showNotification(result.message, 'error');
            }
        } catch (error) {
            showNotification('Erro de conexão ao salvar.', 'error');
        }
    });

    // 2. Endereço: Editar
    editAddressBtn.addEventListener('click', () => {
        const addressFields = [campos.cep, campos.rua, campos.numero, campos.bairro, campos.cidade, campos.estado];
        setReadonly(addressFields, false);
        editAddressBtn.classList.add('hidden');
        addressEditActions.classList.remove('hidden');
        editBtn.classList.add('hidden'); 
        logoutBtn.classList.add('hidden');
        campos.cep.focus();
    });

    // 2.1 Endereço: Cancelar
    cancelAddressBtn.addEventListener('click', () => {
        const addressFields = [campos.cep, campos.rua, campos.numero, campos.bairro, campos.cidade, campos.estado];
        restaurarValores(); // Restaura valores originais
        setReadonly(addressFields, true);
        editAddressBtn.classList.remove('hidden');
        addressEditActions.classList.add('hidden');
        editBtn.classList.remove('hidden'); 
        logoutBtn.classList.remove('hidden');
    });

    // 2.2 Endereço: Salvar (MODIFICADO COM FETCH)
    saveAddressBtn.addEventListener('click', async () => {
        const addressFields = [campos.cep, campos.rua, campos.numero, campos.bairro, campos.cidade, campos.estado];
        
        const data = {
            action: 'save_address',
            endereco: {
                cep: campos.cep.value,
                rua: campos.rua.value,
                numero: campos.numero.value,
                bairro: campos.bairro.value,
                cidade: campos.cidade.value,
                estado: campos.estado.value
            }
        };

        try {
            const response = await fetch('processa_perfil.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            const result = await response.json();
            
            if (result.success) {
                showNotification(result.message, 'success');
                setReadonly(addressFields, true);
                editAddressBtn.classList.remove('hidden');
                addressEditActions.classList.add('hidden');
                editBtn.classList.remove('hidden'); 
                logoutBtn.classList.remove('hidden');
                atualizarBackup(); // Salva os novos valores
            } else {
                showNotification(result.message, 'error');
            }
        } catch (error) {
            showNotification('Erro de conexão ao salvar endereço.', 'error');
        }
    });

    // 3. Logout (MODIFICADO)
    // O botão agora é um <a>, mas o JS de confirmação ainda é útil.
    logoutBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Impede o link de ser seguido imediatamente
        
        showPopup("Deseja realmente sair da conta?", () => {
            // Ação de Sim (Logout)
            showNotification('Saindo...', 'success');
            setTimeout(() => {
                window.location.href = logoutBtn.href; // Redireciona para o script de logout
            }, 1500);
        });
    });
    
    // 4. Apagar Conta (MODIFICADO COM FETCH)
    deleteBtn.addEventListener('click', () => {
        showPopup("Deseja apagar sua conta permanentemente? Esta ação é irreversível.", async () => {
            // Ação de Sim (Excluir Conta)
            try {
                const response = await fetch('processa_apagar_conta.php', {
                    method: 'POST'
                });
                const result = await response.json();

                if (result.success) {
                    showNotification('Sua conta foi apagada. Redirecionando...', 'error');
                    setTimeout(() => {
                        window.location.href = '../index.php';
                    }, 3000);
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                showNotification('Erro de conexão.', 'error');
            }
        });
    });

    // Lógica de visibilidade do menu FOI REMOVIDA
    // O header.php agora cuida disso no servidor.
});