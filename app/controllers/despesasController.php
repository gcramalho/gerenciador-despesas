<?php
/* PENDENTE 
    - Implementar mais segurança (Filter_Input nos dados POST)
    - Consertar gambiarra do metodo upload
*/

// Dependencias
declare(strict_types=1);
require_once '../app/models/despesas.php';
require_once 'authController.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

    // Método Pegar Todas As Depesas (models)
    public function getAllDespesas(int $usuarioId): array
    {
        // Número da página da URL
        $paginaAtual = $_GET['pagina'] ?? 1;
        $itensPorPagina = 10; 

        return $this->despesaModel->getAllDespesas($usuarioId, $itensPorPagina, (int)$paginaAtual);
    }

    // Método Adicionar Despesas (models)
    public function addDespesa(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Dados do form add_despesa.php 
            $usuarioId = $_SESSION['usuario_dados']['id']; /* ajustar */
            $quant = $_POST['quantidade'];
            $valor = $_POST['valor'];
            $tipo = $_POST['tipo'];
            $data = $_POST['data'];

            /* error log para verificar como está sendo enviado 
            error_log("valor recebido: " . $valor); */

            try {
                $this->despesaModel->addDespesa((int)$usuarioId, (int)$quant, (float)$valor, (string)$tipo, (string)$data);
                $this->authController->redirecionar('dashboard');
                //header("Location: index.php?acao=dashboard");
                //exit();
            } catch (Exception $e) {
                // error_log("Erro ao adicionar despesa: " . $e->getMessage());
                $_SESSION['erro'] = "Erro ao adicionar despesa: " . $e->getMessage();
                $this->authController->redirecionar('add_despesa');
            }
        } else {
            include '../app/views/add_despesas.php';
        }
    }

    // Método Editar Despesa (models)
    public function editarDespesa(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Dados form edição
            $despesaId =  $_POST['despesa_id']; /* input hidden */

            $quant = $_POST['quantidade'];
            $valor = $_POST['valor'];
            $tipo = $_POST['tipo'];
            $data = $_POST['data'];

            // Por algum motivo, é o único jeito de remover a formatação da máscara JS
            $valor = str_replace(['R$', '', ','], ['', '', '.'], $valor);

            try {
                $this->despesaModel->editarDespesa((int)$despesaId, (float)$valor, (int)$quant, (string)$tipo, (string)$data);
                $this->authController->redirecionar('dashboard');
            } catch (Exception $e) {
                // Erro de entrada de dados
                $_SESSION['erro'] = "Erro ao editar despesa: " . $e->getMessage();
                $this->authController->redirecionar('editar_despesa&id=' . $despesaId);
            }
        } else { // Reexibe página de edição
            $despesaId = $_GET['id'] ?? null;

            if ($despesaId) {
                // Dados que serão exibidos p/ edição
                $despesa = $this->despesaModel->getDespesaId((int)$despesaId);

                // Verifica se o usuário logado tem permissão para editar despesa
                if ($despesa['usuario_id'] !== $_SESSION['usuario_dados']['id']) {
                    $_SESSION['erro'] = "Você não tem permissão para editar esta despesa.";
                    $this->authController->redirecionar('dashboard');
                    return;
                }
                include '../app/views/editar_despesa.php';
            } else {
                echo "ID da despesa não fornecido.";
            }
        }
    }

    // Método Excluir Despesas
    public function excluirDespesas(): void
    {
        if (!isset($_GET['id'])) {
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


    // Método p/ Upload
    public function upload(): void
    {
        // Verifica se o usuário está logado
        if (!$this->authController->verificarLogin()) {
            $this->authController->redirecionar('login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $arquivo = $_FILES['file'];

            // Verifica se upload foi bem sucedido
            if ($arquivo['error'] !== UPLOAD_ERR_OK) {
                throw new RuntimeException("Falha de upload, código de erro: " . $arquivo['error']);
            }

            // Valida tipo de arquivo (Excel)
            $tiposPermitidos = [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel',
                'application/octet-stream'
            ];
            $tipoArquivo = mime_content_type($arquivo['tmp_name']);

            if (!in_array($tipoArquivo, $tiposPermitidos)) {
                throw new RuntimeException("Formato inválido. Por favor, faça upload de um arquivo Excel (.xlsx ou .xls).");
            }

            try {
                // Carrega o arquivo excel
                $planilha = IOFactory::load($arquivo['tmp_name']);
                $dadosCedula = $planilha->getActiveSheet();

                $usuarioId = $_SESSION['usuario_dados']['id'];

                // Itera por cada linha do arquivo
                foreach ($dadosCedula->getRowIterator() as $linha) {

                    $indiceLinha = $linha->getRowIndex();
                    
                    // Ignora primeira linha (cabeçalho)
                    if ($indiceLinha ===  1) {
                        continue;
                    }

                    $valoresLinha = [];

                    // Itera por cada célula da linha
                    foreach($linha->getCellIterator() as $cedula){
                        $valorCelula = $cedula->getValue();
                        
                        // Ignora células vazias
                        if($valorCelula === null || trim((string)$valorCelula) === '') {
                            continue;
                        }
                        
                        $valoresLinha[] = $valorCelula;
                    }

                    // VER LINHAS DO QUE FOI UPADO
                    //error_log("Processando linha " . $indiceLinha . ": " . print_r($valoresLinha, true)); 

                    if(empty($valoresLinha)){
                        continue;
                    }

                    // Verifica se linha tem 4 colunas 
                    if (count($valoresLinha) !== 4) {
                        throw new RuntimeException("Formato inválido na linha " . $indiceLinha . ". O arquivo deve conter exatamente 4 colunas.");
                    }

                    // Valida dados
                    $quantidade = filter_var($valoresLinha[0], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
                    $valor = filter_var(str_replace(',', '.', (string)$valoresLinha[1]), FILTER_VALIDATE_FLOAT); // formatar separador decimal
                    $tipo = htmlspecialchars($valoresLinha[2], ENT_QUOTES, 'UTF-8');
                    $data = DateTime::createFromFormat('Y-m-d', $valoresLinha[3])->format('Y-m-d');

                    // Verifica data
                    if ($data === false) {
                        throw new RuntimeException("Data inválida na linha " . $indiceLinha);
                    }

                    // Verifica se todas as celulas estão preenchidas
                    if ($quantidade === false || $valor === false || empty($tipo) || empty($data) ) {
                        throw new RuntimeException("Dados inválidos ou ausentes na linha " . $indiceLinha);
                    }

                    // Insere despesa no banco de dados
                    $this->despesaModel->addDespesa($usuarioId, $quantidade, $valor, $tipo, $data);
                }

                // Redireciona após upload
                $_SESSION['mensagem'] = "Despesas importadas com sucesso.";
                $this->authController->redirecionar('dashboard');
            } catch (RuntimeException $e) {
                error_log("Erro ao fazer upload: " . $e->getMessage()); // verificar log do erro
                $_SESSION['erro'] = "Erro ao processar arquivo. Por favor, tente novamente.";
                $this->authController->redirecionar('upload');
            }
        } else {
            include '../app/views/upload.php';
        }
    }


    // Método p/ Download
    public function download(): void
    {
        // Verifica se o usuário está logado
        if (!$this->authController->verificarLogin()) {
            $this->authController->redirecionar('login');
            return;
        }

        $usuarioId = $_SESSION['usuario_dados']['id'];

        try {
            // Busca as despesas do usuario
            $despesas = $this->despesaModel->getAllDespesas($usuarioId);

            if (empty($despesas)) {
                throw new RuntimeException("Nenhuma despesa encontrada.");
            }

            // Cria planilha
            $planilha = new Spreadsheet();
            $cedula = $planilha->getActiveSheet();

            // Define cabeçalho da planilha
            $cedula->setCellValue('A1', 'Quantidade');
            $cedula->setCellValue('B1', 'Valor');
            $cedula->setCellValue('C1', 'Tipo');
            $cedula->setCellValue('D1', 'Data');

            // Preenche linhas com os dados
            $linha = 2;
            foreach ($despesas as $despesa) {
                $cedula->setCellValue('A' . $linha, (int)$despesa['quant']);
                $cedula->setCellValue('B' . $linha, (float)$despesa['valor']);
                $cedula->setCellValue('C' . $linha, (string)$despesa['tipo']);
                $cedula->setCellValue('D' . $linha, (string)$despesa['data']);
                $linha++;
            }

            // Configura cabeçalho para forçar downlaod
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="despesas.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($planilha);
            $writer->save('php://output');
            exit();
        } catch (RuntimeException $e) {
            error_log("Erro ao fazer download: " . $e->getMessage()); // verificar log do erro

            $_SESSION['erro'] = "Erro ao gerar arquivo de download. Por favor, tente novamente.";
            $this->authController->redirecionar('despesas');
        };
    }
}
