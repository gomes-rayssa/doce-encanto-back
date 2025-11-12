<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_logado'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    exit;
}

$action = $data['action'];
$emailUsuario = $_SESSION['usuario_data']['email'];
$usuarioIndex = -1;
foreach ($_SESSION['lista_usuarios'] as $index => $usuario) {
    if ($usuario['email'] === $emailUsuario) {
        $usuarioIndex = $index;
        break;
    }
}

if ($usuarioIndex === -1) {
    echo json_encode(['success' => false, 'message' => 'Usuário não encontrado.']);
    exit;
}

if ($action === 'save_personal') {
    $novoNome = $data['nome'];
    $novaData = $data['dataNascimento'];
    $novoCelular = $data['celular'] ?? '';

    $_SESSION['usuario_data']['nome'] = $novoNome;
    $_SESSION['usuario_data']['dataNascimento'] = $novaData;
    $_SESSION['usuario_data']['celular'] = $novoCelular;

    $_SESSION['lista_usuarios'][$usuarioIndex]['nome'] = $novoNome;
    $_SESSION['lista_usuarios'][$usuarioIndex]['dataNascimento'] = $novaData;
    $_SESSION['lista_usuarios'][$usuarioIndex]['celular'] = $novoCelular;

    echo json_encode(['success' => true, 'message' => 'Informações pessoais salvas!']);
    exit;
}

if ($action === 'save_address') {
    $novoEndereco = $data['endereco'];

    $_SESSION['usuario_data']['endereco'] = $novoEndereco;

    $_SESSION['lista_usuarios'][$usuarioIndex]['endereco'] = $novoEndereco;

    echo json_encode(['success' => true, 'message' => 'Endereço salvo com sucesso!']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação desconhecida.']);
?>