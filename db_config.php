<?php
// Configurações de conexão com o banco de dados MySQL
// Estas configurações são tipicamente usadas em um ambiente de desenvolvimento local (XAMPP, WAMP, MAMP)
// Se você estiver usando um servidor de hospedagem, as configurações serão fornecidas pelo seu provedor.

define('DB_SERVER', 'localhost'); // Geralmente 'localhost' para ambiente local
define('DB_USERNAME', 'root');    // Usuário padrão do MySQL no ambiente local
define('DB_PASSWORD', 'YES');        // Senha padrão do MySQL no ambiente local (geralmente vazia)
define('DB_NAME', 'doce_encanto'); // Nome do banco de dados que você criará no MySQL Workbench

// Tenta conectar ao banco de dados MySQL
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica a conexão
if($link === false){
    die("ERRO: Não foi possível conectar ao banco de dados. " . mysqli_connect_error());
}
?>
