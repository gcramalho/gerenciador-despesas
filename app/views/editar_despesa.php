<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Despesas</title>
</head>

<body>

    <h1>Editar Despesas</h1>

    <form id="formEditarDespesa" action="index.php?acao=editar_despesas" method="post">

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

        <input type="hidden" name="despesa_id" value="<?= htmlspecialchars($despesa['id']) ?>">

        <label for="tipo">Tipo:</label>
        <input type="text" name="tipo" id="tipo" value="<?= htmlspecialchars($despesa['tipo']) ?>" required>
        <br>

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" value="<?= htmlspecialchars($despesa['quant']) ?>" required>
        <br>

        <label for="valor">Valor</label>
        <input type="text" name="valor" id="valor" value="<?= htmlspecialchars('R$ ' . number_format($despesa['valor'], 2, ',', '.')) ?>" required>
        <br>

        <label for="data">Data:</label>
        <input type="date" name="data" id="data" value="<?= htmlspecialchars($despesa['data']) ?>" required>
        <br>

        <button type="submit">Salvar Alterações</button>

    </form>

    <script src="../public/js/mascaraValor.js"></script>

</body>

</html>