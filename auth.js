// Funções de autenticação para o sistema Doce Encanto

// Classe para gerenciar usuários
class UserManager {
    constructor() {
        this.users = this.loadUsers();
        this.currentUser = this.loadCurrentUser();
    }

    // Carrega usuários do localStorage
    loadUsers() {
        const users = localStorage.getItem('doceEncanto_users');
        return users ? JSON.parse(users) : [];
    }

    // Salva usuários no localStorage
    saveUsers() {
        localStorage.setItem('doceEncanto_users', JSON.stringify(this.users));
    }

    // Carrega usuário atual do localStorage
    loadCurrentUser() {
        const user = localStorage.getItem('doceEncanto_currentUser');
        return user ? JSON.parse(user) : null;
    }

    // Salva usuário atual no localStorage
    saveCurrentUser(user) {
        localStorage.setItem('doceEncanto_currentUser', JSON.stringify(user));
        this.currentUser = user;
    }

    // Remove usuário atual do localStorage
    clearCurrentUser() {
        localStorage.removeItem('doceEncanto_currentUser');
        this.currentUser = null;
    }

    // Registra um novo usuário
    register(userData) {
        // Verifica se o email já existe
        if (this.users.find(user => user.email === userData.email)) {
            throw new Error('Email já cadastrado');
        }

        // Cria novo usuário
        const newUser = {
            id: Date.now().toString(),
            nome: userData.nome,
            email: userData.email,
            dob: userData.dob,
            cep: userData.cep,
            rua: userData.rua,
            bairro: userData.bairro,
            cidade: userData.cidade,
            uf: userData.uf,
            senha: userData.senha, // Em produção, seria hash da senha
            createdAt: new Date().toISOString()
        };

        this.users.push(newUser);
        this.saveUsers();
        return newUser;
    }

    // Faz login do usuário
    login(email, senha) {
        const user = this.users.find(u => u.email === email && u.senha === senha);
        if (!user) {
            throw new Error('Email ou senha incorretos');
        }

        this.saveCurrentUser(user);
        return user;
    }

    // Faz logout do usuário
    logout() {
        this.clearCurrentUser();
    }

    // Verifica se há usuário logado
    isLoggedIn() {
        return this.currentUser !== null;
    }

    // Retorna usuário atual
    getCurrentUser() {
        return this.currentUser;
    }

    // Atualiza dados do usuário
    updateUser(userData) {
        if (!this.currentUser) {
            throw new Error('Usuário não logado');
        }

        const userIndex = this.users.findIndex(u => u.id === this.currentUser.id);
        if (userIndex === -1) {
            throw new Error('Usuário não encontrado');
        }

        // Atualiza dados
        this.users[userIndex] = { ...this.users[userIndex], ...userData };
        this.saveUsers();
        this.saveCurrentUser(this.users[userIndex]);
        
        return this.users[userIndex];
    }
}

// Instância global do gerenciador de usuários
const userManager = new UserManager();

// Função para atualizar a navegação baseada no status de login
function updateNavigation() {
    const userLinks = document.querySelectorAll('a[href*="login.html"], a[href*="usuario.html"]');
    
    userLinks.forEach(userLink => {
        if (userManager.isLoggedIn()) {
            const user = userManager.getCurrentUser();
            userLink.href = userLink.href.includes('paginas/') ? 'usuario.html' : 'paginas/usuario.html';
            userLink.title = `Logado como ${user.nome}`;
        } else {
            userLink.href = userLink.href.includes('paginas/') ? 'login.html' : 'paginas/login.html';
            userLink.title = 'Fazer login';
        }
    });
}

// Função para redirecionar se não estiver logado
function requireLogin() {
    if (!userManager.isLoggedIn()) {
        alert('Você precisa fazer login para acessar esta página');
        window.location.href = 'login.html';
        return false;
    }
    return true;
}

// Atualiza navegação quando a página carrega
document.addEventListener('DOMContentLoaded', updateNavigation);



// auth.js

document.addEventListener('DOMContentLoaded', function() {
  const userActionLink = document.querySelector('.nav-action.user-action');

  function updateNavbar() {
    // Verifica se o usuário está logado
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    
    // Altera o link com base no status de login
    if (isLoggedIn) {
      userActionLink.href = '../paginas/usuario.html';
      userActionLink.title = 'Minha Conta';
    } else {
      userActionLink.href = '../paginas/cadastro.html';
      userActionLink.title = 'Minha Conta';
    }
  }

  // Chama a função ao carregar a página
  updateNavbar();

  // Opcional: Adicionar um evento de logout se você tiver um botão de saída
  // const logoutButton = document.getElementById('logout-button');
  // if (logoutButton) {
  //   logoutButton.addEventListener('click', function() {
  //     localStorage.removeItem('isLoggedIn');
  //     window.location.href = '../index.html';
  //   });
  // }
});