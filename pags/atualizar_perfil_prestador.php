<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'prestador') {
    echo json_encode(['success' => false, 'error' => 'Acesso negado']);
    exit;
}

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prestador_id = $_SESSION['user_id'];
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $nicho = trim($_POST['nicho']);
    $descricao = trim($_POST['descricao']);
    
    // Validações básicas
    if (empty($nome) || empty($telefone) || empty($nicho)) {
        echo json_encode(['success' => false, 'error' => 'Campos obrigatórios não preenchidos']);
        exit;
    }
    
    // Processar upload da nova foto (se fornecida)
    $foto_nome = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        
        if (in_array($extensao, $extensoes_permitidas)) {
            // Buscar foto atual para deletar depois
            $stmt = $conn->prepare("SELECT foto FROM prestadores WHERE id = ?");
            $stmt->bind_param("i", $prestador_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $prestador_atual = $result->fetch_assoc();
            $foto_antiga = $prestador_atual['foto'];
            
            $foto_nome = uniqid() . '.' . $extensao;
            $caminho_upload = '../uploads/' . $foto_nome;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_upload)) {
                // Deletar foto antiga se existir
                if ($foto_antiga && file_exists('../uploads/' . $foto_antiga)) {
                    unlink('../uploads/' . $foto_antiga);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro no upload da foto']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Formato de imagem não permitido']);
            exit;
        }
    }
    
    // Atualizar dados no banco
    if ($foto_nome) {
        // Atualizar com nova foto
        $stmt = $conn->prepare("UPDATE prestadores SET nome = ?, telefone = ?, endereco = ?, nicho = ?, descricao = ?, foto = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $nome, $telefone, $endereco, $nicho, $descricao, $foto_nome, $prestador_id);
    } else {
        // Atualizar sem alterar a foto
        $stmt = $conn->prepare("UPDATE prestadores SET nome = ?, telefone = ?, endereco = ?, nicho = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nome, $telefone, $endereco, $nicho, $descricao, $prestador_id);
    }
    
    if ($stmt->execute()) {
        // Atualizar nome na sessão
        $_SESSION['user_name'] = $nome;
        
        echo json_encode(['success' => true, 'message' => 'Perfil atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erro ao atualizar perfil no banco de dados']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
}
?>

