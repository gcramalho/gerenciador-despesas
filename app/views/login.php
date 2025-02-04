<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gerenciador de Despesas</title>
    <!-- link css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="auth-page">
    <div class="auth-container">

        <h2>Login</h2>

        <!-- Apresenta erro, se existir. -->
        <?php if(!empty($_SESSION['erro'])) : ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['erro']) ?>
                <?php unset($_SESSION['erro']); ?>
            </div>
        <?php endif; ?>

        <!-- Apresenta mensagem, se existir -->
        <?php if(isset($_SESSION['sucesso'])) : ?>
            <div class="alert alert-sucess">
                <?= htmlspecialchars($_SESSION['sucesso']) ?>
                <?php unset($_SESSION['sucesso']); ?>
            </div>
        <?php endif; ?>


        <!-- FORMULARIO LOGIN -->

        <form action="index.php?acao=login" method="post" class="auth-form">

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" name="usuario" id="usuario">
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            </div>

            <button type="submit" class="btn btn-primary">Entrar</button>

            <!-- Registro -->
            <p class="auth-links">
                Ainda não tem uma conta? <a href="index.php?acao=registro">Registrar-se</a>
            </p>
        </form>
    </div>

</body>

</html>