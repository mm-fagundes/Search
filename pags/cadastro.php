<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

  <div class="min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
      <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Criar Conta</h2>
      
      <?php if (isset($_GET['erro'])): ?>
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded">
          <span class="text-red-700 font-medium">
            <?php
              switch($_GET['erro']) {
                case 'dados_invalidos': echo 'Por favor, preencha todos os campos.'; break;
                case 'senha_curta': echo 'A senha deve ter no mínimo 8 caracteres.'; break;
                case 'senhas_diferentes': echo 'As senhas não coincidem.'; break;
                case 'email_existe': echo 'Este e-mail já está cadastrado.'; break;
                case 'erro_banco': echo 'Erro ao salvar os dados. Tente novamente.'; break;
                default: echo 'Erro no cadastro. Tente novamente.';
              }
            ?>
          </span>
        </div>
      <?php endif; ?>

      <form id="cadastroForm" action="processa_cadastro.php" method="POST" class="space-y-4">
        <div>
          <label for="nome" class="block text-gray-700">Nome completo</label>
          <input type="text" id="nome" name="nome" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div>
          <label for="email" class="block text-gray-700">Email</label>
          <input type="email" id="email" name="email" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div>
          <label for="senha" class="block text-gray-700">Senha</label>
          <div class="relative">
            <input type="password" id="senha" name="senha" required minlength="8"
              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button type="button" data-target="senha" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-500" aria-label="Mostrar senha">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </button>
          </div>
          <p id="senhaHelp" class="text-sm text-red-600 mt-1 hidden">A senha deve ter no mínimo 8 caracteres.</p>
        </div>
        <div>
          <label for="confirmar_senha" class="block text-gray-700">Confirmar Senha</label>
          <div class="relative">
            <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="8"
              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button type="button" data-target="confirmar_senha" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-500" aria-label="Mostrar senha">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </button>
          </div>
        </div>
        <button type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-lg shadow hover:bg-blue-700 transition">
          Cadastrar
        </button>
      </form>

      <p class="mt-4 text-center text-gray-600">
        Já tem conta?
        <a href="login.php" class="text-blue-600 hover:underline">Entrar</a>
      </p>
    </div>
  </div>


  <script>
    // Impedir envio se senha tiver menos de 8 caracteres (melhora UX antes do envio)
    const cadastroForm = document.getElementById('cadastroForm');
    const senha = document.getElementById('senha');
    const senhaHelp = document.getElementById('senhaHelp');

    cadastroForm.addEventListener('submit', function(e) {
      if (senha.value.length < 8) {
        e.preventDefault();
        senhaHelp.classList.remove('hidden');
        senha.focus();
      }
    });
  </script>

</body>
</html>

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
