<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if ($senha !== $confirmar_senha) {
        echo "<p>As senhas não coincidem. <a href='cadastro.php'>Voltar</a></p>";
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<p>Este email já está cadastrado. <a href='cadastro.php'>Voltar</a></p>";
        $stmt->close();
        exit;
    }
    $stmt->close();

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha_hash);
    if ($stmt->execute()) {
        echo "<p>Cadastro realizado com sucesso! <a href='login.php'>Login</a></p>";
    } else {
        echo "<p>Erro ao cadastrar. Tente novamente.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: cadastro.php");
    exit;
}
?>
