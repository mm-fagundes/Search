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
        <a href="home.php" class="text-gray-700 hover:text-blue-600 font-medium">Clientes</a>
        <a href="prestadores.php" class="text-gray-700 hover:text-blue-600 font-medium">Prestadores</a>
        <a href="home.php" class="text-gray-700 hover:text-blue-600 font-medium">Sobre</a>
        <a href="home.php" class="text-gray-700 hover:text-blue-600 font-medium">Contato</a>
        <a href="login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">Entrar</a>
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
    <a href="home.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Clientes</a>
    <a href="prestadores.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Prestadores</a>
    <a href="home.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Sobre</a>
    <a href="home.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Contato</a>
    <a href="login_cliente.php" class="block px-4 py-2 text-blue-600 font-medium hover:bg-gray-100">Entrar</a>
  </div>
</nav>

<script>
  const btn = document.getElementById('menu-btn');
  const menu = document.getElementById('mobile-menu');
  if (btn) btn.addEventListener('click', () => menu.classList.toggle('hidden'));
</script>
