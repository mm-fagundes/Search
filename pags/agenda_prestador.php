<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'prestador') {
    header("Location: login_prestador.php");
    exit;
}

include 'connection.php';

$prestador_id = $_SESSION['user_id'];

// Buscar serviços do prestador
$stmt = $conn->prepare("SELECT * FROM servicos WHERE prestador_id = ? ORDER BY criado_em DESC");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result_servicos = $stmt->get_result();
$servicos = [];

while ($row = $result_servicos->fetch_assoc()) {
    $servicos[] = $row;
}

$stmt->close();

// Buscar agendamentos do prestador
$stmt = $conn->prepare("
    SELECT a.*, c.nome as cliente_nome, c.telefone as cliente_telefone, s.nome_servico
    FROM agendamentos a
    JOIN clientes c ON a.cliente_id = c.id
    JOIN servicos s ON a.servico_id = s.id
    WHERE a.prestador_id = ?
    ORDER BY a.data_agendamento ASC
");

$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$result_agendamentos = $stmt->get_result();
$agendamentos = [];

while ($row = $result_agendamentos->fetch_assoc()) {
    $agendamentos[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agenda - Search</title>
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
          <a href="agenda_prestador.php" class="text-green-600 font-semibold border-b-2 border-green-600 pb-1">
            <i class="fas fa-calendar mr-2"></i>Agenda
          </a>
          <a href="cadastrar_servicos.php" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
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
          <i class="fas fa-calendar text-green-600 mr-3"></i>Minha Agenda
        </h1>
        <p class="text-xl text-gray-600">Gerencie seus agendamentos com visualização em calendário</p>
      </div>

      <!-- Grid com Calendário e Agenda -->
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Calendário à Esquerda -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover sticky top-24">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
              <i class="fas fa-calendar-alt text-green-600 mr-2"></i>Calendário
            </h2>
            
            <div id="calendar" class="space-y-2">
              <!-- Calendário dinâmico -->
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
              <h3 class="font-semibold text-gray-800 mb-3">Resumo</h3>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-gray-600">Total</span>
                  <span class="font-bold text-green-600"><?php echo count($agendamentos); ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Pendentes</span>
                  <span class="font-bold text-yellow-600">
                    <?php echo count(array_filter($agendamentos, fn($a) => $a['status'] === 'pendente')); ?>
                  </span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Confirmados</span>
                  <span class="font-bold text-blue-600">
                    <?php echo count(array_filter($agendamentos, fn($a) => $a['status'] === 'confirmado')); ?>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Agenda à Direita -->
        <div class="lg:col-span-3">
          
          <!-- Seletor de Data e Hora -->
          <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Selecione um Horário</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Data</label>
                <input type="date" id="seletor-data" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select id="filtro-status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                  <option value="">Todos</option>
                  <option value="pendente">Pendentes</option>
                  <option value="confirmado">Confirmados</option>
                  <option value="concluido">Concluídos</option>
                  <option value="cancelado">Cancelados</option>
                </select>
              </div>
            </div>

            <!-- Grade de Horários (Slots de 30 min) -->
            <div class="mb-4">
              <label class="block text-sm font-semibold text-gray-700 mb-3">Horários Disponíveis (30 min)</label>
              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2" id="slots-horarios">
                <!-- Slots gerados por JavaScript -->
              </div>
            </div>
          </div>

          <!-- Lista de Agendamentos -->
          <div class="space-y-4">
            <?php if (empty($agendamentos)): ?>
              <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                  <i class="fas fa-calendar text-green-600 text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Nenhum agendamento</h2>
                <p class="text-gray-600 max-w-md mx-auto">
                  Quando clientes agendarem seus serviços, aparecerão aqui.
                </p>
              </div>
            <?php else: ?>
              <h3 class="text-xl font-bold text-gray-800 mb-4">Agendamentos</h3>
              <?php foreach ($agendamentos as $agendamento): ?>
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover agendamento-item" 
                     data-status="<?php echo $agendamento['status']; ?>"
                     data-data="<?php echo date('Y-m-d', strtotime($agendamento['data_agendamento'])); ?>">
                  
                  <div class="flex items-start justify-between mb-4">
                    <div>
                      <h4 class="text-lg font-bold text-gray-800 mb-1">
                        <i class="fas fa-tools text-green-600 mr-2"></i><?php echo htmlspecialchars($agendamento['nome_servico']); ?>
                      </h4>
                      <p class="text-gray-600">
                        <i class="fas fa-user mr-2"></i><?php echo htmlspecialchars($agendamento['cliente_nome']); ?>
                      </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold <?php 
                      $status = $agendamento['status'];
                      echo $status === 'confirmado' ? 'bg-blue-100 text-blue-700' : 
                           ($status === 'pendente' ? 'bg-yellow-100 text-yellow-700' :
                           ($status === 'concluido' ? 'bg-green-100 text-green-700' :
                           'bg-red-100 text-red-700'));
                    ?>">
                      <?php echo ucfirst($agendamento['status']); ?>
                    </span>
                  </div>

                  <div class="space-y-2 mb-4 text-gray-600">
                    <div class="flex items-center">
                      <i class="fas fa-calendar-check w-5 text-green-600"></i>
                      <span class="ml-2"><?php echo date('d/m/Y H:i', strtotime($agendamento['data_agendamento'])); ?></span>
                    </div>
                    <div class="flex items-center">
                      <i class="fas fa-phone w-5 text-green-600"></i>
                      <span class="ml-2"><?php echo htmlspecialchars($agendamento['cliente_telefone']); ?></span>
                    </div>
                    <?php if ($agendamento['observacoes']): ?>
                    <div class="flex items-start">
                      <i class="fas fa-sticky-note w-5 text-green-600 mt-1"></i>
                      <span class="ml-2"><?php echo htmlspecialchars($agendamento['observacoes']); ?></span>
                    </div>
                    <?php endif; ?>
                  </div>

                  <div class="flex space-x-2 pt-4 border-t border-gray-200">
                    <?php if ($agendamento['status'] === 'pendente'): ?>
                      <button onclick="atualizarStatus(<?php echo $agendamento['id']; ?>, 'confirmado')" 
                              class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold">
                        <i class="fas fa-check mr-2"></i>Confirmar
                      </button>
                      <button onclick="atualizarStatus(<?php echo $agendamento['id']; ?>, 'cancelado')" 
                              class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                        <i class="fas fa-times mr-2"></i>Cancelar
                      </button>
                    <?php elseif ($agendamento['status'] === 'confirmado'): ?>
                      <button onclick="atualizarStatus(<?php echo $agendamento['id']; ?>, 'concluido')" 
                              class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold">
                        <i class="fas fa-check-double mr-2"></i>Concluído
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // Gerar slots de 30 minutos
    function gerarSlots() {
      const slots = [];
      for (let hora = 7; hora < 18; hora++) {
        for (let minuto = 0; minuto < 60; minuto += 30) {
          const horarioStr = `${String(hora).padStart(2, '0')}:${String(minuto).padStart(2, '0')}`;
          slots.push(horarioStr);
        }
      }
      return slots;
    }

    // Renderizar calendário
    function gerarCalendario() {
      const hoje = new Date();
      const calendar = document.getElementById('calendar');
      let html = `<h3 class="font-semibold text-gray-800 mb-2">${hoje.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' }).toUpperCase()}</h3>`;
      
      const agendamentos = <?php echo json_encode(array_map(fn($a) => date('Y-m-d', strtotime($a['data_agendamento'])), array_filter($agendamentos, fn($a) => $a['status'] !== 'cancelado'))); ?>;
      
      for (let i = 0; i < 14; i++) {
        const data = new Date(hoje);
        data.setDate(data.getDate() + i);
        const dataStr = data.toISOString().split('T')[0];
        const dia = data.getDate();
        const temAgendamento = agendamentos.includes(dataStr);
        
        html += `
          <div class="p-3 border border-gray-200 rounded-lg ${temAgendamento ? 'bg-green-50 border-green-300' : 'hover:bg-gray-50'} cursor-pointer transition-colors"
               onclick="selecionarData('${dataStr}')">
            <div class="font-semibold text-gray-800 text-sm">${dia}</div>
            <div class="text-xs text-gray-600">${data.toLocaleDateString('pt-BR', { weekday: 'short' })}</div>
            ${temAgendamento ? '<div class="text-xs text-green-600 font-bold mt-1">● Agendado</div>' : ''}
          </div>
        `;
      }
      
      calendar.innerHTML = html;
    }

    function selecionarData(dataStr) {
      document.getElementById('seletor-data').value = dataStr;
      atualizarSlots();
    }

    function atualizarSlots() {
      const data = document.getElementById('seletor-data').value;
      const slotsContainer = document.getElementById('slots-horarios');
      const slots = gerarSlots();
      
      const agendamentos = <?php echo json_encode(array_values(array_filter($agendamentos, fn($a) => $a['status'] !== 'cancelado'))); ?>;
      const horariosBloqueados = agendamentos
        .filter(a => a.data_agendamento.startsWith(data))
        .map(a => a.data_agendamento.split(' ')[1]);
      
      let html = '';
      slots.forEach(slot => {
        const isBloqueado = horariosBloqueados.some(h => h.startsWith(slot));
        html += `
          <button class="px-3 py-2 rounded-lg text-xs font-semibold transition-colors ${
            isBloqueado 
              ? 'bg-red-100 text-red-600 cursor-not-allowed' 
              : 'bg-green-100 text-green-700 hover:bg-green-200'
          }" ${isBloqueado ? 'disabled' : ''}>
            ${slot}
          </button>
        `;
      });
      
      slotsContainer.innerHTML = html;
    }

    function filtrarAgendamentos() {
      const status = document.getElementById('filtro-status').value;
      const data = document.getElementById('seletor-data').value;
      
      document.querySelectorAll('.agendamento-item').forEach(item => {
        let mostrar = true;
        if (status && item.dataset.status !== status) mostrar = false;
        if (data && item.dataset.data !== data) mostrar = false;
        item.style.display = mostrar ? 'block' : 'none';
      });
    }

    function atualizarStatus(agendamentoId, novoStatus) {
      fetch('processa_agendamento.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          acao: 'atualizar_status',
          agendamento_id: agendamentoId,
          novo_status: novoStatus
        })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) location.reload();
        else alert('Erro ao atualizar');
      });
    }

    // Event Listeners
    document.getElementById('seletor-data').addEventListener('change', atualizarSlots);
    document.getElementById('filtro-status').addEventListener('change', filtrarAgendamentos);

    // Inicializar
    document.addEventListener('DOMContentLoaded', () => {
      gerarCalendario();
      const hoje = new Date().toISOString().split('T')[0];
      document.getElementById('seletor-data').value = hoje;
      atualizarSlots();
    });

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
        <a href="agenda_prestador.php" class="block px-4 py-2 text-green-600 font-medium hover:bg-gray-100">
          <i class="fas fa-calendar mr-2"></i>Agenda
        </a>
        <a href="cadastrar_servicos.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
          <i class="fas fa-plus-circle mr-2"></i>Serviços
        </a>
        <a href="perfil_prestador.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
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
