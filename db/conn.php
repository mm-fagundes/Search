<?php

$host = 'localhost';    
$dbname = 'search_db'; 
$user = 'root';    
$password = '';   


// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retorna arrays associativos por padrão
    PDO::ATTR_EMULATE_PREPARES => false, // Usa prepared statements reais
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
    echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    
    die('Erro na conexão: ' . $e->getMessage());
    die('Erro ao conectar ao banco de dados.');
}
?>
