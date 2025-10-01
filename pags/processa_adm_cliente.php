<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validações básicas
    if (empty($nome) || empty($email) || empty($senha)) {
        header("Location: cadastro_adm_cliente.php?erro=dados_invalidos");
        exit;
    }
    
    if ($senha !== $confirmar_senha) {
        header("Location: cadastro_adm_cliente.php?erro=senhas_diferentes");
        exit;
    }
    
    // Verificar se o email já existe
    $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: adm.php?erro=email_existe");
        exit;
    }
    
    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir no banco de dados
    $stmt = $conn->prepare("INSERT INTO clientes (nome, email, telefone, endereco, senha) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $email, $telefone, $endereco, $senha_hash);
    
    if ($stmt->execute()) {
        header("Location: adm.php?sucesso=cadastro_realizado");
        echo "Cliente cadastrado.";
        
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: adm.php");
}
?>

