<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Despesa - Gerenciador de Despesas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="add-page">

      <!-- Barra de navegação -->
      <?php include '../app/views/partials/navbar.php'; ?>

    <div class="container- mt-5">

        <h1 class="text-center mb-4">Adicionar Nova Despesa</h1>

        <!-- Formulário Adicionar Despesa -->
        <form id="formAddDespesa" action="index.php?acao=add_despesas" method="post" class="add-form">

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

            <!-- Campo Tipo -->
            <div class="form-group mb-3">
                <label for="tipo" class="form-label">Tipo:</label>
                <input type="text" name="tipo" id="tipo" class="form-control" value="<?= htmlspecialchars($_POST['tipo'] ?? '')?>" required>
            </div>
            
            <!-- Campo Quantidade -->
            <div class="form-group mb-3">
                <label for="quantidade" class="form-label">Quantidade:</label>
                <input type="number" name="quantidade" id="quantidade" class="form-control" value="<?= htmlspecialchars($_POST['quantidade'] ?? '')?>" required>
            </div>

            <!-- Campo Valor -->
            <div class="form-group mb-3">
                <label for="valor" class="form-label">Valor:</label>
                <input type="text" name="valor" id="valor" class="form-control" value="<?= htmlspecialchars($_POST['valor'] ?? '')?>" required>
            </div>

            <!-- Campo Data -->
            <div class="form-group mb-3">
                <label for="data" class="form-label">Data:</label>
                <input type="date" name="data" id="data" class="form-control" value="<?= htmlspecialchars($_POST['data'] ?? '')?>" required>
            </div>

            <!-- Botão Adicionar Despesa -->
            <div class="d-grid mb-1">
                <button type="submit" class="btn btn-primary">Adicionar Despesa</button>
            </div>

            <!-- Botão Cancelar -->
             <div class="d-grid">
                <a href="index.php?acao=dashboard" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS (caso necessário) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- JS -->
    <script src="../public/js/mascaraValor.js"></script>
</body>

</html>