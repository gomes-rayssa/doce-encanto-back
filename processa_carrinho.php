<?php
include 'db_config.php';

session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? 'unknown';

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

try {
    switch ($action) {
        case 'update':
            $id = $data['id'];
            $novaQuantidade = (int) $data['quantidade'];

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
            if (!isset($_SESSION['usuario_logado'])) {
                echo json_encode(['success' => false, 'message' => 'Você precisa estar logado.', 'redirect' => 'login.php']);
                exit;
            }

            if (empty($_SESSION['carrinho'])) {
                echo json_encode(['success' => false, 'message' => 'Carrinho vazio.']);
                exit;
            }

            $usuario_id = $_SESSION['usuario_data']['id'] ?? null;
            if (!$usuario_id) {
                echo json_encode(['success' => false, 'message' => 'Usuário não identificado.']);
                exit;
            }

            $metodo_pagamento = $data['metodo_pagamento'] ?? 'credito';
            $parcelas = (int) ($data['parcelas'] ?? 1);

            $valor_total = 0;
            foreach ($_SESSION['carrinho'] as $item) {
                $valor_total += $item['preco'] * $item['quantidade'];
            }

            $taxa_entrega = 10.00;
            $valor_total += $taxa_entrega;

            $conn->begin_transaction();

            try {
                $status = 'Novo';

                $metodo_pagamento_formatado = '';
                switch ($metodo_pagamento) {
                    case 'cartao': // <-- CORREÇÃO: Agora corresponde ao valor enviado pelo carrinho.php
                        $metodo_pagamento_formatado = 'Cartão de Crédito';
                        if ($parcelas > 1) {
                            $metodo_pagamento_formatado .= " ({$parcelas}x)";
                        }
                        break;
                    case 'debito':
                        $metodo_pagamento_formatado = 'Cartão de Débito';
                        break;
                    case 'pix':
                        $metodo_pagamento_formatado = 'PIX';
                        break;
                    case 'dinheiro':
                        $metodo_pagamento_formatado = 'Dinheiro';
                        break;
                    default:
                        $metodo_pagamento_formatado = 'Não informado';
                }

                $sql_pedido = "INSERT INTO pedidos (usuario_id, data_pedido, status, valor_total, metodo_pagamento) VALUES (?, NOW(), ?, ?, ?)";
                $stmt_pedido = $conn->prepare($sql_pedido);
                $stmt_pedido->bind_param("isds", $usuario_id, $status, $valor_total, $metodo_pagamento_formatado);

                if (!$stmt_pedido->execute()) {
                    throw new Exception('Erro ao criar pedido: ' . $stmt_pedido->error);
                }

                $pedido_id = $conn->insert_id;
                $stmt_pedido->close();

                $sql_item = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
                $stmt_item = $conn->prepare($sql_item);

                foreach ($_SESSION['carrinho'] as $produto_id => $item) {
                    $quantidade = $item['quantidade'];
                    $preco_unitario = $item['preco'];

                    $stmt_item->bind_param("iiid", $pedido_id, $produto_id, $quantidade, $preco_unitario);

                    if (!$stmt_item->execute()) {
                        throw new Exception('Erro ao adicionar item do pedido: ' . $stmt_item->error);
                    }

                    $sql_estoque = "UPDATE produtos SET estoque = estoque - ? WHERE id = ?";
                    $stmt_estoque = $conn->prepare($sql_estoque);
                    $stmt_estoque->bind_param("ii", $quantidade, $produto_id);
                    $stmt_estoque->execute();
                    $stmt_estoque->close();
                }

                $stmt_item->close();

                $conn->commit();

                $_SESSION['carrinho'] = [];

                echo json_encode([
                    'success' => true,
                    'message' => 'Pedido realizado com sucesso!',
                    'pedido_id' => $pedido_id,
                    'novoTotalItens' => 0
                ]);

            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }

            break;

        default:
            throw new Exception('Ação desconhecida.');
    }

    if ($action !== 'finalize') {
        $totalItensHeader = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $totalItensHeader += $item['quantidade'];
        }

        echo json_encode([
            'success' => true,
            'novoTotalItens' => $totalItensHeader
        ]);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>