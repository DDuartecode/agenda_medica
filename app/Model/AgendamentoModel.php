<?php

namespace Model;

use DB\MySQL;
use InvalidArgumentException;
use PDOException;
use Util\GenericConsts;

class AgendamentoModel
{
    private object $MySQL;
    public const TABELA = 'agendamentos';

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }


    public function insertAgendamento($idmedico, $idpaciente, $consulta_ini, $tempo_consulta)
    {
        $consulta_fim = date('Y-m-d H:i', strtotime("{$consulta_ini} + {$tempo_consulta} minutes"));

        $consultaInsert = "INSERT INTO " . self::TABELA . " (idmedico, idpaciente, consulta_ini, consulta_fim, tempo_consulta) VALUES (:idmedico, :idpaciente, :consulta_ini, :consulta_fim, :tempo_consulta)";

        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindparam(':idmedico', $idmedico);
        $stmt->bindparam(':idpaciente', $idpaciente);
        $stmt->bindparam(':consulta_ini', $consulta_ini);
        $stmt->bindparam(':consulta_fim', $consulta_fim);
        $stmt->bindparam(':tempo_consulta', $tempo_consulta);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
        return $stmt->rowCount();
    }

    public function updateAgendamento($id, $dados)
    {
        $consulta_fim = date('Y-m-d H:i', strtotime("{$dados['consulta_ini']} + {$dados['tempo_consulta']} minutes"));

        $consultaUpdate = "UPDATE " . self::TABELA . " SET idmedico = :idmedico, idpaciente = :idpaciente, consulta_ini = :consulta_ini, consulta_fim = :consulta_fim, tempo_consulta = :tempo_consulta WHERE id = :id";

        $this->MySQL->getDb()->beginTransaction();

        $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
        $stmt->bindparam(':id', $id);
        $stmt->bindparam(':idmedico', $dados['idmedico']);
        $stmt->bindparam(':idpaciente', $dados['idpaciente']);
        $stmt->bindparam(':consulta_ini', $dados['consulta_ini']);
        $stmt->bindparam(':consulta_fim', $consulta_fim);
        $stmt->bindparam(':tempo_consulta', $dados['tempo_consulta']);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
        return $stmt->rowCount();
    }

    public function validateTime($idmedico, $consulta_ini)
    {
        $consultaValidate = "SELECT * FROM " . self::TABELA . " WHERE idmedico = :idmedico and :consulta_ini BETWEEN consulta_ini and consulta_fim";

        $stmt = $this->MySQL->getDb()->prepare($consultaValidate);
        $stmt->bindparam(':idmedico', $idmedico);
        $stmt->bindparam(':consulta_ini', $consulta_ini);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_HORARIO_AGENDADO);
        };
    }


    public function getMySQL()
    {
        return $this->MySQL;
    }
}
