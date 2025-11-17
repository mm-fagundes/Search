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
    if ($acao === 'atualizar_dados') {
        $nome = $data['nome'];
        $email = $data['email'];
        $telefone = $data['telefone'];
        
        $stmt = $conn->prepare("UPDATE clientes SET nome = ?, email = ?, telefone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $email, $telefone, $cliente_id);
        $stmt->execute();
        
        $_SESSION['user_name'] = $nome;
        echo json_encode(['success' => true, 'message' => 'Dados atualizados']);
    } 
    elseif ($acao === 'atualizar_endereco') {
        $endereco = $data['endereco'];
        
        $stmt = $conn->prepare("UPDATE clientes SET endereco = ? WHERE id = ?");
        $stmt->bind_param("si", $endereco, $cliente_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Endereço atualizado']);
    }
    elseif ($acao === 'alterar_senha') {
        $senha_atual = $data['senha_atual'];
        $nova_senha = $data['nova_senha'];
        
        // Verificar senha atual
        $stmt = $conn->prepare("SELECT senha FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cliente = $result->fetch_assoc();
        $stmt->close();
        
        if (!password_verify($senha_atual, $cliente['senha'])) {
            echo json_encode(['success' => false, 'error' => 'Senha atual incorreta']);
            exit;
        }
        
        $senha_hash = password_hash($nova_senha, PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare("UPDATE clientes SET senha = ? WHERE id = ?");
        $stmt->bind_param("si", $senha_hash, $cliente_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Senha alterada']);
    }
    elseif ($acao === 'deletar_conta') {
        $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
        
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Conta deletada']);
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
