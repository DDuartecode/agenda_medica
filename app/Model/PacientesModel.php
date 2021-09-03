<?php

namespace Model;

use DB\MySQL;


class PacientesModel
{
    private object $MySQL;
    public const TABELA = 'pacientes';

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }


    public function insertPaciente($nome, $data_nasc, $telefone)
    {
        $consultaInsert = "INSERT INTO " . self::TABELA . "(nome, data_nasc, telefone) VALUES (:nome, :data_nasc, :telefone)";
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindparam(':nome', $nome);
        $stmt->bindparam(':data_nasc', $data_nasc);
        $stmt->bindparam(':telefone', $telefone);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updatePaciente($id, $dados)
    {

        $consultaUpdate = "UPDATE " . self::TABELA . " SET nome = :nome, data_nasc = :data_nasc, telefone = :telefone WHERE id = :id";

        $this->MySQL->getDb()->beginTransaction();

        $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
        $stmt->bindparam(':id', $id);
        $stmt->bindparam(':nome', $dados['nome']);
        $stmt->bindparam(':data_nasc', $dados['data_nasc']);
        $stmt->bindparam(':telefone', $dados['telefone']);
        $stmt->execute();
        return $stmt->rowCount();
    }


    public function getMySQL()
    {
        return $this->MySQL;
    }
}
