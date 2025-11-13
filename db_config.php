<?php

define('DB_SERVER', 'localhost'); 
define('DB_USERNAME', 'root');    
define('DB_PASSWORD', 'YES');        
define('DB_NAME', 'doce_encanto'); 

// Tenta conectar ao banco de dados MySQL
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica a conexão
if($link === false){
    die("ERRO: Não foi possível conectar ao banco de dados. " . mysqli_connect_error());
}
?>
