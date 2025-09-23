<!-- login.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

  <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8">
    <!-- Logo -->
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-blue-600">Search</h1>
      <p class="text-gray-500">Faça login para continuar</p>
    </div>

    <!-- Formulário de Login -->
    <form action="processa_login.php" method="POST" class="space-y-4">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
        <input type="email" id="email" name="email" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
      </div>

      <div>
        <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
        <input type="password" id="senha" name="senha" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
      </div>

      <button type="submit"
              class="w-full bg-blue-600 text-white py-2 rounded-lg shadow hover:bg-blue-700 transition">
        Entrar
      </button>
    </form>

    <!-- Criar Conta -->
    <p class="mt-6 text-center text-gray-600">
      Não tem conta?
      <a href="cadastro.php" class="text-blue-600 font-medium hover:underline">
        Criar conta
      </a>
    </p>
  </div>

</body>
</html>
