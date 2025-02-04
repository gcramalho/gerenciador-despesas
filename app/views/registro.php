<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Gerenciador de Despesas</title>
    <!-- css / bootstrap -->
</head>

<body class="auth-page">

    <div class="auth-container">

        <h2>Criar Conta</h2>

        <!-- Apresenta erro -->
        <?php if (isset($_SESSION['erro'])) : ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['erro']) ?>
                <?php unset($_SESSION['erro']); ?>
            </div>
        <?php endif; ?>


        <!-- FORMULÁRIO -->
        <form action="index.php?acao=registro" method="POST" class="auth-form">

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            </div>

            <button type="submit" class="btn btn-primary">Registrar-se</button>

            <p class="auth-links">
                Já tem uma conta? <a href="index.php?acao=login">Fazer login</a>
            </p>

        </form>

    </div>

</body>

</html>