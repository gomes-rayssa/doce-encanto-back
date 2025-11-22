const employeeModal = document.getElementById("employeeModal");
let currentEmployeeId = null;
function showAdminNotification(message, type = "success") {
  alert(`${type.toUpperCase()}: ${message}`);
}

async function sendAdminAction(action, data) {
  const formData = new FormData();
  for (const key in data) {
    formData.append(key, data[key]);
  }

  formData.append("action", action);

  try {
    const response = await fetch("processa_admin.php", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.success) {
      showAdminNotification(result.message, "success");
      closeEmployeeModal();
      setTimeout(() => {
        window.location.reload();
      }, 500);
    } else {
      showAdminNotification(result.message, "error");
    }
  } catch (error) {
    console.error("Erro no fetch:", error);
    showAdminNotification("Erro de conexão com o servidor.", "error");
  }
}

async function fetchEmployeeData(id) {
  try {
    const response = await fetch("processa_admin.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "fetch_employee", id: id }),
    });
    const result = await response.json();
    if (result.success && result.data) {
      return result.data;
    } else {
      showAdminNotification(
        result.message || "Erro ao buscar dados do funcionário.",
        "error"
      );
      return null;
    }
  } catch (error) {
    console.error("Erro no fetch:", error);
    showAdminNotification(
      "Erro de conexão ao buscar dados do funcionário.",
      "error"
    );
    return null;
  }
}

async function openEmployeeModal(isEdit = false, id = null) {
  const modalTitle = document.getElementById("modalTitle");
  const form = document.getElementById("employeeForm");

  form.reset();
  currentEmployeeId = id;
  document.getElementById("funcaoField").style.display = "none";
  document.getElementById("veiculoField").style.display = "none";
  document.getElementById("placaField").style.display = "none";

  if (isEdit && id) {
    modalTitle.textContent = "Carregando Funcionário #" + id;
    const employeeData = await fetchEmployeeData(id);

    if (employeeData) {
      modalTitle.textContent = "Editar Funcionário #" + id;
      form.elements["nome"].value = employeeData.nome;
      form.elements["email"].value = employeeData.email;
      form.elements["telefone"].value = employeeData.telefone;
      form.elements["endereco"].value = employeeData.endereco;

      form.elements["tipo"].value = employeeData.tipo;

      toggleVehicleField();

      if (employeeData.tipo === "funcionario") {
        form.elements["funcao"].value = employeeData.funcao;
      } else if (employeeData.tipo === "entregador") {
        form.elements["veiculo_tipo"].value = employeeData.veiculo_tipo;
        form.elements["placa"].value = employeeData.placa;
      }
    } else {
      closeEmployeeModal();
      return;
    }
  } else {
    modalTitle.textContent = "Novo Funcionário";
  }

  employeeModal.classList.add("active");
}

function closeEmployeeModal() {
  employeeModal.classList.remove("active");
}

function toggleVehicleField() {
  const type = document.getElementById("employeeType").value;
  const funcaoField = document.getElementById("funcaoField");
  const veiculoField = document.getElementById("veiculoField");
  const placaField = document.getElementById("placaField");

  if (type === "entregador") {
    funcaoField.style.display = "none";
    veiculoField.style.display = "flex";
    placaField.style.display = "flex";
  } else if (type === "funcionario") {
    funcaoField.style.display = "flex";
    veiculoField.style.display = "none";
    placaField.style.display = "none";
  } else {
    funcaoField.style.display = "none";
    veiculoField.style.display = "none";
    placaField.style.display = "none";
  }
}

document
  .getElementById("employeeForm")
  ?.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    const action = currentEmployeeId ? "edit_employee" : "add_employee";

    formData.append("action", action);
    if (currentEmployeeId) {
      formData.append("id", currentEmployeeId);
    }

    fetch("processa_admin.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((result) => {
        if (result.success) {
          showAdminNotification(result.message, "success");
          closeEmployeeModal();
          setTimeout(() => {
            window.location.reload();
          }, 500);
        } else {
          showAdminNotification(result.message, "error");
        }
      })
      .catch((error) => {
        console.error("Erro no fetch:", error);
        showAdminNotification("Erro de conexão com o servidor.", "error");
      });
  });

function editEmployee(id) {
  openEmployeeModal(true, id);
}

function deleteEmployee(id) {
  if (confirm("Tem certeza que deseja excluir este entregador?")) {
    sendAdminAction("delete_employee", { id: id });
  }
}

function editStaff(id) {
  openEmployeeModal(true, id);
}

function deleteStaff(id) {
  if (confirm("Tem certeza que deseja excluir este funcionário?")) {
    sendAdminAction("delete_employee", { id: id });
  }
}
