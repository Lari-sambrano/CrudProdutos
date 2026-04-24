<?php
//a camada que se comunica diretamente com o banco de dados. Cada método:
//Pega a SQL da classe _SQL
//Prepara o statement com prepare()
//Vincula os valores com bindValue() (evita SQL Injection)
//cliquei e nExecuta e retorna o resultado

namespace Src\Model;

use Src\Config\Conexao;
use Src\Model\SQL\PRODUTO_SQL;
use Src\VO\ProdutoVO;

class ProdutoMODEL
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = Conexao::retornarConexao();
        // $this->conexao → é o PDO
        // ->prepare() → PDO prepara o SQL
        // ->bindValue() → PDO substitui os ? pelos valores reais
        // ->execute() → PDO executa no banco
        // ->fetch() / ->fetchAll() → PDO retorna os dados
    }

    public function ListarMODEL(): array
    {
        $sql = $this->conexao->prepare(PRODUTO_SQL::LISTAR());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function CadastrarMODEL(ProdutoVO $vo): int
    {
        try {
            $sql = $this->conexao->prepare(PRODUTO_SQL::CADASTRAR());
            $i = 1;
            $sql->bindValue($i++, $vo->getNome());
            $sql->bindValue($i++, $vo->getDescricao());
            $sql->bindValue($i++, $vo->getPreco());
            $sql->bindValue($i++, $vo->getQuantidade());
            $sql->bindValue($i++, $vo->getStatus());
            $sql->execute();
            return 1;
        } catch (\Exception $e) {
            return -1;
        }
    }

    public function BuscarPorIdMODEL(int $id): array|bool
    {
        $sql = $this->conexao->prepare(PRODUTO_SQL::BUSCAR_POR_ID());
        $sql->bindValue(1, $id);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    public function AlterarMODEL(ProdutoVO $vo): int
    {
        try {
            $sql = $this->conexao->prepare(PRODUTO_SQL::ALTERAR());
            $i = 1;
            $sql->bindValue($i++, $vo->getNome());
            $sql->bindValue($i++, $vo->getDescricao());
            $sql->bindValue($i++, $vo->getPreco());
            $sql->bindValue($i++, $vo->getQuantidade());
            $sql->bindValue($i++, $vo->getId());
            $sql->execute();
            return 1;
        } catch (\Exception $e) {
            return -1;
        }
    }

    public function ExcluirMODEL(int $id): int
    {
        try {
            $sql = $this->conexao->prepare(PRODUTO_SQL::EXCLUIR());
            $sql->bindValue(1, $id);
            $sql->execute();
            return 1;
        } catch (\Exception $e) {
            return -1;
        }
    }
}