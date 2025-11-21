<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    $conn->close();
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    $conn->close();
    exit;
}

$action = $data['action'];
$usuario_id = $_SESSION['usuario_id'];

if ($action === 'save_personal') {
    $sql = "UPDATE usuarios SET nome = ?, celular = ?, dataNascimento = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "sssi",
            $data['nome'],
            $data['celular'],
            $data['dataNascimento'],
            $usuario_id
        );

        if ($stmt->execute()) {
            $_SESSION['usuario_data']['nome'] = $data['nome'];
            $_SESSION['usuario_data']['celular'] = $data['celular'];
            $_SESSION['usuario_data']['dataNascimento'] = $data['dataNascimento'];

            $stmt->close();
            echo json_encode(['success' => true, 'message' => 'Informações pessoais atualizadas!']);
        } else {
            $stmt->close();
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro de preparação da query']);
    }
    $conn->close();
    exit;
}

if ($action === 'save_address') {
    $endereco = $data['endereco'];

    $sql_check = "SELECT id FROM enderecos WHERE usuario_id = ?";
    $endereco_existe = false;

    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("i", $usuario_id);
        $stmt_check->execute();
        $stmt_check->store_result();
        $endereco_existe = $stmt_check->num_rows > 0;
        $stmt_check->close();
    }

    if ($endereco_existe) {
        $sql = "UPDATE enderecos SET cep = ?, rua = ?, numero = ?, bairro = ?, cidade = ?, estado = ? WHERE usuario_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param(
                "ssssssi",
                $endereco['cep'],
                $endereco['rua'],
                $endereco['numero'],
                $endereco['bairro'],
                $endereco['cidade'],
                $endereco['estado'],
                $usuario_id
            );

            if ($stmt->execute()) {
                $_SESSION['usuario_data']['endereco'] = $endereco;
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Endereço atualizado com sucesso!']);
            } else {
                $stmt->close();
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar endereço']);
            }
        }
    } else {
        $sql = "INSERT INTO enderecos (usuario_id, cep, rua, numero, bairro, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param(
                "issssss",
                $usuario_id,
                $endereco['cep'],
                $endereco['rua'],
                $endereco['numero'],
                $endereco['bairro'],
                $endereco['cidade'],
                $endereco['estado']
            );

            if ($stmt->execute()) {
                $_SESSION['usuario_data']['endereco'] = $endereco;
                $stmt->close();
                echo json_encode(['success' => true, 'message' => 'Endereço salvo com sucesso!']);
            } else {
                $stmt->close();
                echo json_encode(['success' => false, 'message' => 'Erro ao salvar endereço']);
            }
        }
    }
    $conn->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação desconhecida.']);
$conn->close();
?>