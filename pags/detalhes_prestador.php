<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_cliente.php");
    exit;
}

// Verificar se o ID do prestador foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: home_cliente.php");
    exit;
}

include 'connection.php';

$prestador_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM prestadores WHERE id = ?");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: home_cliente.php");
    exit;
}

$prestador = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($prestador['nome']); ?> - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .glass-effect {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .service-card {
      transition: all 0.3s ease;
    }
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="bg-gray-50">

  <!-- Navbar -->
  <nav class="bg-white/95 backdrop-blur-md shadow-lg fixed w-full z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <a href="home_cliente.php" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-search text-white text-lg"></i>
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Search</span>
          </a>
        </div>

        <div class="flex items-center space-x-4">
          <a href="home_cliente.php" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
          </a>
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-blue-600 text-sm"></i>
            </div>
            <span class="text-gray-700 font-medium"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Cliente'); ?></span>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section do Prestador -->
  <div class="pt-16 gradient-bg text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
        
        <!-- Foto e Info Principal -->
        <div class="lg:col-span-1">
          <div class="text-center">
            <div class="relative inline-block">
              <img src="<?php echo $prestador['foto'] ? '../uploads/' . htmlspecialchars($prestador['foto']) : 'https://via.placeholder.com/200x200/4F46E5/FFFFFF?text=Sem+Foto'; ?>" 
                   alt="Foto de <?php echo htmlspecialchars($prestador['nome']); ?>" 
                   class="w-48 h-48 rounded-full object-cover border-4 border-white shadow-2xl mx-auto">
              <div class="absolute bottom-2 right-2 w-12 h-12 bg-green-500 rounded-full flex items-center justify-center border-4 border-white">
                <i class="fas fa-check text-white text-lg"></i>
              </div>
            </div>
            <div class="mt-6">
              <h1 class="text-4xl font-bold mb-2"><?php echo htmlspecialchars($prestador['nome']); ?></h1>
              <p class="text-xl text-blue-100 mb-4">
                <i class="fas fa-tools mr-2"></i><?php echo htmlspecialchars($prestador['nicho'] ?? 'Prestador de Serviços'); ?>
              </p>
              <div class="flex items-center justify-center space-x-1 mb-4">
                <i class="fas fa-star text-yellow-400"></i>
                <i class="fas fa-star text-yellow-400"></i>
                <i class="fas fa-star text-yellow-400"></i>
                <i class="fas fa-star text-yellow-400"></i>
                <i class="fas fa-star text-yellow-400"></i>
                <span class="ml-2 text-lg font-semibold">4.9</span>
                <span class="text-blue-100 ml-1">(127 avaliações)</span>
              </div>
              <div class="flex items-center justify-center space-x-4 text-sm">
                <span class="glass-effect px-3 py-1 rounded-full">
                  <i class="fas fa-medal mr-1"></i>Verificado
                </span>
                <span class="glass-effect px-3 py-1 rounded-full">
                  <i class="fas fa-clock mr-1"></i>Resposta rápida
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Informações de Contato -->
        <div class="lg:col-span-2">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Card de Contato -->
            <div class="glass-effect rounded-2xl p-6">
              <h3 class="text-xl font-bold mb-4">
                <i class="fas fa-phone mr-2"></i>Contato
              </h3>
              <div class="space-y-3">
                <div class="flex items-center">
                  <i class="fas fa-phone w-5 text-green-400"></i>
                  <span class="ml-3 text-lg"><?php echo htmlspecialchars($prestador['telefone']); ?></span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-envelope w-5 text-blue-400"></i>
                  <span class="ml-3"><?php echo htmlspecialchars($prestador['email'] ?? 'contato@prestador.com'); ?></span>
                </div>
                <div class="flex items-center">
                  <i class="fas fa-map-marker-alt w-5 text-red-400"></i>
                  <span class="ml-3"><?php echo htmlspecialchars($prestador['endereco'] ?? 'Endereço não informado'); ?></span>
                </div>
              </div>
            </div>

            <!-- Card de Disponibilidade -->
            <div class="glass-effect rounded-2xl p-6">
              <h3 class="text-xl font-bold mb-4">
                <i class="fas fa-calendar mr-2"></i>Disponibilidade
              </h3>
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span>Segunda - Sexta</span>
                  <span class="font-semibold">08:00 - 18:00</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Sábado</span>
                  <span class="font-semibold">08:00 - 14:00</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Domingo</span>
                  <span class="text-red-300">Fechado</span>
                </div>
                <div class="mt-4 p-3 bg-green-500/20 rounded-lg">
                  <div class="flex items-center">
                    <i class="fas fa-circle text-green-400 text-xs mr-2"></i>
                    <span class="font-semibold">Disponível agora</span>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Conteúdo Principal -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- Coluna Principal -->
      <div class="lg:col-span-2 space-y-8">
        
        <!-- Sobre o Prestador -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-user-circle mr-3 text-blue-600"></i>Sobre o Prestador
          </h2>
          <p class="text-gray-600 leading-relaxed text-lg">
            <?php echo htmlspecialchars($prestador['descricao'] ?? 'Profissional experiente e dedicado, comprometido em oferecer serviços de alta qualidade. Com anos de experiência no mercado, garanto satisfação e excelência em cada trabalho realizado. Atendo com pontualidade, responsabilidade e sempre com foco no resultado que o cliente espera.'); ?>
          </p>
          
          <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-xl">
              <div class="text-2xl font-bold text-blue-600">5+</div>
              <div class="text-sm text-gray-600">Anos de experiência</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-xl">
              <div class="text-2xl font-bold text-green-600">200+</div>
              <div class="text-sm text-gray-600">Serviços realizados</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-xl">
              <div class="text-2xl font-bold text-yellow-600">98%</div>
              <div class="text-sm text-gray-600">Satisfação</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-xl">
              <div class="text-2xl font-bold text-purple-600">24h</div>
              <div class="text-sm text-gray-600">Tempo de resposta</div>
            </div>
          </div>
        </div>

        <!-- Serviços Oferecidos -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-cogs mr-3 text-blue-600"></i>Serviços Oferecidos
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="service-card bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100">
              <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                  <i class="fas fa-wrench text-white"></i>
                </div>
                <div class="ml-4">
                  <h3 class="font-bold text-gray-800">Manutenção Geral</h3>
                  <p class="text-sm text-gray-600">A partir de R$ 50</p>
                </div>
              </div>
              <p class="text-gray-600 text-sm">Reparos e manutenções diversas para sua casa ou empresa.</p>
            </div>
            
            <div class="service-card bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-green-100">
              <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                  <i class="fas fa-bolt text-white"></i>
                </div>
                <div class="ml-4">
                  <h3 class="font-bold text-gray-800">Serviços Elétricos</h3>
                  <p class="text-sm text-gray-600">A partir de R$ 80</p>
                </div>
              </div>
              <p class="text-gray-600 text-sm">Instalações e reparos elétricos com segurança garantida.</p>
            </div>
            
            <div class="service-card bg-gradient-to-r from-purple-50 to-violet-50 p-6 rounded-xl border border-purple-100">
              <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                  <i class="fas fa-paint-brush text-white"></i>
                </div>
                <div class="ml-4">
                  <h3 class="font-bold text-gray-800">Pintura</h3>
                  <p class="text-sm text-gray-600">A partir de R$ 25/m²</p>
                </div>
              </div>
              <p class="text-gray-600 text-sm">Pintura residencial e comercial com acabamento profissional.</p>
            </div>
            
            <div class="service-card bg-gradient-to-r from-red-50 to-pink-50 p-6 rounded-xl border border-red-100">
              <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
                  <i class="fas fa-tint text-white"></i>
                </div>
                <div class="ml-4">
                  <h3 class="font-bold text-gray-800">Hidráulica</h3>
                  <p class="text-sm text-gray-600">A partir de R$ 60</p>
                </div>
              </div>
              <p class="text-gray-600 text-sm">Reparos e instalações hidráulicas com qualidade.</p>
            </div>
          </div>
        </div>

        <!-- Avaliações -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
              <i class="fas fa-star mr-3 text-yellow-500"></i>Avaliações dos Clientes
            </h2>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'cliente'): ?>
              <button onclick="abrirModalAvaliacao()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Avaliar
              </button>
            <?php endif; ?>
          </div>
          
          <div id="avaliacoes-container">
            <!-- Avaliações serão carregadas aqui via JavaScript -->
            <div class="text-center py-8">
              <i class="fas fa-spinner fa-spin text-gray-400 text-2xl mb-2"></i>
              <p class="text-gray-500">Carregando avaliações...</p>
            </div>
          </div>
          
          <div class="mt-6 text-center">
            <button id="ver-mais-avaliacoes" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors" style="display: none;">
              Ver todas as avaliações <i class="fas fa-arrow-right ml-1"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-1">
        <div class="sticky top-24 space-y-6">
          
          <!-- Card de Contato Rápido -->
          <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
              <i class="fas fa-phone mr-2 text-green-600"></i>Contato Rápido
            </h3>
            
            <div class="space-y-4">
              <a href="tel:<?php echo htmlspecialchars($prestador['telefone']); ?>" 
                 class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                <i class="fas fa-phone mr-2"></i>Ligar Agora
              </a>
              
              <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $prestador['telefone']); ?>" 
                 target="_blank"
                 class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                <i class="fab fa-whatsapp mr-2"></i>WhatsApp
              </a>
              
              <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                <i class="fas fa-calendar mr-2"></i>Agendar Serviço
              </button>
            </div>
            
            <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
              <div class="flex items-center text-yellow-800">
                <i class="fas fa-info-circle mr-2"></i>
                <span class="text-sm font-medium">Orçamento gratuito</span>
              </div>
            </div>
          </div>

          <!-- Card de Localização -->
          <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
              <i class="fas fa-map-marker-alt mr-2 text-red-600"></i>Localização
            </h3>
            
            <div class="space-y-3">
              <div class="flex items-start">
                <i class="fas fa-map-marker-alt text-red-600 mt-1 mr-3"></i>
                <div>
                  <p class="text-gray-700 font-medium">Área de Atendimento</p>
                  <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($prestador['endereco'] ?? 'Região Central e adjacências'); ?></p>
                </div>
              </div>
              
              <div class="flex items-center">
                <i class="fas fa-route text-blue-600 mr-3"></i>
                <div>
                  <p class="text-gray-700 font-medium">Distância</p>
                  <p class="text-gray-600 text-sm">Aproximadamente 2.5 km</p>
                </div>
              </div>
              
              <div class="flex items-center">
                <i class="fas fa-car text-green-600 mr-3"></i>
                <div>
                  <p class="text-gray-700 font-medium">Deslocamento</p>
                  <p class="text-gray-600 text-sm">Incluso no orçamento</p>
                </div>
              </div>
            </div>
            
            <button class="w-full mt-4 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
              <i class="fas fa-directions mr-2"></i>Ver no Mapa
            </button>
          </div>

          <!-- Card de Certificações -->
          <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
              <i class="fas fa-certificate mr-2 text-yellow-600"></i>Certificações
            </h3>
            
            <div class="space-y-3">
              <div class="flex items-center p-3 bg-green-50 rounded-lg">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <div>
                  <p class="font-medium text-gray-800">Identidade Verificada</p>
                  <p class="text-sm text-gray-600">Documentos confirmados</p>
                </div>
              </div>
              
              <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                <i class="fas fa-shield-alt text-blue-600 mr-3"></i>
                <div>
                  <p class="font-medium text-gray-800">Seguro Responsabilidade</p>
                  <p class="text-sm text-gray-600">Cobertura até R$ 50.000</p>
                </div>
              </div>
              
              <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                <i class="fas fa-graduation-cap text-purple-600 mr-3"></i>
                <div>
                  <p class="font-medium text-gray-800">Qualificação Técnica</p>
                  <p class="text-sm text-gray-600">Certificado profissional</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Avaliação -->
  <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'cliente'): ?>
  <div id="modal-avaliacao" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full">
      <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Avaliar Prestador</h3>
        <p class="text-gray-600">Compartilhe sua experiência</p>
      </div>
      
      <form id="form-avaliacao" class="space-y-6">
        <input type="hidden" name="prestador_id" value="<?php echo $prestador['id']; ?>">
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-3">Nota</label>
          <div class="flex justify-center space-x-2">
            <button type="button" class="estrela text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-nota="1">
              <i class="fas fa-star"></i>
            </button>
            <button type="button" class="estrela text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-nota="2">
              <i class="fas fa-star"></i>
            </button>
            <button type="button" class="estrela text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-nota="3">
              <i class="fas fa-star"></i>
            </button>
            <button type="button" class="estrela text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-nota="4">
              <i class="fas fa-star"></i>
            </button>
            <button type="button" class="estrela text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-nota="5">
              <i class="fas fa-star"></i>
            </button>
          </div>
          <input type="hidden" name="nota" id="nota-selecionada" required>
        </div>
        
        <div>
          <label for="comentario" class="block text-sm font-semibold text-gray-700 mb-2">Comentário</label>
          <textarea name="comentario" id="comentario" rows="4" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Conte sobre sua experiência..."></textarea>
        </div>
        
        <div class="flex space-x-4">
          <button type="button" onclick="fecharModalAvaliacao()" 
                  class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 transition-colors">
            Cancelar
          </button>
          <button type="submit" 
                  class="flex-1 bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition-colors">
            Enviar Avaliação
          </button>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>

  <!-- JavaScript -->
  <script>
    const prestadorId = <?php echo $prestador['id']; ?>;
    
    // Carregar avaliações ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
      carregarAvaliacoes();
    });
    
    function carregarAvaliacoes() {
      fetch(`obter_avaliacoes.php?prestador_id=${prestadorId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            exibirAvaliacoes(data.data);
          } else {
            document.getElementById('avaliacoes-container').innerHTML = 
              '<p class="text-center text-gray-500 py-8">Erro ao carregar avaliações</p>';
          }
        })
        .catch(error => {
          console.error('Erro:', error);
          document.getElementById('avaliacoes-container').innerHTML = 
            '<p class="text-center text-gray-500 py-8">Erro ao carregar avaliações</p>';
        });
    }
    
    function exibirAvaliacoes(dados) {
      const container = document.getElementById('avaliacoes-container');
      const avaliacoes = dados.avaliacoes;
      const stats = dados.estatisticas;
      
      if (avaliacoes.length === 0) {
        container.innerHTML = `
          <div class="text-center py-8">
            <i class="fas fa-star text-gray-300 text-4xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Ainda não há avaliações</h3>
            <p class="text-gray-500">Seja o primeiro a avaliar este prestador!</p>
          </div>
        `;
        return;
      }
      
      // Mostrar estatísticas
      let estatisticasHtml = `
        <div class="bg-gray-50 rounded-xl p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div>
              <div class="text-3xl font-bold text-blue-600">${stats.media}</div>
              <div class="text-sm text-gray-600">Nota Média</div>
              <div class="flex justify-center mt-1">
                ${gerarEstrelas(stats.media)}
              </div>
            </div>
            <div>
              <div class="text-3xl font-bold text-green-600">${stats.total}</div>
              <div class="text-sm text-gray-600">Avaliações</div>
            </div>
            <div>
              <div class="text-3xl font-bold text-purple-600">${Math.round((stats.distribuicao[5] / stats.total) * 100)}%</div>
              <div class="text-sm text-gray-600">5 Estrelas</div>
            </div>
          </div>
        </div>
      `;
      
      // Mostrar avaliações (máximo 3 inicialmente)
      let avaliacoesHtml = '<div class="space-y-6">';
      const avaliacoesParaMostrar = avaliacoes.slice(0, 3);
      
      avaliacoesParaMostrar.forEach((avaliacao, index) => {
        const isLast = index === avaliacoesParaMostrar.length - 1;
        avaliacoesHtml += `
          <div class="${!isLast ? 'border-b border-gray-100 pb-6' : ''}">
            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-blue-600"></i>
              </div>
              <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                  <h4 class="font-semibold text-gray-800">${avaliacao.cliente_nome}</h4>
                  <div class="flex items-center text-yellow-400">
                    ${gerarEstrelas(avaliacao.nota)}
                  </div>
                </div>
                ${avaliacao.comentario ? `<p class="text-gray-600 mb-2">${avaliacao.comentario}</p>` : ''}
                <p class="text-sm text-gray-400">${formatarData(avaliacao.criado_em)}</p>
              </div>
            </div>
          </div>
        `;
      });
      
      avaliacoesHtml += '</div>';
      
      container.innerHTML = estatisticasHtml + avaliacoesHtml;
      
      // Mostrar botão "Ver mais" se houver mais avaliações
      if (avaliacoes.length > 3) {
        document.getElementById('ver-mais-avaliacoes').style.display = 'block';
      }
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
    
    // Modal de avaliação
    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'cliente'): ?>
    function abrirModalAvaliacao() {
      document.getElementById('modal-avaliacao').classList.remove('hidden');
    }
    
    function fecharModalAvaliacao() {
      document.getElementById('modal-avaliacao').classList.add('hidden');
      document.getElementById('form-avaliacao').reset();
      document.getElementById('nota-selecionada').value = '';
      document.querySelectorAll('.estrela').forEach(estrela => {
        estrela.classList.remove('text-yellow-400');
        estrela.classList.add('text-gray-300');
      });
    }
    
    // Sistema de estrelas
    document.querySelectorAll('.estrela').forEach(estrela => {
      estrela.addEventListener('click', function() {
        const nota = parseInt(this.dataset.nota);
        document.getElementById('nota-selecionada').value = nota;
        
        document.querySelectorAll('.estrela').forEach((e, index) => {
          if (index < nota) {
            e.classList.remove('text-gray-300');
            e.classList.add('text-yellow-400');
          } else {
            e.classList.remove('text-yellow-400');
            e.classList.add('text-gray-300');
          }
        });
      });
    });
    
    // Enviar avaliação
    document.getElementById('form-avaliacao').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch('avaliar_prestador.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          fecharModalAvaliacao();
          carregarAvaliacoes(); // Recarregar avaliações
          alert('Avaliação enviada com sucesso!');
        } else {
          alert('Erro ao enviar avaliação: ' + data.error);
        }
      })
      .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao enviar avaliação');
      });
    });
    <?php endif; ?>

    // Smooth scroll para âncoras
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });

    // Animação de entrada dos elementos
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    // Aplicar animação aos cards de serviço
    document.querySelectorAll('.service-card').forEach((card, index) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
      observer.observe(card);
    });
  </script>

</body>
</html>

