<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado']);
    exit;
}

include 'connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['acao'])) {
    echo json_encode(['success' => false, 'error' => 'Parâmetros inválidos']);
    exit;
}

$acao = $data['acao'];

try {
    if ($acao === 'criar') {
        $prestador_id = intval($data['prestador_id']);
        $cliente_id = intval($_SESSION['user_id']);
        $servico_id = intval($data['servico_id']);
        $data_agendamento = $data['data_agendamento'];
        $observacoes = $data['observacoes'] ?? null;
        
        // Validar que prestador e cliente existem
        $stmt_check = $conn->prepare("SELECT id FROM clientes WHERE id = ?");
        $stmt_check->bind_param("i", $cliente_id);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows === 0) {
            throw new Exception("Cliente não encontrado: " . $cliente_id);
        }
        $stmt_check->close();
        
        $stmt = $conn->prepare("INSERT INTO agendamentos (prestador_id, cliente_id, servico_id, data_agendamento, observacoes) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $prestador_id, $cliente_id, $servico_id, $data_agendamento, $observacoes);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Agendamento criado']);
    } 
    elseif ($acao === 'atualizar_status') {
        $agendamento_id = $data['agendamento_id'];
        $novo_status = $data['novo_status'];
        
        $stmt = $conn->prepare("UPDATE agendamentos SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_status, $agendamento_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Status atualizado']);
    }
    elseif ($acao === 'cancelar') {
        $agendamento_id = $data['agendamento_id'];
        
        $stmt = $conn->prepare("UPDATE agendamentos SET status = 'cancelado' WHERE id = ?");
        $stmt->bind_param("i", $agendamento_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Agendamento cancelado']);
    }
    else {
        echo json_encode(['success' => false, 'error' => 'Ação inválida']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
