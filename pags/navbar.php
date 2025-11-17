<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!-- navbar.php -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="styles.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<nav class="bg-white shadow-md fixed w-full z-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex-shrink-0 flex items-center">
        <span class="text-2xl font-bold text-blue-600">Search</span>
      </div>

      <div class="hidden md:flex space-x-8 items-center">
        <?php if (!empty($_SESSION['user_id'])): ?>
          <?php if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'prestador'): ?>
            <a href="dashboard_prestador.php" class="text-green-600 font-semibold">Dashboard</a>
            <a href="agenda_prestador.php" class="text-gray-700 hover:text-green-600 font-medium">Agenda</a>
            <a href="cadastrar_servicos.php" class="text-gray-700 hover:text-green-600 font-medium">Cadastrar Serviço</a>
            <a href="relatorios.php" class="text-gray-700 hover:text-green-600 font-medium">Relatórios</a>
            <a href="perfil_prestador.php" class="text-gray-700 hover:text-green-600 font-medium">Perfil</a>
            <a href="logout.php" class="text-red-600 hover:text-red-700 font-medium">Sair</a>
          <?php else: ?>
            <a href="home_cliente.php" class="text-gray-700 hover:text-blue-600 font-medium">Início</a>
            <a href="favoritos.php" class="text-gray-700 hover:text-blue-600 font-medium">Favoritos</a>
            <a href="historico.php" class="text-gray-700 hover:text-blue-600 font-medium">Histórico</a>
            <a href="perfil_cliente.php" class="text-gray-700 hover:text-blue-600 font-medium">Perfil</a>
            <a href="logout.php" class="text-red-600 hover:text-red-700 font-medium">Sair</a>
          <?php endif; ?>
        <?php else: ?>
          <a href="home.php" class="text-gray-700 hover:text-blue-600 font-medium">Clientes</a>
          <a href="prestadores.php" class="text-gray-700 hover:text-blue-600 font-medium">Prestadores</a>
          <a href="home.php" class="text-gray-700 hover:text-blue-600 font-medium">Sobre</a>
          <a href="home.php" class="text-gray-700 hover:text-blue-600 font-medium">Contato</a>
          <a href="login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">Entrar</a>
        <?php endif; ?>
      </div>

      <div class="md:hidden flex items-center">
        <button id="menu-btn" class="text-gray-700 hover:text-blue-600 focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <div id="mobile-menu" class="hidden md:hidden bg-white border-t shadow-md">
    <?php if (!empty($_SESSION['user_id'])): ?>
      <?php if (!empty($_SESSION['user_type']) && $_SESSION['user_type'] === 'prestador'): ?>
        <a href="dashboard_prestador.php" class="block px-4 py-2 text-green-600 font-medium hover:bg-gray-100">Dashboard</a>
        <a href="agenda_prestador.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Agenda</a>
        <a href="cadastrar_servicos.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Cadastrar Serviço</a>
        <a href="relatorios.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Relatórios</a>
        <a href="perfil_prestador.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Perfil</a>
        <a href="logout.php" class="block px-4 py-2 text-red-600 font-medium hover:bg-gray-100">Sair</a>
      <?php else: ?>
        <a href="home_cliente.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Início</a>
        <a href="favoritos.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Favoritos</a>
        <a href="historico.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Histórico</a>
        <a href="perfil_cliente.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Perfil</a>
        <a href="logout.php" class="block px-4 py-2 text-red-600 font-medium hover:bg-gray-100">Sair</a>
      <?php endif; ?>
    <?php else: ?>
      <a href="home.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Clientes</a>
      <a href="prestadores.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Prestadores</a>
      <a href="home.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Sobre</a>
      <a href="home.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Contato</a>
      <a href="login_cliente.php" class="block px-4 py-2 text-blue-600 font-medium hover:bg-gray-100">Entrar</a>
    <?php endif; ?>
  </div>
</nav>

<script>
  const btn = document.getElementById('menu-btn');
  const menu = document.getElementById('mobile-menu');
  if (btn) btn.addEventListener('click', () => menu.classList.toggle('hidden'));
</script>
