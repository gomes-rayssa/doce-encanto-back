// Sistema de carrinho atualizado para Doce Encanto

document.addEventListener("DOMContentLoaded", function() {
    // Verifica se estamos na página do carrinho
    if (window.location.pathname.includes("carrinho.html")) {
        exibirCarrinho();
    }
});

// Exibir itens no carrinho
function exibirCarrinho() {
    const listaCarrinho = document.getElementById("lista-carrinho");
    const totalEl = document.getElementById("total");
    const finalizarBtn = document.getElementById("finalizar-compra");

    if (!listaCarrinho || !totalEl) return;

    const carrinho = cartManager.getItems();
    listaCarrinho.innerHTML = "";

    if (carrinho.length === 0) {
        listaCarrinho.innerHTML = `
            <div class="carrinho-vazio">
                <i class="fas fa-shopping-cart"></i>
                <h3>Seu carrinho está vazio</h3>
                <p>Adicione alguns produtos deliciosos!</p>
                <a href="../index.html" class="btn-continuar">Continuar Comprando</a>
            </div>
        `;
        totalEl.textContent = "0,00";
        if (finalizarBtn) finalizarBtn.disabled = true;
        return;
    }

    carrinho.forEach((item, index) => {
        const li = document.createElement("li");
        li.className = "item-carrinho";

        li.innerHTML = `
            <div class="item-imagem">
                <img src="${item.imagem || '../assets/logos/logo-navbar.jpg'}" alt="${item.nome}" />
            </div>
            <div class="item-info">
                <h3>${item.nome}</h3>
                <p class="item-categoria">${item.categoria}</p>
                <p class="item-preco">R$ ${item.preco.toFixed(2)}</p>
            </div>
            <div class="item-quantidade">
                <button onclick="alterarQuantidade('${item.id}', ${item.quantidade - 1})" class="btn-quantidade">-</button>
                <span class="quantidade">${item.quantidade}</span>
                <button onclick="alterarQuantidade('${item.id}', ${item.quantidade + 1})" class="btn-quantidade">+</button>
            </div>
            <div class="item-total">
                <p>R$ ${(item.preco * item.quantidade).toFixed(2)}</p>
            </div>
            <div class="item-remover">
                <button onclick="removerItem('${item.id}')" class="btn-remover">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        listaCarrinho.appendChild(li);
    });

    const total = cartManager.getTotalValue();
    totalEl.textContent = total.toFixed(2);
    
    if (finalizarBtn) {
        finalizarBtn.disabled = false;
    }
}

// Alterar quantidade de um item
function alterarQuantidade(itemId, novaQuantidade) {
    cartManager.updateQuantity(itemId, novaQuantidade);
    exibirCarrinho();
}

// Remover item do carrinho
function removerItem(itemId) {
    if (confirm("Tem certeza que deseja remover este item?")) {
        cartManager.removeItem(itemId);
        exibirCarrinho();
        showCartNotification("Item removido do carrinho!");
    }
}

// Limpar carrinho
function limparCarrinho() {
    if (confirm("Tem certeza que deseja limpar todo o carrinho?")) {
        cartManager.clearCart();
        exibirCarrinho();
        showCartNotification("Carrinho limpo!");
    }
}

// Modal de compra
const modal = document.getElementById("modal-compra-sucesso");
const closeBtn = document.querySelector(".close-btn");

function exibirModal() {
    if (modal) modal.style.display = "flex";
}

function fecharModal() {
    if (modal) modal.style.display = "none";
}

// Finalizar compra
function finalizarCompra() {
    const carrinho = cartManager.getItems();
    
    if (carrinho.length === 0) {
        alert("Seu carrinho está vazio!");
        return;
    }

    // Verifica se o usuário está logado
    if (!userManager.isLoggedIn()) {
        if (confirm("Você precisa estar logado para finalizar a compra. Deseja fazer login agora?")) {
            window.location.href = "login.html";
        }
        return;
    }

    // Simula processamento da compra
    const total = cartManager.getTotalValue();
    const user = userManager.getCurrentUser();
    
    // Em uma aplicação real, aqui seria feita a integração com gateway de pagamento
    console.log("Processando compra:", {
        usuario: user.nome,
        itens: carrinho,
        total: total
    });

    // Limpa o carrinho e mostra modal de sucesso
    cartManager.clearCart();
    exibirModal();
    exibirCarrinho();
}

// Event listeners
const finalizarCompraBtn = document.getElementById("finalizar-compra");
if (finalizarCompraBtn) {
    finalizarCompraBtn.addEventListener("click", finalizarCompra);
}

const limparCarrinhoBtn = document.getElementById("limpar-carrinho");
if (limparCarrinhoBtn) {
    limparCarrinhoBtn.addEventListener("click", limparCarrinho);
}

if (closeBtn) {
    closeBtn.addEventListener("click", fecharModal);
}

// Fecha modal ao clicar fora
window.addEventListener("click", (event) => {
    if (event.target === modal) {
        fecharModal();
    }
});

