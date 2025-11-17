<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_cliente.php");
    exit;
}

include 'connection.php';

$cliente_id = $_SESSION['user_id'];

// Buscar favoritos do cliente
$stmt = $conn->prepare("
    SELECT p.*, 
           COUNT(DISTINCT a.id) as total_avaliacoes,
           COALESCE(AVG(a.nota), 0) as media_avaliacao
    FROM prestadores p
    LEFT JOIN favoritos f ON p.id = f.prestador_id
    LEFT JOIN avaliacoes a ON p.id = a.prestador_id
    WHERE f.cliente_id = ?
    GROUP BY p.id
    ORDER BY f.criado_em DESC
");

$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$favoritos = [];

while ($row = $result->fetch_assoc()) {
    $favoritos[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Favoritos - Search</title>
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
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
          <a href="favoritos.php" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">
            <i class="fas fa-heart mr-2"></i>Favoritos
          </a>
          <a href="historico.php" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
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
          <i class="fas fa-heart text-red-500 mr-3"></i>Meus Favoritos
        </h1>
        <p class="text-xl text-gray-600">Prestadores que você salvou para acessar depois</p>
      </div>

      <?php if (empty($favoritos)): ?>
        <!-- Estado Vazio -->
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
          <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-heart text-red-500 text-4xl"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-800 mb-2">Nenhum favorito ainda</h2>
          <p class="text-gray-600 mb-6 max-w-md mx-auto">
            Comece a adicionar prestadores aos seus favoritos para encontrá-los facilmente depois!
          </p>
          <a href="home_cliente.php" class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold">
            <i class="fas fa-search mr-2"></i>Buscar Prestadores
          </a>
        </div>
      <?php else: ?>
        <!-- Grid de Favoritos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <?php foreach ($favoritos as $prestador): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover border border-gray-100">
              <div class="relative">
                <img src="<?php echo $prestador['foto'] ? '../uploads/' . htmlspecialchars($prestador['foto']) : 'https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=Sem+Foto'; ?>" 
                     alt="Foto de <?php echo htmlspecialchars($prestador['nome']); ?>" 
                     class="w-full h-48 object-cover">
                <div class="absolute top-3 right-3">
                  <button class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg" 
                          onclick="removerFavorito(<?php echo $prestador['id']; ?>)">
                    <i class="fas fa-heart"></i>
                  </button>
                </div>
                <div class="absolute bottom-3 left-3">
                  <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                    <i class="fas fa-circle text-xs mr-1"></i>Disponível
                  </span>
                </div>
              </div>
              
              <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                  <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1"><?php echo htmlspecialchars($prestador['nome']); ?></h3>
                    <p class="text-blue-600 font-medium text-sm">
                      <i class="fas fa-tools mr-1"></i><?php echo htmlspecialchars($prestador['nicho']); ?>
                    </p>
                  </div>
                  <div class="text-right">
                    <div class="flex items-center text-yellow-400 mb-1">
                      <?php 
                        $media = $prestador['media_avaliacao'];
                        for ($i = 1; $i <= 5; $i++) {
                          echo $i <= $media ? '<i class="fas fa-star text-xs"></i>' : '<i class="far fa-star text-xs"></i>';
                        }
                      ?>
                      <span class="text-gray-600 text-xs ml-1">(<?php echo intval($prestador['total_avaliacoes']); ?>)</span>
                    </div>
                  </div>
                </div>
                
                <div class="space-y-2 mb-4">
                  <div class="flex items-center text-gray-600 text-sm">
                    <i class="fas fa-phone w-4 text-green-600"></i>
                    <span class="ml-2"><?php echo htmlspecialchars($prestador['telefone']); ?></span>
                  </div>
                  <div class="flex items-center text-gray-600 text-sm">
                    <i class="fas fa-map-marker-alt w-4 text-red-600"></i>
                    <span class="ml-2"><?php echo htmlspecialchars($prestador['endereco'] ?? 'Localização não informada'); ?></span>
                  </div>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                  <button class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105"
                          onclick="window.location.href='detalhes_prestador.php?id=<?php echo $prestador['id']; ?>'">
                    Ver Perfil
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Resumo -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-bold text-gray-800">Total de Favoritos</h3>
              <p class="text-gray-600">Você tem <?php echo count($favoritos); ?> prestador(es) salvo(s)</p>
            </div>
            <div class="text-4xl font-bold text-blue-600"><?php echo count($favoritos); ?></div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    function mostrarToast(msg, tipo='success') {
      const toast = document.createElement('div');
      toast.className = `toast ${tipo}`;
      toast.style.zIndex = 9999;
      toast.innerHTML = `<div class="flex items-center gap-3"><i class="fas fa-${tipo==='success'?'check-circle':'exclamation-circle'} text-${tipo==='success'?'green':'red'}-600"></i><span>${msg}</span></div>`;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3500);
    }

    function confirmAction(message) {
      return new Promise((resolve) => {
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.background = 'rgba(0,0,0,0.4)';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.zIndex = 10000;

        const box = document.createElement('div');
        box.className = 'bg-white rounded-xl p-6 max-w-sm text-center shadow-2xl';
        box.innerHTML = `
          <p class="text-gray-800 mb-4">${message}</p>
          <div class="flex justify-center gap-3">
            <button class="px-4 py-2 rounded-lg bg-gray-200" id="confirm-no">Cancelar</button>
            <button class="px-4 py-2 rounded-lg bg-red-600 text-white" id="confirm-yes">Remover</button>
          </div>
        `;

        overlay.appendChild(box);
        document.body.appendChild(overlay);

        overlay.querySelector('#confirm-yes').addEventListener('click', () => {
          overlay.remove(); resolve(true);
        });
        overlay.querySelector('#confirm-no').addEventListener('click', () => {
          overlay.remove(); resolve(false);
        });
      });
    }

    async function removerFavorito(prestadorId) {
      const ok = await confirmAction('Tem certeza que deseja remover este favorito?');
      if (!ok) return;

      fetch('processa_favorito.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          acao: 'remover',
          prestador_id: prestadorId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          mostrarToast('Favorito removido', 'success');
          setTimeout(() => location.reload(), 700);
        } else {
          mostrarToast(data.error || 'Erro ao remover favorito', 'error');
        }
      })
      .catch(error => {
        console.error('Erro:', error);
        mostrarToast('Erro ao remover favorito', 'error');
      });
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
        <a href="favoritos.php" class="block px-4 py-2 text-blue-600 font-medium hover:bg-gray-100">
          <i class="fas fa-heart mr-2"></i>Favoritos
        </a>
        <a href="historico.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
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
