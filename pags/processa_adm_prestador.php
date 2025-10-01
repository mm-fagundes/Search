<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $nicho = trim($_POST['nicho']);
    $descricao = trim($_POST['descricao']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validações básicas
    if (empty($nome) || empty($email) || empty($telefone) || empty($nicho) || empty($senha)) {
        header("Location: adm.php?erro=dados_invalidos");
        exit;
    }
    
    if ($senha !== $confirmar_senha) {
        header("Location: adm.php?erro=senhas_diferentes");
        exit;
    }
    
    // Verificar se o email já existe
    $stmt = $conn->prepare("SELECT id FROM prestadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: adm.php?erro=email_existe");
        exit;
    }
    
    // Processar upload da foto
    $foto_nome = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        
        if (in_array($extensao, $extensoes_permitidas)) {
            $foto_nome = uniqid() . '.' . $extensao;
            $caminho_upload = '../uploads/' . $foto_nome;
            
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_upload)) {
                header("Location: adm.php?erro=upload_erro");
                exit;
            }
        } else {
            header("Location: adm.php?erro=formato_invalido");
            exit;
        }
    }
    
    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir no banco de dados
    $stmt = $conn->prepare("INSERT INTO prestadores (nome, email, telefone, endereco, nicho, descricao, foto, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nome, $email, $telefone, $endereco, $nicho, $descricao, $foto_nome, $senha_hash);
    
    if ($stmt->execute()) {
        header("Location: adm.php?erro=erro_banco");
        echo "Added";
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: adm.php");
}
?>

