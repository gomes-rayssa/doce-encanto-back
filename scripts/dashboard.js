document.addEventListener("DOMContentLoaded", function () {
  const salesChartElement = document.getElementById("salesChart");
  if (salesChartElement) {
    new Chart(salesChartElement, {
      type: "line",
      data: {
        labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul"],
        datasets: [
          {
            label: "Vendas (R$)",
            data: [12000, 19000, 30000, 25000, 40000, 45890, 42000],
            borderColor: "#990053",
            backgroundColor: "rgba(153, 0, 83, 0.1)",
            tension: 0.3,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  document
    .getElementById("period-filter")
    ?.addEventListener("change", function () {
      console.log("Filtro de período alterado para: " + this.value);
    });
});
