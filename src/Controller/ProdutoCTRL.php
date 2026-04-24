<?php

namespace Src\Controller;

use Src\Model\ProdutoMODEL;
use Src\VO\ProdutoVO;

class ProdutoCTRL
{
    private $model;

    public function __construct()
    {
        $this->model = new ProdutoMODEL();
    }

    public function ListarCTRL(): array
    {
        return $this->model->ListarMODEL();
    }

    public function CadastrarCTRL(ProdutoVO $vo): int
    {
        if (empty($vo->getNome()) || empty($vo->getPreco()) || empty($vo->getQuantidade()))
            return 0;
        //Preço não pode ser menor ou igual a zero, e quantidade não pode ser negativa
        if ($vo->getPreco() <= 0 || $vo->getQuantidade() < 0)
            return 0;
        //defne o status do produto como 1 (ativo) antes de chamar o método de cadastro no modelo
        $vo->setStatus(1);
        //Chama o método CadastrarMODEL da camada Model
        //Passa o objeto $vo já validado
        return $this->model->CadastrarMODEL($vo);
    }

    public function BuscarPorIdCTRL(int $id): array|bool
    {
        if ($id <= 0) return false;
        return $this->model->BuscarPorIdMODEL($id);
    }

    public function AlterarCTRL(ProdutoVO $vo): int
    {
        if (empty($vo->getId()) || empty($vo->getNome()) || empty($vo->getPreco()) || empty($vo->getQuantidade()))
            return 0;

        if ($vo->getPreco() <= 0 || $vo->getQuantidade() < 0)
            return 0;

        return $this->model->AlterarMODEL($vo);
    }

    public function ExcluirCTRL(int $id): int
    {
        if ($id <= 0) return 0;
        return $this->model->ExcluirMODEL($id);
    }
}