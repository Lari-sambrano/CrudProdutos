<?php

namespace Src\VO;

class ProdutoVO
{
    private $id;
    private $nome;
    private $descricao;
    private $preco;
    private $quantidade;
    private $status;

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNome(): string { return $this->nome; }
    //O htmlspecialchars converte caracteres especiais como <, >, " em entidades HTML, protegendo contra ataques XSS.
    public function setNome(string $nome): void { $this->nome = htmlspecialchars($nome); }

    public function getDescricao(): string { return $this->descricao; }
    //O htmlspecialchars converte caracteres especiais como <, >, " em entidades HTML, protegendo contra ataques XSS.
    public function setDescricao(string $descricao): void { $this->descricao = htmlspecialchars($descricao); }

    public function getPreco(): float { return $this->preco; }
    public function setPreco(float $preco): void { $this->preco = $preco; }

    public function getQuantidade(): int { return $this->quantidade; }
    public function setQuantidade(int $quantidade): void { $this->quantidade = $quantidade; }

    public function getStatus(): int { return $this->status; }
    public function setStatus(int $status): void { $this->status = $status; }
}