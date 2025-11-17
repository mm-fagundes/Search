<?php
session_start();

// Login do admin
if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === 'mateusfagundes' && $password === '123') {
        $_SESSION['admin_id'] = 'admin_' . time();
        $_SESSION['admin_name'] = 'Mateus Fagundes';
        header("Location: adm.php");
        exit;
    } else {
        $error = 'Credenciais inválidas';
    }
}

// Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_name']);
    session_destroy();
    header("Location: adm.php");
    exit;
}

// Verificar se está logado
if (!isset($_SESSION['admin_id'])) {
    // Mostrar formulário de login
    ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Search</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-r from-red-600 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Painel Admin</h1>
                <p class="text-gray-600">Acesso restrito à administração</p>
            </div>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Usuário</label>
                    <input type="text" id="username" name="username" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Seu usuário">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Senha</label>
                    <input type="password" id="password" name="password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Sua senha">
                </div>

                <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <button type="submit" name="login" class="w-full bg-gradient-to-r from-red-600 to-pink-600 text-white py-3 rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 font-semibold flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>Acessar Painel
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    <a href="index.php" class="text-red-600 hover:text-red-700 font-semibold">Voltar ao site</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
    <?php
    exit;
}

// Se chegou aqui, está logado
include 'connection.php';

$aba = $_GET['aba'] ?? 'dashboard';

// Buscar dados
$clientes_total = $conn->query("SELECT COUNT(*) as total FROM clientes")->fetch_assoc()['total'];
$prestadores_total = $conn->query("SELECT COUNT(*) as total FROM prestadores")->fetch_assoc()['total'];
$avaliacoes_total = $conn->query("SELECT COUNT(*) as total FROM avaliacoes")->fetch_assoc()['total'];
$agendamentos_total = $conn->query("SELECT COUNT(*) as total FROM agendamentos")->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Search</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-red-600 to-pink-600 shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                        <i class="fas fa-shield-alt text-red-600 text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-white">Admin Panel</span>
                </div>

                <div class="flex items-center space-x-6">
                    <span class="text-white font-semibold"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <a href="adm.php?logout=1" class="bg-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Sair
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo -->
    <div class="pt-20 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Menu Lateral -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
                        <div class="bg-gradient-to-r from-red-600 to-pink-600 p-4">
                            <h3 class="text-white font-bold text-lg">Menu</h3>
                        </div>
                        <nav class="p-4 space-y-2">
                            <a href="adm.php?aba=dashboard" class="block px-4 py-3 rounded-lg <?php echo $aba === 'dashboard' ? 'bg-red-100 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-100'; ?> transition-colors">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                            <a href="adm.php?aba=clientes" class="block px-4 py-3 rounded-lg <?php echo $aba === 'clientes' ? 'bg-red-100 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-100'; ?> transition-colors">
                                <i class="fas fa-users mr-2"></i>Clientes
                            </a>
                            <a href="adm.php?aba=prestadores" class="block px-4 py-3 rounded-lg <?php echo $aba === 'prestadores' ? 'bg-red-100 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-100'; ?> transition-colors">
                                <i class="fas fa-briefcase mr-2"></i>Prestadores
                            </a>
                            <a href="adm.php?aba=avaliacoes" class="block px-4 py-3 rounded-lg <?php echo $aba === 'avaliacoes' ? 'bg-red-100 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-100'; ?> transition-colors">
                                <i class="fas fa-star mr-2"></i>Avaliações
                            </a>
                            <a href="adm.php?aba=agendamentos" class="block px-4 py-3 rounded-lg <?php echo $aba === 'agendamentos' ? 'bg-red-100 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-100'; ?> transition-colors">
                                <i class="fas fa-calendar mr-2"></i>Agendamentos
                            </a>
                            <a href="adm.php?aba=categorias" class="block px-4 py-3 rounded-lg <?php echo $aba === 'categorias' ? 'bg-red-100 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-100'; ?> transition-colors">
                                <i class="fas fa-tags mr-2"></i>Categorias
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Conteúdo Principal -->
                <div class="md:col-span-3">
                    
                    <!-- Dashboard -->
                    <?php if ($aba === 'dashboard'): ?>
                    <div class="space-y-6">
                        <h1 class="text-4xl font-bold text-gray-800">Dashboard</h1>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-600">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 font-semibold">Total de Clientes</p>
                                        <p class="text-4xl font-bold text-gray-800 mt-2"><?php echo $clientes_total; ?></p>
                                    </div>
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-600">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 font-semibold">Total de Prestadores</p>
                                        <p class="text-4xl font-bold text-gray-800 mt-2"><?php echo $prestadores_total; ?></p>
                                    </div>
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-briefcase text-green-600 text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-600">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 font-semibold">Total de Avaliações</p>
                                        <p class="text-4xl font-bold text-gray-800 mt-2"><?php echo $avaliacoes_total; ?></p>
                                    </div>
                                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-star text-yellow-600 text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-600">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-600 font-semibold">Total de Agendamentos</p>
                                        <p class="text-4xl font-bold text-gray-800 mt-2"><?php echo $agendamentos_total; ?></p>
                                    </div>
                                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar text-purple-600 text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gerenciar Clientes -->
                    <?php elseif ($aba === 'clientes'): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Gerenciar Clientes</h2>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 border-b">
                                        <th class="px-4 py-3 text-left font-semibold">ID</th>
                                        <th class="px-4 py-3 text-left font-semibold">Nome</th>
                                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                                        <th class="px-4 py-3 text-left font-semibold">Telefone</th>
                                        <th class="px-4 py-3 text-left font-semibold">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT * FROM clientes ORDER BY criado_em DESC LIMIT 50");
                                    if ($result && $result->num_rows > 0):
                                        while ($cliente = $result->fetch_assoc()):
                                    ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">#<?php echo $cliente['id']; ?></td>
                                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($cliente['email']); ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                                        <td class="px-4 py-3">
                                            <button onclick="deletarCliente(<?php echo $cliente['id']; ?>)" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Gerenciar Prestadores -->
                    <?php elseif ($aba === 'prestadores'): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Gerenciar Prestadores</h2>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 border-b">
                                        <th class="px-4 py-3 text-left font-semibold">ID</th>
                                        <th class="px-4 py-3 text-left font-semibold">Nome</th>
                                        <th class="px-4 py-3 text-left font-semibold">Nicho</th>
                                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                                        <th class="px-4 py-3 text-left font-semibold">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT * FROM prestadores ORDER BY criado_em DESC LIMIT 50");
                                    if ($result && $result->num_rows > 0):
                                        while ($prestador = $result->fetch_assoc()):
                                    ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">#<?php echo $prestador['id']; ?></td>
                                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($prestador['nome']); ?></td>
                                        <td class="px-4 py-3"><span class="bg-green-100 text-green-800 px-2 py-1 rounded"><?php echo htmlspecialchars($prestador['nicho']); ?></span></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($prestador['email']); ?></td>
                                        <td class="px-4 py-3">
                                            <button onclick="deletarPrestador(<?php echo $prestador['id']; ?>)" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Avaliações -->
                    <?php elseif ($aba === 'avaliacoes'): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Avaliações Recentes</h2>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 border-b">
                                        <th class="px-4 py-3 text-left font-semibold">Prestador</th>
                                        <th class="px-4 py-3 text-left font-semibold">Nota</th>
                                        <th class="px-4 py-3 text-left font-semibold">Comentário</th>
                                        <th class="px-4 py-3 text-left font-semibold">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT a.*, p.nome as prestador_nome FROM avaliacoes a LEFT JOIN prestadores p ON a.prestador_id = p.id ORDER BY a.criado_em DESC LIMIT 50");
                                    if ($result && $result->num_rows > 0):
                                        while ($avaliacao = $result->fetch_assoc()):
                                    ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($avaliacao['prestador_nome']); ?></td>
                                        <td class="px-4 py-3">
                                            <span class="text-yellow-500">
                                                <?php for ($i = 0; $i < $avaliacao['nota']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 max-w-xs truncate"><?php echo htmlspecialchars($avaliacao['comentario']); ?></td>
                                        <td class="px-4 py-3 text-gray-500 text-xs"><?php echo date('d/m/Y H:i', strtotime($avaliacao['criado_em'])); ?></td>
                                    </tr>
                                    <?php endwhile; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Agendamentos -->
                    <?php elseif ($aba === 'agendamentos'): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Todos os Agendamentos</h2>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 border-b">
                                        <th class="px-4 py-3 text-left font-semibold">Cliente</th>
                                        <th class="px-4 py-3 text-left font-semibold">Prestador</th>
                                        <th class="px-4 py-3 text-left font-semibold">Data</th>
                                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT a.*, c.nome as cliente_nome, p.nome as prestador_nome FROM agendamentos a LEFT JOIN clientes c ON a.cliente_id = c.id LEFT JOIN prestadores p ON a.prestador_id = p.id ORDER BY a.data_agendamento DESC LIMIT 50");
                                    if ($result && $result->num_rows > 0):
                                        while ($agendamento = $result->fetch_assoc()):
                                            $status_colors = [
                                                'pendente' => 'bg-yellow-100 text-yellow-800',
                                                'confirmado' => 'bg-green-100 text-green-800',
                                                'cancelado' => 'bg-red-100 text-red-800',
                                                'concluido' => 'bg-blue-100 text-blue-800'
                                            ];
                                    ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($agendamento['cliente_nome']); ?></td>
                                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($agendamento['prestador_nome']); ?></td>
                                        <td class="px-4 py-3"><?php echo date('d/m/Y H:i', strtotime($agendamento['data_agendamento'])); ?></td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $status_colors[$agendamento['status']] ?? 'bg-gray-100'; ?>">
                                                <?php echo ucfirst($agendamento['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Categorias -->
                    <?php elseif ($aba === 'categorias'): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Gerenciar Categorias</h2>

                        <div class="mb-6">
                            <form id="categoria-form" class="flex gap-3 items-start">
                                <input type="text" id="categoria-nome" placeholder="Nome da categoria" class="px-4 py-3 border border-gray-300 rounded-xl w-1/3" required>
                                <input type="text" id="categoria-descricao" placeholder="Descrição (opcional)" class="px-4 py-3 border border-gray-300 rounded-xl w-1/2">
                                <button type="button" onclick="criarCategoria()" class="bg-gradient-to-r from-red-600 to-pink-600 text-white px-4 py-3 rounded-xl font-semibold">Adicionar</button>
                            </form>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 border-b">
                                        <th class="px-4 py-3 text-left font-semibold">ID</th>
                                        <th class="px-4 py-3 text-left font-semibold">Nome</th>
                                        <th class="px-4 py-3 text-left font-semibold">Descrição</th>
                                        <th class="px-4 py-3 text-left font-semibold">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $res_cat = $conn->query("SELECT * FROM categorias ORDER BY criado_em DESC");
                                    if ($res_cat && $res_cat->num_rows > 0):
                                        while ($cat = $res_cat->fetch_assoc()):
                                    ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">#<?php echo $cat['id']; ?></td>
                                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($cat['nome']); ?></td>
                                        <td class="px-4 py-3"><?php echo htmlspecialchars($cat['descricao']); ?></td>
                                        <td class="px-4 py-3">
                                            <button onclick="deletarCategoria(<?php echo $cat['id']; ?>)" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

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

        async function deletarCliente(id) {
            const ok = await confirmAction('Tem certeza que deseja deletar este cliente?');
            if (!ok) return;
            fetch('processa_adm_cliente.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ acao: 'deletar', id: id })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Cliente deletado', 'success');
                    setTimeout(() => location.reload(), 700);
                } else {
                    mostrarToast('Erro: ' + (data.error || 'Desconhecido'), 'error');
                }
            })
            .catch(e => mostrarToast('Erro: ' + e, 'error'));
        }

        async function deletarPrestador(id) {
            const ok = await confirmAction('Tem certeza que deseja deletar este prestador?');
            if (!ok) return;
            fetch('processa_adm_prestador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ acao: 'deletar', id: id })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Prestador deletado', 'success');
                    setTimeout(() => location.reload(), 700);
                } else {
                    mostrarToast('Erro: ' + (data.error || 'Desconhecido'), 'error');
                }
            })
            .catch(e => mostrarToast('Erro: ' + e, 'error'));
        }

        function criarCategoria() {
            const nome = document.getElementById('categoria-nome').value.trim();
            const descricao = document.getElementById('categoria-descricao').value.trim();
            if (!nome) { mostrarToast('Informe o nome da categoria', 'error'); return; }

            const form = new URLSearchParams();
            form.append('nome', nome);
            form.append('descricao', descricao);

            fetch('processa_adm_categoria.php?acao=criar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: form.toString()
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Categoria criada', 'success');
                    setTimeout(() => location.reload(), 700);
                } else {
                    mostrarToast('Erro: ' + (data.error || 'Desconhecido'), 'error');
                }
            })
            .catch(e => mostrarToast('Erro: ' + e, 'error'));
        }

        async function deletarCategoria(id) {
            const ok = await confirmAction('Remover categoria? Isso não removerá prestadores, apenas a categoria.');
            if (!ok) return;
            fetch('processa_adm_categoria.php?acao=deletar&id=' + encodeURIComponent(id), {
                method: 'GET'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Categoria removida', 'success');
                    setTimeout(() => location.reload(), 700);
                } else {
                    mostrarToast('Erro: ' + (data.error || 'Desconhecido'), 'error');
                }
            })
            .catch(e => mostrarToast('Erro: ' + e, 'error'));
        }
    </script>

</body>
</html>
<?php
$conn->close();
?>

</html>