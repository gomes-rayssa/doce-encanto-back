<?php
session_start();
header('Content-Type: application/json');

// Pega os dados JSON enviados pelo fetch
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? 'unknown';

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

try {
    switch ($action) {
        case 'update':
            $id = $data['id'];
            $novaQuantidade = (int)$data['quantidade'];

            if ($novaQuantidade <= 0) {
                unset($_SESSION['carrinho'][$id]);
            } else {
                $_SESSION['carrinho'][$id]['quantidade'] = $novaQuantidade;
            }
            break;

        case 'remove':
            $id = $data['id'];
            unset($_SESSION['carrinho'][$id]);
            break;

        case 'clear':
            $_SESSION['carrinho'] = [];
            break;

        case 'finalize':
            // Verifica se o usuário está logado ANTES de finalizar
            if (!isset($_SESSION['usuario_logado'])) {
                 echo json_encode(['success' => false, 'message' => 'Você precisa estar logado.', 'redirect' => 'login.php']);
                 exit;
            }
            
            // Lógica de finalização (ex: salvar no banco, etc)
            // Por enquanto, apenas limpamos o carrinho
            $_SESSION['carrinho'] = [];
            break;

        default:
            throw new Exception('Ação desconhecida.');
    }

    // Recalcula o total de itens para o header
    $totalItensHeader = 0;
    foreach ($_SESSION['carrinho'] as $item) {
        $totalItensHeader += $item['quantidade'];
    }

    echo json_encode([
        'success' => true, 
        'novoTotalItens' => $totalItensHeader
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>