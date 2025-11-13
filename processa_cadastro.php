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

$sql_check = "SELECT id FROM usuarios WHERE email = ?";
if ($stmt_check = mysqli_prepare($link, $sql_check)) {
    mysqli_stmt_bind_param($stmt_check, "s", $param_email_check);
    $param_email_check = $email;
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado.']);
        mysqli_stmt_close($stmt_check);
        exit;
    }
    mysqli_stmt_close($stmt_check);
}

$celular = $data['celular'] ?? '';
$celular_limpo = preg_replace('/\D/', '', $celular); 

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

$sql_user = "INSERT INTO usuarios (nome, email, celular, dataNascimento, senha_hash) VALUES (?, ?, ?, ?, ?)";

if ($stmt_user = mysqli_prepare($link, $sql_user)) {
    mysqli_stmt_bind_param($stmt_user, "sssss", $param_nome, $param_email, $param_celular, $param_dataNascimento, $param_senha_hash);

    $param_nome = $data['nome'];
    $param_email = $data['email'];
    $param_celular = $data['celular'] ?? '';
    $param_dataNascimento = $data['dataNascimento'] ?? '';
    $param_senha_hash = $senha_hash;

    if (mysqli_stmt_execute($stmt_user)) {
        $usuario_id = mysqli_insert_id($link);
        mysqli_stmt_close($stmt_user);

        $endereco = $data['endereco'] ?? [];
        if (!empty($endereco)) {
            $sql_address = "INSERT INTO enderecos (usuario_id, cep, rua, numero, bairro, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if ($stmt_address = mysqli_prepare($link, $sql_address)) {
                mysqli_stmt_bind_param($stmt_address, "issssss", $param_user_id, $param_cep, $param_rua, $param_numero, $param_bairro, $param_cidade, $param_estado);

                $param_user_id = $usuario_id;
                $param_cep = $endereco['cep'] ?? '';
                $param_rua = $endereco['rua'] ?? '';
                $param_numero = $endereco['numero'] ?? '';
                $param_bairro = $endereco['bairro'] ?? '';
                $param_cidade = $endereco['cidade'] ?? '';
                $param_estado = $endereco['estado'] ?? '';

                mysqli_stmt_execute($stmt_address);
                mysqli_stmt_close($stmt_address);
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso!',
            'usuario_id' => $usuario_id
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao cadastrar usuário: ' . mysqli_error($link)
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro de preparação da query: ' . mysqli_error($link)
    ]);
}
?>