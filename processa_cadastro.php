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
    exit;
}

if (empty($data['nome']) || empty($data['email']) || empty($data['celular']) || empty($data['senha'])) {
    echo json_encode(['success' => false, 'message' => 'Dados essenciais ausentes.']);
    exit;
}

$nome           = trim($data['nome']);
$email          = trim($data['email']);
$celular        = trim($data['celular']);
$dataNascimento = $data['dataNascimento'] ?? null;
$senha          = $data['senha'];

$sql_check = "SELECT id FROM usuarios WHERE email = ?";
if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado.']);
        mysqli_stmt_close($stmt_check);
        exit;
    }
    mysqli_stmt_close($stmt_check);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao preparar consulta de verificação de e-mail: ' . mysqli_error($conn)
    ]);
    exit;
}

$celular_limpo = preg_replace('/\D/', '', $celular);

if (!empty($celular) && (strlen($celular_limpo) < 10 || strlen($celular_limpo) > 11)) {
    echo json_encode(['success' => false, 'message' => 'Número de celular inválido.']);
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$sql_user = "INSERT INTO usuarios (nome, email, celular, dataNascimento, senha_hash) VALUES (?, ?, ?, ?, ?)";

if ($stmt_user = mysqli_prepare($conn, $sql_user)) {

    mysqli_stmt_bind_param(
        $stmt_user,
        "sssss",
        $nome,
        $email,
        $celular,
        $dataNascimento,
        $senha_hash
    );

    if (mysqli_stmt_execute($stmt_user)) {
        $usuario_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt_user);

        $endereco = $data['endereco'] ?? [];

        if (!empty($endereco)) {
            $sql_address = "INSERT INTO enderecos (usuario_id, cep, rua, numero, bairro, cidade, estado) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";

            if ($stmt_address = mysqli_prepare($conn, $sql_address)) {

                $cep    = $endereco['cep'] ?? '';
                $rua    = $endereco['rua'] ?? '';
                $numero = $endereco['numero'] ?? '';
                $bairro = $endereco['bairro'] ?? '';
                $cidade = $endereco['cidade'] ?? '';
                $estado = $endereco['estado'] ?? '';

                mysqli_stmt_bind_param(
                    $stmt_address,
                    "issssss",
                    $usuario_id,
                    $cep,
                    $rua,
                    $numero,
                    $bairro,
                    $cidade,
                    $estado
                );

                mysqli_stmt_execute($stmt_address);
                mysqli_stmt_close($stmt_address);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao preparar cadastro de endereço: ' . mysqli_error($conn)
                ]);
                mysqli_close($conn);
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
            'message' => 'Erro ao cadastrar usuário: ' . mysqli_error($conn)
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro de preparação da query de usuário: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>