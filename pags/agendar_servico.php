<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_cliente.php");
    exit;
}

include 'connection.php';

$prestador_id = $_GET['prestador_id'] ?? null;
if (!$prestador_id) {
    header("Location: home_cliente.php");
    exit;
}

// Buscar dados do prestador
$stmt = $conn->prepare("SELECT * FROM prestadores WHERE id = ?");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();

$res = $stmt->get_result();
$prestador = $res ? $res->fetch_assoc() : null;
$res->free();
$stmt->close();

if (!$prestador) {
    header("Location: home_cliente.php");
    exit;
}

// Buscar serviços do prestador
$stmt = $conn->prepare("SELECT * FROM servicos WHERE prestador_id = ? ORDER BY nome_servico");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$res = $stmt->get_result();
$servicos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
$res->free();
$stmt->close();

// Buscar agendamentos existentes para este prestador
$stmt = $conn->prepare("
    SELECT DATE(data_agendamento) as data, TIME(data_agendamento) as hora
    FROM agendamentos 
    WHERE prestador_id = ? AND status != 'cancelado'
");
$stmt->bind_param("i", $prestador_id);
$stmt->execute();
$res = $stmt->get_result();
$agendamentos_existentes = [];
if ($res) {
  while ($row = $res->fetch_assoc()) {
    $agendamentos_existentes[] = $row['data'] . ' ' . $row['hora'];
  }
  $res->free();
}
$stmt->close();

// Buscar avaliações do prestador (total e média)
$stmt = $conn->prepare("SELECT COUNT(*) as total, COALESCE(AVG(nota),0) as media FROM avaliacoes WHERE prestador_id = ?");
$stmt->bind_param('i', $prestador_id);
$stmt->execute();
$resAv = $stmt->get_result();
$resAvRow = $resAv ? $resAv->fetch_assoc() : null;
$total_avaliacoes = intval($resAvRow['total'] ?? 0);
$media_avaliacao = round(floatval($resAvRow['media'] ?? 0),1);
if ($resAv) $resAv->free();
$stmt->close();


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendar Serviço - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .slot-button {
      transition: all 0.2s ease;
    }
    .slot-button:hover:not(:disabled) {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    .slot-button:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
    .toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 16px 24px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      z-index: 1000;
      animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
      from {
        transform: translateX(400px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    .toast.success {
      border-left: 4px solid #10b981;
    }
    .toast.error {
      border-left: 4px solid #ef4444;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">

  <!-- Navbar -->
  <nav class="bg-white/80 backdrop-blur-md shadow-lg fixed w-full z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex-shrink-0 flex items-center">
          <a href="home_cliente.php" class="flex items-center space-x-3 hover:opacity-80 transition">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-search text-white text-lg"></i>
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Search</span>
          </a>
        </div>

        <div class="flex items-center space-x-3">
          <a href="home_cliente.php" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
          </a>
          <a href="logout.php" class="text-red-600 hover:text-red-700 font-medium">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Conteúdo Principal -->
  <div class="pt-24 pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Grid Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Esquerda: Informações do Prestador -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
            <img src="<?php echo $prestador['foto'] ? '../uploads/' . htmlspecialchars($prestador['foto']) : 'https://via.placeholder.com/400x300/4F46E5/FFFFFF?text=Sem+Foto'; ?>" 
                 alt="<?php echo htmlspecialchars($prestador['nome']); ?>"
                 class="w-full h-56 object-cover">
            
            <div class="p-6">
              <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($prestador['nome']); ?></h2>
              
              <div class="flex items-center space-x-2 mb-4 pb-4 border-b border-gray-100">
                  <div class="flex text-yellow-400">
                  <?php
                    $rounded = floor($media_avaliacao);
                    for ($i = 1; $i <= 5; $i++) {
                      if ($i <= $rounded) echo '<i class="fas fa-star"></i>';
                      else echo '<i class="far fa-star"></i>';
                    }
                  ?>
                  </div>
                  <span class="text-gray-600 text-sm">(<?php echo $total_avaliacoes; ?> avaliações)</span>
              </div>
              
              <div class="space-y-3 text-sm">
                <div class="flex items-start">
                  <i class="fas fa-briefcase text-blue-600 mr-3 mt-1"></i>
                  <div>
                    <p class="font-semibold text-gray-700">Especialidade</p>
                    <p class="text-gray-600"><?php echo htmlspecialchars($prestador['nicho']); ?></p>
                  </div>
                </div>
                
                <div class="flex items-start">
                  <i class="fas fa-phone text-green-600 mr-3 mt-1"></i>
                  <div>
                    <p class="font-semibold text-gray-700">Telefone</p>
                    <p class="text-gray-600"><?php echo htmlspecialchars($prestador['telefone']); ?></p>
                  </div>
                </div>
                
                <div class="flex items-start">
                  <i class="fas fa-map-marker-alt text-red-600 mr-3 mt-1"></i>
                  <div>
                    <p class="font-semibold text-gray-700">Localização</p>
                    <p class="text-gray-600"><?php echo htmlspecialchars($prestador['endereco'] ?? 'Não informado'); ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Centro e Direita: Formulário de Agendamento -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- Seleção de Serviço -->
          <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
              <i class="fas fa-tools text-blue-600 mr-2"></i>Selecione o Serviço
            </h3>
            
            <?php if (!empty($servicos)): ?>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="servicos-list">
                <?php foreach ($servicos as $servico): ?>
                  <div class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer transition-all hover:border-blue-600 hover:bg-blue-50" 
                       onclick="selecionarServico(<?php echo $servico['id']; ?>)">
                    <input type="radio" name="servico_id" value="<?php echo $servico['id']; ?>" class="hidden">
                    <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($servico['nome_servico']); ?></p>
                    <?php if ($servico['preco_base']): ?>
                      <p class="text-blue-600 font-bold mt-1">R$ <?php echo number_format($servico['preco_base'], 2, ',', '.'); ?></p>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <p class="text-gray-600">Este prestador ainda não cadastrou serviços.</p>
            <?php endif; ?>
          </div>

          <!-- Seleção de Data -->
          <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
              <i class="fas fa-calendar text-blue-600 mr-2"></i>Escolha a Data
            </h3>
            
            <div class="mb-4">
              <input type="date" id="data-agendamento" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition"
                     min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>

            <!-- Calendário Visual (Próximos 30 dias) -->
            <div class="grid grid-cols-7 gap-2" id="calendario">
              <!-- Preenchido por JavaScript -->
            </div>
          </div>

          <!-- Seleção de Horário -->
          <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
              <i class="fas fa-clock text-blue-600 mr-2"></i>Escolha o Horário
            </h3>
            
            <div class="text-sm text-gray-600 mb-4 p-3 bg-blue-50 rounded-lg">
              <p><strong>Data selecionada:</strong> <span id="data-selecionada">Nenhuma</span></p>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2" id="horarios-list">
              <!-- Preenchido por JavaScript -->
            </div>
          </div>

          <!-- Observações -->
          <div class="bg-white rounded-2xl shadow-lg p-6">
            <label class="text-lg font-bold text-gray-800 mb-3 block">
              <i class="fas fa-pen-square text-blue-600 mr-2"></i>Observações (Opcional)
            </label>
            <textarea id="observacoes" placeholder="Deixe uma mensagem ao prestador..." 
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition" 
                      rows="3"></textarea>
          </div>

          <!-- Botão de Confirmação -->
          <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="mb-4 text-sm opacity-90">Você será redirecionado para confirmar seu agendamento. Tenha certeza de que os dados estão corretos.</p>
            <button onclick="confirmarAgendamento()" class="w-full bg-white text-blue-600 font-bold py-3 rounded-xl hover:bg-gray-100 transition-all duration-200 transform hover:scale-105">
              <i class="fas fa-check-circle mr-2"></i>Confirmar Agendamento
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Script -->
  <script>
    const agendamentosExistentes = <?php echo json_encode($agendamentos_existentes); ?>;
    let dataSelecionada = null;
    let horarioSelecionado = null;
    let servicoSelecionado = null;

    // Gerar slots de 30 minutos
    function gerarHorarios() {
      const horarios = [];
      for (let h = 7; h < 18; h++) {
        for (let m of [0, 30]) {
          horarios.push(`${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`);
        }
      }
      return horarios;
    }

    // Preencher calendário
    function preencherCalendario() {
      const cal = document.getElementById('calendario');
      cal.innerHTML = '';
      const hoje = new Date();
      hoje.setDate(hoje.getDate() + 1);

      for (let i = 0; i < 30; i++) {
        const data = new Date(hoje);
        data.setDate(data.getDate() + i);
        const dateStr = data.toISOString().split('T')[0];
        const dia = data.getDate();
        const temAgendamento = agendamentosExistentes.some(a => a.startsWith(dateStr));

        const btn = document.createElement('button');
        btn.className = 'p-2 rounded-lg text-sm font-semibold transition-all ' +
          (temAgendamento ? 'bg-yellow-100 text-yellow-800 border-2 border-yellow-300' : 'bg-gray-100 text-gray-800 border-2 border-gray-200 hover:border-blue-600');
        btn.textContent = dia;
        btn.onclick = () => selecionarData(dateStr);
        cal.appendChild(btn);
      }
    }

    // Selecionar data
    function selecionarData(data) {
      dataSelecionada = data;
      document.getElementById('data-agendamento').value = data;
      
      const opcoes = data.split('-');
      const dataFormatada = new Date(opcoes[0], opcoes[1] - 1, opcoes[2]);
      const diaSemanaNome = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'][dataFormatada.getDay()];
      const diaFormat = dataFormatada.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });
      
      document.getElementById('data-selecionada').textContent = `${diaSemanaNome}, ${diaFormat}`;
      
      atualizarHorarios();
      preencherCalendario();
    }

    // Atualizar horários disponíveis
    function atualizarHorarios() {
      const lista = document.getElementById('horarios-list');
      lista.innerHTML = '';

      if (!dataSelecionada) {
        lista.innerHTML = '<p class="col-span-full text-gray-500 text-center py-4">Selecione uma data primeiro</p>';
        return;
      }

      const horarios = gerarHorarios();
      horarios.forEach(hora => {
        const horarioCompleto = `${dataSelecionada} ${hora}`;
        const ocupado = agendamentosExistentes.includes(horarioCompleto);

        const btn = document.createElement('button');
        btn.className = 'slot-button py-2 px-3 rounded-lg font-semibold transition-all ' +
          (ocupado ? 'bg-red-100 text-red-600 cursor-not-allowed opacity-50' : 'bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white');
        btn.textContent = hora;
        btn.disabled = ocupado;
        btn.onclick = () => selecionarHorario(hora, !ocupado);
        lista.appendChild(btn);
      });
    }

    function selecionarHorario(hora, disponivel) {
      if (disponivel) {
        horarioSelecionado = hora;
        atualizarHorarios();
        
        // Destacar o selecionado
        document.querySelectorAll('#horarios-list button').forEach(btn => {
          if (btn.textContent === hora) {
            btn.classList.add('bg-green-600', 'text-white');
          }
        });
        
        mostrarToast('Horário selecionado com sucesso!', 'success');
      }
    }

    function selecionarServico(servicoId) {
      servicoSelecionado = servicoId;
      document.querySelectorAll('#servicos-list > div').forEach(div => {
        div.classList.remove('border-blue-600', 'bg-blue-50');
        div.classList.add('border-gray-200');
      });
      event.currentTarget.classList.remove('border-gray-200');
      event.currentTarget.classList.add('border-blue-600', 'bg-blue-50');
    }

    function confirmarAgendamento() {
      if (!servicoSelecionado) {
        mostrarToast('Selecione um serviço', 'error');
        return;
      }
      if (!dataSelecionada) {
        mostrarToast('Selecione uma data', 'error');
        return;
      }
      if (!horarioSelecionado) {
        mostrarToast('Selecione um horário', 'error');
        return;
      }

      const dataHora = `${dataSelecionada} ${horarioSelecionado}:00`;
      const observacoes = document.getElementById('observacoes').value;

      fetch('processa_agendamento.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          acao: 'criar',
          prestador_id: <?php echo $prestador_id; ?>,
          servico_id: servicoSelecionado,
          data_agendamento: dataHora,
          observacoes: observacoes
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          mostrarToast('Agendamento realizado com sucesso!', 'success');
          setTimeout(() => window.location.href = 'historico.php', 1500);
        } else {
          mostrarToast(data.error || 'Erro ao agendar', 'error');
        }
      })
      .catch(err => mostrarToast('Erro ao processar', 'error'));
    }

    function mostrarToast(mensagem, tipo) {
      const toast = document.createElement('div');
      toast.className = `toast ${tipo}`;
      toast.innerHTML = `
        <div class="flex items-center space-x-3">
          <i class="fas fa-${tipo === 'success' ? 'check-circle text-green-600' : 'exclamation-circle text-red-600'}"></i>
          <span>${mensagem}</span>
        </div>
      `;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 4000);
    }

    // Inicializar
    preencherCalendario();
    atualizarHorarios();
  </script>

  <?php include 'footer.php'; ?>
</body>
</html>
