<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'prestador') {
    header("Location: login_prestador.php");
    exit;
}

include 'connection.php';

$prestador_id = $_SESSION['user_id'];

// Buscar serviços já cadastrados
$stmt = $conn->prepare("SELECT * FROM servicos WHERE prestador_id = ? ORDER BY criado_em DESC");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result = $stmt->get_result();
$servicos = [];

while ($row = $result->fetch_assoc()) {
    $servicos[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Serviços - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .card-hover {
      transition: all 0.3s ease;
    }
    .card-hover:hover {
      transform: translateY(-4px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-emerald-50 min-h-screen">

  <!-- Navbar -->
  <nav class="bg-white/95 backdrop-blur-md shadow-lg fixed w-full z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-tools text-white text-lg"></i>
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Search</span>
          </div>
        </div>

        <div class="hidden md:flex items-center space-x-6">
          <a href="dashboard_prestador.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
          </a>
          <a href="agenda_prestador.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-calendar mr-2"></i>Agenda
          </a>
          <a href="cadastrar_servicos.php" class="text-green-600 font-semibold border-b-2 border-green-600 pb-1">
            <i class="fas fa-plus-circle mr-2"></i>Cadastrar Serviço
          </a>
          <a href="relatorios.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-chart-line mr-2"></i>Relatórios
          </a>
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-green-600 text-sm"></i>
            </div>
            <span class="text-gray-700 font-medium">Prestador</span>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
          <i class="fas fa-plus-circle text-green-600 mr-3"></i>Cadastrar Serviços
        </h1>
        <p class="text-xl text-gray-600">Adicione e gerencie seus serviços</p>
      </div>

      <!-- Grid com formulário e lista -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Coluna Formulário -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 card-hover sticky top-24">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
              <i class="fas fa-plus text-green-600 mr-2"></i>Novo Serviço
            </h2>
            
            <form id="form-servico" class="space-y-4">
              <div>
                <label for="nome_servico" class="block text-sm font-semibold text-gray-700 mb-2">Nome do Serviço</label>
                <input type="text" id="nome_servico" name="nome_servico" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Ex: Reparo Hidráulico">
              </div>

              <div>
                <label for="descricao_servico" class="block text-sm font-semibold text-gray-700 mb-2">Descrição</label>
                <textarea id="descricao_servico" name="descricao_servico" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                          placeholder="Descreva detalhadamente o seu serviço..."></textarea>
              </div>

              <div>
                <label for="preco_base" class="block text-sm font-semibold text-gray-700 mb-2">Preço Base (R$)</label>
                <input type="number" id="preco_base" name="preco_base" step="0.01" min="0" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="0.00">
              </div>

              <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-xl hover:bg-green-700 transition-colors font-bold mt-6">
                <i class="fas fa-check mr-2"></i>Cadastrar Serviço
              </button>
            </form>
          </div>
        </div>

        <!-- Coluna Lista -->
        <div class="lg:col-span-2">
          <div class="space-y-4">
            <?php if (empty($servicos)): ?>
              <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                  <i class="fas fa-plus-circle text-green-600 text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Nenhum serviço cadastrado</h2>
                <p class="text-gray-600">Comece a adicionar seus serviços usando o formulário ao lado!</p>
              </div>
            <?php else: ?>
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                  <i class="fas fa-list text-green-600 mr-2"></i>Seus Serviços
                </h2>
                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-bold"><?php echo count($servicos); ?> serviço<?php echo count($servicos) !== 1 ? 's' : ''; ?></span>
              </div>

              <?php foreach ($servicos as $servico): ?>
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
                  <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                      <h3 class="text-lg font-bold text-gray-800 mb-1">
                        <i class="fas fa-tools text-green-600 mr-2"></i><?php echo htmlspecialchars($servico['nome_servico']); ?>
                      </h3>
                      <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($servico['descricao_servico'] ?? ''); ?></p>
                    </div>
                    <div class="text-right">
                      <p class="text-3xl font-bold text-green-600">
                        R$ <?php echo number_format($servico['preco_base'], 2, ',', '.'); ?>
                      </p>
                      <p class="text-xs text-gray-500">Preço base</p>
                    </div>
                  </div>

                  <div class="flex space-x-2 pt-4 border-t border-gray-200">
                    <button onclick="editarServico(<?php echo $servico['id']; ?>)" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                      <i class="fas fa-edit mr-2"></i>Editar
                    </button>
                    <button onclick="deletarServico(<?php echo $servico['id']; ?>)" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                      <i class="fas fa-trash mr-2"></i>Deletar
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Edição -->
  <div id="modal-editar" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 max-w-lg w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Editar Serviço</h2>
      
      <form id="form-editar-servico" class="space-y-4">
        <input type="hidden" id="servico_id" name="servico_id">
        
        <div>
          <label for="edit-nome_servico" class="block text-sm font-semibold text-gray-700 mb-2">Nome do Serviço</label>
          <input type="text" id="edit-nome_servico" name="nome_servico" required
                 class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div>
          <label for="edit-descricao_servico" class="block text-sm font-semibold text-gray-700 mb-2">Descrição</label>
          <textarea id="edit-descricao_servico" name="descricao_servico" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
        </div>

        <div>
          <label for="edit-preco_base" class="block text-sm font-semibold text-gray-700 mb-2">Preço Base (R$)</label>
          <input type="number" id="edit-preco_base" name="preco_base" step="0.01" min="0" required
                 class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="flex space-x-4 pt-4">
          <button type="button" onclick="fecharModal()" 
                  class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 transition-colors">
            Cancelar
          </button>
          <button type="submit" 
                  class="flex-1 bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition-colors font-bold">
            Salvar Alterações
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // Criar novo serviço
    document.getElementById('form-servico').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = {
        nome_servico: document.getElementById('nome_servico').value,
        descricao_servico: document.getElementById('descricao_servico').value,
        preco_base: document.getElementById('preco_base').value
      };
      
      fetch('processa_servico.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          acao: 'criar',
          ...formData
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert('Erro ao cadastrar serviço: ' + data.error);
        }
      })
      .catch(error => console.error('Erro:', error));
    });

    function editarServico(servicoId) {
      const servicos = <?php echo json_encode($servicos); ?>;
      const servico = servicos.find(s => s.id == servicoId);
      
      if (servico) {
        document.getElementById('servico_id').value = servico.id;
        document.getElementById('edit-nome_servico').value = servico.nome_servico;
        document.getElementById('edit-descricao_servico').value = servico.descricao_servico || '';
        document.getElementById('edit-preco_base').value = servico.preco_base;
        document.getElementById('modal-editar').classList.remove('hidden');
      }
    }

    function fecharModal() {
      document.getElementById('modal-editar').classList.add('hidden');
    }

    document.getElementById('form-editar-servico').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = {
        servico_id: document.getElementById('servico_id').value,
        nome_servico: document.getElementById('edit-nome_servico').value,
        descricao_servico: document.getElementById('edit-descricao_servico').value,
        preco_base: document.getElementById('edit-preco_base').value
      };
      
      fetch('processa_servico.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          acao: 'atualizar',
          ...formData
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert('Erro ao atualizar serviço: ' + data.error);
        }
      })
      .catch(error => console.error('Erro:', error));
    });

    function deletarServico(servicoId) {
      if (confirm('Tem certeza que deseja deletar este serviço?')) {
        fetch('processa_servico.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            acao: 'deletar',
            servico_id: servicoId
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Erro ao deletar serviço');
          }
        })
        .catch(error => console.error('Erro:', error));
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
          <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
        </a>
        <a href="agenda_prestador.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-calendar mr-2"></i>Agenda
        </a>
        <a href="cadastrar_servicos.php" class="block px-4 py-2 text-green-600 font-medium hover:bg-gray-100">
          <i class="fas fa-plus-circle mr-2"></i>Cadastrar Serviço
        </a>
        <a href="relatorios.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-chart-line mr-2"></i>Relatórios
        </a>
        <a href="logout.php" class="block px-4 py-2 text-red-600 font-medium hover:bg-gray-100">
          <i class="fas fa-sign-out-alt mr-2"></i>Sair
        </a>
      `;
      document.querySelector('nav').parentNode.appendChild(menu);
      return menu;
    }
  </script>

</body>
</html>
