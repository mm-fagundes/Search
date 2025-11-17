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
$prestador_id = $_SESSION['user_id'];

try {
    if ($acao === 'atualizar_dados') {
        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';
        $telefone = $data['telefone'] ?? '';
        $nicho = $data['nicho'] ?? '';
        $descricao = $data['descricao'] ?? '';
        
        $stmt = $conn->prepare("UPDATE prestadores SET nome = ?, email = ?, telefone = ?, nicho = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nome, $email, $telefone, $nicho, $descricao, $prestador_id);
        $stmt->execute();
        
        // Atualizar nome na sessão
        $_SESSION['user_name'] = $nome;
        
        echo json_encode(['success' => true, 'message' => 'Dados atualizados com sucesso']);
    }
    elseif ($acao === 'atualizar_endereco') {
        $endereco = $data['endereco'] ?? '';
        
        $stmt = $conn->prepare("UPDATE prestadores SET endereco = ? WHERE id = ?");
        $stmt->bind_param("si", $endereco, $prestador_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Localização atualizada com sucesso']);
    }
    elseif ($acao === 'alterar_senha') {
        $senha_atual = $data['senha_atual'] ?? '';
        $nova_senha = $data['nova_senha'] ?? '';
        
        // Verificar senha atual
        $stmt = $conn->prepare("SELECT senha FROM prestadores WHERE id = ?");
        $stmt->bind_param("i", $prestador_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $prestador = $result->fetch_assoc();
        $stmt->close();
        
        if (!$prestador) {
            throw new Exception('Prestador não encontrado');
        }
        
        if (!password_verify($senha_atual, $prestador['senha'])) {
            throw new Exception('Senha atual incorreta');
        }
        
        // Validar nova senha
        if (strlen($nova_senha) < 8) {
            throw new Exception('A senha deve ter no mínimo 8 caracteres');
        }
        
        // Atualizar senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE prestadores SET senha = ? WHERE id = ?");
        $stmt->bind_param("si", $nova_senha_hash, $prestador_id);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Senha alterada com sucesso']);
    }
    elseif ($acao === 'deletar_conta') {
        // Deletar prestador
        $stmt = $conn->prepare("DELETE FROM prestadores WHERE id = ?");
        $stmt->bind_param("i", $prestador_id);
        $stmt->execute();
        
        // Destruir sessão
        session_destroy();
        
        echo json_encode(['success' => true, 'message' => 'Conta deletada']);
    }
    else {
        echo json_encode(['success' => false, 'error' => 'Ação inválida']);
    }
    
    if (isset($stmt)) {
        $stmt->close();
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
