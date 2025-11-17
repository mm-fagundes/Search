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
        header("Location: cadastro_cliente.php?erro=dados_invalidos");
        exit;
    }
    
    // Validar comprimento da senha (mínimo 8 caracteres)
    if (strlen($senha) < 8) {
        header("Location: cadastro_cliente.php?erro=senha_curta");
        exit;
    }
    
    if ($senha !== $confirmar_senha) {
        header("Location: cadastro_cliente.php?erro=senhas_diferentes");
        exit;
    }
    
    // Validar telefone: aceitar vazio ou 10/11 dígitos após limpeza (DDD + 8/9 dígitos)
    $telefone_limpo = preg_replace('/\D/', '', $telefone);
    if ($telefone_limpo !== '' && !in_array(strlen($telefone_limpo), [10, 11])) {
        $len = strlen($telefone_limpo);
        $last = $len >= 4 ? substr($telefone_limpo, -4) : $telefone_limpo;
        $det = urlencode("len={$len};last={$last}");
        header("Location: cadastro_cliente.php?erro=telefone_invalido&detalhes={$det}");
        exit;
    }
    
    // Verificar se o email já existe
    $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
    if ($stmt === false) {
        $erro_detalhado = urlencode("Erro no banco (prepare): " . $conn->error);
        header("Location: cadastro_cliente.php?erro=erro_banco&detalhes=" . $erro_detalhado);
        exit;
    }
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        $erro_detalhado = urlencode("Erro no banco (execute): " . $stmt->error);
        header("Location: cadastro_cliente.php?erro=erro_banco&detalhes=" . $erro_detalhado);
        exit;
    }
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        header("Location: cadastro_cliente.php?erro=email_existe");
        exit;
    }
    
    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir no banco de dados
    $stmt = $conn->prepare("INSERT INTO clientes (nome, email, telefone, endereco, senha) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        $erro_detalhado = urlencode("Erro no banco (prepare): " . $conn->error);
        header("Location: cadastro_cliente.php?erro=erro_banco&detalhes=" . $erro_detalhado);
        exit;
    }
    $stmt->bind_param("sssss", $nome, $email, $telefone, $endereco, $senha_hash);
    
    if ($stmt->execute()) {
        header("Location: login_cliente.php?sucesso=cadastro_realizado");
    } else {
        $erro_detalhado = urlencode("Erro no banco: " . $stmt->error);
        header("Location: cadastro_cliente.php?erro=erro_banco&detalhes=" . $erro_detalhado);
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: cadastro_cliente.php");
}
?>

