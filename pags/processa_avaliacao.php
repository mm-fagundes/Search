<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    echo json_encode(['success' => false, 'error' => 'Acesso negado']);
    exit;
}

include 'connection.php';

$cliente_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['prestador_id']) || !isset($data['nota'])) {
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
    exit;
}

try {
    $prestador_id = intval($data['prestador_id']);
    $nota = intval($data['nota']);
    $comentario = $data['comentario'] ?? '';
    
    // Validar nota
    if ($nota < 1 || $nota > 5) {
        throw new Exception('Nota deve estar entre 1 e 5');
    }
    
    // Verificar se já existe avaliação deste cliente para este prestador
    $stmt = $conn->prepare("SELECT id FROM avaliacoes WHERE cliente_id = ? AND prestador_id = ?");
    $stmt->bind_param("ii", $cliente_id, $prestador_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Atualizar avaliação existente
        $stmt = $conn->prepare("UPDATE avaliacoes SET nota = ?, comentario = ?, criado_em = NOW() WHERE cliente_id = ? AND prestador_id = ?");
        $stmt->bind_param("isii", $nota, $comentario, $cliente_id, $prestador_id);
        $stmt->execute();
        $msg = 'Avaliação atualizada com sucesso';
    } else {
        // Inserir nova avaliação
        $stmt = $conn->prepare("INSERT INTO avaliacoes (cliente_id, prestador_id, nota, comentario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $cliente_id, $prestador_id, $nota, $comentario);
        $stmt->execute();
        $msg = 'Avaliação enviada com sucesso';
    }
    
    $stmt->close();
    
    echo json_encode(['success' => true, 'message' => $msg]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
