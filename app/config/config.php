<?php 
declare(strict_types=1);

/* // Config ambiente
    define('AMBIENTE', 'desenvolvimento'); */

    
// Definindo constantes banco de dados
define('BD_HOST', 'localhost');
define('BD_NOME', 'despesas_gerenciador');
define('BD_USERNAME', 'root'); 
define('BD_SENHA', '');    /* CRIAR USER E SENHA NO 'GESTOR DE UTILIZADOR' HEIDISQL '*/


// Config globais
define('CAMINHO_BASE', __DIR__ . '/../../');


// Composer
require_once CAMINHO_BASE . 'vendor/autoload.php';

