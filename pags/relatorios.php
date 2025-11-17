<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'prestador') {
    header("Location: login_prestador.php");
    exit;
}

include 'connection.php';

$prestador_id = $_SESSION['user_id'];

// Buscar estatísticas
$stmt = $conn->prepare("SELECT 
    COUNT(DISTINCT a.id) as total_agendamentos,
    COUNT(DISTINCT CASE WHEN a.status = 'confirmado' THEN a.id END) as confirmados,
    COUNT(DISTINCT CASE WHEN a.status = 'pendente' THEN a.id END) as pendentes,
    COUNT(DISTINCT CASE WHEN a.status = 'concluido' THEN a.id END) as concluidos,
    COUNT(DISTINCT CASE WHEN a.status = 'cancelado' THEN a.id END) as cancelados
    FROM agendamentos a
    WHERE a.prestador_id = ?");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result = $stmt->get_result();
$stats_agendamentos = $result->fetch_assoc();
$stmt->close();

// Buscar avaliações
$stmt = $conn->prepare("SELECT 
    COUNT(*) as total_avaliacoes,
    COALESCE(AVG(nota), 0) as media_avaliacao,
    COUNT(DISTINCT CASE WHEN nota = 5 THEN id END) as estrelas_5,
    COUNT(DISTINCT CASE WHEN nota = 4 THEN id END) as estrelas_4,
    COUNT(DISTINCT CASE WHEN nota = 3 THEN id END) as estrelas_3,
    COUNT(DISTINCT CASE WHEN nota = 2 THEN id END) as estrelas_2,
    COUNT(DISTINCT CASE WHEN nota = 1 THEN id END) as estrelas_1
    FROM avaliacoes
    WHERE prestador_id = ?");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result = $stmt->get_result();
$stats_avaliacoes = $result->fetch_assoc();
$stmt->close();

// Buscar receita por serviço
$stmt = $conn->prepare("SELECT 
    s.nome_servico,
    COUNT(a.id) as total_agendamentos,
    SUM(s.preco_base) as receita_total
    FROM servicos s
    LEFT JOIN agendamentos a ON s.id = a.servico_id AND a.prestador_id = ?
    WHERE s.prestador_id = ?
    GROUP BY s.id, s.nome_servico
    ORDER BY receita_total DESC");
$stmt->bind_param("ii", $prestador_id, $prestador_id);
$stmt->execute();
$result = $stmt->get_result();
$receita_servicos = [];

while ($row = $result->fetch_assoc()) {
    $receita_servicos[] = $row;
}
$stmt->close();

// Buscar agendamentos por mês
$stmt = $conn->prepare("SELECT 
    MONTH(data_agendamento) as mes,
    YEAR(data_agendamento) as ano,
    COUNT(*) as total
    FROM agendamentos
    WHERE prestador_id = ?
    GROUP BY YEAR(data_agendamento), MONTH(data_agendamento)
    ORDER BY ano DESC, mes DESC
    LIMIT 12");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result = $stmt->get_result();
$agendamentos_mes = [];

while ($row = $result->fetch_assoc()) {
    $agendamentos_mes[] = $row;
}
$stmt->close();
$conn->close();

$meses = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relatórios - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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
          <a href="cadastrar_servicos.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
            <i class="fas fa-plus-circle mr-2"></i>Cadastrar Serviço
          </a>
          <a href="relatorios.php" class="text-green-600 font-semibold border-b-2 border-green-600 pb-1">
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
      <div class="mb-8 flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-bold text-gray-800 mb-2">
            <i class="fas fa-chart-line text-green-600 mr-3"></i>Relatórios
          </h1>
          <p class="text-xl text-gray-600">Acompanhe seus indicadores de desempenho</p>
        </div>
        <button onclick="imprimirRelatorio()" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-colors font-semibold">
          <i class="fas fa-print mr-2"></i>Imprimir
        </button>
      </div>

      <!-- KPIs Principais -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Agendamentos</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo $stats_agendamentos['total_agendamentos']; ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Confirmados</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo $stats_agendamentos['confirmados']; ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Pendentes</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo $stats_agendamentos['pendentes']; ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-check-double text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Concluídos</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo $stats_agendamentos['concluidos']; ?></p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-star text-red-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Avaliação Média</p>
              <p class="text-2xl font-bold text-gray-900"><?php echo number_format($stats_avaliacoes['media_avaliacao'], 1); ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Grid com gráficos -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Gráfico de Distribuição de Avaliações -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-star text-yellow-500 mr-2"></i>Distribuição de Avaliações
          </h2>
          <div class="relative h-64">
            <canvas id="chart-avaliacoes"></canvas>
          </div>
        </div>

        <!-- Gráfico de Agendamentos por Mês -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
          <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-calendar text-green-600 mr-2"></i>Agendamentos por Mês
          </h2>
          <div class="relative h-64">
            <canvas id="chart-mes"></canvas>
          </div>
        </div>
      </div>

      <!-- Tabela de Receita por Serviço -->
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
          <i class="fas fa-coins text-green-600 mr-2"></i>Desempenho por Serviço
        </h2>
        
        <?php if (empty($receita_servicos)): ?>
          <div class="text-center py-8 text-gray-500">
            <i class="fas fa-inbox text-3xl mb-2"></i>
            <p>Nenhum dado de serviços disponível</p>
          </div>
        <?php else: ?>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="text-left py-3 px-4 font-semibold text-gray-700">Serviço</th>
                  <th class="text-center py-3 px-4 font-semibold text-gray-700">Agendamentos</th>
                  <th class="text-right py-3 px-4 font-semibold text-gray-700">Receita Total</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($receita_servicos as $servico): ?>
                  <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4">
                      <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($servico['nome_servico']); ?></div>
                    </td>
                    <td class="py-3 px-4 text-center">
                      <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                        <?php echo $servico['total_agendamentos'] ?? 0; ?>
                      </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                      <span class="font-bold text-green-600">
                        R$ <?php echo number_format($servico['receita_total'] ?? 0, 2, ',', '.'); ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

      <!-- Análise de Avaliações -->
      <div class="mt-8 bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
          <i class="fas fa-comments text-blue-600 mr-2"></i>Análise de Avaliações
        </h2>
        
        <div class="space-y-4">
          <?php for ($i = 5; $i >= 1; $i--): ?>
            <?php 
              $chave = 'estrelas_' . $i;
              $total = $stats_avaliacoes[$chave];
              $percentual = $stats_avaliacoes['total_avaliacoes'] > 0 ? ($total / $stats_avaliacoes['total_avaliacoes']) * 100 : 0;
            ?>
            <div>
              <div class="flex items-center justify-between mb-2">
                <div class="flex items-center">
                  <?php for ($j = 0; $j < $i; $j++): ?>
                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                  <?php endfor; ?>
                  <?php for ($j = $i; $j < 5; $j++): ?>
                    <i class="far fa-star text-gray-300 text-sm"></i>
                  <?php endfor; ?>
                  <span class="ml-2 font-semibold text-gray-700"><?php echo $total; ?> avaliação<?php echo $total !== 1 ? 's' : ''; ?></span>
                </div>
                <span class="font-bold text-gray-800"><?php echo number_format($percentual, 1); ?>%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo $percentual; ?>%"></div>
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // Gráfico de Avaliações
    const ctxAvaliacoes = document.getElementById('chart-avaliacoes').getContext('2d');
    new Chart(ctxAvaliacoes, {
      type: 'doughnut',
      data: {
        labels: ['5 Estrelas', '4 Estrelas', '3 Estrelas', '2 Estrelas', '1 Estrela'],
        datasets: [{
          data: [
            <?php echo $stats_avaliacoes['estrelas_5']; ?>,
            <?php echo $stats_avaliacoes['estrelas_4']; ?>,
            <?php echo $stats_avaliacoes['estrelas_3']; ?>,
            <?php echo $stats_avaliacoes['estrelas_2']; ?>,
            <?php echo $stats_avaliacoes['estrelas_1']; ?>
          ],
          backgroundColor: [
            '#10b981',
            '#3b82f6',
            '#f59e0b',
            '#ef4444',
            '#dc2626'
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { font: { family: "'Montserrat', sans-serif" } }
          }
        }
      }
    });

    // Gráfico de Agendamentos por Mês
    const ctxMes = document.getElementById('chart-mes').getContext('2d');
    new Chart(ctxMes, {
      type: 'line',
      data: {
        labels: [
          <?php 
            foreach (array_reverse($agendamentos_mes) as $item) {
              echo "'" . ($meses[$item['mes']] ?? '') . " " . $item['ano'] . "', ";
            }
          ?>
        ],
        datasets: [{
          label: 'Agendamentos',
          data: [
            <?php 
              foreach (array_reverse($agendamentos_mes) as $item) {
                echo $item['total'] . ', ';
              }
            ?>
          ],
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { font: { family: "'Montserrat', sans-serif" } }
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    function imprimirRelatorio() {
      window.print();
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
        <a href="cadastrar_servicos.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-plus-circle mr-2"></i>Cadastrar Serviço
        </a>
        <a href="relatorios.php" class="block px-4 py-2 text-green-600 font-medium hover:bg-gray-100">
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
