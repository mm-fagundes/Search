<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'prestador') {
    header("Location: login_prestador.php");
    exit;
}

include 'connection.php';

// Buscar dados do prestador
$prestador_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM prestadores WHERE id = ?");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result = $stmt->get_result();
$prestador = $result->fetch_assoc();

// Buscar estatísticas
$stmt = $conn->prepare("SELECT 
    COUNT(*) as total_avaliacoes,
    COALESCE(AVG(nota), 0) as media_avaliacao
    FROM avaliacoes WHERE prestador_id = ?");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();

// Buscar visualizações (historico) e contatos (agendamentos)
$stmt2 = $conn->prepare("SELECT COUNT(*) as visualizacoes FROM historico WHERE prestador_id = ? AND tipo_acao = 'visualizacao'");
$stmt2->bind_param("i", $prestador_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
$visualizacoes = $res2->fetch_assoc()['visualizacoes'] ?? 0;
$stmt2->close();

$stmt3 = $conn->prepare("SELECT COUNT(*) as contatos FROM agendamentos WHERE prestador_id = ?");
$stmt3->bind_param("i", $prestador_id);
$stmt3->execute();
$res3 = $stmt3->get_result();
$contatos = $res3->fetch_assoc()['contatos'] ?? 0;
$stmt3->close();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Prestador - Search</title>
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

        <div class="flex items-center space-x-6">
          <a href="dashboard_prestador.php" class="text-green-600 font-semibold border-b-2 border-green-600 pb-1">
            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
          </a>
          <a href="agenda_prestador.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-calendar mr-2"></i>Agenda
          </a>
          <a href="cadastrar_servicos.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-plus-circle mr-2"></i>Cadastrar Serviço
          </a>
          <a href="relatorios.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-chart-line mr-2"></i>Relatórios
          </a>
          <a href="perfil_prestador.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-user-cog mr-2"></i>Perfil
          </a>
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-green-600 text-sm"></i>
            </div>
            <span class="text-gray-700 font-medium"><?php echo htmlspecialchars($prestador['nome']); ?></span>
            <a href="logout.php" class="text-red-600 hover:text-red-700 font-medium transition-colors">
              <i class="fas fa-sign-out-alt"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Conteúdo Principal -->
  <div class="pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Header do Dashboard -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
          Bem-vindo, <?php echo htmlspecialchars($prestador['nome']); ?>!
        </h1>
        <p class="text-xl text-gray-600">Gerencie seu perfil e acompanhe seu desempenho</p>
      </div>

      <!-- Cards de Estatísticas -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-star text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Avaliação Média</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['media_avaliacao'], 1); ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-comments text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Avaliações</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total_avaliacoes']; ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-eye text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Visualizações</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($visualizacoes ?? 0); ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-phone text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Contatos</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($contatos ?? 0); ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Grid Principal -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Coluna Principal -->
        <div class="lg:col-span-2 space-y-8">
          
          <!-- Perfil do Prestador -->
          <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-circle mr-3 text-green-600"></i>Meu Perfil
              </h2>
              <button onclick="editarPerfil()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Editar
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="text-center md:text-left">
                <img src="<?php echo $prestador['foto'] ? '../uploads/' . htmlspecialchars($prestador['foto']) : 'https://via.placeholder.com/150x150/22C55E/FFFFFF?text=Foto'; ?>" 
                     alt="Foto do perfil" 
                     class="w-32 h-32 rounded-full object-cover mx-auto md:mx-0 mb-4 border-4 border-green-100">
                <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($prestador['nome']); ?></h3>
                <p class="text-green-600 font-medium mb-4">
                  <i class="fas fa-tools mr-2"></i><?php echo htmlspecialchars($prestador['nicho']); ?>
                </p>
              </div>

              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">E-mail</label>
                  <p class="text-gray-600"><?php echo htmlspecialchars($prestador['email']); ?></p>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Telefone</label>
                  <p class="text-gray-600"><?php echo htmlspecialchars($prestador['telefone']); ?></p>
                </div>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-1">Endereço</label>
                  <p class="text-gray-600"><?php echo htmlspecialchars($prestador['endereco'] ?? 'Não informado'); ?></p>
                </div>
              </div>
            </div>

            <?php if ($prestador['descricao']): ?>
            <div class="mt-6 pt-6 border-t border-gray-200">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Descrição dos Serviços</label>
              <p class="text-gray-600 leading-relaxed"><?php echo htmlspecialchars($prestador['descricao']); ?></p>
            </div>
            <?php endif; ?>
          </div>

          <!-- Avaliações Recentes -->
          <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
              <i class="fas fa-star mr-3 text-yellow-500"></i>Avaliações Recentes
            </h2>
            
            <div id="avaliacoes-recentes">
              <!-- Avaliações serão carregadas aqui -->
              <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-gray-400 text-2xl mb-2"></i>
                <p class="text-gray-500">Carregando avaliações...</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          
          <!-- Ações Rápidas -->
          <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
              <i class="fas fa-bolt mr-2 text-green-600"></i>Ações Rápidas
            </h3>
            
            <div class="space-y-3">
              <button onclick="editarPerfil()" class="w-full bg-green-600 text-white py-3 px-4 rounded-xl hover:bg-green-700 transition-colors flex items-center justify-center">
                <i class="fas fa-edit mr-2"></i>Editar Perfil
              </button>
              
              <a href="cadastrar_servicos.php" class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl hover:bg-blue-700 transition-colors flex items-center justify-center block">
                <i class="fas fa-plus mr-2"></i>Adicionar Serviço
              </a>
              
              <a href="relatorios.php" class="w-full bg-purple-600 text-white py-3 px-4 rounded-xl hover:bg-purple-700 transition-colors flex items-center justify-center block">
                <i class="fas fa-chart-line mr-2"></i>Ver Relatórios
              </a>
            </div>
          </div>

          <!-- Status do Perfil -->
          <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
              <i class="fas fa-check-circle mr-2 text-green-600"></i>Status do Perfil
            </h3>
            
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-gray-600">Foto do perfil</span>
                <i class="fas fa-check text-green-600"></i>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-600">Descrição</span>
                <i class="fas fa-check text-green-600"></i>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-600">Telefone</span>
                <i class="fas fa-check text-green-600"></i>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-600">Endereço</span>
                <?php if ($prestador['endereco']): ?>
                  <i class="fas fa-check text-green-600"></i>
                <?php else: ?>
                  <i class="fas fa-times text-red-600"></i>
                <?php endif; ?>
              </div>
            </div>
            
            <div class="mt-4 p-3 bg-green-50 rounded-lg">
              <p class="text-sm text-green-700 font-medium">
                <i class="fas fa-info-circle mr-2"></i>
                Perfil 85% completo
              </p>
            </div>
          </div>

          <!-- Dicas -->
          <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
              <i class="fas fa-lightbulb mr-2 text-blue-600"></i>Dicas para Sucesso
            </h3>
            
            <div class="space-y-3 text-sm">
              <div class="flex items-start">
                <i class="fas fa-check-circle text-blue-600 mt-1 mr-2"></i>
                <span class="text-gray-700">Mantenha seu perfil sempre atualizado</span>
              </div>
              <div class="flex items-start">
                <i class="fas fa-check-circle text-blue-600 mt-1 mr-2"></i>
                <span class="text-gray-700">Responda rapidamente aos contatos</span>
              </div>
              <div class="flex items-start">
                <i class="fas fa-check-circle text-blue-600 mt-1 mr-2"></i>
                <span class="text-gray-700">Peça avaliações aos clientes satisfeitos</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Edição de Perfil -->
  <div id="modal-editar-perfil" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Editar Perfil</h3>
        <p class="text-gray-600">Mantenha suas informações atualizadas</p>
      </div>
      
      <form id="form-editar-perfil" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="edit-nome" class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo</label>
            <input type="text" id="edit-nome" name="nome" value="<?php echo htmlspecialchars($prestador['nome']); ?>" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
          </div>
          
          <div>
            <label for="edit-telefone" class="block text-sm font-semibold text-gray-700 mb-2">Telefone</label>
            <input type="tel" id="edit-telefone" name="telefone" value="<?php echo htmlspecialchars($prestador['telefone']); ?>" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
          </div>
        </div>
        
        <div>
          <label for="edit-endereco" class="block text-sm font-semibold text-gray-700 mb-2">Endereço</label>
          <input type="text" id="edit-endereco" name="endereco" value="<?php echo htmlspecialchars($prestador['endereco'] ?? ''); ?>"
                 class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>
        
        <div>
          <label for="edit-nicho" class="block text-sm font-semibold text-gray-700 mb-2">Área de Atuação</label>
          <select id="edit-nicho" name="nicho" required
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
            <option value="Eletricista" <?php echo $prestador['nicho'] === 'Eletricista' ? 'selected' : ''; ?>>Eletricista</option>
            <option value="Encanador" <?php echo $prestador['nicho'] === 'Encanador' ? 'selected' : ''; ?>>Encanador</option>
            <option value="Pintor" <?php echo $prestador['nicho'] === 'Pintor' ? 'selected' : ''; ?>>Pintor</option>
            <option value="Pedreiro" <?php echo $prestador['nicho'] === 'Pedreiro' ? 'selected' : ''; ?>>Pedreiro</option>
            <option value="Marceneiro" <?php echo $prestador['nicho'] === 'Marceneiro' ? 'selected' : ''; ?>>Marceneiro</option>
            <option value="Jardineiro" <?php echo $prestador['nicho'] === 'Jardineiro' ? 'selected' : ''; ?>>Jardineiro</option>
            <option value="Limpeza" <?php echo $prestador['nicho'] === 'Limpeza' ? 'selected' : ''; ?>>Limpeza</option>
            <option value="Manutenção Geral" <?php echo $prestador['nicho'] === 'Manutenção Geral' ? 'selected' : ''; ?>>Manutenção Geral</option>
            <option value="Técnico em Informática" <?php echo $prestador['nicho'] === 'Técnico em Informática' ? 'selected' : ''; ?>>Técnico em Informática</option>
            <option value="Outros" <?php echo $prestador['nicho'] === 'Outros' ? 'selected' : ''; ?>>Outros</option>
          </select>
        </div>
        
        <div>
          <label for="edit-descricao" class="block text-sm font-semibold text-gray-700 mb-2">Descrição dos Serviços</label>
          <textarea id="edit-descricao" name="descricao" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Descreva seus serviços, experiência e diferenciais..."><?php echo htmlspecialchars($prestador['descricao'] ?? ''); ?></textarea>
        </div>
        
        <div>
          <label for="edit-foto" class="block text-sm font-semibold text-gray-700 mb-2">Nova Foto do Perfil</label>
          <input type="file" id="edit-foto" name="foto" accept="image/*"
                 class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
          <p class="text-sm text-gray-500 mt-1">Deixe em branco para manter a foto atual</p>
        </div>
        
        <div class="flex space-x-4">
          <button type="button" onclick="fecharModalEdicao()" 
                  class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 transition-colors">
            Cancelar
          </button>
          <button type="submit" 
                  class="flex-1 bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition-colors">
            Salvar Alterações
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // Carregar avaliações recentes
    document.addEventListener('DOMContentLoaded', function() {
      carregarAvaliacoesRecentes();
    });
    
    function carregarAvaliacoesRecentes() {
      fetch(`obter_avaliacoes.php?prestador_id=<?php echo $prestador_id; ?>`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            exibirAvaliacoesRecentes(data.data.avaliacoes);
          } else {
            document.getElementById('avaliacoes-recentes').innerHTML = 
              '<p class="text-center text-gray-500 py-8">Erro ao carregar avaliações</p>';
          }
        })
        .catch(error => {
          console.error('Erro:', error);
          document.getElementById('avaliacoes-recentes').innerHTML = 
            '<p class="text-center text-gray-500 py-8">Erro ao carregar avaliações</p>';
        });
    }
    
    function exibirAvaliacoesRecentes(avaliacoes) {
      const container = document.getElementById('avaliacoes-recentes');
      
      if (avaliacoes.length === 0) {
        container.innerHTML = `
          <div class="text-center py-8">
            <i class="fas fa-star text-gray-300 text-4xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Ainda não há avaliações</h3>
            <p class="text-gray-500">Suas avaliações aparecerão aqui</p>
          </div>
        `;
        return;
      }
      
      const avaliacoesRecentes = avaliacoes.slice(0, 3);
      let html = '<div class="space-y-4">';
      
      avaliacoesRecentes.forEach(avaliacao => {
        html += `
          <div class="border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="font-semibold text-gray-800">${avaliacao.cliente_nome}</h4>
              <div class="flex items-center text-yellow-400">
                ${gerarEstrelas(avaliacao.nota)}
              </div>
            </div>
            ${avaliacao.comentario ? `<p class="text-gray-600 mb-2">${avaliacao.comentario}</p>` : ''}
            <p class="text-sm text-gray-400">${formatarData(avaliacao.criado_em)}</p>
          </div>
        `;
      });
      
      html += '</div>';
      
      if (avaliacoes.length > 3) {
        html += `
          <div class="mt-4 text-center">
            <a href="detalhes_prestador.php?id=<?php echo $prestador_id; ?>" class="text-green-600 hover:text-green-800 font-semibold">
              Ver todas as avaliações <i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
        `;
      }
      
      container.innerHTML = html;
    }
    
    function gerarEstrelas(nota) {
      let estrelas = '';
      for (let i = 1; i <= 5; i++) {
        if (i <= nota) {
          estrelas += '<i class="fas fa-star text-sm"></i>';
        } else {
          estrelas += '<i class="far fa-star text-sm"></i>';
        }
      }
      return estrelas;
    }
    
    function formatarData(dataString) {
      const data = new Date(dataString);
      const agora = new Date();
      const diffTime = Math.abs(agora - data);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      
      if (diffDays === 1) return 'Há 1 dia';
      if (diffDays < 7) return `Há ${diffDays} dias`;
      if (diffDays < 30) return `Há ${Math.ceil(diffDays / 7)} semanas`;
      return `Há ${Math.ceil(diffDays / 30)} meses`;
    }
    
    // Modal de edição
    function editarPerfil() {
      document.getElementById('modal-editar-perfil').classList.remove('hidden');
    }
    
    function fecharModalEdicao() {
      document.getElementById('modal-editar-perfil').classList.add('hidden');
    }
    
    // Enviar edição do perfil
    document.getElementById('form-editar-perfil').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch('atualizar_perfil_prestador.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          fecharModalEdicao();
          location.reload(); // Recarregar página para mostrar alterações
        } else {
          alert('Erro ao atualizar perfil: ' + data.error);
        }
      })
      .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao atualizar perfil');
      });
    });
  </script>

  <?php include 'footer.php'; ?>
</body>
</html>

