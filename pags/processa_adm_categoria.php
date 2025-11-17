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

$acao = $_GET['acao'] ?? $_POST['acao'] ?? '';
$id = $_GET['id'] ?? $_POST['id'] ?? '';
$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';

try {
    if ($acao === 'criar') {
        if (trim($nome) === '') {
            throw new Exception('Nome da categoria é obrigatório');
        }

        $stmt = $conn->prepare("INSERT INTO categorias (nome, descricao) VALUES (?, ?)");
        $stmt->bind_param('ss', $nome, $descricao);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Categoria criada']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erro ao criar categoria']);
        }
        $stmt->close();
        exit;
    }

    if ($acao === 'deletar') {
        if (empty($id)) {
            throw new Exception('ID não fornecido');
        }

        // excluir categoria (não remove prestadores automaticamente - eles podem manter nicho texto)
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Categoria removida']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Categoria não encontrada']);
        }
        $stmt->close();
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Ação inválida']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
