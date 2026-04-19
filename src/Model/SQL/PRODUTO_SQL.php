<?php

namespace Src\Model\SQL;
//Armazenam apenas as strings SQL como métodos estáticos.
// Centraliza todas as queries em um único lugar, facilitando manutenção.
// Os ? são parâmetros que serão preenchidos com segurança pelo PDO (proteção contra SQL Injection).

class PRODUTO_SQL
{
     public static function CADASTRAR(): string
    {
        return 'INSERT INTO tb_produto (nome, descricao, preco, quantidade, status)
                VALUES (?, ?, ?, ?, ?)';
    }
     public static function ALTERAR(): string
    {
        return 'UPDATE tb_produto
                   SET nome = ?,
                       descricao = ?,
                       preco = ?,
                       quantidade = ?
                 WHERE id = ?';
    }
    public static function LISTAR(): string
    {
        return 'SELECT id, nome, descricao, preco, quantidade, status
                  FROM tb_produto
              ORDER BY nome';
    }   

    public static function BUSCAR_POR_ID(): string
    {
        return 'SELECT id, nome, descricao, preco, quantidade, status
                  FROM tb_produto
                 WHERE id = ?';
    }   

    public static function EXCLUIR(): string
    {
        return 'DELETE FROM tb_produto
                 WHERE id = ?';
    }
}