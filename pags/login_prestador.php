<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Prestador - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-md">
    <!-- Logo e Título -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 rounded-full mb-4">
        <i class="fas fa-tools text-white text-2xl"></i>
      </div>
      <h1 class="text-4xl font-bold text-gray-800 mb-2">Search</h1>
      <p class="text-gray-600">Plataforma para prestadores de serviço</p>
    </div>

    <!-- Card de Login -->
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Login Prestador</h2>
        <p class="text-gray-500">Acesse sua conta profissional</p>
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
        <input type="hidden" name="tipo_usuario" value="prestador">
        
        <div class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-envelope mr-2 text-green-600"></i>E-mail Profissional
            </label>
            <input type="email" id="email" name="email" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-green-400"
                   placeholder="seu@email.com">
          </div>

          <div>
            <label for="senha" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-lock mr-2 text-green-600"></i>Senha
            </label>
            <div class="relative">
              <input type="password" id="senha" name="senha" required minlength="8"
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 hover:border-green-400"
                     placeholder="••••••••">
              <button type="button" data-target="senha" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-500" aria-label="Mostrar senha">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              </button>
            </div>
            <p id="senhaHelpLoginPrestador" class="text-sm text-red-600 mt-1 hidden">A senha deve ter no mínimo 8 caracteres.</p>
          </div>
        </div>

        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
            <span class="ml-2 text-sm text-gray-600">Lembrar-me</span>
          </label>
          <a href="redefinir_senha.php" class="text-sm text-green-600 hover:text-green-800 font-medium">
            Esqueceu a senha?
          </a>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-xl shadow-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105 font-semibold">
          <i class="fas fa-sign-in-alt mr-2"></i>Entrar como Prestador
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-gray-600 mb-4">Ainda não oferece seus serviços?</p>
        <a href="cadastro_prestador.php" 
           class="inline-block w-full bg-gray-100 text-gray-700 py-3 rounded-xl shadow hover:bg-gray-200 transition-all duration-200 font-semibold">
          <i class="fas fa-user-plus mr-2"></i>Cadastrar como Prestador
        </a>
      </div>

      <div class="mt-6 pt-6 border-t border-gray-200 text-center">
        <p class="text-gray-600 mb-3">Procurando por serviços?</p>
        <a href="login_cliente.php" 
           class="inline-block text-green-600 hover:text-green-800 font-semibold transition-colors duration-200">
          <i class="fas fa-search mr-2"></i>Fazer login como Cliente
        </a>
      </div>
    </div>

    <!-- Benefícios para Prestadores -->
    <div class="mt-6 bg-white/50 backdrop-blur-sm rounded-xl p-4">
      <h3 class="text-sm font-semibold text-gray-700 mb-3 text-center">
        <i class="fas fa-star mr-2 text-yellow-500"></i>Benefícios para Prestadores
      </h3>
      <div class="grid grid-cols-2 gap-3 text-xs text-gray-600">
        <div class="flex items-center">
          <i class="fas fa-check-circle text-green-500 mr-2"></i>
          <span>Mais clientes</span>
        </div>
        <div class="flex items-center">
          <i class="fas fa-check-circle text-green-500 mr-2"></i>
          <span>Gestão fácil</span>
        </div>
        <div class="flex items-center">
          <i class="fas fa-check-circle text-green-500 mr-2"></i>
          <span>Sem taxas</span>
        </div>
        <div class="flex items-center">
          <i class="fas fa-check-circle text-green-500 mr-2"></i>
          <span>Suporte 24h</span>
        </div>
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

<script>
  // Bloquear envio se senha < 8 para melhorar UX
  (function(){
    const form = document.querySelector('form[action="processa_login.php"]');
    const senha = document.getElementById('senha');
    const help = document.getElementById('senhaHelpLoginPrestador');
    if (!form) return;
    form.addEventListener('submit', function(e){
      if (senha.value.length < 8) {
        e.preventDefault();
        help.classList.remove('hidden');
        senha.focus();
      }
    });
  })();
</script>

<script>
  (function(){
    function initToggle() {
      document.querySelectorAll('.toggle-password').forEach(function(btn){
        btn.addEventListener('click', function(){
          var targetId = btn.getAttribute('data-target');
          var input = document.getElementById(targetId);
          if (!input) return;
          if (input.type === 'password') { input.type = 'text'; btn.setAttribute('aria-label','Ocultar senha'); btn.setAttribute('title','Ocultar senha'); }
          else { input.type = 'password'; btn.setAttribute('aria-label','Mostrar senha'); btn.setAttribute('title','Mostrar senha'); }
        });
      });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initToggle); else initToggle();
  })();
</script>

