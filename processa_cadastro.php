<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

$data = json_decode(file_get_contents('php://input'), true);

// Validação básica
if (empty($data['nome']) || empty($data['email']) || empty($data['celular']) || empty($data['senha'])) {
    echo json_encode(['success' => false, 'message' => 'Dados essenciais ausentes.']);
    exit;
}

$email = $data['email'];

// Verifica se o e-mail já existe
$sql_check = "SELECT id FROM usuarios WHERE email = ?";
if ($stmt_check = mysqli_prepare($link, $sql_check)) {
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado.']);
        mysqli_stmt_close($stmt_check);
        exit;
    }
    mysqli_stmt_close($stmt_check);
}

// Valida celular
$celular = $data['celular'] ?? '';
$celular_limpo = preg_replace('/\D/', '', $celular);

if (!empty($celular) && (strlen($celular_limpo) < 10 || strlen($celular_limpo) > 11)) {
    echo json_encode(['success' => false, 'message' => 'Número de celular inválido.']);
    exit;
}

// Hash da senha
$senha_hash = password_hash($data['senha'], PASSWORD_DEFAULT);

// Insere o usuário
$sql_user = "INSERT INTO usuarios (nome, email, celular, dataNascimento, senha_hash) VALUES (?, ?, ?, ?, ?)";

if ($stmt_user = mysqli_prepare($link, $sql_user)) {
    mysqli_stmt_bind_param($stmt_user, "sssss", 
        $data['nome'],
        $data['email'],
        $data['celular'],
        $data['dataNascimento'],
        $senha_hash
    );

    if (mysqli_stmt_execute($stmt_user)) {
        $usuario_id = mysqli_insert_id($link);
        mysqli_stmt_close($stmt_user);

        // Insere o endereço se fornecido
        $endereco = $data['endereco'] ?? [];
        if (!empty($endereco)) {
            $sql_address = "INSERT INTO enderecos (usuario_id, cep, rua, numero, bairro, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if ($stmt_address = mysqli_prepare($link, $sql_address)) {
                mysqli_stmt_bind_param($stmt_address, "issssss", 
                    $usuario_id,
                    $endereco['cep'] ?? '',
                    $endereco['rua'] ?? '',
                    $endereco['numero'] ?? '',
                    $endereco['bairro'] ?? '',
                    $endereco['cidade'] ?? '',
                    $endereco['estado'] ?? ''
                );
                mysqli_stmt_execute($stmt_address);
                mysqli_stmt_close($stmt_address);
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso!'
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

mysqli_close($link);
?>