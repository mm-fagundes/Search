<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Prestador | Search</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="pro-register-body">
    <div class="register-container">
            <h1>Cadastrar Prestador</h1>
        <form action="../../handlers/pro-register-handler.php" method="POST" class="register-form">
            <input type="text" name="name" placeholder="Nome Completo" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="text" name="address" placeholder="Endereço" required>
            <input type="text" name="phone" placeholder="Telefone" required>
            <input type="password" name="password" placeholder="Senha" required>
            <input type="password" name="confirm-password" placeholder="Confirmar Senha" required>
            <input type="submit" value="Cadastrar" class="btn-primary">
        </form>

        <a href="../pro/login-pro.php" class="back-link">Já tem uma conta? Faça login</a>
    </div>

    <!-- Ícones (Font Awesome) -->
    <script src="https://kit.fontawesome.com/a2e0b5b0a7.js" crossorigin="anonymous"></script>
</body>
</html>
