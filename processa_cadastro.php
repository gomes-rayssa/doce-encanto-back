<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include 'db_config.php';

$rawBody = file_get_contents('php://input');
$data = json_decode($rawBody, true);

if ($data === null) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos enviados (JSON mal formatado).'
    ]);
    $conn->close();
    exit;
}

if (empty($data['nome']) || empty($data['email']) || empty($data['celular']) || empty($data['senha'])) {
    echo json_encode(['success' => false, 'message' => 'Dados essenciais ausentes.']);
    $conn->close();
    exit;
}

$nome           = trim($data['nome']);
$email          = trim($data['email']);
$celular        = trim($data['celular']);
$dataNascimento = $data['dataNascimento'] ?? null;
$senha          = $data['senha'];

// Checagem de e-mail único
$sql_check = "SELECT id FROM usuarios WHERE email = ?";
if ($stmt_check = $conn->prepare($sql_check)) {
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado.']);
        $stmt_check->close();
        $conn->close();
        exit;
    }
    $stmt_check->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao preparar consulta de verificação de e-mail: ' . $conn->error
    ]);
    $conn->close();
    exit;
}

$celular_limpo = preg_replace('/\D/', '', $celular);

if (!empty($celular) && (strlen($celular_limpo) < 10 || strlen($celular_limpo) > 11)) {
    echo json_encode(['success' => false, 'message' => 'Número de celular inválido.']);
    $conn->close();
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$sql_user = "INSERT INTO usuarios (nome, email, celular, dataNascimento, senha_hash) VALUES (?, ?, ?, ?, ?)";

if ($stmt_user = $conn->prepare($sql_user)) {

    $stmt_user->bind_param(
        "sssss",
        $nome,
        $email,
        $celular,
        $dataNascimento,
        $senha_hash
    );

    if ($stmt_user->execute()) {
        $usuario_id = $conn->insert_id;
        $stmt_user->close();

        $endereco = $data['endereco'] ?? [];

        if (!empty($endereco)) {
            $sql_address = "INSERT INTO enderecos (usuario_id, cep, rua, numero, bairro, cidade, estado) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";

            if ($stmt_address = $conn->prepare($sql_address)) {

                $cep    = $endereco['cep'] ?? '';
                $rua    = $endereco['rua'] ?? '';
                $numero = $endereco['numero'] ?? '';
                $bairro = $endereco['bairro'] ?? '';
                $cidade = $endereco['cidade'] ?? '';
                $estado = $endereco['estado'] ?? '';

                $stmt_address->bind_param(
                    "issssss",
                    $usuario_id,
                    $cep,
                    $rua,
                    $numero,
                    $bairro,
                    $cidade,
                    $estado
                );

                $stmt_address->execute();
                $stmt_address->close();
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao preparar cadastro de endereço: ' . $conn->error
                ]);
                $conn->close();
                exit;
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso!'
        ]);

    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao cadastrar usuário: ' . $conn->error
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro de preparação da query de usuário: ' . $conn->error
    ]);
}

$conn->close();
?>