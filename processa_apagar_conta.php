<?php
include 'db_config.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_logado'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$emailUsuario = $_SESSION['usuario_data']['email'];
$usuarioIndex = -1;

foreach ($_SESSION['lista_usuarios'] as $index => $usuario) {
    if ($usuario['email'] === $emailUsuario) {
        $usuarioIndex = $index;
        break;
    }
}

if ($usuarioIndex !== -1) {
    array_splice($_SESSION['lista_usuarios'], $usuarioIndex, 1);
}

session_unset();
session_destroy();

echo json_encode(['success' => true, 'message' => 'Conta apagada.']);
exit;
?>