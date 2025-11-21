<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    $conn->close();
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql_endereco = "DELETE FROM enderecos WHERE usuario_id = ?";
if ($stmt_endereco = $conn->prepare($sql_endereco)) {
    $stmt_endereco->bind_param("i", $usuario_id);
    $stmt_endereco->execute();
    $stmt_endereco->close();
}

$sql_usuario = "DELETE FROM usuarios WHERE id = ?";
if ($stmt_usuario = $conn->prepare($sql_usuario)) {
    $stmt_usuario->bind_param("i", $usuario_id);

    if ($stmt_usuario->execute()) {
        $stmt_usuario->close();

        session_unset();
        session_destroy();

        echo json_encode(['success' => true, 'message' => 'Conta apagada com sucesso.']);
    } else {
        $stmt_usuario->close();
        echo json_encode(['success' => false, 'message' => 'Erro ao apagar conta: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Erro de preparação da query: ' . $conn->error]);
}

$conn->close();
?>