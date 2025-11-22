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
  window.alterarQuantidade = function (itemId, delta) {
    const quantidadeAtual = parseInt(document.querySelectorAll('.quantidade-valor')[itemId].textContent);
    const novaQuantidade = quantidadeAtual + delta;
    
    if (novaQuantidade < 1) {
      removerItem(itemId);
      return;
    }
    
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

  // Sistema de Pagamento
  const modalPagamento = document.getElementById("modal-pagamento");
  const modalSucesso = document.getElementById("modal-compra-sucesso");
  const formPagamento = document.getElementById("form-pagamento");
  const btnCancelarPagamento = document.querySelector(".btn-cancelar-pagamento");
  const btnFecharSucesso = document.querySelector(".btn-fechar-sucesso");
  const modalClose = document.querySelector(".modal-close");

  // Função para abrir modal de pagamento (chamada pelo botão no HTML)
  window.abrirModalPagamento = function() {
    if (modalPagamento) {
      modalPagamento.style.display = "flex";
      modalPagamento.classList.add("show");
      modalPagamento.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden"; // Prevenir scroll da página
    }
  };

  // Fechar modal de pagamento
  function fecharModalPagamento() {
    if (modalPagamento) {
      modalPagamento.style.display = "none";
      modalPagamento.classList.remove("show");
      modalPagamento.setAttribute("aria-hidden", "true");
      document.body.style.overflow = "auto";
    }
  }

  if (modalClose) {
    modalClose.addEventListener("click", fecharModalPagamento);
  }

  if (btnCancelarPagamento) {
    btnCancelarPagamento.addEventListener("click", fecharModalPagamento);
  }

  // Alternar entre métodos de pagamento
  const metodosPagamento = document.querySelectorAll('input[name="metodo-pagamento"]');
  const pixSection = document.getElementById("pix-section");
  const cartaoSection = document.getElementById("cartao-section");
  
  metodosPagamento.forEach((metodo) => {
    metodo.addEventListener("change", (e) => {
      const metodoPagamento = e.target.value;
      
      if (metodoPagamento === "pix") {
        if (pixSection) {
          pixSection.style.display = "block";
          pixSection.setAttribute("aria-hidden", "false");
        }
        if (cartaoSection) {
          cartaoSection.style.display = "none";
          cartaoSection.setAttribute("aria-hidden", "true");
          // Remover required dos campos de cartão
          cartaoSection.querySelectorAll("input").forEach(input => {
            input.removeAttribute("required");
          });
        }
      } else if (metodoPagamento === "cartao") {
        if (cartaoSection) {
          cartaoSection.style.display = "block";
          cartaoSection.setAttribute("aria-hidden", "false");
          // Adicionar required aos campos de cartão
          cartaoSection.querySelectorAll("input[type='text']").forEach(input => {
            input.setAttribute("required", "true");
          });
        }
        if (pixSection) {
          pixSection.style.display = "none";
          pixSection.setAttribute("aria-hidden", "true");
        }
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

  // Apenas números no CVV
  const cvvInput = document.getElementById("cvv");
  if (cvvInput) {
    cvvInput.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(/\D/g, "");
    });
  }

  // Processar pagamento
  if (formPagamento) {
    formPagamento.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(formPagamento);
      const metodoPagamento = formData.get("metodo-pagamento");
      const parcelas = formData.get("parcelas") || "1";

      // Validar campos de cartão se necessário
      if (metodoPagamento === "cartao") {
        const numeroCartao = formData.get("numero-cartao");
        const nomeCartao = formData.get("nome-cartao");
        const validade = formData.get("validade");
        const cvv = formData.get("cvv");

        if (!numeroCartao || !nomeCartao || !validade || !cvv) {
          alert("Por favor, preencha todos os dados do cartão.");
          return;
        }

        // Validação básica do número do cartão
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
      const btnTextoOriginal = btnConfirmar.innerHTML;
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
          if (modalSucesso) {
            modalSucesso.style.display = "flex";
            modalSucesso.classList.add("show");
            modalSucesso.setAttribute("aria-hidden", "false");
          }

          // Atualizar contador do carrinho
          const cartCountElement = document.querySelector(".cart-count");
          if (cartCountElement) cartCountElement.textContent = "0";
        } else {
          alert(result.message || "Erro ao processar pagamento.");
          btnConfirmar.disabled = false;
          btnConfirmar.innerHTML = btnTextoOriginal;
        }
      } catch (error) {
        console.error("Erro:", error);
        alert("Erro de conexão. Tente novamente.");
        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = btnTextoOriginal;
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

  // Fechar modal com tecla ESC
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      if (modalPagamento && modalPagamento.style.display === "flex") {
        fecharModalPagamento();
      }
    }
  });
});
