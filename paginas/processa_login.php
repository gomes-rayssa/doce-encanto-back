<?php
session_start();
header('Content-Type: application/json'); // Informa que a resposta é JSON

// Pega os dados JSON enviados pelo fetch do login.js
$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';

// Validação básica
if (empty($email) || empty($senha)) {
  echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
  exit;
}

// Procura o usuário no nosso "banco de dados" de sessão
$usuarioEncontrado = null;
if (isset($_SESSION['lista_usuarios'])) {
  foreach ($_SESSION['lista_usuarios'] as $usuario) {
    if ($usuario['email'] === $email) {
      $usuarioEncontrado = $usuario;
      break;
    }
  }
}

$data = json_decode(file_get_contents('php://input'), true);
// ... (código de validação de email/senha) ...

if ($usuarioEncontrado) {
    if (password_verify($senha, $usuarioEncontrado['senha_hash'])) {
        
        // --- INÍCIO DA MODIFICAÇÃO ---
        
        // Sucesso! Armazena os dados do usuário na sessão
        $_SESSION['usuario_logado'] = true;
        $_SESSION['usuario_data'] = $usuarioEncontrado; // Armazena TODOS os dados

        // VERIFICA SE O USUÁRIO É ADMIN
        if (isset($usuarioEncontrado['isAdmin']) && $usuarioEncontrado['isAdmin'] === true) {
            $_SESSION['is_admin'] = true;
        } else {
            // Garante que a flag não exista se não for admin
            unset($_SESSION['is_admin']); 
        }

        // Envia resposta de sucesso
        echo json_encode([
            'success' => true,
            'message' => 'Login realizado com sucesso! Redirecionando...',
            // Manda o admin para a página de usuário, onde ele verá o link
            'redirect' => 'usuario.php' 
        ]);
        exit;
        // --- FIM DA MODIFICAÇÃO ---

    } else {
        // Senha incorreta
        echo json_encode(['success' => false, 'message' => 'Senha incorreta!']);
        exit;
    }
}
?>