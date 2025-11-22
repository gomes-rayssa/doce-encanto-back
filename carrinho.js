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

  // Sistema de Pagamento
  const modalPagamento = document.getElementById("modal-pagamento");
  const modalSucesso = document.getElementById("modal-compra-sucesso");
  const finalizarCompraBtn = document.getElementById("finalizar-compra");
  const formPagamento = document.getElementById("form-pagamento");
  const closeModalPagamento = document.querySelector(".close-modal-pagamento");
  const btnCancelarPagamento = document.querySelector(".btn-cancelar-pagamento");
  const btnFecharSucesso = document.querySelector(".btn-fechar-sucesso");
  const dadosCartaoSection = document.getElementById("dados-cartao");
  const parcelasSection = document.getElementById("parcelas-section");

  // Abrir modal de pagamento
  if (finalizarCompraBtn) {
    finalizarCompraBtn.addEventListener("click", () => {
      modalPagamento.style.display = "flex";
      modalPagamento.setAttribute("aria-hidden", "false");
    });
  }

  // Fechar modal de pagamento
  function fecharModalPagamento() {
    modalPagamento.style.display = "none";
    modalPagamento.setAttribute("aria-hidden", "true");
  }

  if (closeModalPagamento) {
    closeModalPagamento.addEventListener("click", fecharModalPagamento);
  }

  if (btnCancelarPagamento) {
    btnCancelarPagamento.addEventListener("click", fecharModalPagamento);
  }

  // Alternar entre métodos de pagamento
  const metodosPagamento = document.querySelectorAll('input[name="metodo"]');
  metodosPagamento.forEach((metodo) => {
    metodo.addEventListener("change", (e) => {
      const metodoPagamento = e.target.value;
      
      if (metodoPagamento === "credito") {
        dadosCartaoSection.style.display = "block";
        parcelasSection.style.display = "block";
        dadosCartaoSection.querySelectorAll("input").forEach(input => {
          input.setAttribute("required", "true");
        });
      } else if (metodoPagamento === "debito") {
        dadosCartaoSection.style.display = "block";
        parcelasSection.style.display = "none";
        dadosCartaoSection.querySelectorAll("input").forEach(input => {
          input.setAttribute("required", "true");
        });
      } else if (metodoPagamento === "entrega") {
        dadosCartaoSection.style.display = "none";
        parcelasSection.style.display = "none";
        dadosCartaoSection.querySelectorAll("input").forEach(input => {
          input.removeAttribute("required");
        });
      }
    });
  });

  // Formatar número do cartão
  const numeroCartaoInput = document.getElementById("numero-cartao");
  if (numeroCartaoInput) {
    numeroCartaoInput.addEventListener("input", (e) => {
      let value = e.target.value.replace(/\s/g, "");
      let formattedValue = value.match(/.{1,4}/g)?.join(" ") || value;
      e.target.value = formattedValue;
    });
  }

  // Formatar validade
  const validadeInput = document.getElementById("validade");
  if (validadeInput) {
    validadeInput.addEventListener("input", (e) => {
      let value = e.target.value.replace(/\D/g, "");
      if (value.length >= 2) {
        value = value.slice(0, 2) + "/" + value.slice(2, 4);
      }
      e.target.value = value;
    });
  }

  // Processar pagamento
  if (formPagamento) {
    formPagamento.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(formPagamento);
      const metodoPagamento = formData.get("metodo");
      const parcelas = formData.get("parcelas") || "1";

      // Validar campos de cartão se necessário
      if (metodoPagamento === "credito" || metodoPagamento === "debito") {
        const numeroCartao = formData.get("numero-cartao");
        const nomeCartao = formData.get("nome-cartao");
        const validade = formData.get("validade");
        const cvv = formData.get("cvv");

        if (!numeroCartao || !nomeCartao || !validade || !cvv) {
          alert("Por favor, preencha todos os dados do cartão.");
          return;
        }

        // Validação básica do número do cartão (deve ter 16 dígitos)
        const numeroLimpo = numeroCartao.replace(/\s/g, "");
        if (numeroLimpo.length < 13 || numeroLimpo.length > 19) {
          alert("Número do cartão inválido.");
          return;
        }

        // Validação da validade
        if (!/^\d{2}\/\d{2}$/.test(validade)) {
          alert("Validade inválida. Use o formato MM/AA.");
          return;
        }

        // Validação do CVV
        if (cvv.length < 3 || cvv.length > 4) {
          alert("CVV inválido.");
          return;
        }
      }

      // Desabilitar botão de confirmação
      const btnConfirmar = document.querySelector(".btn-confirmar-pagamento");
      btnConfirmar.disabled = true;
      btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';

      // Enviar dados para o servidor
      try {
        const response = await fetch("processa_carrinho.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            action: "finalize",
            metodo_pagamento: metodoPagamento,
            parcelas: parcelas,
          }),
        });

        const result = await response.json();

        if (result.success) {
          // Fechar modal de pagamento
          fecharModalPagamento();

          // Atualizar número do pedido no modal de sucesso
          const pedidoNumero = document.querySelector(".pedido-numero strong");
          if (pedidoNumero && result.pedido_id) {
            pedidoNumero.textContent = "#" + result.pedido_id;
          }

          // Exibir modal de sucesso
          modalSucesso.style.display = "flex";
          modalSucesso.setAttribute("aria-hidden", "false");

          // Atualizar contador do carrinho
          const cartCountElement = document.querySelector(".cart-count");
          if (cartCountElement) cartCountElement.textContent = "0";

          // Limpar lista do carrinho
          document.getElementById("lista-carrinho").innerHTML = `
            <div class="carrinho-vazio" role="status">
              <i class="fas fa-check-circle" style="color: green; font-size: 4rem;" aria-hidden="true"></i>
              <h3>Compra realizada com sucesso!</h3>
              <p>Obrigado por comprar conosco.</p>
              <a href="index.php" class="btn-continuar">Continuar Comprando</a>
            </div>`;
          
          document.getElementById("total").textContent = "0,00";
          if (limparCarrinhoBtn) limparCarrinhoBtn.disabled = true;
          finalizarCompraBtn.style.display = "none";
        } else {
          alert(result.message || "Erro ao processar pagamento.");
          btnConfirmar.disabled = false;
          btnConfirmar.innerHTML = '<i class="fas fa-check"></i> Confirmar Pagamento';
        }
      } catch (error) {
        console.error("Erro:", error);
        alert("Erro de conexão. Tente novamente.");
        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = '<i class="fas fa-check"></i> Confirmar Pagamento';
      }
    });
  }

  // Fechar modal de sucesso
  if (btnFecharSucesso) {
    btnFecharSucesso.addEventListener("click", () => {
      window.location.href = "index.php";
    });
  }

  // Fechar modal ao clicar fora
  window.addEventListener("click", (event) => {
    if (event.target === modalPagamento) {
      fecharModalPagamento();
    }
    if (event.target === modalSucesso) {
      window.location.href = "index.php";
    }
  });
});
