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
$prestador_id = $_GET['id'] ?? $_POST['id'] ?? '';

if (!$prestador_id) {
    echo json_encode(['success' => false, 'error' => 'ID do prestador não fornecido']);
    exit;
}

switch ($acao) {
    case 'deletar':
        try {
            // Primeiro, delete todas as avaliações do prestador
            $stmt = $conn->prepare("DELETE FROM avaliacoes WHERE prestador_id = ?");
            $stmt->bind_param('i', $prestador_id);
            $stmt->execute();
            
            // Delete todos os agendamentos do prestador
            $stmt = $conn->prepare("DELETE FROM agendamentos WHERE prestador_id = ?");
            $stmt->bind_param('i', $prestador_id);
            $stmt->execute();
            
            // Delete todos os favoritos do prestador
            $stmt = $conn->prepare("DELETE FROM favoritos WHERE prestador_id = ?");
            $stmt->bind_param('i', $prestador_id);
            $stmt->execute();
            
            // Delete todos os serviços do prestador
            $stmt = $conn->prepare("DELETE FROM servicos WHERE prestador_id = ?");
            $stmt->bind_param('i', $prestador_id);
            $stmt->execute();
            
            // Finalmente, delete o prestador
            $stmt = $conn->prepare("DELETE FROM prestadores WHERE id = ?");
            $stmt->bind_param('i', $prestador_id);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Prestador deletado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Prestador não encontrado']);
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

