<?php

namespace Src\Model;

use Src\Config\Conexao;
use Src\Model\SQL\USUARIO_SQL;

class UsuarioMODEL
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = Conexao::retornarConexao();
    }

    public function BuscarPorEmailMODEL(string $email): array|bool
    {
        $sql = $this->conexao->prepare(USUARIO_SQL::BUSCAR_POR_EMAIL());
        $sql->bindValue(1, $email);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }
}