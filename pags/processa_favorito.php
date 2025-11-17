<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado']);
    exit;
}

include 'connection.php';

$cliente_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['acao']) || !isset($data['prestador_id'])) {
    echo json_encode(['success' => false, 'error' => 'Parâmetros inválidos']);
    exit;
}

$acao = $data['acao'];
$prestador_id = $data['prestador_id'];

try {
    if ($acao === 'adicionar') {
        $stmt = $conn->prepare("INSERT INTO favoritos (cliente_id, prestador_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $cliente_id, $prestador_id);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Adicionado aos favoritos']);
    } elseif ($acao === 'remover') {
        $stmt = $conn->prepare("DELETE FROM favoritos WHERE cliente_id = ? AND prestador_id = ?");
        $stmt->bind_param("ii", $cliente_id, $prestador_id);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Removido dos favoritos']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Ação inválida']);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
