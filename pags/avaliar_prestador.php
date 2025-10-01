<?php
session_start();
include 'connection.php';

// Verificar se o usuário está logado como cliente
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header("Location: login_cliente.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prestador_id = intval($_POST['prestador_id']);
    $cliente_id = $_SESSION['user_id'];
    $nota = intval($_POST['nota']);
    $comentario = trim($_POST['comentario']);
    
    // Validações
    if ($nota < 1 || $nota > 5) {
        echo json_encode(['success' => false, 'error' => 'Nota deve ser entre 1 e 5']);
        exit;
    }
    
    // Verificar se o cliente já avaliou este prestador
    $stmt = $conn->prepare("SELECT id FROM avaliacoes WHERE prestador_id = ? AND cliente_id = ?");
    $stmt->bind_param("ii", $prestador_id, $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Atualizar avaliação existente
        $stmt = $conn->prepare("UPDATE avaliacoes SET nota = ?, comentario = ? WHERE prestador_id = ? AND cliente_id = ?");
        $stmt->bind_param("isii", $nota, $comentario, $prestador_id, $cliente_id);
    } else {
        // Inserir nova avaliação
        $stmt = $conn->prepare("INSERT INTO avaliacoes (prestador_id, cliente_id, nota, comentario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $prestador_id, $cliente_id, $nota, $comentario);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Avaliação salva com sucesso']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erro ao salvar avaliação']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
}
?>

