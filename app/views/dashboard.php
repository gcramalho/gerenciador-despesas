<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gerenciador de Despesas</title>

    <!-- <link rel="stylesheet" href="/public/css/style.css"> -->

    <!-- Fonte externa (Font Awesome) para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dashboard-page">

    <!-- Barra de navegação -->
    <nav class="navbar">
        <div class="navbar-brand">
            Gerenciador de Despesas
        </div>

        <div class="navbar-menu">
            <!-- Link para logout -->
            <a href="index.php?acao=logout" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="sidebar">
            <div class="summary-card">

                <h3>Resumo</h3>
                <?php
                // Variáveis para calcular total de despesas e itens
                $totalDespesas = 0;
                $totalItens = 0;
                foreach ($despesas as $despesa){
                    // Soma valores das despesas
                    $totalDespesas += $despesa['valor'];
                    // Soma quantidade de itens
                    $totalItens += $despesa['quant'];
                }
                ?>

                <div class="summary-item">
                    <i class="fas fa-money-bill"></i>
                    <div>
                        <span>Total Gasto:</span>
                        <!-- Formata e exibe o total de despesas -->
                        <strong>R$ <?= number_format($totalDespesas, 2, ',', '.') ?> </strong>
                    </div>
                </div>

                <div class="summary-item">
                    <i class="fas fa-shopping-cart"></i>
                    <div>
                        <!-- Exibe o total de itens -->
                        <span>Total Itens:</span>
                        <strong> <?= $totalItens ?> </strong>
                    </div>
                </div>

            </div>

            <!-- Botões ações -->
            <div class="action-buttons">
                <a href="index.php?acao=add_despesas" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nova Despesa
                </a>
                <a href="index.php?acao=upload" class="btn btn-secondary">
                    <i class="fas fa-upload"></i> Importar Excel
                </a>
                <a href="index.php?acao=download" class="btn btn-secondary">
                    <i class="fas fa-file-download"></i> Exportar Excel
                </a>
            </div>
        </div>

        <!-- Conteúdo principal -->
        <main class="content">
            <!-- Exibe mensagem de sucesso, se houver -->
            <?php if(isset($_SESSION['mensagem'])): ?>
                <div class="alert alert-sucess">
                    <?= htmlspecialchars($_SESSION['sucesso']) ?>
                </div>
            <?php endif; ?>

            <!-- Exibe mensagem de erro, se houver -->
            <?php if(isset($_SESSION['erro'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['erro']) ?>
                    <?php unset($_SESSION['erro']); ?>
                </div>
            <?php endif; ?>

            <!-- Tabela de despesas -->
            <div class="table-container">
                <table class="despesas-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Valor Unit.</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Se não houver despesas registradas, exibe mensagem -->
                        <?php if(empty($despesas)): ?>
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-receipt"></i>
                                    <p>Nenhuma despesa registrada</p>
                                </td>
                            </tr>

                        <?php else: ?>
                            <!-- Lista de despesas registradas -->
                            <?php foreach($despesas as $despesa): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($despesa['data'])) ?></td>

                                    <td><?= htmlspecialchars($despesa['tipo']) ?></td>

                                    <td><?= htmlspecialchars($despesa['quant']) ?></td>

                                    <td>R$ <?= number_format($despesa['valor'], 2, ',', '.') ?></td>

                                    <td>R$ <?= number_format($despesa['valor'] * $despesa['quant'], 2, ',', '.') ?></td>

                                    <td class="actions">
                                        <!-- Botão editar despesa -->
                                        <a href="index.php?acao=editar_despesas&id=<?=$despesa['id']?>" class="btn-icon" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Botão excluir despesa -->
                                        <button onclick="confirmarExclusao(<?=$despesa['id']?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Janela confirmação de exclusão -->
    <div id="deletarModal" class="modal">
        <div class="modal-content">
            <h3>Confirmar Exclusão</h3>
            <p>Tem certeza que deseja excluir esta despesa?</p>
            <div class="modal-actions">
                <button onclick="fecharModal()" class="btn btn-secondary">Cancelar</button>
                <button onclick="excluirDespesa()" class="btn btn-danger">Excluir</button>
            </div>
        </div>
    </div>


    <script src="js/dashboard.js"></script>
</body>
</html>