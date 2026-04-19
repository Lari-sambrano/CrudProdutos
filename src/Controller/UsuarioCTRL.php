<?php

namespace Src\Controller;

use Src\Model\UsuarioMODEL;

class UsuarioCTRL
{
    private $model;

    public function __construct()
    {
        $this->model = new UsuarioMODEL();
    }

    public function LoginCTRL(string $email, string $senha): int
    {
        if (empty($email) || empty($senha))
            return 0;

        $usuario = $this->model->BuscarPorEmailMODEL($email);

        if (empty($usuario))
            return -1;

        if (!password_verify($senha, $usuario['senha']))
            return -1;

        session_start();
        $_SESSION['id']   = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        return 1;
    }

    public function LogoutCTRL(): void
    {
        session_start();
        session_destroy();
    }
}