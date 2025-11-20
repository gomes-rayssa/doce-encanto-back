<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
    $conn->close();
    exit;
}

$sql = "SELECT id, nome, email, celular, dataNascimento, senha_hash, isAdmin 
        FROM usuarios 
        WHERE email = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result(
                $id,
                $nome,
                $email_db,
                $celular,
                $dataNascimento,
                $senha_hash,
                $isAdmin
            );

            if ($stmt->fetch()) {

                if (password_verify($senha, $senha_hash)) {
                    $stmt->close();

                    $endereco = null;
                    $sql_endereco = "SELECT cep, rua, numero, bairro, cidade, estado 
                                     FROM enderecos 
                                     WHERE usuario_id = ? 
                                     LIMIT 1";

                    if ($stmt_endereco = $conn->prepare($sql_endereco)) {
                        $stmt_endereco->bind_param("i", $id);
                        $stmt_endereco->execute();
                        $stmt_endereco->store_result();

                        if ($stmt_endereco->num_rows > 0) {
                            $stmt_endereco->bind_result(
                                $cep,
                                $rua,
                                $numero,
                                $bairro,
                                $cidade,
                                $estado
                            );

                            if ($stmt_endereco->fetch()) {
                                $endereco = [
                                    'cep'    => $cep,
                                    'rua'    => $rua,
                                    'numero' => $numero,
                                    'bairro' => $bairro,
                                    'cidade' => $cidade,
                                    'estado' => $estado
                                ];
                            }
                        }

                        $stmt_endereco->close();
                    }

                    $_SESSION['usuario_logado'] = true;
                    $_SESSION['usuario_id'] = $id;
                    $_SESSION['usuario_data'] = [
                        'id'             => $id,
                        'nome'           => $nome,
                        'email'          => $email_db,
                        'celular'        => $celular,
                        'dataNascimento' => $dataNascimento,
                        'isAdmin'        => $isAdmin,
                        'endereco'       => $endereco
                    ];

                    if ((int)$isAdmin === 1) {
                        $_SESSION['is_admin'] = true;
                    }

                    $conn->close();

                    $redirect_url = ((int)$isAdmin === 1) ? 'admin.php' : 'usuario.php';

                    echo json_encode([
                        'success'  => true,
                        'message'  => 'Login realizado com sucesso!',
                        'redirect' => $redirect_url
                    ]);
                    exit;

                } else {
                    $stmt->close();
                    $conn->close();

                    echo json_encode(['success' => false, 'message' => 'Senha incorreta!']);
                    exit;
                }
            } else {
                $stmt->close();
                $conn->close();

                echo json_encode(['success' => false, 'message' => 'Erro ao buscar dados do usuário.']);
                exit;
            }
        } else {
            $stmt->close();
            $conn->close();

            echo json_encode(['success' => false, 'message' => 'E-mail não encontrado!']);
            exit;
        }
    } else {
        $erro = $conn->error;
        $stmt->close();
        $conn->close();

        echo json_encode(['success' => false, 'message' => 'Erro ao executar consulta: ' . $erro]);
        exit;
    }
} else {
    $erro = $conn->error;
    $conn->close();

    echo json_encode(['success' => false, 'message' => 'Erro de preparação da consulta: ' . $erro]);
    exit;
}
?>