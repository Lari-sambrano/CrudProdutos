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
        //VALIDA OS CAMPOS DE EMAIL E SENHA, SE ESTIVEREM VAZIOS, RETORNA 0 (FALHA)
        if (empty($email) || empty($senha))
            return 0;
        //Busca o usuário no banco pelo email.
        $usuario = $this->model->BuscarPorEmailMODEL($email);

        //VERIFICA SE O USUÁRIO EXISTE 
            return -1;

            //Verifica se a senha digitada confere com a senha salva.
        if (!password_verify($senha, $usuario['senha']))
            return -1;

        //Inicia a sessão para guardar dados do usuário logado
        session_start();
        //Salva informações do usuário na sessão:
        $_SESSION['id']   = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        return 1;
    }

    public function LogoutCTRL(): void
    {
        //Inicia (ou retoma) a sessão atual.
        //Mesmo no logout, isso é necessário porque o PHP precisa “acessar” a sessão existente para poder destruí-la.
        session_start();
        //Destrói a sessão completamente.
        //Isso remove todos os dados armazenados, como:
        session_destroy();
    }
}