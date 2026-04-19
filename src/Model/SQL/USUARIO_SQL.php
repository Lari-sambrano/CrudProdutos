<?php

namespace Src\Model\SQL;

class USUARIO_SQL
{
    public static function BUSCAR_POR_EMAIL(): string
    {
        return 'SELECT id, nome, email, senha, status
                  FROM tb_usuario
                 WHERE email = ?
                   AND status = 1';
    }
}