<?php
// redefinir_senha.php

session_start();

// Se já está logado, redireciona
if (isset($_SESSION['user_id'])) {
    header("Location: home_cliente.php");
    exit;
}

include 'connection.php';

$mensagem = '';
$tipo_mensagem = '';
$etapa = $_GET['etapa'] ?? 'email';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($etapa === 'email' && isset($_POST['email'])) {
        $email = trim($_POST['email']);
        
        // Verificar se email existe
        $stmt = $conn->prepare("SELECT id, nome, telefone FROM clientes WHERE email = ? UNION SELECT id, nome, telefone FROM prestadores WHERE email = ?");
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            // Email encontrado, pedir confirmação de telefone
            $_SESSION['reset_email'] = $email;
            header("Location: redefinir_senha.php?etapa=telefone");
            exit;
        } else {
            $mensagem = 'Email não encontrado em nosso sistema.';
            $tipo_mensagem = 'error';
        }
    }
    elseif ($etapa === 'telefone' && isset($_POST['telefone'])) {
        $telefone_digitado = preg_replace('/\D/', '', $_POST['telefone']);
        $email = $_SESSION['reset_email'] ?? '';
        
        // Verificar email e telefone
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ? AND REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', '') = ? UNION SELECT id FROM prestadores WHERE email = ? AND REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', '') = ?");
        $stmt->bind_param("ssss", $email, $telefone_digitado, $email, $telefone_digitado);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            // Validação OK
            $_SESSION['reset_telefone_validado'] = true;
            header("Location: redefinir_senha.php?etapa=nova_senha");
            exit;
        } else {
            $mensagem = 'Telefone não corresponde ao email informado.';
            $tipo_mensagem = 'error';
        }
    }
    elseif ($etapa === 'nova_senha' && isset($_POST['nova_senha']) && isset($_POST['confirmar_senha'])) {
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];
        $email = $_SESSION['reset_email'] ?? '';
        
        if (strlen($nova_senha) < 8) {
            $mensagem = 'A senha deve ter pelo menos 8 caracteres.';
            $tipo_mensagem = 'error';
        } elseif ($nova_senha !== $confirmar_senha) {
            $mensagem = 'As senhas não correspondem.';
            $tipo_mensagem = 'error';
        } else {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            
            // Atualizar senha em clientes ou prestadores
            $stmt = $conn->prepare("UPDATE clientes SET senha = ? WHERE email = ?");
            $stmt->bind_param("ss", $senha_hash, $email);
            $stmt->execute();
            $linhas_atualizadas = $stmt->affected_rows;
            
            if ($linhas_atualizadas === 0) {
                $stmt = $conn->prepare("UPDATE prestadores SET senha = ? WHERE email = ?");
                $stmt->bind_param("ss", $senha_hash, $email);
                $stmt->execute();
                $linhas_atualizadas = $stmt->affected_rows;
            }
            
            if ($linhas_atualizadas > 0) {
                $mensagem = 'Senha atualizada com sucesso! Redirecionando...';
                $tipo_mensagem = 'success';
                unset($_SESSION['reset_email']);
                unset($_SESSION['reset_telefone_validado']);
                echo '<script>
                    setTimeout(() => {
                        window.location.href = "login_cliente.php";
                    }, 1500);
                </script>';
            } else {
                $mensagem = 'Erro ao atualizar senha. Tente novamente.';
                $tipo_mensagem = 'error';
            }
        }
    }
}

// Verificar se pode acessar cada etapa
if ($etapa === 'telefone' && !isset($_SESSION['reset_email'])) {
    header("Location: redefinir_senha.php?etapa=email");
    exit;
}
if ($etapa === 'nova_senha' && !isset($_SESSION['reset_email'])) {
    header("Location: redefinir_senha.php?etapa=email");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redefinir Senha - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .step-indicator {
      display: flex;
      gap: 20px;
      margin-bottom: 40px;
      justify-content: center;
    }
    .step {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
    }
    .step-number {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      transition: all 0.3s;
    }
    .step.active .step-number {
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: white;
      box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
    }
    .step.completed .step-number {
      background: #10b981;
      color: white;
    }
    .step.inactive .step-number {
      background: #e5e7eb;
      color: #9ca3af;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen flex items-center justify-center p-4">

  <div class="max-w-md w-full">
    <div class="bg-white rounded-2xl shadow-2xl p-8">
      
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-lock text-white text-2xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Redefinir Senha</h1>
        <p class="text-gray-600 mt-2">Recupere o acesso à sua conta</p>
      </div>

      <!-- Step Indicator -->
      <div class="step-indicator">
        <div class="step <?php echo $etapa === 'email' ? 'active' : ($etapa !== 'email' ? 'completed' : 'inactive'); ?>">
          <div class="step-number"><?php echo $etapa !== 'email' ? '✓' : '1'; ?></div>
          <span class="hidden sm:inline text-sm">Email</span>
        </div>
        <div class="step <?php echo $etapa === 'telefone' ? 'active' : ($etapa === 'nova_senha' ? 'completed' : 'inactive'); ?>">
          <div class="step-number"><?php echo $etapa === 'nova_senha' ? '✓' : '2'; ?></div>
          <span class="hidden sm:inline text-sm">Telefone</span>
        </div>
        <div class="step <?php echo $etapa === 'nova_senha' ? 'active' : 'inactive'; ?>">
          <div class="step-number">3</div>
          <span class="hidden sm:inline text-sm">Nova Senha</span>
        </div>
      </div>

      <!-- Mensagem de Erro/Sucesso -->
      <?php if ($mensagem): ?>
        <div class="mb-6 p-4 rounded-xl <?php echo $tipo_mensagem === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
          <i class="fas fa-<?php echo $tipo_mensagem === 'error' ? 'exclamation-circle' : 'check-circle'; ?> mr-2"></i>
          <?php echo htmlspecialchars($mensagem); ?>
        </div>
      <?php endif; ?>

      <!-- Formulário -->
      <form method="POST" class="space-y-6">
        
        <?php if ($etapa === 'email'): ?>
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-envelope text-blue-600 mr-2"></i>Seu Email
            </label>
            <input type="email" id="email" name="email" required 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition"
                   placeholder="seu@email.com">
            <p class="text-xs text-gray-500 mt-2">Insira o email cadastrado em sua conta</p>
          </div>
          <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold">
            <i class="fas fa-arrow-right mr-2"></i>Próximo
          </button>

        <?php elseif ($etapa === 'telefone'): ?>
          <div>
            <label for="telefone" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-phone text-blue-600 mr-2"></i>Seu Telefone
            </label>
            <input type="tel" id="telefone" name="telefone" required 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition"
                   placeholder="(11) 99999-9999"
                   maxlength="15">
            <p class="text-xs text-gray-500 mt-2">Confirme o telefone cadastrado em sua conta</p>
          </div>
          <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold">
            <i class="fas fa-check mr-2"></i>Verificar
          </button>

        <?php elseif ($etapa === 'nova_senha'): ?>
          <div>
            <label for="nova_senha" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-key text-blue-600 mr-2"></i>Nova Senha
            </label>
            <input type="password" id="nova_senha" name="nova_senha" required 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition"
                   placeholder="Mínimo 8 caracteres"
                   minlength="8">
          </div>
          <div>
            <label for="confirmar_senha" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-key text-blue-600 mr-2"></i>Confirmar Senha
            </label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition"
                   placeholder="Digite novamente a senha"
                   minlength="8">
          </div>
          <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-semibold">
            <i class="fas fa-check-circle mr-2"></i>Atualizar Senha
          </button>
        <?php endif; ?>
      </form>

      <!-- Link para voltar -->
      <div class="mt-8 pt-8 border-t border-gray-200 text-center">
        <p class="text-gray-600">
          <a href="login_cliente.php" class="text-blue-600 hover:text-blue-700 font-semibold">Voltar para login</a>
        </p>
      </div>
    </div>
  </div>

</body>
</html>
