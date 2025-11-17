<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'prestador') {
    header("Location: login_prestador.php");
    exit;
}

include 'connection.php';

$prestador_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM prestadores WHERE id = ?");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result = $stmt->get_result();
$prestador = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meu Perfil - Prestador</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-emerald-50 min-h-screen">

  <!-- Navbar -->
  <nav class="bg-white/80 backdrop-blur-md shadow-lg fixed w-full z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex-shrink-0 flex items-center">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-briefcase text-white text-lg"></i>
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Search</span>
          </div>
        </div>

        <div class="hidden md:flex space-x-6 items-center">
          <a href="dashboard_prestador.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-home mr-2"></i>Início
          </a>
          <a href="agenda_prestador.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-calendar mr-2"></i>Agenda
          </a>
          <a href="cadastrar_servicos.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-plus-circle mr-2"></i>Serviços
          </a>
          <a href="relatorios.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-chart-line mr-2"></i>Relatórios
          </a>
          <a href="perfil_prestador.php" class="text-green-600 font-semibold border-b-2 border-green-600 pb-1">
            <i class="fas fa-user-cog mr-2"></i>Perfil
          </a>
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-green-600 text-sm"></i>
            </div>
            <span class="text-gray-700 font-medium"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Prestador'); ?></span>
            <a href="logout.php" class="text-red-600 hover:text-red-700 font-medium transition-colors">
              <i class="fas fa-sign-out-alt"></i>
            </a>
          </div>
        </div>

        <div class="md:hidden flex items-center">
          <button id="menu-btn" class="text-gray-700 hover:text-green-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Conteúdo Principal -->
  <div class="pt-24 pb-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
          <i class="fas fa-user-cog text-green-600 mr-3"></i>Meu Perfil
        </h1>
        <p class="text-xl text-gray-600">Gerencie suas informações profissionais</p>
      </div>

      <!-- Grid com abas -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <!-- Sidebar de Navegação -->
        <div class="md:col-span-1">
          <div class="bg-white rounded-2xl shadow-lg p-4 border border-gray-100 sticky top-24">
            <nav class="space-y-2">
              <button class="tab-nav w-full text-left px-4 py-3 rounded-xl bg-green-100 text-green-600 font-semibold transition-colors" data-tab="dados">
                <i class="fas fa-user mr-2"></i>Dados Profissionais
              </button>
              <button class="tab-nav w-full text-left px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-100 transition-colors" data-tab="endereco">
                <i class="fas fa-map-marker-alt mr-2"></i>Localização
              </button>
              <button class="tab-nav w-full text-left px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-100 transition-colors" data-tab="preferencias">
                <i class="fas fa-cog mr-2"></i>Preferências
              </button>
              <button class="tab-nav w-full text-left px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-100 transition-colors" data-tab="seguranca">
                <i class="fas fa-shield-alt mr-2"></i>Segurança
              </button>
            </nav>
          </div>
        </div>

        <!-- Conteúdo das Abas -->
        <div class="md:col-span-3 space-y-6">
          
          <!-- Aba Dados Profissionais -->
          <div id="dados-tab" class="tab-content bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Dados Profissionais</h2>
            
            <form id="form-dados" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="nome" class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo</label>
                  <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($prestador['nome']); ?>" 
                         required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                  <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">E-mail</label>
                  <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($prestador['email']); ?>" 
                         required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="telefone" class="block text-sm font-semibold text-gray-700 mb-2">Telefone</label>
                  <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($prestador['telefone'] ?? ''); ?>" 
                         class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                         placeholder="(11) 99999-9999">
                </div>

                <div>
                  <label for="nicho" class="block text-sm font-semibold text-gray-700 mb-2">Nicho/Especialidade</label>
                  <input type="text" id="nicho" name="nicho" value="<?php echo htmlspecialchars($prestador['nicho'] ?? ''); ?>" 
                         required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                         placeholder="Ex: Encanador, Eletricista, Designer">
                </div>
              </div>

              <div>
                <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-2">Descrição Profissional</label>
                <textarea id="descricao" name="descricao" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                          placeholder="Descreva seu trabalho, experiência e principais qualificações..."><?php echo htmlspecialchars($prestador['descricao'] ?? ''); ?></textarea>
              </div>

              <div class="flex space-x-4 pt-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-colors font-semibold">
                  <i class="fas fa-save mr-2"></i>Salvar Alterações
                </button>
                <button type="reset" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-colors font-semibold">
                  <i class="fas fa-redo mr-2"></i>Cancelar
                </button>
              </div>
            </form>
          </div>

          <!-- Aba Localização -->
          <div id="endereco-tab" class="tab-content hidden bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Localização</h2>
            
            <form id="form-endereco" class="space-y-6">
              <div>
                <label for="endereco" class="block text-sm font-semibold text-gray-700 mb-2">Endereço Completo</label>
                <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($prestador['endereco'] ?? ''); ?>" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Rua, número, complemento">
              </div>

              <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                <p class="text-sm text-green-700">
                  <i class="fas fa-info-circle mr-2"></i>
                  Seu endereço é usado para mostrar sua localização aos clientes que procuram serviços em sua região.
                </p>
              </div>

              <div class="flex space-x-4 pt-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-colors font-semibold">
                  <i class="fas fa-save mr-2"></i>Salvar Alterações
                </button>
                <button type="reset" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-colors font-semibold">
                  <i class="fas fa-redo mr-2"></i>Cancelar
                </button>
              </div>
            </form>
          </div>

          <!-- Aba Preferências -->
          <div id="preferencias-tab" class="tab-content hidden bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Preferências</h2>
            
            <div class="space-y-6">
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                  <h3 class="font-semibold text-gray-800">Notificações por E-mail</h3>
                  <p class="text-sm text-gray-600">Receba avisos sobre novos agendamentos</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked>
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                  <h3 class="font-semibold text-gray-800">Notificações por SMS</h3>
                  <p class="text-sm text-gray-600">Receba mensagens sobre confirmações de serviço</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked>
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                  <h3 class="font-semibold text-gray-800">Aparecer em Buscas</h3>
                  <p class="text-sm text-gray-600">Permitir que clientes o encontrem em buscas</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked>
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                  <h3 class="font-semibold text-gray-800">Avisos de Pagamentos</h3>
                  <p class="text-sm text-gray-600">Notificações sobre pagamentos recebidos</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked>
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                </label>
              </div>
            </div>
          </div>

          <!-- Aba Segurança -->
          <div id="seguranca-tab" class="tab-content hidden bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Segurança</h2>
            
            <form id="form-seguranca" class="space-y-6">
              <div>
                <label for="senha-atual" class="block text-sm font-semibold text-gray-700 mb-2">Senha Atual</label>
                <input type="password" id="senha-atual" name="senha_atual" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Digite sua senha atual">
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="nova-senha" class="block text-sm font-semibold text-gray-700 mb-2">Nova Senha</label>
                  <input type="password" id="nova-senha" name="nova_senha" 
                         class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                         placeholder="Digite a nova senha">
                  <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres com números e símbolos</p>
                </div>

                <div>
                  <label for="confirmar-senha" class="block text-sm font-semibold text-gray-700 mb-2">Confirmar Senha</label>
                  <input type="password" id="confirmar-senha" name="confirmar_senha" 
                         class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                         placeholder="Confirme a nova senha">
                </div>
              </div>

              <div class="flex space-x-4 pt-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-colors font-semibold">
                  <i class="fas fa-lock mr-2"></i>Alterar Senha
                </button>
              </div>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-200">
              <h3 class="text-lg font-bold text-gray-800 mb-4">Deletar Conta</h3>
              <p class="text-gray-600 mb-4">Uma vez deletada, não há como recuperar sua conta e todos os seus dados serão removidos.</p>
              <button onclick="deletarConta()" class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-colors font-semibold">
                <i class="fas fa-trash mr-2"></i>Deletar Conta Permanentemente
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // Abas de navegação
    document.querySelectorAll('.tab-nav').forEach(btn => {
      btn.addEventListener('click', function() {
        const tab = this.dataset.tab;
        
        // Remover ativo de todos
        document.querySelectorAll('.tab-nav').forEach(b => {
          b.classList.remove('bg-green-100', 'text-green-600');
          b.classList.add('text-gray-700', 'hover:bg-gray-100');
        });
        
        // Adicionar ativo ao clicado
        this.classList.add('bg-green-100', 'text-green-600');
        this.classList.remove('text-gray-700', 'hover:bg-gray-100');
        
        // Mostrar/ocultar conteúdo
        document.querySelectorAll('.tab-content').forEach(content => {
          content.classList.add('hidden');
        });
        document.getElementById(tab + '-tab').classList.remove('hidden');
      });
    });

    // Formulário Dados Profissionais
    document.getElementById('form-dados').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = {
        nome: document.getElementById('nome').value,
        email: document.getElementById('email').value,
        telefone: document.getElementById('telefone').value,
        nicho: document.getElementById('nicho').value,
        descricao: document.getElementById('descricao').value
      };
      
      fetch('processa_perfil_prestador.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          acao: 'atualizar_dados',
          ...formData
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Dados atualizados com sucesso!');
        } else {
          alert('Erro ao atualizar dados: ' + data.error);
        }
      })
      .catch(error => console.error('Erro:', error));
    });

    // Formulário Endereço
    document.getElementById('form-endereco').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = {
        endereco: document.getElementById('endereco').value
      };
      
      fetch('processa_perfil_prestador.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          acao: 'atualizar_endereco',
          ...formData
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Localização atualizada com sucesso!');
        } else {
          alert('Erro ao atualizar localização: ' + data.error);
        }
      })
      .catch(error => console.error('Erro:', error));
    });

    // Formulário Segurança
    document.getElementById('form-seguranca').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const novaSenha = document.getElementById('nova-senha').value;
      const confirmarSenha = document.getElementById('confirmar-senha').value;
      
      if (novaSenha !== confirmarSenha) {
        alert('As senhas não correspondem!');
        return;
      }
      
      if (novaSenha.length < 8) {
        alert('A senha deve ter no mínimo 8 caracteres!');
        return;
      }
      
      const formData = {
        senha_atual: document.getElementById('senha-atual').value,
        nova_senha: novaSenha
      };
      
      fetch('processa_perfil_prestador.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          acao: 'alterar_senha',
          ...formData
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Senha alterada com sucesso!');
          document.getElementById('form-seguranca').reset();
        } else {
          alert('Erro ao alterar senha: ' + data.error);
        }
      })
      .catch(error => console.error('Erro:', error));
    });

    function deletarConta() {
      if (confirm('Tem CERTEZA que deseja deletar sua conta? Esta ação é irreversível!')) {
        if (confirm('Última confirmação: Todos os seus dados profissionais serão perdidos permanentemente. Deseja continuar?')) {
          fetch('processa_perfil_prestador.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              acao: 'deletar_conta'
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Conta deletada com sucesso. Você será redirecionado.');
              window.location.href = 'index.php';
            } else {
              alert('Erro ao deletar conta: ' + data.error);
            }
          })
          .catch(error => console.error('Erro:', error));
        }
      }
    }

    // Menu mobile
    const btn = document.getElementById('menu-btn');
    if (btn) {
      btn.addEventListener('click', () => {
        const menu = document.getElementById('mobile-menu') || createMobileMenu();
        menu.classList.toggle('hidden');
      });
    }

    function createMobileMenu() {
      const menu = document.createElement('div');
      menu.id = 'mobile-menu';
      menu.className = 'hidden md:hidden bg-white border-t shadow-md';
      menu.innerHTML = `
        <a href="dashboard_prestador.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-home mr-2"></i>Início
        </a>
        <a href="agenda_prestador.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-calendar mr-2"></i>Agenda
        </a>
        <a href="cadastrar_servicos.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-plus-circle mr-2"></i>Serviços
        </a>
        <a href="relatorios.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-chart-line mr-2"></i>Relatórios
        </a>
        <a href="perfil_prestador.php" class="block px-4 py-2 text-green-600 font-medium hover:bg-gray-100">
          <i class="fas fa-user-cog mr-2"></i>Perfil
        </a>
        <a href="logout.php" class="block px-4 py-2 text-red-600 font-medium hover:bg-gray-100">
          <i class="fas fa-sign-out-alt mr-2"></i>Sair
        </a>
      `;
      document.querySelector('nav').parentNode.appendChild(menu);
      return menu;
    }
  </script>

  <?php include 'footer.php'; ?>
</body>
</html>
