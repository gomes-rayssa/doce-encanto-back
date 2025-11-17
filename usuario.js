document.addEventListener("DOMContentLoaded", function () {
  const campos = {
    nome: document.getElementById("nome-completo"),
    email: document.getElementById("email"),
    celular: document.getElementById("celular"),
    dataNascimento: document.getElementById("data-nascimento"),
    cep: document.getElementById("cep"),
    rua: document.getElementById("rua"),
    numero: document.getElementById("numero"),
    bairro: document.getElementById("bairro"),
    cidade: document.getElementById("cidade"),
    estado: document.getElementById("estado"),
  };

  const originalValues = {};
  Object.keys(campos).forEach((key) => {
    if (campos[key]) {
      originalValues[key] = campos[key].value;
    }
  });

  // Informações Pessoais
  const editBtn = document.getElementById("edit-btn");
  const saveBtn = document.getElementById("save-btn");
  const cancelBtn = document.getElementById("cancel-btn");
  const editActions = document.getElementById("edit-actions");
  const logoutBtn = document.getElementById("logout-btn");

  // Endereço
  const editAddressBtn = document.getElementById("edit-address-btn");
  const addressEditActions = document.getElementById("address-edit-actions");
  const saveAddressBtn = document.getElementById("save-address-btn");
  const cancelAddressBtn = document.getElementById("cancel-address-btn");

  const deleteBtn = document.getElementById("delete-btn");

  // Pop-up e Notificação
  const customPopup = document.getElementById("custom-popup");
  const popupMessage = document.getElementById("popup-message");
  const notification = document.getElementById("notification");
  const notificationMessage = document.getElementById("notification-message");

  function showNotification(message, type = "success") {
    notificationMessage.textContent = message;
    notification.classList.remove("hidden", "success", "error");
    notification.classList.add(type);
    notification.offsetHeight;
    notification.classList.add("show");

    setTimeout(() => {
      notification.classList.remove("show");
      setTimeout(() => notification.classList.add("hidden"), 500);
    }, 3000);
  }

  function restaurarValores() {
    Object.keys(campos).forEach((key) => {
      if (campos[key]) {
        campos[key].value = originalValues[key];
      }
    });
  }

  function atualizarBackup() {
    Object.keys(campos).forEach((key) => {
      if (campos[key]) {
        originalValues[key] = campos[key].value;
      }
    });
  }

  function setReadonly(inputs, valor) {
    inputs.forEach((input) => {
      if (input) {
        input.readOnly = valor;
      }
    });
  }

  function showPopup(message, callbackYes) {
    popupMessage.textContent = message;
    customPopup.classList.remove("hidden");

    const oldYesBtn = document.getElementById("popup-yes-btn");
    const oldNoBtn = document.getElementById("popup-no-btn");
    const newYesBtn = oldYesBtn.cloneNode(true);
    const newNoBtn = oldNoBtn.cloneNode(true);
    oldYesBtn.replaceWith(newYesBtn);
    oldNoBtn.replaceWith(newNoBtn);

    newYesBtn.addEventListener(
      "click",
      () => {
        customPopup.classList.add("hidden");
        callbackYes();
      },
      { once: true }
    );

    newNoBtn.addEventListener(
      "click",
      () => {
        customPopup.classList.add("hidden");
      },
      { once: true }
    );
  }

  setReadonly(Object.values(campos), true);

  if (editBtn) {
    editBtn.addEventListener("click", () => {
      const personalFields = [campos.nome, campos.celular, campos.dataNascimento];
      setReadonly(personalFields, false);
      editActions.classList.remove("hidden");
      editBtn.classList.add("hidden");
      if (logoutBtn) logoutBtn.classList.add("hidden");
      if (editAddressBtn) editAddressBtn.classList.add("hidden");
      if (campos.nome) campos.nome.focus();
    });
  }

  if (cancelBtn) {
    cancelBtn.addEventListener("click", () => {
      const personalFields = [campos.nome, campos.celular, campos.dataNascimento];
      restaurarValores();
      setReadonly(personalFields, true);
      editActions.classList.add("hidden");
      editBtn.classList.remove("hidden");
      if (logoutBtn) logoutBtn.classList.remove("hidden");
      if (editAddressBtn) editAddressBtn.classList.remove("hidden");
    });
  }

  if (saveBtn) {
    saveBtn.addEventListener("click", async () => {
      const personalFields = [campos.nome, campos.celular, campos.dataNascimento];

      const data = {
        action: "save_personal",
        nome: campos.nome.value,
        celular: campos.celular.value,
        dataNascimento: campos.dataNascimento.value,
      };

      try {
        const response = await fetch("processa_perfil.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
        });
        const result = await response.json();

        if (result.success) {
          showNotification(result.message, "success");
          setReadonly(personalFields, true);
          editActions.classList.add("hidden");
          editBtn.classList.remove("hidden");
          if (logoutBtn) logoutBtn.classList.remove("hidden");
          if (editAddressBtn) editAddressBtn.classList.remove("hidden");
          atualizarBackup();
        } else {
          showNotification(result.message, "error");
        }
      } catch (error) {
        showNotification("Erro de conexão ao salvar.", "error");
      }
    });
  }

  if (editAddressBtn) {
    editAddressBtn.addEventListener("click", () => {
      const addressFields = [
        campos.cep,
        campos.rua,
        campos.numero,
        campos.bairro,
        campos.cidade,
        campos.estado,
      ];
      setReadonly(addressFields, false);
      editAddressBtn.classList.add("hidden");
      addressEditActions.classList.remove("hidden");
      if (editBtn) editBtn.classList.add("hidden");
      if (logoutBtn) logoutBtn.classList.add("hidden");
      if (campos.cep) campos.cep.focus();
    });
  }

  if (cancelAddressBtn) {
    cancelAddressBtn.addEventListener("click", () => {
      const addressFields = [
        campos.cep,
        campos.rua,
        campos.numero,
        campos.bairro,
        campos.cidade,
        campos.estado,
      ];
      restaurarValores();
      setReadonly(addressFields, true);
      if (editAddressBtn) editAddressBtn.classList.remove("hidden");
      addressEditActions.classList.add("hidden");
      if (editBtn) editBtn.classList.remove("hidden");
      if (logoutBtn) logoutBtn.classList.remove("hidden");
    });
  }

  if (saveAddressBtn) {
    saveAddressBtn.addEventListener("click", async () => {
      const addressFields = [
        campos.cep,
        campos.rua,
        campos.numero,
        campos.bairro,
        campos.cidade,
        campos.estado,
      ];

      const data = {
        action: "save_address",
        endereco: {
          cep: campos.cep.value,
          rua: campos.rua.value,
          numero: campos.numero.value,
          bairro: campos.bairro.value,
          cidade: campos.cidade.value,
          estado: campos.estado.value,
        },
      };

      try {
        const response = await fetch("processa_perfil.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
        });
        const result = await response.json();

        if (result.success) {
          showNotification(result.message, "success");
          setReadonly(addressFields, true);
          if (editAddressBtn) editAddressBtn.classList.remove("hidden");
          addressEditActions.classList.add("hidden");
          if (editBtn) editBtn.classList.remove("hidden");
          if (logoutBtn) logoutBtn.classList.remove("hidden");
          atualizarBackup();
        } else {
          showNotification(result.message, "error");
        }
      } catch (error) {
        showNotification("Erro de conexão ao salvar endereço.", "error");
      }
    });
  }

  if (logoutBtn) {
    logoutBtn.addEventListener("click", (e) => {
      e.preventDefault();

      showPopup("Deseja realmente sair da conta?", () => {
        showNotification("Saindo...", "success");
        setTimeout(() => {
          window.location.href = logoutBtn.href;
        }, 1000);
      });
    });
  }

  if (deleteBtn) {
    deleteBtn.addEventListener("click", () => {
      showPopup(
        "Deseja apagar sua conta permanentemente? Esta ação é irreversível.",
        async () => {
          try {
            const response = await fetch("processa_apagar_conta.php", {
              method: "POST",
            });
            const result = await response.json();

            if (result.success) {
              showNotification(
                "Sua conta foi apagada. Redirecionando...",
                "error"
              );
              setTimeout(() => {
                window.location.href = "../index.php";
              }, 3000);
            } else {
              showNotification(result.message, "error");
            }
          } catch (error) {
            showNotification("Erro de conexão.", "error");
          }
        }
      );
    });
  }
});