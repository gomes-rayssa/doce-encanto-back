document.addEventListener("DOMContentLoaded", function() {
    // exibir dados
    function loadUserDataForProfile() {
        const storedData = localStorage.getItem("cadastroFormData");
        if (storedData) {
            const userData = JSON.parse(storedData);

            document.getElementById("welcomeName").textContent = userData.nome || "Usuário";
            document.getElementById("userName").textContent = userData.nome || "Não informado";
            document.getElementById("userEmail").textContent = userData.email || "Não informado";
            document.getElementById("userDob").textContent = userData.dob || "Não informado";
            document.getElementById("userCep").textContent = userData.cep || "Não informado";

            let fullAddress = [];
            if (userData.rua) fullAddress.push(userData.rua);
            if (userData.bairro) fullAddress.push(userData.bairro);
            if (userData.cidade && userData.uf) fullAddress.push(`${userData.cidade} - ${userData.uf}`);
            else if (userData.cidade) fullAddress.push(userData.cidade);
            else if (userData.uf) fullAddress.push(userData.uf);

            document.getElementById("userAddress").textContent = fullAddress.join(", ") || "Não informado";
            document.getElementById("userCity").textContent = userData.cidade || "Não informado";

        } else {
            console.log("Nenhum dado de usuário encontrado no localStorage.");
        }
    }

    loadUserDataForProfile();

  });