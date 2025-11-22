document.addEventListener("DOMContentLoaded", function () {
  function updateStatus() {
    const newStatus = document.getElementById("orderStatus").value;
    alert("Status do pedido alterado para: " + newStatus);
    console.log("Status do pedido alterado para: " + newStatus);
  }

  function sendInvoice() {
    alert("Nota fiscal enviada!");
    console.log("Nota fiscal enviada.");
  }

  window.updateStatus = updateStatus;
  window.sendInvoice = sendInvoice;

  const deliverySelect = document.querySelector(
    ".chart-card:nth-child(3) select"
  );
  const deliveryInfo = document.getElementById("deliveryInfo");

  if (deliverySelect && deliveryInfo) {
    deliverySelect.addEventListener("change", function () {
      if (this.value) {
        deliveryInfo.innerHTML = `
                    <div><strong>Nome:</strong> ${
                      this.options[this.selectedIndex].text.split(" - ")[0]
                    }</div>
                    <div><strong>Veículo:</strong> ${
                      this.options[this.selectedIndex].text.split(" - ")[1]
                    } - ABC-1234 (Simulado)</div>
                `;
        deliveryInfo.style.display = "block";
      } else {
        deliveryInfo.style.display = "none";
      }
    });
  }
});
