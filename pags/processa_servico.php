<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'prestador') {
    echo json_encode(['success' => false, 'error' => 'Acesso negado']);
    exit;
}

include 'connection.php';

$prestador_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['acao'])) {
    echo json_encode(['success' => false, 'error' => 'Parâmetros inválidos']);
    exit;
}

$acao = $data['acao'];

try {
    if ($acao === 'criar') {
        $nome_servico = $data['nome_servico'];
        $descricao_servico = $data['descricao_servico'];
        $preco_base = $data['preco_base'];
        
        $stmt = $conn->prepare("INSERT INTO servicos (prestador_id, nome_servico, descricao_servico, preco_base) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $prestador_id, $nome_servico, $descricao_servico, $preco_base);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Serviço cadastrado com sucesso']);
    } 
    elseif ($acao === 'atualizar') {
        $servico_id = $data['servico_id'];
        $nome_servico = $data['nome_servico'];
        $descricao_servico = $data['descricao_servico'];
        $preco_base = $data['preco_base'];
        
        $stmt = $conn->prepare("UPDATE servicos SET nome_servico = ?, descricao_servico = ?, preco_base = ? WHERE id = ? AND prestador_id = ?");
        $stmt->bind_param("ssdii", $nome_servico, $descricao_servico, $preco_base, $servico_id, $prestador_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Serviço atualizado com sucesso']);
    }
    elseif ($acao === 'deletar') {
        $servico_id = $data['servico_id'];
        
        $stmt = $conn->prepare("DELETE FROM servicos WHERE id = ? AND prestador_id = ?");
        $stmt->bind_param("ii", $servico_id, $prestador_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Serviço deletado com sucesso']);
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
