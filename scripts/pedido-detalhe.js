document.addEventListener("DOMContentLoaded", function () {
  
  async function updateStatus(pedidoId) {
    const newStatus = document.getElementById("orderStatus").value;
    
    if (!confirm(`Deseja alterar o status do pedido para "${newStatus}"?`)) {
      return;
    }

    try {
      const response = await fetch("processa_admin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          action: "update_pedido_status",
          pedido_id: pedidoId,
          status: newStatus,
        }),
      });

      const result = await response.json();

      if (result.success) {
        alert("Status do pedido atualizado com sucesso!");
        window.location.reload();
      } else {
        alert("Erro: " + (result.message || "Não foi possível atualizar o status."));
      }
    } catch (error) {
      console.error("Erro:", error);
      alert("Erro de conexão. Tente novamente.");
    }
  }

  async function updateEntregador(pedidoId) {
    const entregadorId = document.getElementById("entregadorSelect").value;
    
    if (!entregadorId) {
      if (!confirm("Deseja remover o entregador deste pedido?")) {
        return;
      }
    }

    try {
      const response = await fetch("processa_admin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          action: "update_pedido_entregador",
          pedido_id: pedidoId,
          entregador_id: entregadorId || null,
        }),
      });

      const result = await response.json();

      if (result.success) {
        alert("Entregador atualizado com sucesso!");
        window.location.reload();
      } else {
        alert("Erro: " + (result.message || "Não foi possível atualizar o entregador."));
      }
    } catch (error) {
      console.error("Erro:", error);
      alert("Erro de conexão. Tente novamente.");
    }
  }

  // Expor funções globalmente
  window.updateStatus = updateStatus;
  window.updateEntregador = updateEntregador;
});
