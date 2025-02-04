<?php
/* PENDENTE 
    - Implementar mais segurança com Filter_Input nos dados vindo de POST 
*/

// Dependencias
declare(strict_types=1);
require_once '../app/models/despesas.php';
require_once 'authController.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class DespesasController
{
    private $despesaModel;
    private $authController;

    public function __construct($bd)
    {
        $this->despesaModel = new Despesa($bd);  // models/despesas.php
        $this->authController = new AuthController($bd);
    }

    // Método pegar todas as depesas (models)
    public function getAllDespesas(int $usuarioId): array
    {
       return $this->despesaModel->getAllDespesas($usuarioId);
    }

    // Método adicionar despesas (models)
    public function addDespesa(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            // dados do form add_despesa.php 
            $usuarioId = $_SESSION['usuario_dados']['id']; /* ajustar */
            $quant = $_POST['quantidade'];
            $valor = $_POST['valor'];
            $tipo = $_POST['tipo'];
            $data = $_POST['data'];

            /* error log para verificar como está sendo enviado */
            error_log("valor recebido: " . $valor); 

            try {
                $this->despesaModel->addDespesa((int)$usuarioId, (int)$quant, (float)$valor, (string)$tipo, (string)$data);
                $this->authController->redirecionar('dashboard');
                //header("Location: index.php?acao=dashboard");
                //exit();
            } catch(Exception $e) {
                echo "Erro ao adicionar despesa: " . $e->getMessage();
            }

        } else {
            // form n submetido
            include '../app/views/add_despesas.php';
        }
    }


    // Método editar despesa (models)
    public function editarDespesa(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            // dados form edição
            $despesaId =  $_POST['despesa_id']; /* PROVAVELMENTE MUDAR ESSA LÓGICA, ID N DEVE VIR DO FORM */

            $quant = $_POST['quantidade'];
            $valor = $_POST['valor'];
            $tipo = $_POST['tipo'];
            $data = $_POST['data'];
            
            // Por algum motivo, é o unico jeito de remover a formatação da mascara JS
            $valor = str_replace(['R$', '', ','], ['', '', '.'], $valor);

            try {

                $this->despesaModel->editarDespesa((int)$despesaId, (float)$valor, (int)$quant, (string)$tipo, (string)$data);
                $this->authController->redirecionar('dashboard');

            } catch(Exception $e){
                echo "Erro ao editar despesa: " . $e->getMessage();
            }

        } else { // tentar limpar isto
            // reexibe pagina de edição
            $despesaId = $_GET['id'] ?? null;
            if($despesaId){
                // dados da despesa p/ edição
                $despesa = $this->despesaModel->getDespesaId((int)$despesaId);
                include '../app/views/editar_despesa.php';
            } else {
                echo "ID da despesa não fornecido.";
            }
        }
    }

    // Método excluir despesas
    public function excluirDespesas(): void
    {
        if(!isset($_GET['id'])){
            $_SESSION['erro'] = "ID da despesa não fornecido.";
            $this->authController->redirecionar('dashboard');
            return;
        }

        try {
            $despesaId = (int)$_GET['id'];
            $this->despesaModel->deletarDespesa($despesaId);
            $_SESSION['sucesso'] = "Despesa excluída com sucesso.";
        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['erro'] = "Erro ao excluir despesa.";
        }

        $this->authController->redirecionar('dashboard');

    }


    // Metodo p/ upload
    public function upload(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']))
        {
            $arquivo = $_FILES['file'];

            // Verifica se upload foi bem sucedido
            if($arquivo['error'] !== UPLOAD_ERR_OK){
                throw new RuntimeException("Falha de upload, código de erro: " . $arquivo['error']);
            }

            // Valida tipo de arquivo (excel)
            $tipoArquivo = mime_content_type($arquivo['tmp_name']);

            if($tipoArquivo !== 'application/vnd.openxmlformats-officedocument.spreadsheet.sheet' && $tipoArquivo !== 'application/vnd.ms-excel'){
                throw new RuntimeException("Formato inválido. Por favor, faça upload de um arquivo Excel.");
            }

            // Carrega o arquivo excel
            $planilha = IOFactory::load($arquivo['tmp_name']);
            $dadosCedula = $planilha->getActiveSheet()->toArray();

            // Para cada linha do arquivo, insere as despesas
            foreach($dadosCedula as $cedula){
                $usuarioId = (int)$cedula[0];
                $quantidade = (int)$cedula[1];
                $valor = (float)$cedula[2];
                $tipo = (string)$cedula[3];
                $data = (string)$cedula[4];

                // Add despesa no banco de dados
                $this->despesaModel->addDespesa($usuarioId, $quantidade, $valor, $tipo, $data);
            }

            // Redireciona após upload
            header("Location: /public/index.php?action=despesas");
            exit();


            // se for GET exibe página de upload
        } else {
            include 'app/views/upload.php';
        }

    }


    // Metodo p/ download
    public function download(): void
    {
        $usuarioId = $_SESSION['usuario_id'];
        // busca as despesas do usuario
        $despesas = $this->despesaModel->getAllDespesas($usuarioId);

        $planilha = new Spreadsheet();
        $cedula = $planilha->getActiveSheet();

        // Cabeçalho da planilha
        $cedula->setCellValue('A1', 'ID Usuario');
        $cedula->setCellValue('B1', 'Quantidade');
        $cedula->setCellValue('C1', 'Valor');
        $cedula->setCellValue('D1', 'Tipo');
        $cedula->setCellValue('E1', 'Data');


        // Linhas
        $linha = 2;
        foreach($despesas as $despesa){
            $cedula->setCellValue('A' . $linha, (int)$despesa['usuario_id']);
            $cedula->setCellValue('B' . $linha, (int)$despesa['quant']);
            $cedula->setCellValue('C' . $linha, (string)$despesa['tipo']);
            $cedula->setCellValue('D' . $linha, (string)$despesa['data']);
            $cedula->setCellValue('E' . $linha, (float)$despesa['valor']);
            $linha++;
        }


        // Cabeçalho para forçar donwlaod
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="expenses.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($planilha, 'xlsx');
        $writer->save('php://output');
        exit();
    }


}

?>