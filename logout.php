<?php
session_start(); // Inicia a sessão para poder destruí-la

session_unset(); // Remove todas as variáveis da sessão

session_destroy(); // Destrói a sessão

// Redireciona o usuário de volta para a página inicial
header('Location: index.php');
exit;
?>