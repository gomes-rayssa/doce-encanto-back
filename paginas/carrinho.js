document.addEventListener("DOMContentLoaded", function () {
  async function enviarAcaoCarrinho(data, recarregar = true) {
    try {
      const response = await fetch("processa_carrinho.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.success) {
        if (recarregar) {
          window.location.reload();
        }
        return result;
      } else {
        alert(
          "Erro: " +
            (result.message || "Não foi possível atualizar o carrinho.")
        );
        if (result.redirect) {
          window.location.href = result.redirect;
        }
        return null;
      }
    } catch (error) {
      alert("Erro de conexão. Tente novamente.");
      return null;
    }
  }

  // Alterar quantidade de um item
  window.alterarQuantidade = function (itemId, novaQuantidade) {
    enviarAcaoCarrinho({
      action: "update",
      id: itemId,
      quantidade: novaQuantidade,
    });
  };

  // Remover item do carrinho
  window.removerItem = function (itemId) {
    if (confirm("Tem certeza que deseja remover este item?")) {
      enviarAcaoCarrinho({
        action: "remove",
        id: itemId,
      });
    }
  };

  // Limpar carrinho
  const limparCarrinhoBtn = document.getElementById("limpar-carrinho");
  if (limparCarrinhoBtn) {
    limparCarrinhoBtn.addEventListener("click", () => {
      if (confirm("Tem certeza que deseja limpar todo o carrinho?")) {
        enviarAcaoCarrinho({ action: "clear" });
      }
    });
  }

  const modal = document.getElementById("modal-compra-sucesso");
  const closeBtn = document.querySelector(".close-btn");

  function exibirModal() {
    if (modal) modal.style.display = "flex";
  }

  function fecharModal() {
    if (modal) modal.style.display = "none";
    window.location.href = "../index.php";
  }

  const finalizarCompraBtn = document.getElementById("finalizar-compra");
  if (finalizarCompraBtn) {
    finalizarCompraBtn.addEventListener("click", async () => {
      finalizarCompraBtn.disabled = true;
      finalizarCompraBtn.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> Processando...';

      const result = await enviarAcaoCarrinho({ action: "finalize" }, false); // Envia sem recarregar

      if (result && result.success) {
        exibirModal();
        const cartCountElement = document.querySelector(".cart-count");
        if (cartCountElement) cartCountElement.textContent = "0";
        document.getElementById("lista-carrinho").innerHTML = `
                    <div class="carrinho-vazio">
                        <i class="fas fa-check-circle" style="color: green; font-size: 4rem;"></i>
                        <h3>Compra realizada com sucesso!</h3>
                        <p>Obrigado por comprar conosco.</p>
                        <a href="../index.php" class="btn-continuar">Continuar Comprando</a>
                    </div>`;
        document.getElementById("total").textContent = "0,00";
        if (limparCarrinhoBtn) limparCarrinhoBtn.disabled = true;
        finalizarCompraBtn.remove();
      } else {
        finalizarCompraBtn.disabled = false;
        finalizarCompraBtn.innerHTML =
          '<i class="fas fa-credit-card"></i> Finalizar Compra';
      }
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener("click", fecharModal);
  }
  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      fecharModal();
    }
  });
});
