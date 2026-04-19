<?php
include_once dirname(__DIR__, 3) . '/vendor/autoload.php';

session_start();
if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => -99, 'msg' => 'Sessão expirada']);
    exit;
}

use Src\Controller\ProdutoCTRL;
use Src\VO\ProdutoVO;

$ctrl = new ProdutoCTRL();
header('Content-Type: application/json');

// LISTAR
if (isset($_POST['listar'])) {
    $dados = $ctrl->ListarCTRL();
    echo json_encode($dados);
}

// CADASTRAR
else if (isset($_POST['btn_cadastrar'])) {
    $vo = new ProdutoVO();
    $vo->setNome($_POST['nome']);
    $vo->setDescricao($_POST['descricao'] ?? '');
    $vo->setPreco((float) str_replace(',', '.', $_POST['preco']));
    $vo->setQuantidade((int) $_POST['quantidade']);
    echo json_encode(['status' => $ctrl->CadastrarCTRL($vo)]);
}

// BUSCAR POR ID (para editar)
else if (isset($_POST['buscar_id'])) {
    $dados = $ctrl->BuscarPorIdCTRL((int) $_POST['id']);
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
    echo json_encode(['status' => $ctrl->AlterarCTRL($vo)]);
}

// EXCLUIR
else if (isset($_POST['excluir'])) {
    echo json_encode(['status' => $ctrl->ExcluirCTRL((int) $_POST['id'])]);
}