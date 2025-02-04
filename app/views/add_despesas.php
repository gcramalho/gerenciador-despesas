<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Despesa</title>
</head>

<body>

    <h1>Adicionar Nova Despesa</h1>

    <form id="formAddDespesa" action="index.php?acao=add_despesas" method="post">

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

        <label for="tipo">Tipo:</label>
        <input type="text" name="tipo" id="tipo" value="<?= htmlspecialchars($_POST['tipo'] ?? '')?>">>
        <br>

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" value="<?= htmlspecialchars($_POST['quantidade'] ?? '')?>">
        <br>

        <label for="valor">Valor:</label>
        <input type="text" name="valor" id="valor" value="<?= htmlspecialchars($_POST['valor'] ?? '')?>">> 
        <br>

        <label for="data">Data:</label>
        <input type="date" name="data" id="data" value="<?= htmlspecialchars($_POST['data'] ?? '')?>">>
        <br>

        <button type="submit">Adicionar Despesa</button>
    </form>


    <script src="../public/js/mascaraValor.js"></script>

</body>

</html>