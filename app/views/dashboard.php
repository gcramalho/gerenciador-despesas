<?php
// Calculo total de despesas e itens
$totalDespesas = 0;
$totalItens = 0;

if(!empty($despesas)) {
    foreach ($despesas as $despesa) {
        // Soma valores das despesas
        $totalDespesas += ($despesa['valor'] * $despesa['quant']);
        // Soma quantidade de itens
        $totalItens += $despesa['quant'];
    };
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gerenciador de Despesas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="dashboard-page">
    <!-- Barra de navegação -->
    <?php include '../app/views/partials/navbar.php'; ?>

    <!-- Container principal -->
    <div class="container-fluid mt-12">
        <div class="row">
            <!-- Card Resumo -->
            <div class="col-md-2">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h3 class="card-title">Resumo</h3>

                        <!-- Total Gasto -->
                        <div class="d-flex align-items-center mb-3 justify-content-center">
                            <i class="fas fa-money-bill me-3"></i>
                            <div>
                                <span>Total Gasto:</span>
                                <strong class="d-block">
                                    R$<?= number_format($totalDespesas, 2, ',', '.') ?>
                                </strong>
                            </div>
                        </div>

                        <!-- Total Itens -->
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas.fa-shopping-cart.me-3"></i>

                            <div>
                                <span>Total Itens:</span>
                                <strong class="d-block">
                                    <?= $totalItens ?>
                                </strong>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="col md-9 mt-3">
                <div class="d-grid gap-2">
                    <!-- Adicionar  -->
                    <a href="index.php?acao=add_despesas" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nova Despesa
                    </a>
                    <!-- Importação -->
                    <a href="index.php?acao=upload" class="btn btn-secondary">
                        <i class="fas fa-upload"></i> Importar Excel
                    </a>
                    <!-- Exportação -->
                    <a href="index.php?acao=download" class="btn btn-secondary">
                        <i class="fas fa-download"></i> Exportar Excel
                    </a>
                </div>
            </div>


            <!-- Conteúdo principal -->
            <div class="col-md-12">
                <!-- Exibe mensagem de sucesso, se houver -->
                <?php if (isset($_SESSION['mensagem'])): ?>
                    <div class="alert alert-sucess">
                        <?= htmlspecialchars($_SESSION['mensagem']) ?>
                    </div>
                <?php endif; ?>

                <!-- Exibe mensagem de erro, se houver -->
                <?php if (isset($_SESSION['erro'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['erro']) ?>
                        <?php unset($_SESSION['erro']); ?>
                    </div>
                <?php endif; ?>

                <!-- Tabela de despesas -->
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>Valor Unit.</th>
                                    <th>Quantidade</th>
                                    <th>Total</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se não houver despesas registradas -->
                                <?php if (empty($despesas)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <i class="fas fa-receipt"></i>
                                            <p>Nenhuma despesa registrada</p>
                                        </td>
                                    </tr>

                                <?php else: ?>
                                    <!-- Lista de despesas registradas -->
                                    <?php foreach ($despesas as $despesa): ?>
                                        <tr>
                                            <!-- Data -->
                                            <td><?= date('d/m/Y', strtotime($despesa['data'])) ?></td>
                                            <!-- Tipo -->
                                            <td><?= htmlspecialchars($despesa['tipo']) ?></td>
                                            <!-- Valor Unitario -->
                                            <td>R$ <?= number_format($despesa['valor'], 2, ',', '.') ?></td>
                                            <!-- Quantidade -->
                                            <td><?= htmlspecialchars($despesa['quant']) ?></td>
                                            <!-- Total -->
                                            <td>R$ <?= number_format($despesa['valor'] * $despesa['quant'], 2, ',', '.') ?></td>

                                            <td>
                                                <!-- Botão Editar Despesa -->
                                                <a href="index.php?acao=editar_despesas&id=<?= $despesa['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Botão Excluir Despesa -->
                                                <button onclick="confirmarExclusao(<?= $despesa['id'] ?>)" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Janela confirmação de exclusão -->
    <div id="deletarModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir esta despesa?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button onclick="excluirDespesa()" type="button" class="btn btn-danger">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS / Dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script src="js/dashboard.js"></script>
</body>

</html>