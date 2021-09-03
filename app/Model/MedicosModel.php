<?php

namespace Model;

use DB\MySQL;


class MedicosModel
{
    private object $MySQL;
    public const TABELA = 'medicos';

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }


    public function insertMedico($nome, $especialidade, $crm)
    {
        $consultaInsert = "INSERT INTO " . self::TABELA . "(nome, especialidade, crm) VALUES (:nome, :especialidade, :crm)";
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindparam(':nome', $nome);
        $stmt->bindparam(':especialidade', $especialidade);
        $stmt->bindparam(':crm', $crm);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateMedico($id, $dados)
    {

        $consultaUpdate = "UPDATE " . self::TABELA . " SET nome = :nome, especialidade = :especialidade, crm = :crm WHERE id = :id";

        $this->MySQL->getDb()->beginTransaction();

        $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
        $stmt->bindparam(':id', $id);
        $stmt->bindparam(':nome', $dados['nome']);
        $stmt->bindparam(':especialidade', $dados['especialidade']);
        $stmt->bindparam(':crm', $dados['crm']);
        $stmt->execute();
        return $stmt->rowCount();
    }


    public function getMySQL()
    {
        return $this->MySQL;
    }
}
