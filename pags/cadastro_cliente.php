<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro Cliente - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-lg">
    <!-- Logo e Título -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
        <i class="fas fa-user-plus text-white text-2xl"></i>
      </div>
      <h1 class="text-4xl font-bold text-gray-800 mb-2">Cadastro Cliente</h1>
      <p class="text-gray-600">Crie sua conta para encontrar prestadores</p>
    </div>

    <!-- Card de Cadastro -->
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
      
      <?php if (isset($_GET['erro'])): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
          <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
            <span class="text-red-700 font-medium">
              <?php 
                switch($_GET['erro']) {
                  case 'email_existe':
                    echo 'Este e-mail já está cadastrado.';
                    break;
                  case 'senhas_diferentes':
                    echo 'As senhas não coincidem.';
                    break;
                  case 'dados_invalidos':
                    echo 'Por favor, preencha todos os campos obrigatórios.';
                    break;
                  default:
                    echo 'Erro no cadastro. Tente novamente.';
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
            <span class="text-green-700 font-medium">Cadastro realizado com sucesso!</span>
          </div>
        </div>
      <?php endif; ?>

      <form action="processa_cadastro_cliente.php" method="POST" class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="nome" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-user mr-2 text-blue-600"></i>Nome Completo
            </label>
            <input type="text" id="nome" name="nome" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                   placeholder="Seu nome completo">
          </div>

          <div>
            <label for="telefone" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-phone mr-2 text-blue-600"></i>Telefone
            </label>
            <input type="tel" id="telefone" name="telefone"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                   placeholder="(11) 99999-9999">
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-envelope mr-2 text-blue-600"></i>E-mail
          </label>
          <input type="email" id="email" name="email" required
                 class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                 placeholder="seu@email.com">
        </div>

        <div>
          <label for="endereco" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Endereço
          </label>
          <input type="text" id="endereco" name="endereco"
                 class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                 placeholder="Rua, número, bairro, cidade">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="senha" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-lock mr-2 text-blue-600"></i>Senha
            </label>
            <input type="password" id="senha" name="senha" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                   placeholder="••••••••">
          </div>

          <div>
            <label for="confirmar_senha" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-lock mr-2 text-blue-600"></i>Confirmar Senha
            </label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                   placeholder="••••••••">
          </div>
        </div>

        <div class="flex items-center">
          <input type="checkbox" id="termos" name="termos" required
                 class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
          <label for="termos" class="ml-2 text-sm text-gray-600">
            Aceito os <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">termos de uso</a> e 
            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">política de privacidade</a>
          </label>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105 font-semibold">
          <i class="fas fa-user-plus mr-2"></i>Criar Conta de Cliente
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-gray-600 mb-4">Já tem uma conta?</p>
        <a href="login_cliente.php" 
           class="inline-block w-full bg-gray-100 text-gray-700 py-3 rounded-xl shadow hover:bg-gray-200 transition-all duration-200 font-semibold">
          <i class="fas fa-sign-in-alt mr-2"></i>Fazer Login
        </a>
      </div>

      <div class="mt-6 pt-6 border-t border-gray-200 text-center">
        <p class="text-gray-600 mb-3">Você é um prestador de serviço?</p>
        <a href="cadastro_prestador.php" 
           class="inline-block text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
          <i class="fas fa-tools mr-2"></i>Cadastrar como Prestador
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

  <!-- JavaScript -->
  <script>
    // Validação de senhas em tempo real
    const senha = document.getElementById('senha');
    const confirmarSenha = document.getElementById('confirmar_senha');

    function validarSenhas() {
      if (senha.value !== confirmarSenha.value) {
        confirmarSenha.setCustomValidity('As senhas não coincidem');
      } else {
        confirmarSenha.setCustomValidity('');
      }
    }

    senha.addEventListener('input', validarSenhas);
    confirmarSenha.addEventListener('input', validarSenhas);

    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    telefoneInput.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length >= 11) {
        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
      } else if (value.length >= 7) {
        value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
      } else if (value.length >= 3) {
        value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
      }
      e.target.value = value;
    });
  </script>

</body>
</html>

