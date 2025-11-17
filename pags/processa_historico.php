<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado']);
    exit;
}

include 'connection.php';

$cliente_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['acao'])) {
    echo json_encode(['success' => false, 'error' => 'Parâmetros inválidos']);
    exit;
}

$acao = $data['acao'];

try {
    if ($acao === 'adicionar_visualizacao') {
        $prestador_id = $data['prestador_id'];
        $stmt = $conn->prepare("INSERT INTO historico (cliente_id, prestador_id, tipo_acao) VALUES (?, ?, 'visualizacao')");
        $stmt->bind_param("ii", $cliente_id, $prestador_id);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } elseif ($acao === 'adicionar_busca') {
        $termo_busca = $data['termo_busca'];
        $stmt = $conn->prepare("INSERT INTO historico (cliente_id, termo_busca, tipo_acao) VALUES (?, ?, 'busca')");
        $stmt->bind_param("is", $cliente_id, $termo_busca);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } elseif ($acao === 'limpar') {
        $stmt = $conn->prepare("DELETE FROM historico WHERE cliente_id = ?");
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Ação inválida']);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
