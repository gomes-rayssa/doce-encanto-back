const productModal = document.getElementById("productModal");
let currentProductId = null;

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
      productModal.classList.remove("active");
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

async function fetchProductData(id) {
  try {
    const response = await fetch("processa_admin.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "fetch_product", id: id }),
    });
    const result = await response.json();
    if (result.success && result.data) {
      return result.data;
    } else {
      showAdminNotification(
        result.message || "Erro ao buscar dados do produto.",
        "error"
      );
      return null;
    }
  } catch (error) {
    console.error("Erro no fetch:", error);
    showAdminNotification(
      "Erro de conexão ao buscar dados do produto.",
      "error"
    );
    return null;
  }
}

async function openProductModal(isEdit = false, id = null) {
  const modalTitle = document.getElementById("modalTitle");
  const form = document.getElementById("productForm");

  form.reset();
  document.getElementById("imagePreview").innerHTML =
    '<span style="color: var(--text-light);">Nenhuma imagem selecionada</span>';
  currentProductId = id;

  if (isEdit && id) {
    modalTitle.textContent = "Carregando Produto #" + id;
    const productData = await fetchProductData(id);

    if (productData) {
      modalTitle.textContent = "Editar Produto #" + id;
      form.elements["nome"].value = productData.nome;
      form.elements["descricao"].value = productData.descricao;
      form.elements["preco"].value = parseFloat(productData.preco).toFixed(2);
      form.elements["categoria"].value = productData.categoria;
      form.elements["estoque"].value = productData.estoque;

      if (
        productData.imagem_url &&
        productData.imagem_url !== "../public/placeholder.svg"
      ) {
        const preview = document.getElementById("imagePreview");
        preview.innerHTML = "";
        const img = document.createElement("img");
        img.src = productData.imagem_url;
        img.alt = "Preview do Produto";
        preview.appendChild(img);
      }
    } else {
      closeProductModal();
      return;
    }
  } else {
    modalTitle.textContent = "Novo Produto";
  }

  productModal.classList.add("active");
}

function closeProductModal() {
  productModal.classList.remove("active");
}

function previewImage(event) {
  const preview = document.getElementById("imagePreview");
  preview.innerHTML = "";
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = document.createElement("img");
      img.src = e.target.result;
      img.alt = "Preview do Produto";
      preview.appendChild(img);
    };
    reader.readAsDataURL(file);
  } else {
    preview.innerHTML =
      '<span style="color: var(--text-light);">Nenhuma imagem selecionada</span>';
  }
}

document
  .getElementById("productForm")
  ?.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    const action = currentProductId ? "edit_product" : "add_product";

    formData.append("action", action);
    if (currentProductId) {
      formData.append("id", currentProductId);
    }

    const imageInput = document.querySelector('input[name="imagem"]');
    if (imageInput && imageInput.files.length > 0) {
      formData.append("imagem", imageInput.files[0]);
    }

    fetch("processa_admin.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((result) => {
        if (result.success) {
          showAdminNotification(result.message, "success");
          closeProductModal();
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

function editProduct(id) {
  openProductModal(true, id);
}

function deleteProduct(id) {
  if (confirm("Tem certeza que deseja excluir este produto?")) {
    sendAdminAction("delete_product", { id: id });
  }
}
