<?php
/*    PENDENTE: 
    - Arrumar modal js de exlusão
    - Boostrap views
*/

declare(strict_types=1);

// Sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Dependencias 
require_once '../app/config/config.php';
require_once '../app/models/bancodados.php';
require_once '../app/controllers/despesasController.php';
require_once '../app/controllers/authController.php';


// Instancia objeto de conexão e controladores
$bd = (new BancoDados())->getConexao();
$despesasController = new DespesasController($bd);
$authController = new AuthController($bd);


// Obtém a ação da URL (parâmetro 'action') ou seta para 'dashboard' por padrão
$acao = $_GET['acao'] ?? 'login';


// Garante que ações, exceto login e registro, só sejam executadas por usuários autenticados
if(!in_array($acao, ['login', 'registro']) && !$authController->verificarLogin() ){
    $authController->redirecionar('login');
}

// Validação CSRF
if ($_SERVER['REQUEST_METHOD'] == 'POST'
    && !in_array($acao, ['login', 'registro'])
    && (!isset($_POST['csrf_token']) 
    || $_POST['csrf_token'] !== $_SESSION['csrf_token']))
{
    // Registra a tentativa
    error_log("Ataque CSRF detectado. IP: " . $_SERVER['REMOTE_ADDR']);

    // Destroi sessão ativa e inicia nova sessão
    session_destroy();

    session_start();

    // Mensagem
    $_SESSION['erro'] = "Erro de segurança. Por favor, faça login novamente.";

    // Redireciona para login
    $authController->redirecionar('login');
    exit();

}


// Switch, controla oque deve ser exibido
switch ($acao) {

    case 'login':
        $authController->processarLogin();
        break;

    case 'registro':
        $authController->processarRegistro();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'dashboard':
    
        // Verifica se está logado
        if(!$authController->verificarLogin()){
            $authController->redirecionar('login');   
            
        }

        // Usuário logado, lógica do dashboard
        try {
            $despesas = $despesasController->getAllDespesas((int)$_SESSION['usuario_dados']['id']);
            include '../app/views/dashboard.php';
        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['erro'] = "Erro ao carregar despesas.";
            include '../app/views/dashboard.php';
        }
        break;

    case 'add_despesas':
        // chama função p/ adicionar uma despesa
        $despesasController->addDespesa();
        break;

    case 'editar_despesas':
        // chama função p/ editar despesa com base no ID
        $despesasController->editarDespesa();
        break;

    case 'excluir_despesas':
        // chama função p/ excluir despesa com base no ID
        $despesasController->excluirDespesas();
        break;

    case 'upload':
        // função upload
        $despesasController->upload();
        break;

    case 'download':
        // função download
        $despesasController->download();
        break;

    case 'logout':
        $authController->logout();
        break;

    default:
        echo "Ação não definida.";
        break;
}
