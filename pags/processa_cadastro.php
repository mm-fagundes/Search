<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validações básicas e redirecionamento com erro via GET para exibição no formulário
    if (empty($nome) || empty($email) || empty($senha)) {
        header("Location: cadastro.php?erro=dados_invalidos");
        exit;
    }

    if (strlen($senha) < 8) {
        header("Location: cadastro.php?erro=senha_curta");
        exit;
    }

    if ($senha !== $confirmar_senha) {
        header("Location: cadastro.php?erro=senhas_diferentes");
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        header("Location: cadastro.php?erro=email_existe");
        exit;
    }
    $stmt->close();

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha_hash);
    if ($stmt->execute()) {
        header("Location: login_cliente.php?sucesso=cadastro_realizado");
        exit;
    } else {
        // Enviar um erro genérico para o formulário com detalhes minimizados
        header("Location: cadastro.php?erro=erro_banco");
        exit;
    }

} else {
    header("Location: cadastro.php");
    exit;
}
?>
