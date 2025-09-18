// Sistema de carrinho de compras para Doce Encanto

// Classe para gerenciar o carrinho
class CartManager {
    constructor() {
        this.cart = this.loadCart();
    }

    // Carrega carrinho do localStorage
    loadCart() {
        const cart = localStorage.getItem('doceEncanto_cart');
        return cart ? JSON.parse(cart) : [];
    }

    // Salva carrinho no localStorage
    saveCart() {
        localStorage.setItem('doceEncanto_cart', JSON.stringify(this.cart));
        this.updateCartDisplay();
    }

    // Adiciona item ao carrinho
    addItem(item) {
        const existingItem = this.cart.find(cartItem => cartItem.id === item.id);
        
        if (existingItem) {
            existingItem.quantidade += item.quantidade || 1;
        } else {
            this.cart.push({
                id: item.id || Date.now().toString(),
                nome: item.nome,
                preco: parseFloat(item.preco),
                quantidade: item.quantidade || 1,
                imagem: item.imagem,
                categoria: item.categoria || 'produto'
            });
        }
        
        this.saveCart();
        return true;
    }

    // Remove item do carrinho
    removeItem(itemId) {
        this.cart = this.cart.filter(item => item.id !== itemId);
        this.saveCart();
    }

    // Atualiza quantidade de um item
    updateQuantity(itemId, newQuantity) {
        const item = this.cart.find(cartItem => cartItem.id === itemId);
        if (item) {
            if (newQuantity <= 0) {
                this.removeItem(itemId);
            } else {
                item.quantidade = newQuantity;
                this.saveCart();
            }
        }
    }

    // Limpa o carrinho
    clearCart() {
        this.cart = [];
        this.saveCart();
    }

    // Retorna itens do carrinho
    getItems() {
        return this.cart;
    }

    // Retorna total de itens
    getTotalItems() {
        return this.cart.reduce((total, item) => total + item.quantidade, 0);
    }

    // Retorna valor total
    getTotalValue() {
        return this.cart.reduce((total, item) => total + (item.preco * item.quantidade), 0);
    }

    // Atualiza exibição do carrinho na navegação
    updateCartDisplay() {
        const cartLinks = document.querySelectorAll('a[href*="carrinho.html"]');
        const totalItems = this.getTotalItems();
        
        cartLinks.forEach(link => {
            // Remove badge existente
            const existingBadge = link.querySelector('.cart-badge');
            if (existingBadge) {
                existingBadge.remove();
            }
            
            // Adiciona novo badge se houver itens
            if (totalItems > 0) {
                const badge = document.createElement('span');
                badge.className = 'cart-badge';
                badge.textContent = totalItems;
                badge.style.cssText = `
                    position: absolute;
                    top: -5px;
                    right: -5px;
                    background: #dc3545;
                    color: white;
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    font-size: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                `;
                
                // Torna o link relativo para posicionar o badge
                link.style.position = 'relative';
                link.appendChild(badge);
            }
        });
    }
}

// Instância global do gerenciador de carrinho
const cartManager = new CartManager();

// Função para adicionar item ao carrinho (usada nos botões)
function adicionarAoCarrinho(nome, preco, imagem = '', categoria = 'produto') {
    const item = {
        id: `${categoria}_${nome.replace(/\s+/g, '_').toLowerCase()}`,
        nome: nome,
        preco: preco,
        quantidade: 1,
        imagem: imagem,
        categoria: categoria
    };
    
    if (cartManager.addItem(item)) {
        // Feedback visual
        showCartNotification(`${nome} adicionado ao carrinho!`);
    }
}

// Função para mostrar notificação do carrinho
function showCartNotification(message) {
    // Remove notificação existente
    const existingNotification = document.querySelector('.cart-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Cria nova notificação
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        font-weight: bold;
        animation: slideIn 0.3s ease;
    `;
    
    // Adiciona animação CSS
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

// Atualiza exibição do carrinho quando a página carrega
document.addEventListener('DOMContentLoaded', () => {
    cartManager.updateCartDisplay();
});

