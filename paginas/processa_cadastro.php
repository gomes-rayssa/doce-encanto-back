<?php
session_start();
header('Content-Type: application/json');

// Pega os dados JSON enviados pelo fetch do cadastro.js
$data = json_decode(file_get_contents('php://input'), true);

// --- Validação no Lado do Servidor (essencial!) ---
if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
    echo json_encode(['success' => false, 'message' => 'Dados essenciais ausentes.']);
    exit;
}

$email = $data['email'];

// 1. Verifica se o e-mail já está em uso
if (isset($_SESSION['lista_usuarios'])) {
  foreach ($_SESSION['lista_usuarios'] as $usuario) {
    if ($usuario['email'] === $email) {
      echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado.']);
      exit;
    }
  }
}

// 2. Criptografa a senha (NUNCA salve senhas em texto puro!)
$senha_hash = password_hash($data['senha'], PASSWORD_DEFAULT);

// 3. Monta o objeto do novo usuário
$novoUsuario = [
  'nome' => $data['nome'],
  'email' => $data['email'],
  'dataNascimento' => $data['dataNascimento'],
  'senha_hash' => $senha_hash, // Salva o hash, não a senha!
  'endereco' => $data['endereco'] ?? []
];

// 4. "Salva" o usuário na sessão
$_SESSION['lista_usuarios'][] = $novoUsuario;

// 5. Responde com sucesso
echo json_encode([
  'success' => true,
  'message' => 'Cadastro realizado com sucesso!'
]);
exit;
?>