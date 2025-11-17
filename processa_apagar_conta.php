<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Deleta o endereço primeiro (por causa da foreign key)
$sql_endereco = "DELETE FROM enderecos WHERE usuario_id = ?";
if ($stmt_endereco = mysqli_prepare($link, $sql_endereco)) {
    mysqli_stmt_bind_param($stmt_endereco, "i", $usuario_id);
    mysqli_stmt_execute($stmt_endereco);
    mysqli_stmt_close($stmt_endereco);
}

// Deleta o usuário
$sql_usuario = "DELETE FROM usuarios WHERE id = ?";
if ($stmt_usuario = mysqli_prepare($link, $sql_usuario)) {
    mysqli_stmt_bind_param($stmt_usuario, "i", $usuario_id);
    
    if (mysqli_stmt_execute($stmt_usuario)) {
        mysqli_stmt_close($stmt_usuario);
        
        // Destroi a sessão
        session_unset();
        session_destroy();
        
        echo json_encode(['success' => true, 'message' => 'Conta apagada com sucesso.']);
    } else {
        mysqli_stmt_close($stmt_usuario);
        echo json_encode(['success' => false, 'message' => 'Erro ao apagar conta: ' . mysqli_error($link)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Erro de preparação da query']);
}

mysqli_close($link);
?>