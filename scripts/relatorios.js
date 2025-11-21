document.addEventListener("DOMContentLoaded", function () {
  const salesTrendChartElement = document.getElementById("salesTrendChart");
  if (salesTrendChartElement) {
    new Chart(salesTrendChartElement, {
      type: "bar",
      data: {
        labels: ["Semana 1", "Semana 2", "Semana 3", "Semana 4"],
        datasets: [
          {
            label: "Receita por Semana (R$)",
            data: [10000, 12000, 11000, 12890],
            backgroundColor: "#990053",
            borderColor: "#5a0022",
            borderWidth: 1,
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
    .getElementById("period-filter-reports")
    ?.addEventListener("change", function () {
      console.log(
        "Filtro de período de relatórios alterado para: " + this.value
      );
    });
});
