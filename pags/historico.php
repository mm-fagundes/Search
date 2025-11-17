<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_cliente.php");
    exit;
}

include 'connection.php';

$cliente_id = $_SESSION['user_id'];

// Buscar histórico do cliente
$stmt = $conn->prepare("
    SELECT h.*, p.id as prestador_id, p.nome, p.foto, p.nicho
    FROM historico h
    LEFT JOIN prestadores p ON h.prestador_id = p.id
    WHERE h.cliente_id = ?
    ORDER BY h.criado_em DESC
    LIMIT 100
");

$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$historico = [];

while ($row = $result->fetch_assoc()) {
    $historico[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Histórico - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .item-hover {
      transition: all 0.3s ease;
    }
    .item-hover:hover {
      transform: translateX(4px);
      background-color: #f9fafb;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">

  <!-- Navbar -->
  <nav class="bg-white/80 backdrop-blur-md shadow-lg fixed w-full z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex-shrink-0 flex items-center">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-search text-white text-lg"></i>
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Search</span>
          </div>
        </div>

        <div class="hidden md:flex space-x-6 items-center">
          <a href="home_cliente.php" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
            <i class="fas fa-home mr-2"></i>Início
          </a>
          <a href="favoritos.php" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
            <i class="fas fa-heart mr-2"></i>Favoritos
          </a>
          <a href="historico.php" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">
            <i class="fas fa-history mr-2"></i>Histórico
          </a>
          <a href="<?php echo (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'prestador') ? 'perfil_prestador.php' : 'perfil_cliente.php'; ?>" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
            <i class="fas fa-user-cog mr-2"></i>Perfil
          </a>
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-blue-600 text-sm"></i>
            </div>
            <span class="text-gray-700 font-medium"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Cliente'); ?></span>
            <a href="logout.php" class="text-red-600 hover:text-red-700 font-medium transition-colors">
              <i class="fas fa-sign-out-alt"></i>
            </a>
          </div>
        </div>

        <div class="md:hidden flex items-center">
          <button id="menu-btn" class="text-gray-700 hover:text-blue-600 focus:outline-none">
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
          <i class="fas fa-history text-blue-600 mr-3"></i>Meu Histórico
        </h1>
        <p class="text-xl text-gray-600">Veja seus acessos recentes e buscas realizadas</p>
      </div>

      <?php if (empty($historico)): ?>
        <!-- Estado Vazio -->
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
          <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-history text-blue-600 text-4xl"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-800 mb-2">Nenhum histórico ainda</h2>
          <p class="text-gray-600 mb-6 max-w-md mx-auto">
            Quando você buscar ou visualizar prestadores, o histórico aparecerá aqui.
          </p>
          <a href="home_cliente.php" class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold">
            <i class="fas fa-search mr-2"></i>Buscar Prestadores
          </a>
        </div>
      <?php else: ?>
        <!-- Abas -->
        <div class="mb-8 flex space-x-4 border-b border-gray-200">
          <button class="tab-btn py-3 px-4 font-semibold text-blue-600 border-b-2 border-blue-600" data-tab="visualizacoes">
            <i class="fas fa-eye mr-2"></i>Visualizações
          </button>
          <button class="tab-btn py-3 px-4 font-semibold text-gray-600 border-b-2 border-transparent hover:text-blue-600" data-tab="buscas">
            <i class="fas fa-search mr-2"></i>Buscas
          </button>
        </div>

        <!-- Conteúdo das Abas -->
        <div id="visualizacoes-tab" class="tab-content">
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="divide-y divide-gray-200">
              <?php 
                $visualizacoes = array_filter($historico, fn($h) => $h['tipo_acao'] === 'visualizacao');
                if (empty($visualizacoes)): 
              ?>
                <div class="p-8 text-center text-gray-500">
                  <i class="fas fa-inbox text-3xl mb-2"></i>
                  <p>Nenhuma visualização registrada</p>
                </div>
              <?php else:
                foreach ($visualizacoes as $item):
              ?>
                <div class="p-6 item-hover flex items-center justify-between cursor-pointer" 
                     onclick="window.location.href='detalhes_prestador.php?id=<?php echo $item['prestador_id']; ?>'">
                  <div class="flex items-center space-x-4">
                    <img src="<?php echo $item['foto'] ? '../uploads/' . htmlspecialchars($item['foto']) : 'https://via.placeholder.com/80x80/4F46E5/FFFFFF?text=Sem+Foto'; ?>" 
                         alt="<?php echo htmlspecialchars($item['nome'] ?? 'Prestador'); ?>"
                         class="w-16 h-16 rounded-lg object-cover">
                    <div>
                      <h3 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($item['nome'] ?? 'Prestador removido'); ?></h3>
                      <p class="text-blue-600 font-medium text-sm">
                        <i class="fas fa-tools mr-1"></i><?php echo htmlspecialchars($item['nicho'] ?? 'Serviços'); ?>
                      </p>
                      <p class="text-gray-500 text-sm"><?php echo formatarDataRelativa($item['criado_em']); ?></p>
                    </div>
                  </div>
                  <button class="text-gray-400 hover:text-red-600 transition-colors">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              <?php 
                endforeach;
                endif;
              ?>
            </div>
          </div>
        </div>

        <div id="buscas-tab" class="tab-content hidden">
          <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="divide-y divide-gray-200">
              <?php 
                $buscas = array_filter($historico, fn($h) => $h['tipo_acao'] === 'busca');
                if (empty($buscas)): 
              ?>
                <div class="p-8 text-center text-gray-500">
                  <i class="fas fa-inbox text-3xl mb-2"></i>
                  <p>Nenhuma busca registrada</p>
                </div>
              <?php else:
                foreach ($buscas as $item):
              ?>
                <div class="p-6 item-hover flex items-center justify-between">
                  <div>
                    <h3 class="text-lg font-bold text-gray-800">
                      <i class="fas fa-search text-blue-600 mr-2"></i><?php echo htmlspecialchars($item['termo_busca']); ?>
                    </h3>
                    <p class="text-gray-500 text-sm mt-1"><?php echo formatarDataRelativa($item['criado_em']); ?></p>
                  </div>
                  <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                          onclick="buscarNovamente('<?php echo htmlspecialchars($item['termo_busca']); ?>')">
                    Buscar Novamente
                  </button>
                </div>
              <?php 
                endforeach;
                endif;
              ?>
            </div>
          </div>
        </div>

        <!-- Botão Limpar Histórico -->
        <div class="mt-8">
          <button onclick="limparHistorico()" class="bg-red-100 text-red-600 px-6 py-3 rounded-xl hover:bg-red-200 transition-colors font-semibold">
            <i class="fas fa-trash mr-2"></i>Limpar Histórico
          </button>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    function formatarDataRelativa(data) {
      const d = new Date(data);
      const agora = new Date();
      const diff = Math.floor((agora - d) / 1000); // diferença em segundos

      if (diff < 60) return 'Há alguns segundos';
      if (diff < 3600) return `Há ${Math.floor(diff / 60)} minutos`;
      if (diff < 86400) return `Há ${Math.floor(diff / 3600)} horas`;
      if (diff < 604800) return `Há ${Math.floor(diff / 86400)} dias`;
      
      return d.toLocaleDateString('pt-BR');
    }

    // Abas
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const tab = this.dataset.tab;
        
        // Remover ativo de todos
        document.querySelectorAll('.tab-btn').forEach(b => {
          b.classList.remove('text-blue-600', 'border-blue-600');
          b.classList.add('text-gray-600', 'border-transparent');
        });
        
        // Adicionar ativo ao clicado
        this.classList.add('text-blue-600', 'border-blue-600');
        this.classList.remove('text-gray-600', 'border-transparent');
        
        // Mostrar/ocultar conteúdo
        document.querySelectorAll('.tab-content').forEach(content => {
          content.classList.add('hidden');
        });
        document.getElementById(tab + '-tab').classList.remove('hidden');
      });
    });

    function buscarNovamente(termo) {
      window.location.href = `home_cliente.php?busca=${encodeURIComponent(termo)}`;
    }

    function limparHistorico() {
      if (confirm('Tem certeza que deseja limpar todo o histórico?')) {
        fetch('processa_historico.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            acao: 'limpar'
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Erro ao limpar histórico');
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
      const profileLink = '<?php echo (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'prestador') ? 'perfil_prestador.php' : 'perfil_cliente.php'; ?>';
      menu.innerHTML = `
        <a href="home_cliente.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-home mr-2"></i>Início
        </a>
        <a href="favoritos.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-heart mr-2"></i>Favoritos
        </a>
        <a href="historico.php" class="block px-4 py-2 text-blue-600 font-medium hover:bg-gray-100">
          <i class="fas fa-history mr-2"></i>Histórico
        </a>
        <a href="${profileLink}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
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

<?php
function formatarDataRelativa($data) {
    $d = new DateTime($data);
    $agora = new DateTime();
    $diff = $agora->diff($d);
    
    if ($diff->days == 0 && $diff->h == 0 && $diff->i < 60) {
        return 'Há poucos minutos';
    } elseif ($diff->days == 0 && $diff->h < 24) {
        return 'Há ' . $diff->h . ' horas';
    } elseif ($diff->days < 7) {
        return 'Há ' . $diff->days . ' dias';
    } else {
        return $d->format('d/m/Y');
    }
}
?>
