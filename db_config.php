<?php
$servername = "localhost"; // Ou o IP do seu servidor MySQL
$username = "root";        // Seu usuário do MySQL (pode ser diferente de 'root')
$password = "";            // Sua senha do MySQL (deixe em branco se não tiver senha)
$dbname = "doce_encanto";  // O nome do banco de dados que acabamos de criar

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
// O restante do seu código de conexão...
?>
