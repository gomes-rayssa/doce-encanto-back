<?php
include 'db_config.php';

session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
    exit;
}

$usuarioEncontrado = null;

$sql = "SELECT id, nome, email, celular, dataNascimento, senha_hash, isAdmin FROM usuarios WHERE email = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $param_email);
    $param_email = $email;

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $id, $nome, $email_db, $celular, $dataNascimento, $senha_hash, $isAdmin);

            if (mysqli_stmt_fetch($stmt)) {
                $usuarioEncontrado = [
                    'id' => $id,
                    'nome' => $nome,
                    'email' => $email_db,
                    'celular' => $celular,
                    'dataNascimento' => $dataNascimento,
                    'senha_hash' => $senha_hash,
                    'isAdmin' => $isAdmin
                ];
            }
        }
    }
    mysqli_stmt_close($stmt);
}

if (!$usuarioEncontrado) {
    echo json_encode(['success' => false, 'message' => 'E-mail ou senha incorretos!']); 
    exit;
}

if (password_verify($senha, $usuarioEncontrado['senha_hash'])) {
    $_SESSION['usuario_logado'] = true;

    unset($usuarioEncontrado['senha_hash']);
    $_SESSION['usuario_data'] = $usuarioEncontrado;

    if ($usuarioEncontrado['isAdmin'] == 1) {
        $_SESSION['is_admin'] = true;
    } else {
        unset($_SESSION['is_admin']);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Login realizado com sucesso!',
        'redirect' => 'usuario.php'
    ]);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Senha incorreta!']);
    exit;
}
?>