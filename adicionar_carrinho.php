<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['nome']) || !isset($data['preco'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$id = $data['id'];

if (isset($_SESSION['carrinho'][$id])) {
    $_SESSION['carrinho'][$id]['quantidade']++;
} else {
    $_SESSION['carrinho'][$id] = [
        'id' => $data['id'],
        'nome' => $data['nome'],
        'preco' => floatval($data['preco']),
        'imagem' => $data['imagem'] ?? '',
        'categoria' => $data['categoria'] ?? 'produto',
        'quantidade' => 1
    ];
}

$totalItens = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $totalItens += $item['quantidade'];
}

echo json_encode([
    'success' => true,
    'message' => 'Item adicionado ao carrinho!',
    'novoTotalItens' => $totalItens
]);
?>