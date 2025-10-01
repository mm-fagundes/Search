<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_cliente.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Cliente - Search</title>
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
    .search-input {
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">

  <!-- Navbar Aprimorada -->
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
          <a href="home_cliente.php" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">
            <i class="fas fa-home mr-2"></i>Início
          </a>
          <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
            <i class="fas fa-heart mr-2"></i>Favoritos
          </a>
          <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
            <i class="fas fa-history mr-2"></i>Histórico
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

  <!-- Hero Section -->
  <div class="pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">
          Encontre o <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">prestador ideal</span>
        </h1>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
          Conectamos você aos melhores profissionais da sua região. Qualidade, confiança e praticidade em um só lugar.
        </p>
        
        <!-- Barra de Pesquisa -->
        <div class="max-w-2xl mx-auto">
          <div class="relative">
            <input type="text" 
                   id="searchInput"
                   placeholder="Buscar por serviço, profissional ou localização..."
                   class="w-full px-6 py-4 pl-14 pr-20 text-lg border-2 border-gray-200 rounded-2xl search-input focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200">
            <i class="fas fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-semibold">
              Buscar
            </button>
          </div>
        </div>

        <!-- Categorias Populares -->
        <div class="mt-8">
          <p class="text-gray-600 mb-4">Categorias populares:</p>
          <div class="flex flex-wrap justify-center gap-3">
            <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-blue-200 cursor-pointer transition-colors">
              <i class="fas fa-wrench mr-2"></i>Manutenção
            </span>
            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-green-200 cursor-pointer transition-colors">
              <i class="fas fa-broom mr-2"></i>Limpeza
            </span>
            <span class="bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-purple-200 cursor-pointer transition-colors">
              <i class="fas fa-paint-brush mr-2"></i>Pintura
            </span>
            <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-yellow-200 cursor-pointer transition-colors">
              <i class="fas fa-bolt mr-2"></i>Elétrica
            </span>
            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-red-200 cursor-pointer transition-colors">
              <i class="fas fa-tint mr-2"></i>Hidráulica
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filtros -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
      <div class="flex flex-wrap items-center gap-4">
        <div class="flex items-center space-x-2">
          <i class="fas fa-filter text-gray-600"></i>
          <span class="font-semibold text-gray-700">Filtros:</span>
        </div>
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="categoria">
          <option value="">Todas as categorias</option>
          <option value="Manutenção Geral">Manutenção</option>
          <option value="Limpeza">Limpeza</option>
          <option value="Pintor">Pintura</option>
          <option value="Eletricista">Elétrica</option>
          <option value="Encanador">Hidráulica</option>
        </select>
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="localizacao">
          <option value="">Localização</option>
          <option value="Centro">Centro</option>
          <option value="Norte">Zona Norte</option>
          <option value="Sul">Zona Sul</option>
        </select>
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="avaliacao">
          <option value="">Avaliação</option>
          <option value="5">5 estrelas</option>
          <option value="4">4+ estrelas</option>
          <option value="3">3+ estrelas</option>
        </select>
        <button class="ml-auto bg-gray-100 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
          <i class="fas fa-times mr-2"></i>Limpar filtros
        </button>
      </div>
    </div>
  </div>

  <!-- Grid de Prestadores -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="flex items-center justify-between mb-8">
      <h2 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-users mr-3 text-blue-600"></i>Prestadores Disponíveis
      </h2>
      <div class="flex items-center space-x-4">
        <span class="text-gray-600">Ordenar por:</span>
        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="ordenacao">
          <option value="relevancia">Mais relevantes</option>
          <option value="melhor_avaliados">Melhor avaliados</option>
          <option value="mais_avaliacoes">Mais avaliações</option>
          <option value="nome">Nome A-Z</option>
          <option value="mais_recentes">Mais recentes</option>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <?php
      include 'connection.php';

      $result = $conn->query("SELECT * FROM prestadores ORDER BY criado_em DESC");

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $foto = $row['foto'] ? "../uploads/" . $row['foto'] : "https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=Sem+Foto";
              $nome = htmlspecialchars($row['nome']);
              $telefone = htmlspecialchars($row['telefone']);
              $nicho = htmlspecialchars($row['nicho'] ?? 'Serviços Gerais');
              $id = $row['id'];
              
              echo '
              <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover cursor-pointer border border-gray-100" onclick="window.location.href=\'detalhes_prestador.php?id=' . $id . '\'">
                <div class="relative">
                  <img src="' . $foto . '" alt="Foto de ' . $nome . '" class="w-full h-48 object-cover">
                  <div class="absolute top-3 right-3">
                    <button class="w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                      <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors"></i>
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
                      <h3 class="text-lg font-bold text-gray-800 mb-1">' . $nome . '</h3>
                      <p class="text-blue-600 font-medium text-sm">
                        <i class="fas fa-tools mr-1"></i>' . $nicho . '
                      </p>
                    </div>
                    <div class="text-right">
                      <div class="flex items-center text-yellow-400 mb-1">
                        <i class="fas fa-star text-xs"></i>
                        <i class="fas fa-star text-xs"></i>
                        <i class="fas fa-star text-xs"></i>
                        <i class="fas fa-star text-xs"></i>
                        <i class="fas fa-star text-xs"></i>
                        <span class="text-gray-600 text-xs ml-1">(4.9)</span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="space-y-2 mb-4">
                    <div class="flex items-center text-gray-600 text-sm">
                      <i class="fas fa-phone w-4 text-green-600"></i>
                      <span class="ml-2">' . $telefone . '</span>
                    </div>
                    <div class="flex items-center text-gray-600 text-sm">
                      <i class="fas fa-map-marker-alt w-4 text-red-600"></i>
                      <span class="ml-2">2.5 km de distância</span>
                    </div>
                    <div class="flex items-center text-gray-600 text-sm">
                      <i class="fas fa-clock w-4 text-blue-600"></i>
                      <span class="ml-2">Responde em 1h</span>
                    </div>
                  </div>
                  
                  <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                      A partir de
                      <span class="text-lg font-bold text-green-600">R$ 50</span>
                    </div>
                    <button class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105">
                      Ver Perfil
                    </button>
                  </div>
                </div>
              </div>
              ';
          }
      } else {
          echo '
          <div class="col-span-full text-center py-12">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-search text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhum prestador encontrado</h3>
            <p class="text-gray-500">Tente ajustar os filtros ou buscar por outros termos.</p>
          </div>
          ';
      }

      $conn->close();
      ?>
    </div>
  </div>

    <!-- JavaScript -->
  <script>
    // Menu mobile
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');
    if (btn) btn.addEventListener('click', () => menu.classList.toggle('hidden'));

    // Busca dinâmica
    let timeoutId;
    const searchInput = document.getElementById('searchInput');
    const prestadoresGrid = document.querySelector('.grid');
    
    function buscarPrestadores() {
      const termo = searchInput.value.trim();
      const categoria = document.querySelector('select[name="categoria"]')?.value || '';
      const localizacao = document.querySelector('select[name="localizacao"]')?.value || '';
      const avaliacao = document.querySelector('select[name="avaliacao"]')?.value || '';
      const ordenacao = document.querySelector('select[name="ordenacao"]')?.value || 'relevancia';
      
      const params = new URLSearchParams({
        busca: termo,
        categoria: categoria,
        localizacao: localizacao,
        avaliacao: avaliacao,
        ordenacao: ordenacao
      });
      
      fetch(`buscar_prestadores.php?${params}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            atualizarGrid(data.data);
          } else {
            console.error('Erro na busca:', data.error);
          }
        })
        .catch(error => {
          console.error('Erro na requisição:', error);
        });
    }
    
    function atualizarGrid(prestadores) {
      if (prestadores.length === 0) {
        prestadoresGrid.innerHTML = `
          <div class="col-span-full text-center py-12">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-search text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhum prestador encontrado</h3>
            <p class="text-gray-500">Tente ajustar os filtros ou buscar por outros termos.</p>
          </div>
        `;
        return;
      }
      
      prestadoresGrid.innerHTML = prestadores.map(prestador => `
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover cursor-pointer border border-gray-100" onclick="window.location.href='detalhes_prestador.php?id=${prestador.id}'">
          <div class="relative">
            <img src="${prestador.foto || 'https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=Sem+Foto'}" alt="Foto de ${prestador.nome}" class="w-full h-48 object-cover">
            <div class="absolute top-3 right-3">
              <button class="w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors"></i>
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
                <h3 class="text-lg font-bold text-gray-800 mb-1">${prestador.nome}</h3>
                <p class="text-blue-600 font-medium text-sm">
                  <i class="fas fa-tools mr-1"></i>${prestador.nicho}
                </p>
              </div>
              <div class="text-right">
                <div class="flex items-center text-yellow-400 mb-1">
                  ${gerarEstrelas(prestador.media_avaliacao)}
                  <span class="text-gray-600 text-xs ml-1">(${prestador.total_avaliacoes})</span>
                </div>
              </div>
            </div>
            
            <div class="space-y-2 mb-4">
              <div class="flex items-center text-gray-600 text-sm">
                <i class="fas fa-phone w-4 text-green-600"></i>
                <span class="ml-2">${prestador.telefone}</span>
              </div>
              <div class="flex items-center text-gray-600 text-sm">
                <i class="fas fa-map-marker-alt w-4 text-red-600"></i>
                <span class="ml-2">${prestador.endereco || 'Localização não informada'}</span>
              </div>
              <div class="flex items-center text-gray-600 text-sm">
                <i class="fas fa-clock w-4 text-blue-600"></i>
                <span class="ml-2">Responde em 1h</span>
              </div>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-500">
                A partir de
                <span class="text-lg font-bold text-green-600">R$ 50</span>
              </div>
              <button class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105">
                Ver Perfil
              </button>
            </div>
          </div>
        </div>
      `).join('');
    }
    
    function gerarEstrelas(media) {
      const estrelas = [];
      for (let i = 1; i <= 5; i++) {
        if (i <= media) {
          estrelas.push('<i class="fas fa-star text-xs"></i>');
        } else {
          estrelas.push('<i class="far fa-star text-xs"></i>');
        }
      }
      return estrelas.join('');
    }

    // Event listeners
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(buscarPrestadores, 500);
      });
    }
    
    // Event listeners para filtros
    document.querySelectorAll('select').forEach(select => {
      select.addEventListener('change', buscarPrestadores);
    });

    // Animação de entrada dos cards
    const cards = document.querySelectorAll('.card-hover');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    });

    cards.forEach((card, index) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
      observer.observe(card);
    });
  </script>

</body>
</html>

