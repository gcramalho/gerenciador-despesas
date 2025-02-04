<?php 
// Dependencias
declare(strict_types=1);
require_once '../app/models/usuario.php';



// Lógica Autenticação Usuário
class AuthController{
    
    private Usuario $usuarioModel;

    public function __construct(PDO $conn)
    {
        $this->usuarioModel = new Usuario($conn);
    }

    public function processarRegistro(): void
    {
        // Verifica tipo da requisição
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            try {
                // Valida dados 
                $usuario = $this->validarInput($_POST['usuario'] ?? '');
                $senha = $this->validarInput($_POST['senha'] ?? '');

                // Validação campos
                if(empty($usuario) || empty($senha)){
                    throw new Exception("Todos os campos são obrigatórios.");
                }

                // Validação tamanho senha
                if(strlen($senha) < 8){
                    throw new Exception("A senha deve conter ao menos 8 caracteres.");
                }

                // Chama model para registrar o usuário
                $this->usuarioModel->registrar($usuario, $senha);

                // Armazena mensagem de sucesso
                $_SESSION['sucesso'] = "Conta criada com sucesso! Faça login para continuar.";

                // Redireciona usuário para pág de login
                $this->redirecionar('login');

            } catch (Exception $e) {
                // Em caso de erro
                $_SESSION['erro'] = $e->getMessage();

                // Redireciona usuário de volta para pág de registro
                $this->redirecionar('registro');
            }
        }

        include '../app/views/registro.php';   /* PENDENTE - criar esta view */
    }

    public function processarLogin(): void
    {
        // Verifica requisição
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            try {
                // Valida dados
                $usuario = $this->validarInput($_POST['usuario'] ?? '');
                $senha = $this->validarInput($_POST['senha'] ?? '');

                // Verifica campos
                if(empty($usuario) || empty($senha)){
                    throw new Exception("Todos os campos são obrigatórios.");
                }

                // Chama metodo de login do model
                $usuario_info = $this->usuarioModel->login($usuario, $senha);

                // Regenera sessão ID, previnir session fixation attack
                session_regenerate_id(true);

                // Armazena dados na sessão
                $_SESSION['usuario_dados'] = $usuario_info;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                // Redireciona para o dashboard 
                $this->redirecionar('dashboard');
            
            } catch (Exception $e) {
                // Em caso de erro, retorna ao login
                $_SESSION['erro'] = $e->getMessage();
                $this->redirecionar('login');
            }
        }
        include '../app/views/login.php';
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        $this->redirecionar('login');
    }

    public function verificarLogin(): bool
    {
        return isset($_SESSION['usuario_dados']);
    }

    private function validarInput(string $dados): string
    {
        // Remove espaços extras
        $dados = trim($dados);
        // Remove barras
        $dados = stripslashes($dados);
        // Converte caracteres especiais, previnir injeção de código
        $dados = htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');

        // Retorna os dados validados e limpos
        return $dados;
    }

    public function redirecionar(string $acao): void
    {
        header("Location: index.php?acao={$acao}");
        exit();
    }

}