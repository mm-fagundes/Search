<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    
    // Upload da foto
    $foto_nome = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nome = uniqid() . "." . $extensao;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/" . $foto_nome);
    }

    $stmt = $conn->prepare("INSERT INTO prestadores (nome, telefone, foto) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $telefone, $foto_nome);

    if ($stmt->execute()) {
        echo "<p>Prestador cadastrado com sucesso! <a href='home.php'>Voltar para Home</a></p>";
    } else {
        echo "<p>Erro ao cadastrar prestador. Tente novamente.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: prestadores.php");
    exit;
}
?>
