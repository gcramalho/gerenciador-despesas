<?php
// Dependencias
declare(strict_types=1);

class Despesa
{
    private PDO $conn;
    private $tabela_nome = "despesas";

    public function __construct(PDO $bd)
    {
        $this->conn = $bd;
    }



    /** * --------- MÉTODO ADICIONAR DESPESAS ----------- * 
     * * @param int $usuarioId O ID do usuário ao qual a despesa pertence. 
     * * @param int $quant A quantidade de itens da despesa. 
     * * @param float $valor O valor da despesa. 
     * * @param string $tipo O tipo de despesa. 
     * * @param string $data A data da despesa no formato 'Y-m-d'. 
     * * @return bool Retorna true se a despesa for adicionada com sucesso, ou false caso contrário. 
     * * @throws InvalidArgumentException Se a data fornecida for inválida. 
     * * @throws Exception Se ocorrer um erro ao adicionar a despesa. 
     */
    public function addDespesa(int $usuarioId, int $quant, float $valor, string $tipo, string $data): bool
    {
        $query = "INSERT INTO " . $this->tabela_nome . "(usuario_id, quant, tipo, data, valor)
        VALUES (:usuarioId, :quant, :tipo, :data, :valor)
        ";
        
        // Validação data
        if (!strtotime($data)) {
            throw new InvalidArgumentException('Data inválida.');
        }
            
        try {
            // Começa transação
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':quant', $quant, PDO::PARAM_INT);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $stmt->bindParam(':data', $data, PDO::PARAM_STR);
            $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);

            // Executa 
            $resultado = $stmt->execute();
            $this->conn->commit();
            return $resultado;

        } catch (PDOException $e) { // Ou, se houver erros
            $this->conn->rollBack();
            error_log("Erro ao adicionar despesa: " . $e->getMessage());
            throw new Exception("Falha ao adicionar despesa");
        }
    }

    
    

    /** * -------- MÉTODO OBTER DESPESAS ----------- *
     * @param int $usuarioId O ID do usuário cujas despesas devem ser recuperadas. 
     * @return array Lista de despesas. 
     * @throws Exception Se ocorrer um erro ao buscar as despesas. 
     */
    public function getAllDespesas(int $usuarioId): array
    {
        // Seleciona todas as despesas do usuario especifico 
        $query = "SELECT * FROM " . $this->tabela_nome . " WHERE usuario_id = :usuario_id";

        
        try {
            // Prepara, vincula parametros
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuarioId);

            // Executa consulta
            $stmt->execute();

            // Retorna as despesas
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar despesas: " . $e->getMessage());
            throw new Exception("Falha ao buscar despesas");
        }
    }




    /** * -------- MÉTODO EDITAR DESPESA -------- * 
     * @param int $expenseId O ID da despesa a ser editada.  
     * @param float $amount O valor da despesa. 
     * @param string $description A descrição da despesa. 
     * @param string $date A data da despesa no formato 'Y-m-d'. 
     * @return bool Retorna true se a despesa for editada com sucesso, ou false caso contrário. 
     * @throws InvalidArgumentException Se a data fornecida for inválida. 
     * @throws Exception Se ocorrer um erro ao editar a despesa. 
    */
    public function editarDespesa(int $despesaId, float $valor, int $quant, string $tipo, string $data): bool
    {
        // Consulta atualizar
        $query = "UPDATE " . $this->tabela_nome . "
        SET quant = :quant,
        valor = :valor,
        tipo = :tipo,
        data = :data
        WHERE id = :despesaId
        ";

        
        // Valida data
        if(!strtotime($data)){
            throw new InvalidArgumentException('Formato de data inválido.');
        }
        
        
        try{
            // Prepara
            $stmt = $this->conn->prepare($query);
            // Vincula parametros
            $stmt->bindParam(':quant', $quant);
            $stmt->bindParam(':valor', $valor);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':data', $data);
            $stmt->bindParam(':despesaId', $despesaId);
            // Executa atualização
            return $stmt->execute();
        } catch(PDOException $e) {
            // Em caso de erro
            error_log("Erro ao editar despesas: " . $e->getMessage());
            throw new Exception("Falha ao editar despesas.");
        }
    }



    /** * -------- MÉTODO DELETAR DESPESA -------- * 
     * @param int $despesaId O ID da despesa a ser excluída. 
     * @return bool Retorna true se a despesa for excluída com sucesso, ou false caso contrário. 
     * @throws Exception Se ocorrer um erro ao excluir a despesa. 
    */
    public function deletarDespesa(int $despesaId): bool
    {
        $query = "DELETE FROM {$this->tabela_nome} WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $despesaId);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erro ao excluir despesa: " . $e->getMessage());
            throw new Exception("Falha ao excluir despesa.");
        }
    }

    

    /** --------- MÉTODO OBTER DESPESA PELO ID ------------
     * @param int $despesaId O ID da despesa a ser recuperada. 
     * @return array Dados da despesa. 
     * @throws Exception Se ocorrer um erro ao buscar a despesa. 
    */
    public function getDespesaId(int $despesaId): array
    {
        $query = "SELECT * FROM " . $this->tabela_nome . " WHERE id = :despesaId";

        
        try {
            // Prepara, vincula parametros
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':despesaId', $despesaId);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erro ao buscar despesa: " . $e->getMessage());
            throw new Exception("Falha ao buscar despesa.");
        }

    }

}
