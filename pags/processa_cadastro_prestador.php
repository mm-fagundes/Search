<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    // categoria_id expected from select
    $categoria_id = isset($_POST['categoria_id']) && $_POST['categoria_id'] !== '' ? intval($_POST['categoria_id']) : null;
    $nicho = '';
    $horario_inicio = isset($_POST['horario_inicio']) ? $_POST['horario_inicio'] : null;
    $horario_fim = isset($_POST['horario_fim']) ? $_POST['horario_fim'] : null;
    $descricao = trim($_POST['descricao']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validações básicas
    if (empty($nome) || empty($email) || empty($telefone) || empty($senha)) {
        header("Location: cadastro_prestador.php?erro=dados_invalidos");
        exit;
    }

    // Require a category selection
    if (empty($categoria_id)) {
        header("Location: cadastro_prestador.php?erro=categoria_obrigatoria");
        exit;
    }
    
    // Validar comprimento da senha (mínimo 8 caracteres)
    if (strlen($senha) < 8) {
        header("Location: cadastro_prestador.php?erro=senha_curta");
        exit;
    }
    
    if ($senha !== $confirmar_senha) {
        header("Location: cadastro_prestador.php?erro=senhas_diferentes");
        exit;
    }
    
    // Validar telefone (11 dígitos para Brasil: DDD + 9 dígitos)
    $telefone_limpo = preg_replace('/\D/', '', $telefone);
    $tel_len = strlen($telefone_limpo);
    if ($tel_len < 10 || $tel_len > 11) {
        $last = $tel_len >= 4 ? substr($telefone_limpo, -4) : $telefone_limpo;
        $det = urlencode("len={$tel_len};last={$last}");
        header("Location: cadastro_prestador.php?erro=telefone_invalido&detalhes={$det}");
        exit;
    }
    
    // Verificar se o email já existe
    $stmt = $conn->prepare("SELECT id FROM prestadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: cadastro_prestador.php?erro=email_existe");
        exit;
    }
    
    // Processar upload da foto
    $foto_nome = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        
        if (in_array($extensao, $extensoes_permitidas)) {
            $foto_nome = uniqid() . '.' . $extensao;
            $caminho_upload = '../uploads/' . $foto_nome;
            
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_upload)) {
                header("Location: cadastro_prestador.php?erro=upload_erro");
                exit;
            }
        } else {
            header("Location: cadastro_prestador.php?erro=formato_invalido");
            exit;
        }
    }
    
    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Resolve category name to store in `nicho` for display/compatibility
    $categoria_nome = '';
    $stmtCat = $conn->prepare("SELECT nome FROM categorias WHERE id = ?");
    $stmtCat->bind_param("i", $categoria_id);
    $stmtCat->execute();
    $resCat = $stmtCat->get_result();
    if ($resCat && $resCat->num_rows > 0) {
        $categoria_nome = $resCat->fetch_assoc()['nome'];
    }
    $stmtCat->close();

    $nicho = $categoria_nome;

    // Inserir no banco de dados (inclui categoria e horários)
    $stmt = $conn->prepare("INSERT INTO prestadores (nome, email, telefone, endereco, nicho, categoria_id, horario_inicio, horario_fim, descricao, foto, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    // types: nome(s), email(s), telefone(s), endereco(s), nicho(s), categoria_id(i), horario_inicio(s), horario_fim(s), descricao(s), foto(s), senha(s)
    $stmt->bind_param("sssssisssss", $nome, $email, $telefone, $endereco, $nicho, $categoria_id, $horario_inicio, $horario_fim, $descricao, $foto_nome, $senha_hash);
    
    if ($stmt->execute()) {
        header("Location: login_prestador.php?sucesso=cadastro_realizado");
    } else {
        $erro_detalhado = urlencode("Erro no banco: " . $stmt->error);
        header("Location: cadastro_prestador.php?erro=erro_banco&detalhes=" . $erro_detalhado);
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: cadastro_prestador.php");
}
?>

