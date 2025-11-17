<?php
header('Content-Type: application/json');
session_start();

// Verificar se está logado como admin
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit;
}

require_once 'connection.php';

// Pegar ação e ID
$acao = $_GET['acao'] ?? $_POST['acao'] ?? '';
$cliente_id = $_GET['id'] ?? $_POST['id'] ?? '';

if (!$cliente_id) {
    echo json_encode(['success' => false, 'error' => 'ID do cliente não fornecido']);
    exit;
}

switch ($acao) {
    case 'deletar':
        try {
            // Primeiro, delete todos os favoritos deste cliente
            $stmt = $conn->prepare("DELETE FROM favoritos WHERE cliente_id = ?");
            $stmt->bind_param('i', $cliente_id);
            $stmt->execute();
            
            // Delete todas as avaliações do cliente
            $stmt = $conn->prepare("DELETE FROM avaliacoes WHERE cliente_id = ?");
            $stmt->bind_param('i', $cliente_id);
            $stmt->execute();
            
            // Delete todos os agendamentos do cliente
            $stmt = $conn->prepare("DELETE FROM agendamentos WHERE cliente_id = ?");
            $stmt->bind_param('i', $cliente_id);
            $stmt->execute();
            
            // Finalmente, delete o cliente
            $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
            $stmt->bind_param('i', $cliente_id);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Cliente deletado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Cliente não encontrado']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;
    
    default:
        echo json_encode(['success' => false, 'error' => 'Ação inválida']);
        break;
}
?>

