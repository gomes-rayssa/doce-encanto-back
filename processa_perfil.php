<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    exit;
}

$action = $data['action'];
$usuario_id = $_SESSION['usuario_id'];

// Atualiza informações pessoais
if ($action === 'save_personal') {
    $sql = "UPDATE usuarios SET nome = ?, celular = ?, dataNascimento = ? WHERE id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", 
            $data['nome'],
            $data['celular'],
            $data['dataNascimento'],
            $usuario_id
        );
        
        if (mysqli_stmt_execute($stmt)) {
            // Atualiza os dados na sessão
            $_SESSION['usuario_data']['nome'] = $data['nome'];
            $_SESSION['usuario_data']['celular'] = $data['celular'];
            $_SESSION['usuario_data']['dataNascimento'] = $data['dataNascimento'];
            
            mysqli_stmt_close($stmt);
            echo json_encode(['success' => true, 'message' => 'Informações pessoais atualizadas!']);
        } else {
            mysqli_stmt_close($stmt);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar: ' . mysqli_error($link)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro de preparação da query']);
    }
    exit;
}

// Atualiza endereço
if ($action === 'save_address') {
    $endereco = $data['endereco'];
    
    // Verifica se já existe endereço
    $sql_check = "SELECT id FROM enderecos WHERE usuario_id = ?";
    $endereco_existe = false;
    
    if ($stmt_check = mysqli_prepare($link, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "i", $usuario_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        $endereco_existe = mysqli_stmt_num_rows($stmt_check) > 0;
        mysqli_stmt_close($stmt_check);
    }
    
    if ($endereco_existe) {
        // Atualiza endereço existente
        $sql = "UPDATE enderecos SET cep = ?, rua = ?, numero = ?, bairro = ?, cidade = ?, estado = ? WHERE usuario_id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssi",
                $endereco['cep'],
                $endereco['rua'],
                $endereco['numero'],
                $endereco['bairro'],
                $endereco['cidade'],
                $endereco['estado'],
                $usuario_id
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['usuario_data']['endereco'] = $endereco;
                mysqli_stmt_close($stmt);
                echo json_encode(['success' => true, 'message' => 'Endereço atualizado com sucesso!']);
            } else {
                mysqli_stmt_close($stmt);
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar endereço']);
            }
        }
    } else {
        // Insere novo endereço
        $sql = "INSERT INTO enderecos (usuario_id, cep, rua, numero, bairro, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "issssss",
                $usuario_id,
                $endereco['cep'],
                $endereco['rua'],
                $endereco['numero'],
                $endereco['bairro'],
                $endereco['cidade'],
                $endereco['estado']
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['usuario_data']['endereco'] = $endereco;
                mysqli_stmt_close($stmt);
                echo json_encode(['success' => true, 'message' => 'Endereço salvo com sucesso!']);
            } else {
                mysqli_stmt_close($stmt);
                echo json_encode(['success' => false, 'message' => 'Erro ao salvar endereço']);
            }
        }
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação desconhecida.']);
mysqli_close($link);
?>