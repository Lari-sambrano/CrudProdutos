<?php

namespace Src\Config;

//armazenar a conexão com o banco (um objeto PDO)
class Conexao
{
    //Declara uma propriedade estática chamada $conexao
    //private: só pode ser acessada dentro da própria classe.
    //static: pertence à classe, não a uma instância (objeto).
    private static $conexao;

    //Pode ser chamado sem criar objeto
    public static function retornarConexao()
    {
        //Verifica se a conexão ainda não foi criada.
        //self::$conexao acessa a variável estática da classe
        if (!self::$conexao) {
            //Cria uma nova conexão com o banco usando a classe PDO
            self::$conexao = new \PDO(
                'mysql:host=localhost;dbname=db_produtos;charset=utf8',
                'root',
                '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        }
        //Retorna a conexão (seja a recém-criada ou a já existente).
        return self::$conexao;
    }
}