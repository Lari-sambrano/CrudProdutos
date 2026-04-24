
<?php
//Antes de qualquer coisa, carrega o mapa do Composer que sabe onde cada classe está
include_once dirname(__DIR__, 3) . '/vendor/autoload.php';

session_start();
//SE O USUÁRIO NÃO ESTIVER LOGADO, RETORNA UM JSON COM STATUS -99 E MENSAGEM DE SESSÃO EXPIRADA, E PARA A EXECUÇÃO DO SCRIPT
if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => -99, 'msg' => 'Sessão expirada']);
    exit;
}

use Src\Controller\ProdutoCTRL;
use Src\VO\ProdutoVO;

//A primeira linha instancia o Controller para ter acesso aos métodos do sistema
$ctrl = new ProdutoCTRL();
// define o cabeçalho HTTP informando que toda resposta desse arquivo será em formato JSON, 
//garantindo que o Ajax no front-end interprete corretamente os dados recebidos."
header('Content-Type: application/json');

// LISTAR
if (isset($_POST['listar'])) {
    $dados = $ctrl->ListarCTRL();
    // enviar a resposta de volta para o JavaScript, convertendo o array de dados em formato JSON usando json_encode.
    echo json_encode($dados);
}

// CADASTRAR
else if (isset($_POST['btn_cadastrar'])) {
    $vo = new ProdutoVO();
    $vo->setNome($_POST['nome']);
    $vo->setDescricao($_POST['descricao'] ?? '');
    $vo->setPreco((float) str_replace(',', '.', $_POST['preco']));
    $vo->setQuantidade((int) $_POST['quantidade']);
    // enviar a resposta de volta para o JavaScript
    echo json_encode(['status' => $ctrl->CadastrarCTRL($vo)]);
}

// BUSCAR POR ID (para editar)
else if (isset($_POST['buscar_id'])) {
    $dados = $ctrl->BuscarPorIdCTRL((int) $_POST['id']);
    // enviar a resposta de volta para o JavaScript
    echo json_encode($dados);
}

// ALTERAR
else if (isset($_POST['btn_alterar'])) {
    $vo = new ProdutoVO();
    $vo->setId((int) $_POST['id']);
    $vo->setNome($_POST['nome']);
    $vo->setDescricao($_POST['descricao'] ?? '');
    $vo->setPreco((float) str_replace(',', '.', $_POST['preco']));
    $vo->setQuantidade((int) $_POST['quantidade']);
    // enviar a resposta de volta para o JavaScript
    echo json_encode(['status' => $ctrl->AlterarCTRL($vo)]);
}

// EXCLUIR
else if (isset($_POST['excluir'])) {
    // enviar a resposta de volta para o JavaScript
    echo json_encode(['status' => $ctrl->ExcluirCTRL((int) $_POST['id'])]);

    //"O json_encode converte o resultado PHP em texto JSON 
    //e o echo envia esse texto como resposta para o Ajax no JavaScript.
    // É a forma que o PHP tem de 'falar' com o front-end."
}