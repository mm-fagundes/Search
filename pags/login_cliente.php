<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Cliente - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-md">
    <!-- Logo e Título -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
        <i class="fas fa-search text-white text-2xl"></i>
      </div>
      <h1 class="text-4xl font-bold text-gray-800 mb-2">Search</h1>
      <p class="text-gray-600">Encontre os melhores prestadores de serviço</p>
    </div>

    <!-- Card de Login -->
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Login Cliente</h2>
        <p class="text-gray-500">Acesse sua conta para encontrar prestadores</p>
      </div>

      <?php if (isset($_GET['erro'])): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
          <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
            <span class="text-red-700 font-medium">
              <?php 
                switch($_GET['erro']) {
                  case 'dados_invalidos':
                    echo 'Por favor, preencha todos os campos.';
                    break;
                  case 'senha_incorreta':
                    echo 'Senha incorreta.';
                    break;
                  case 'usuario_nao_encontrado':
                    echo 'E-mail não encontrado.';
                    break;
                  default:
                    echo 'Erro no login. Tente novamente.';
                }
              ?>
            </span>
          </div>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['sucesso'])): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
          <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            <span class="text-green-700 font-medium">Cadastro realizado com sucesso! Faça seu login.</span>
          </div>
        </div>
      <?php endif; ?>

      <form action="processa_login.php" method="POST" class="space-y-6">
        <input type="hidden" name="tipo_usuario" value="cliente">
        
        <div class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-envelope mr-2 text-blue-600"></i>E-mail
            </label>
            <input type="email" id="email" name="email" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-blue-400"
                   placeholder="seu@email.com">
          </div>

          <div>
            <label for="senha" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-lock mr-2 text-blue-600"></i>Senha
            </label>
            <input type="password" id="senha" name="senha" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-blue-400"
                   placeholder="••••••••">
          </div>
        </div>

        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            <span class="ml-2 text-sm text-gray-600">Lembrar-me</span>
          </label>
          <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
            Esqueceu a senha?
          </a>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105 font-semibold">
          <i class="fas fa-sign-in-alt mr-2"></i>Entrar como Cliente
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-gray-600 mb-4">Não tem conta?</p>
        <a href="cadastro_cliente.php" 
           class="inline-block w-full bg-gray-100 text-gray-700 py-3 rounded-xl shadow hover:bg-gray-200 transition-all duration-200 font-semibold">
          <i class="fas fa-user-plus mr-2"></i>Criar conta de Cliente
        </a>
      </div>

      <div class="mt-6 pt-6 border-t border-gray-200 text-center">
        <p class="text-gray-600 mb-3">Você é um prestador de serviço?</p>
        <a href="login_prestador.php" 
           class="inline-block text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
          <i class="fas fa-tools mr-2"></i>Fazer login como Prestador
        </a>
      </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-8">
      <p class="text-gray-500 text-sm">
        © 2024 Search. Conectando pessoas e serviços.
      </p>
    </div>
  </div>

</body>
</html>

