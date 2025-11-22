<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Acesso negado. Você precisa ser um administrador.']);
    $conn->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
    $conn->close();
    exit;
}

$action = $_POST['action'] ?? null;
$data = [];

if (!$action) {
    $json_data = json_decode(file_get_contents('php://input'), true);
    $action = $json_data['action'] ?? null;
    $data = $json_data;
} else {
    $data = $_POST;
    // Se a requisição for multipart/form-data (upload de arquivo), os dados estão em $_POST
    // Se for JSON, os dados estão em $json_data (já tratado acima)
}

if ($action === 'add_product' || $action === 'edit_product') {
    // Para garantir que a categoria seja tratada como string e não como 0 se vazia
    $categoria = trim($data['categoria'] ?? '');
    if (empty($categoria)) {
        // Se a categoria estiver vazia, pode ser um erro de formulário, mas vamos forçar uma string vazia
        // para evitar que o banco de dados insira 0 se o campo for numérico
        $data['categoria'] = '';
    }
}

if (!$action) {
    echo json_encode(['success' => false, 'message' => 'Ação não especificada.']);
    $conn->close();
    exit;
}

function handle_image_upload($file_key)
{
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {

        $filename = basename($_FILES[$file_key]['name']);
        return "./assets/produtos/" . $filename;
    }
    return './public/placeholder.svg';
}


try {
    switch ($action) {
        case 'fetch_product':
            $id = (int) ($data['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID do produto inválido para busca.');
            }

            $sql = "SELECT id, nome, descricao, preco, categoria, estoque, imagem_url FROM produtos WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                $stmt->close();

                if ($product) {
                    echo json_encode(['success' => true, 'data' => $product]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Produto não encontrado.']);
                }
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            $conn->close();
            exit;

        case 'fetch_employee':
            $id = (int) ($data['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID do funcionário inválido para busca.');
            }

            $sql = "SELECT id, nome, email, telefone, tipo, funcao, veiculo_tipo, placa, endereco FROM equipe WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $employee = $result->fetch_assoc();
                $stmt->close();

                if ($employee) {
                    echo json_encode(['success' => true, 'data' => $employee]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Funcionário não encontrado.']);
                }
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            $conn->close();
            exit;



        case 'add_product':
            $nome = trim($data['nome'] ?? '');
            $descricao = trim($data['descricao'] ?? '');
            $preco = (float) ($data['preco'] ?? 0);
            $categoria = trim($data['categoria'] ?? '');
            $estoque = (int) ($data['estoque'] ?? 0);
            $imagem_url = handle_image_upload('imagem');

            if (empty($nome) || $preco <= 0 || empty($categoria)) {
                throw new Exception('Dados obrigatórios do produto ausentes.');
            }

            $sql = "INSERT INTO produtos (nome, descricao, preco, categoria, estoque, imagem_url) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssdsis", $nome, $descricao, $preco, $categoria, $estoque, $imagem_url);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao adicionar produto: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Produto adicionado com sucesso!']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;

        case 'edit_product':
            $id = (int) ($data['id'] ?? 0);
            $nome = trim($data['nome'] ?? '');
            $descricao = trim($data['descricao'] ?? '');
            $preco = (float) ($data['preco'] ?? 0);
            $categoria = trim($data['categoria'] ?? '');
            $estoque = (int) ($data['estoque'] ?? 0);

            if ($id <= 0 || empty($nome) || $preco <= 0 || empty($categoria)) {
                throw new Exception('Dados obrigatórios para edição ausentes.');
            }

            $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, categoria = ?, estoque = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssdsii", $nome, $descricao, $preco, $categoria, $estoque, $id);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao editar produto: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso!']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;

        case 'delete_product':
            $id = (int) ($data['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID do produto inválido.');
            }

            $sql = "DELETE FROM produtos WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $id);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao deletar produto: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Produto deletado com sucesso!']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;

        case 'add_employee':
        case 'edit_employee':
            $id = ($action === 'edit_employee') ? (int) ($data['id'] ?? 0) : 0;
            $nome = trim($data['nome'] ?? '');
            $email = trim($data['email'] ?? '');
            $telefone = trim($data['telefone'] ?? '');
            $tipo = trim($data['tipo'] ?? '');
            $endereco = trim($data['endereco'] ?? '');

            $funcao = ($tipo === 'funcionario') ? trim($data['funcao'] ?? '') : NULL;
            $veiculo_tipo = ($tipo === 'entregador') ? trim($data['veiculo_tipo'] ?? '') : NULL;
            $placa = ($tipo === 'entregador') ? trim($data['placa'] ?? '') : NULL;

            if (empty($nome) || empty($email) || empty($tipo)) {
                throw new Exception('Dados obrigatórios do funcionário ausentes.');
            }

            if ($action === 'add_employee') {
                $sql = "INSERT INTO equipe (nome, email, telefone, tipo, endereco, funcao, veiculo_tipo, placa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssssssss", $nome, $email, $telefone, $tipo, $endereco, $funcao, $veiculo_tipo, $placa);
                    if (!$stmt->execute()) {
                        throw new Exception('Erro ao adicionar funcionário: ' . $stmt->error);
                    }
                    $stmt->close();
                    echo json_encode(['success' => true, 'message' => 'Funcionário adicionado com sucesso!']);
                } else {
                    throw new Exception('Erro de preparação: ' . $conn->error);
                }
            } else {
                if ($id <= 0)
                    throw new Exception('ID do funcionário inválido.');
                $sql = "UPDATE equipe SET nome = ?, email = ?, telefone = ?, tipo = ?, endereco = ?, funcao = ?, veiculo_tipo = ?, placa = ? WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssssssssi", $nome, $email, $telefone, $tipo, $endereco, $funcao, $veiculo_tipo, $placa, $id);
                    if (!$stmt->execute()) {
                        throw new Exception('Erro ao editar funcionário: ' . $stmt->error);
                    }
                    $stmt->close();
                    echo json_encode(['success' => true, 'message' => 'Funcionário atualizado com sucesso!']);
                } else {
                    throw new Exception('Erro de preparação: ' . $conn->error);
                }
            }
            break;

        case 'delete_employee':
            $id = (int) ($data['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID do funcionário inválido.');
            }

            $sql = "DELETE FROM equipe WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $id);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao deletar funcionário: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Funcionário deletado com sucesso!']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;

        case 'update_site_config':
            $config_data = $data;
            unset($config_data['action']);

            if (empty($config_data)) {
                throw new Exception('Nenhuma configuração para atualizar.');
            }


            $conn->begin_transaction();
            try {
                $sql_delete = "DELETE FROM configuracoes WHERE chave IN ('store_name', 'contact_email', 'phone', 'delivery_fee', 'store_address')";
                $conn->query($sql_delete);

                $sql_insert = "INSERT INTO configuracoes (chave, valor) VALUES (?, ?)";
                if ($stmt = $conn->prepare($sql_insert)) {
                    foreach ($config_data as $chave => $valor) {
                        $stmt->bind_param("ss", $chave, $valor);
                        if (!$stmt->execute()) {
                            throw new Exception("Erro ao atualizar a chave {$chave}: " . $stmt->error);
                        }
                    }
                    $stmt->close();
                } else {
                    throw new Exception('Erro de preparação para atualização de configuração: ' . $conn->error);
                }

                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Configurações do site salvas com sucesso!']);
            } catch (Exception $e) {
                $conn->rollback();
                throw new Exception($e->getMessage());
            }
            break;

        case 'add_promotion':
            $nome = trim($data['promotion_name'] ?? '');
            $desconto = (float) ($data['discount'] ?? 0);
            $data_inicio = trim($data['start_date'] ?? '');
            $data_termino = trim($data['end_date'] ?? '');
            $status = 'Ativa';

            if (empty($nome) || $desconto <= 0 || empty($data_inicio) || empty($data_termino)) {
                throw new Exception('Dados obrigatórios da promoção ausentes.');
            }

            $sql = "INSERT INTO promocoes (nome, desconto, data_inicio, data_termino, status) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sdsss", $nome, $desconto, $data_inicio, $data_termino, $status);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao adicionar promoção: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Promoção adicionada com sucesso!']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;

        case 'delete_promotion':
            $id = (int) ($data['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID da promoção inválido.');
            }

            $sql = "DELETE FROM promocoes WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $id);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao deletar promoção: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Promoção deletada com sucesso!']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;

        case 'add_admin':
            $email = trim($data['admin_email'] ?? '');
            $senha_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
            $isAdmin = 1;

            if (empty($email)) {
                throw new Exception('Email do administrador é obrigatório.');
            }


            $sql = "INSERT INTO usuarios (nome, email, senha_hash, isAdmin) 
                    VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE isAdmin = VALUES(isAdmin)";

            $nome_padrao = "Admin - " . $email;

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sssi", $nome_padrao, $email, $senha_hash, $isAdmin);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao adicionar administrador: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Administrador criado/atualizado com sucesso! (Senha padrão: Doce2025@)']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;

        case 'delete_admin':
            $id = (int) ($data['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID do administrador inválido.');
            }

            if ($id == 1) {
                throw new Exception('Não é possível remover o status de administrador principal (ID 1).');
            }

            $sql = "UPDATE usuarios SET isAdmin = 0 WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $id);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao remover status de administrador: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Status de administrador removido com sucesso.']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;


        case 'update_pedido_status':
            $pedido_id = (int) ($data['pedido_id'] ?? 0);
            $status = trim($data['status'] ?? '');

            if ($pedido_id <= 0 || empty($status)) {
                throw new Exception('Dados inválidos para atualizar status do pedido.');
            }

            $sql = "UPDATE pedidos SET status = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $status, $pedido_id);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao atualizar status do pedido: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Status do pedido atualizado com sucesso!']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;

        case 'update_pedido_entregador':
            $pedido_id = (int) ($data['pedido_id'] ?? 0);
            $entregador_id = isset($data['entregador_id']) && $data['entregador_id'] !== '' ? (int) $data['entregador_id'] : null;

            if ($pedido_id <= 0) {
                throw new Exception('ID do pedido inválido.');
            }

            $sql = "UPDATE pedidos SET entregador_id = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ii", $entregador_id, $pedido_id);
                if (!$stmt->execute()) {
                    throw new Exception('Erro ao atualizar entregador do pedido: ' . $stmt->error);
                }
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Entregador atualizado com sucesso!']);
            } else {
                throw new Exception('Erro de preparação: ' . $conn->error);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Ação desconhecida.']);
            break;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>