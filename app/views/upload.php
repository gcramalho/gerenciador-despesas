<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload - Gerenciador de Despesas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Font Awesome (ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="upload-page">
    <!-- Barra de navegação -->
    <?php include '../app/views/partials/navbar.php'; ?>

    <!-- Container principal -->
     <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title text-center">
                            <i class="fas fa-upload"></i> Importar Arquivo Excel
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Exibe mensagem, removido por causa do redirecionamento -->
                          <?php  /* if(isset($_SESSION['mensagem'])): ?>
                            <div class="alert alert-sucess">
                                <?= htmlspecialchars($_SESSION ['mensagem']) ?>
                                <?php unset($_SESSION['mensagem']); ?>
                            </div>
                        <?php endif; */ ?> 

                        <!-- Exibe mensagem de erro, se houver -->
                        <?php if(isset($_SESSION['erro'])): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($_SESSION['erro']) ?>
                                <?php unset($_SESSION['erro']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulário de Upload -->
                         <form action="index.php?acao=upload" method="post" enctype="multipart/form-data">

                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                            <div class="mb-3">
                                <label for="arquivo" class="form-label">Selecione o arquivo:</label>
                                <input type="file"
                                name="file" id="file" class="form-control" accept=".xlsx, .xls" required>
                                <small class="form-text text-muted">Formatos suportados: .xlsx, .xls</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Enviar Arquivo
                                </button>
                            </div>
                         </form>

                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php?acao=dashboard" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
     </div>

    <!-- Bootstrap JS (caso necessário) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>