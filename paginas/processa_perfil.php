<?php
session_start();
header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_logado'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

// Pega os dados JSON enviados pelo fetch
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    exit;
}

$action = $data['action'];
$emailUsuario = $_SESSION['usuario_data']['email']; // ID do usuário

// Encontra o usuário na "lista de usuários" (nosso banco dedados simulado)
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

// Ação para salvar dados pessoais
if ($action === 'save_personal') {
    $novoNome = $data['nome'];
    $novaData = $data['dataNascimento'];

    // Atualiza os dados na sessão
    $_SESSION['usuario_data']['nome'] = $novoNome;
    $_SESSION['usuario_data']['dataNascimento'] = $novaData;
    
    // Atualiza na "tabela" de usuários
    $_SESSION['lista_usuarios'][$usuarioIndex]['nome'] = $novoNome;
    $_SESSION['lista_usuarios'][$usuarioIndex]['dataNascimento'] = $novaData;

    echo json_encode(['success' => true, 'message' => 'Informações pessoais salvas!']);
    exit;
}

// Ação para salvar endereço
if ($action === 'save_address') {
    $novoEndereco = $data['endereco'];

    // Atualiza os dados na sessão
    $_SESSION['usuario_data']['endereco'] = $novoEndereco;

    // Atualiza na "tabela" de usuários
    $_SESSION['lista_usuarios'][$usuarioIndex]['endereco'] = $novoEndereco;

    echo json_encode(['success' => true, 'message' => 'Endereço salvo com sucesso!']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação desconhecida.']);
?>