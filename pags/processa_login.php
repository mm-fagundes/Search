<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario'] ?? 'cliente'; // Default para cliente
    
    // Validações básicas
    if (empty($email) || empty($senha)) {
        $redirect_page = ($tipo_usuario == 'prestador') ? 'login_prestador.php' : 'login_cliente.php';
        header("Location: $redirect_page?erro=dados_invalidos");
        exit;
    }
    
    // Determinar a tabela baseada no tipo de usuário
    $tabela = ($tipo_usuario == 'prestador') ? 'prestadores' : 'clientes';
    
    // Buscar usuário no banco de dados
    $stmt = $conn->prepare("SELECT id, nome, email, senha FROM $tabela WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        
        // Verificar senha
        if (password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_type'] = $tipo_usuario;
            
            // Redirecionar baseado no tipo de usuário
            if ($tipo_usuario == 'prestador') {
                header("Location: dashboard_prestador.php");
            } else {
                header("Location: home_cliente.php");
            }
            exit;
        } else {
            // Senha incorreta
            $redirect_page = ($tipo_usuario == 'prestador') ? 'login_prestador.php' : 'login_cliente.php';
            header("Location: $redirect_page?erro=senha_incorreta");
            exit;
        }
    } else {
        // Usuário não encontrado
        $redirect_page = ($tipo_usuario == 'prestador') ? 'login_prestador.php' : 'login_cliente.php';
        header("Location: $redirect_page?erro=usuario_nao_encontrado");
        exit;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit;
}
?>
