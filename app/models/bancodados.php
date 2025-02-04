<?php 
// Dependencias
declare(strict_types=1);

if (file_exists('../app/config/config.php')){
    require_once '../app/config/config.php';
} else {
    die('O arquivo config.php não foi encontrado.');
}

// Criando objeto para conexão banco de dados

class BancoDados{
    private $host;    // Passando constantes de conexão
    private $bd_nome;
    private $username;
    private $senha;
    private $conn;

    public function __construct()
    {
        $this->host = BD_HOST;
        $this->bd_nome = BD_NOME;
        $this->username = BD_USERNAME;
        $this->senha = BD_SENHA;
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->bd_nome}", $this->username, $this->senha);
        } catch(PDOException $e) {
            error_log("Erro de conexão: " . $e->getMessage());
            throw new Exception("Erro ao conectar com o banco de dados.");
        }
    }

    public function getConexao(): ?PDO {
        return $this->conn;
    }

}


?>