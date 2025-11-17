<?php
$servername = "localhost";
$username = "root"; // Altere para o seu usuário do MySQL
$password = "";     // Altere para a sua senha do MySQL
$dbname = "search_db";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Define charset
$conn->set_charset("utf8mb4");
?>

