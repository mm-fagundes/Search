<?php
// connection.php

$host = "localhost";      // servidor do banco
$db   = "search_db";      // nome do banco de dados
$user = "root";           // usuário do banco
$pass = "";               // senha do banco

// Cria a conexão
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Opcional: definir charset para evitar problemas com acentos
$conn->set_charset("utf8mb4");
?>
