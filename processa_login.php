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
if (isset($_SESSION['lista_usuarios'])) {
    foreach ($_SESSION['lista_usuarios'] as $usuario) {
        if ($usuario['email'] === $email) {
            $usuarioEncontrado = $usuario;
            break;
        }
    }
}

if (!$usuarioEncontrado) {
    echo json_encode(['success' => false, 'message' => 'E-mail não cadastrado!']);
    exit;
}

if (password_verify($senha, $usuarioEncontrado['senha_hash'])) {
    $_SESSION['usuario_logado'] = true;
    $_SESSION['usuario_data'] = $usuarioEncontrado;

    if (isset($usuarioEncontrado['isAdmin']) && $usuarioEncontrado['isAdmin'] === true) {
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