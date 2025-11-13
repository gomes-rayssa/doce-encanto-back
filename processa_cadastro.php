<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

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

$sql = "INSERT INTO usuarios (nome, email, celular, dataNascimento, senha_hash) VALUES (?, ?, ?, ?, ?)";

if ($stmt = mysqli_prepare($link, $sql)) {
  // Vincula as variáveis à instrução preparada como parâmetros
  mysqli_stmt_bind_param($stmt, "sssss", $param_nome, $param_email, $param_celular, $param_dataNascimento, $param_senha_hash);

  // Define os parâmetros
  $param_nome = $data['nome'];
  $param_email = $data['email'];
  $param_celular = $data['celular'] ?? '';
  $param_dataNascimento = $data['dataNascimento'] ?? NULL;
  $param_senha_hash = $senha_hash;

  // Tenta executar a instrução preparada
  if (mysqli_stmt_execute($stmt)) {
    // Sucesso
    echo json_encode([
      'success' => true,
      'message' => 'Cadastro realizado com sucesso!',
      'usuario_id' => mysqli_insert_id($link)
    ]);
  } else {
    // Erro
    echo json_encode([
      'success' => false,
      'message' => 'Erro ao cadastrar usuário: ' . mysqli_error($link)
    ]);
  }

  // Fecha a instrução
  mysqli_stmt_close($stmt);
}
?>