<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Gerenciador de Despesas</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">

    <div class="auth-container">

        <h2>Criar Conta</h2>

        <!-- Apresenta erro, se existir -->
        <?php if (isset($_SESSION['erro'])) : ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['erro']) ?>
                <?php unset($_SESSION['erro']); ?>
            </div>
        <?php endif; ?>


        <!-- FORMULÁRIO -->
        <form action="index.php?acao=registro" method="POST" class="auth-form">

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

            <!-- Campo Usuario -->
            <div class="form-group">
                <label for="usuario" class="form-label">Usuário:</label>
                <input type="text" name="usuario" id="usuario" class="form-control" required>
            </div>

            <!-- Campo Senha -->
            <div class="form-group">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Registrar-se</button>

            <p class="auth-links">
                Já tem uma conta? <a href="index.php?acao=login">Fazer login</a>
            </p>
        </form>
    </div>
    
    <!-- Bootstrap JS (talvez não será necesssário) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>