<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Prestador - Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">


  <div class="pt-20 flex justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
      <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Cadastrar Prestador</h2>

      <form action="processa_prestador.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <!-- Nome -->
        <div>
          <label for="nome" class="block text-gray-700">Nome completo</label>
          <input type="text" id="nome" name="nome" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Telefone -->
        <div>
          <label for="telefone" class="block text-gray-700">Telefone</label>
          <input type="text" id="telefone" name="telefone" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Foto -->
        <div>
          <label for="foto" class="block text-gray-700">Foto</label>
          <input type="file" id="foto" name="foto" accept="image/*"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Botão -->
        <button type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-lg shadow hover:bg-blue-700 transition">
          Cadastrar
        </button>
      </form>
    </div>
  </div>

</body>
</html>
