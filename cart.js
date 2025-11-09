/**
 * Adiciona um item ao carrinho enviando dados para o servidor (adicionar_carrinho.php)
 * Esta função substitui a antiga 'adicionarAoCarrinho'
 */
async function adicionarItem(id, nome, preco, imagem = '', categoria = 'produto') {
    
    const item = {
        id: id,
        nome: nome,
        preco: preco,
        imagem: imagem,
        categoria: categoria
    };

    try {
        const response = await fetch('adicionar_carrinho.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(item)
        });

        if (!response.ok) {
            throw new Error('Falha ao adicionar item ao carrinho. Status: ' + response.status);
        }

        const data = await response.json();

        if (data.success) {
            // Atualiza o contador do carrinho no header
            atualizarContadorCarrinho(data.novoTotalItens);
            // Mostra a notificação de sucesso
            showCartNotification(data.message || `${nome} adicionado ao carrinho!`);
        } else {
            // Mostra notificação de erro (se o servidor enviar)
            showCartNotification(`Erro: ${data.message || 'Não foi possível adicionar o item'}`, 'error');
        }

    } catch (error) {
        console.error('Erro no fetch:', error);
        showCartNotification('Erro de conexão ao adicionar item.', 'error');
    }
}

/**
 * Atualiza o número no ícone do carrinho
 */
function atualizarContadorCarrinho(novoTotal) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = novoTotal;
    }
}


/**
 * Função para mostrar notificação do carrinho (mantida do seu original)
 */
function showCartNotification(message, type = 'success') {
    // Remove notificação existente
    const existingNotification = document.querySelector('.cart-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Cria nova notificação
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.textContent = message;
    
    const backgroundColor = (type === 'error') ? '#dc3545' : '#28a745'; // Vermelho para erro, verde para sucesso
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${backgroundColor};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        font-weight: bold;
        animation: slideIn 0.3s ease;
    `;
    
    // Adiciona animação CSS (mantida)
    if (!document.querySelector('#cart-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'cart-notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(notification);
    
    // Remove após 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

// O DOMContentLoaded que atualizava o carrinho foi removido,
// pois o PHP já renderiza o número correto no <header.php>.