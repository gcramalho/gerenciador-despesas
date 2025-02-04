<?php
// Dependencias
declare(strict_types=1);

class Usuario
{
    private PDO $conn;
    private string $tabela = 'usuarios';

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /** * -------- MÉTODO REGISTRAR USUÁRIO --------
     * 
     * @param string $usuario O nome de usuário a ser registrado.
     * @param string $senha A senha do usuário a ser registrada.
     * @return bool Retorna true se o usuário for registrado com sucesso, ou false caso contrário.
     * @throws Exception Se o nome de usuário já estiver em uso.
     * @throws Exception Se ocorrer um erro ao tentar registrar o usuário no banco de dados.
     */
    public function registrar(string $usuario, string $senha): bool
    {
        // Verifica se nome de usuario já está em uso
        if ($this->usuarioExiste($usuario)) {
            throw new Exception("Nome de usuário já está em uso.");
        }

        // Cria hash da senha inserida
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Define consulta SQL
        $query = "INSERT INTO {$this->tabela} (usuario, senha) VALUES (:usuario, :senha)";

        try {
            // Prepara consulta, vincula parametros 

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':senha', $senha_hash);

            // Executa consulta e retorna true
            return $stmt->execute();
        } catch (PDOException $e) {
            // Em caso de erro
            error_log("Erro ao registrar usuário: " . $e->getMessage());
            throw new Exception("Erro ao criar conta.");
        }
    }

    /** * -------- MÉTODO LOGIN --------
     * 
     * @param string $usuario O nome de usuário que o usuário deseja fazer login.
     * @param string $senha A senha fornecida pelo usuário para autenticação.
     * @return int Retorna o ID do usuário se o login for bem-sucedido.
     * @throws Exception Se o usuário ou senha forem inválidos.
     * @throws Exception Se ocorrer um erro ao tentar realizar o login no banco de dados.
     */
    public function login(string $usuario, string $senha): array //int
    {
        // Consulta SQL 
        $query = "SELECT id, usuario, senha from {$this->tabela} WHERE usuario = :usuario";

        try {
            // Prepara consulta, vincula parametros
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            // Resultado da consulta
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se existe resultado e senha é válida
            if ($resultado && password_verify($senha, $resultado['senha'])) {

                // Retorna info do usuário
                return (array)$resultado;     //return (int)$resultado['id'];

            } else {
                throw new Exception("Usuário ou senha inválidos.");
            }
        } catch (PDOException $e) {
            // Em caso de erro na consulta
            error_log("Erro no login: " . $e->getMessage());
            throw new Exception("Erro ao fazer login.");
        }
    }


    /** * ---------- METODO VERIFICA USUÁRIO ----------
     * Verifica se o usuário já existe no banco de dados.
     * 
     * @param string $usuario Nome de usuário a ser verificado.
     * @return bool Retorna true se o usuário já existir, caso contrário false.
     */
    private function usuarioExiste(string $usuario): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->tabela} WHERE usuario = :usuario";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        return (bool)$stmt->fetchColumn();
    }
}
