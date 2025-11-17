<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro Prestador - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-2xl">
    <!-- Logo e Título -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 rounded-full mb-4">
        <i class="fas fa-briefcase text-white text-2xl"></i>
      </div>
      <h1 class="text-4xl font-bold text-gray-800 mb-2">Cadastro Prestador</h1>
      <p class="text-gray-600">Cadastre-se para oferecer seus serviços</p>
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
                  case 'upload_erro':
                    echo 'Erro no upload da foto. Tente novamente.';
                    break;
                  case 'categoria_obrigatoria':
                    echo 'Selecione uma categoria.';
                    break;
                  case 'senha_curta':
                    echo 'A senha deve ter no mínimo 8 caracteres.';
                    break;
                  case 'telefone_invalido':
                    $det = isset($_GET['detalhes']) ? htmlspecialchars(urldecode($_GET['detalhes'])) : '';
                    echo 'Telefone inválido. Use o formato (XX) 9XXXX-XXXX.' . ($det ? ' (detalhes: ' . $det . ')' : '');
                    break;
                  case 'formato_invalido':
                    echo 'Formato de imagem inválido. Use JPG, PNG ou GIF.';
                    break;
                  case 'erro_banco':
                    $detalhes = isset($_GET['detalhes']) ? htmlspecialchars(urldecode($_GET['detalhes'])) : 'Erro desconhecido';
                    echo 'Erro no banco de dados: ' . $detalhes;
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

      <?php
        include 'connection.php';
        // Buscar categorias para popular o select
        $categorias = [];
        $res = $conn->query("SELECT id, nome FROM categorias ORDER BY nome ASC");
        if ($res) {
          while ($row = $res->fetch_assoc()) {
            $categorias[] = $row;
          }
        }
      ?>

      <form action="processa_cadastro_prestador.php" method="POST" enctype="multipart/form-data" class="space-y-6">
        
        <!-- Informações Pessoais -->
        <div class="border-b border-gray-200 pb-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-user mr-2 text-green-600"></i>Informações Pessoais
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="nome" class="block text-sm font-semibold text-gray-700 mb-2">
                Nome Completo *
              </label>
              <input type="text" id="nome" name="nome" required
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                     placeholder="Seu nome completo">
            </div>

            <div>
              <label for="telefone" class="block text-sm font-semibold text-gray-700 mb-2">
                Telefone *
              </label>
              <input type="tel" id="telefone" name="telefone" required
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                     placeholder="(11) 99999-9999">
            </div>
          </div>

          <div class="mt-4">
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
              E-mail *
            </label>
            <input type="email" id="email" name="email" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                   placeholder="seu@email.com">
          </div>

          <div class="mt-4">
            <label for="endereco" class="block text-sm font-semibold text-gray-700 mb-2">
              Endereço de Atendimento
            </label>
            <input type="text" id="endereco" name="endereco"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                   placeholder="Rua, número, bairro, cidade">
          </div>
        </div>

        <!-- Informações Profissionais -->
        <div class="border-b border-gray-200 pb-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-tools mr-2 text-green-600"></i>Informações Profissionais
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="categoria_id" class="block text-sm font-semibold text-gray-700 mb-2">
                Categoria (área) *
              </label>
              <select id="categoria_id" name="categoria_id" required
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                <option value="">Selecione sua categoria</option>
                <?php foreach ($categorias as $cat): ?>
                  <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nome']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label for="foto" class="block text-sm font-semibold text-gray-700 mb-2">
                Foto do Perfil
              </label>
              <input type="file" id="foto" name="foto" accept="image/*"
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
            </div>
          </div>

          <div class="mt-4">
            <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-2">
              Descrição dos Serviços
            </label>
            <textarea id="descricao" name="descricao" rows="4"
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                      placeholder="Descreva seus serviços, experiência e diferenciais..."></textarea>
          </div>
        </div>

        <!-- Horário de Atendimento -->
        <div class="border-b border-gray-200 pb-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-clock mr-2 text-green-600"></i>Horário de Atendimento
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="horario_inicio" class="block text-sm font-semibold text-gray-700 mb-2">Início (dia)</label>
              <input type="time" id="horario_inicio" name="horario_inicio" value="08:00"
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
              <label for="horario_fim" class="block text-sm font-semibold text-gray-700 mb-2">Término (dia)</label>
              <input type="time" id="horario_fim" name="horario_fim" value="18:00"
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
          </div>
        </div>

        <!-- Segurança -->
        <div class="pb-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-lock mr-2 text-green-600"></i>Segurança
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="senha" class="block text-sm font-semibold text-gray-700 mb-2">
                Senha *
              </label>
                 <div class="relative">
                   <input type="password" id="senha" name="senha" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                          placeholder="••••••••">
                   <button type="button" data-target="senha" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700" aria-label="Mostrar senha" title="Mostrar senha">
                     <!-- eye SVG -->
                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                       <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                       <circle cx="12" cy="12" r="3"></circle>
                     </svg>
                   </button>
                 </div>
            </div>

            <div>
              <label for="confirmar_senha" class="block text-sm font-semibold text-gray-700 mb-2">
                Confirmar Senha *
              </label>
                 <div class="relative">
                   <input type="password" id="confirmar_senha" name="confirmar_senha" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                          placeholder="••••••••">
                   <button type="button" data-target="confirmar_senha" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700" aria-label="Mostrar senha" title="Mostrar senha">
                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                       <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                       <circle cx="12" cy="12" r="3"></circle>
                     </svg>
                   </button>
                 </div>
            </div>
          </div>
        </div>

        <div class="flex items-center">
          <input type="checkbox" id="termos" name="termos" required
                 class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
          <label for="termos" class="ml-2 text-sm text-gray-600">
            Aceito os <a href="#" class="text-green-600 hover:text-green-800 font-medium">termos de uso</a> e 
            <a href="#" class="text-green-600 hover:text-green-800 font-medium">política de privacidade</a>
          </label>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-xl shadow-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105 font-semibold">
          <i class="fas fa-briefcase mr-2"></i>Criar Conta de Prestador
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-gray-600 mb-4">Já tem uma conta?</p>
        <a href="login_prestador.php" 
           class="inline-block w-full bg-gray-100 text-gray-700 py-3 rounded-xl shadow hover:bg-gray-200 transition-all duration-200 font-semibold">
          <i class="fas fa-sign-in-alt mr-2"></i>Fazer Login
        </a>
      </div>

      <div class="mt-6 pt-6 border-t border-gray-200 text-center">
        <p class="text-gray-600 mb-3">Procurando por serviços?</p>
        <a href="cadastro_cliente.php" 
           class="inline-block text-green-600 hover:text-green-800 font-semibold transition-colors duration-200">
          <i class="fas fa-search mr-2"></i>Cadastrar como Cliente
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

    // Preview da foto
    const fotoInput = document.getElementById('foto');
    fotoInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          // Aqui você pode adicionar um preview da imagem se desejar
          console.log('Foto selecionada:', file.name);
        };
        reader.readAsDataURL(file);
      }
    });

    // Bloquear envio do formulário se a senha tiver menos de 8 caracteres
    const formPrestador = document.querySelector('form[action="processa_cadastro_prestador.php"]');
    const senhaHelpPrestador = document.createElement('p');
    senhaHelpPrestador.id = 'senhaHelpPrestador';
    senhaHelpPrestador.className = 'text-sm text-red-600 mt-2 hidden';
    senhaHelpPrestador.innerText = 'A senha deve ter no mínimo 8 caracteres.';
    senha.parentNode.appendChild(senhaHelpPrestador);

    formPrestador.addEventListener('submit', function(e) {
      if (senha.value.length < 8) {
        e.preventDefault();
        senhaHelpPrestador.classList.remove('hidden');
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
          if (input.type === 'password') {
            input.type = 'text';
            btn.setAttribute('aria-label','Ocultar senha');
            btn.setAttribute('title','Ocultar senha');
          } else {
            input.type = 'password';
            btn.setAttribute('aria-label','Mostrar senha');
            btn.setAttribute('title','Mostrar senha');
          }
        });
      });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initToggle); else initToggle();
  })();
</script>

