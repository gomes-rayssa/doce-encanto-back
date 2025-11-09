<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_logado'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$emailUsuario = $_SESSION['usuario_data']['email'];
$usuarioIndex = -1;

// Encontra o usuário na "lista de usuários"
foreach ($_SESSION['lista_usuarios'] as $index => $usuario) {
    if ($usuario['email'] === $emailUsuario) {
        $usuarioIndex = $index;
        break;
    }
}

// Remove o usuário da "tabela"
if ($usuarioIndex !== -1) {
    array_splice($_SESSION['lista_usuarios'], $usuarioIndex, 1);
}

// Destrói a sessão (faz logout completo)
session_unset();
session_destroy();

echo json_encode(['success' => true, 'message' => 'Conta apagada.']);
exit;
?>