<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Search</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

  <div class="min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
      <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Criar Conta</h2>
      
      <form action="processa_cadastro.php" method="POST" class="space-y-4">
        <!-- Nome -->
        <div>
          <label for="nome" class="block text-gray-700">Nome completo</label>
          <input type="text" id="nome" name="nome" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        
        <!-- Email -->
        <div>
          <label for="email" class="block text-gray-700">Email</label>
          <input type="email" id="email" name="email" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        
        <!-- Senha -->
        <div>
          <label for="senha" class="block text-gray-700">Senha</label>
          <input type="password" id="senha" name="senha" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        
        <!-- Confirmar Senha -->
        <div>
          <label for="confirmar_senha" class="block text-gray-700">Confirmar Senha</label>
          <input type="password" id="confirmar_senha" name="confirmar_senha" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        
        <!-- Botão -->
        <button type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-lg shadow hover:bg-blue-700 transition">
          Cadastrar
        </button>
      </form>

      <p class="mt-4 text-center text-gray-600">
        Já tem conta?
        <a href="login.php" class="text-blue-600 hover:underline">Entrar</a>
      </p>
    </div>
  </div>

</body>
</html>
