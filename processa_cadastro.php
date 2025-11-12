<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['nome']) || empty($data['email']) || empty($data['celular']) || empty($data['senha'])) {
  echo json_encode(['success' => false, 'message' => 'Dados essenciais ausentes.']);
  exit;
}

$email = $data['email'];

if (isset($_SESSION['lista_usuarios'])) {
  foreach ($_SESSION['lista_usuarios'] as $usuario) {
    if ($usuario['email'] === $email) {
      echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado.']);
      exit;
    }
  }
}

$celular = $data['celular'] ?? '';
$celular_limpo = preg_replace('/\D/', '', $celular); // mantém só dígitos

if (!empty($celular)) {
  if (strlen($celular_limpo) < 10 || strlen($celular_limpo) > 11) {
    echo json_encode(['success' => false, 'message' => 'Número de celular inválido.']);
    exit;
  }
}

$senha_hash = password_hash($data['senha'], PASSWORD_DEFAULT);

$novoUsuario = [
  'nome' => $data['nome'],
  'email' => $data['email'],
  "celular" => $data['celular'],
  'dataNascimento' => $data['dataNascimento'],
  'senha_hash' => $senha_hash,
  'endereco' => $data['endereco'] ?? []
];

$_SESSION['lista_usuarios'][] = $novoUsuario;

echo json_encode([
  'success' => true,
  'message' => 'Cadastro realizado com sucesso!'
]);
exit;
?>