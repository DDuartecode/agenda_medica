<?php

namespace Model;

use DB\MySQL;


class UsuariosModel
{
    private object $MySQL;
    public const TABELA = 'usuarios';

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    /**
     * Undocumented function
     *
     * @param [string] $login
     * @param [string] $senha
     * @param [string] $nome
     * @return int
     */
    public function insertUser($login, $senha, $nome)
    {
        $consultaInsert = "INSERT INTO " . self::TABELA . "(login, senha, nome) VALUES (:login, :senha, :nome)";
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindparam(':login', $login);
        $stmt->bindparam(':senha', $senha);
        $stmt->bindparam(':nome', $nome);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateUser($id, $dados)
    {

        $consultaUpdate = "UPDATE " . self::TABELA . " SET login = :login, senha = :senha, nome = :nome WHERE id = :id";

        $this->MySQL->getDb()->beginTransaction();

        $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
        $stmt->bindparam(':id', $id);
        $stmt->bindparam(':login', $dados['login']);
        $stmt->bindparam(':senha', $dados['senha']);
        $stmt->bindparam(':nome', $dados['nome']);
        $stmt->execute();
        return $stmt->rowCount();
    }


    public function getMySQL()
    {
        return $this->MySQL;
    }
}
