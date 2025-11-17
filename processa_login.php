<?php
session_start();
header('Content-Type: application/json');

include 'db_config.php';

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
    exit;
}

// Busca o usuário no banco
$sql = "SELECT id, nome, email, celular, dataNascimento, senha_hash, isAdmin FROM usuarios WHERE email = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $email);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $id, $nome, $email_db, $celular, $dataNascimento, $senha_hash, $isAdmin);

            if (mysqli_stmt_fetch($stmt)) {
                // Verifica a senha
                if (password_verify($senha, $senha_hash)) {
                    mysqli_stmt_close($stmt);

                    // Busca o endereço do usuário
                    $endereco = null;
                    $sql_endereco = "SELECT cep, rua, numero, bairro, cidade, estado FROM enderecos WHERE usuario_id = ? LIMIT 1";
                    if ($stmt_endereco = mysqli_prepare($link, $sql_endereco)) {
                        mysqli_stmt_bind_param($stmt_endereco, "i", $id);
                        mysqli_stmt_execute($stmt_endereco);
                        mysqli_stmt_store_result($stmt_endereco);

                        if (mysqli_stmt_num_rows($stmt_endereco) > 0) {
                            mysqli_stmt_bind_result($stmt_endereco, $cep, $rua, $numero, $bairro, $cidade, $estado);
                            if (mysqli_stmt_fetch($stmt_endereco)) {
                                $endereco = [
                                    'cep' => $cep,
                                    'rua' => $rua,
                                    'numero' => $numero,
                                    'bairro' => $bairro,
                                    'cidade' => $cidade,
                                    'estado' => $estado
                                ];
                            }
                        }
                        mysqli_stmt_close($stmt_endereco);
                    }

                    // Salva os dados na sessão
                    $_SESSION['usuario_logado'] = true;
                    $_SESSION['usuario_id'] = $id;
                    $_SESSION['usuario_data'] = [
                        'id' => $id,
                        'nome' => $nome,
                        'email' => $email_db,
                        'celular' => $celular,
                        'dataNascimento' => $dataNascimento,
                        'isAdmin' => $isAdmin,
                        'endereco' => $endereco
                    ];

                    if ($isAdmin == 1) {
                        $_SESSION['is_admin'] = true;
                    }

                    echo json_encode([
                        'success' => true,
                        'message' => 'Login realizado com sucesso!',
                        'redirect' => 'usuario.php'
                    ]);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Senha incorreta!']);
                    exit;
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'E-mail não encontrado!']);
            exit;
        }
    }
    mysqli_stmt_close($stmt);
}

echo json_encode(['success' => false, 'message' => 'Erro ao processar login']);
mysqli_close($link);
?>