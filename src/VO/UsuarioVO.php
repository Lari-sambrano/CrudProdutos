<?php

namespace Src\VO;

class UsuarioVO
{
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $status;

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = htmlspecialchars($nome); }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = htmlspecialchars($email); }

    public function getSenha(): string { return $this->senha; }
    public function setSenha(string $senha): void { $this->senha = $senha; }

    public function getStatus(): int { return $this->status; }
    public function setStatus(int $status): void { $this->status = $status; }
}