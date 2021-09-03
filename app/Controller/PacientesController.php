<?php

namespace Controller;

use InvalidArgumentException;
use Model\PacientesModel;
use Util\GenericConsts;

class PacientesController
{
    public const TABELA = 'pacientes';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    private array $dados;

    private array $dadosCorpoRequest;

    private object $PacientesModel;

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->PacientesModel = new PacientesModel;
    }

    public function validarGet()
    {
        $return = null;
        $resource = $this->dados['resource'];

        if (in_array($resource, self::RECURSOS_GET, true)) {
            $return = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$resource();
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($return);

        return $return;
    }

    public function validarDelete()
    {
        $return = null;
        $resource = $this->dados['resource'];

        if (in_array($resource, self::RECURSOS_DELETE, true)) {
            if ($this->dados['id'] > 0) {
                $return = $this->$resource(); //chama o método de acordo com o dado contido na variável
            } else {
                throw new InvalidArgumentException(GenericConsts::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($return);

        return $return;
    }

    public function validarPost()
    {
        $return = null;
        $resource = $this->dados['resource'];

        if (in_array($resource, self::RECURSOS_POST, true)) {
            $return = $this->$resource();
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($return);

        return $return;
    }

    public function validarPut()
    {
        $return = null;
        $resource = $this->dados['resource'];

        if (in_array($resource, self::RECURSOS_PUT, true)) {
            if ($this->dados['id'] > 0) {
                $return = $this->$resource(); //chama o método de acordo com o dado contido na variável
            } else {
                throw new InvalidArgumentException(GenericConsts::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($return);

        return $return;
    }

    public function setDadosCorpoRequest($dadosRequest)
    {
        $this->dadosCorpoRequest = $dadosRequest;
    }

    private function getOneByKey()
    {
        return $this->PacientesModel->getMySQL()->getOneByKey(self::TABELA, $this->dados['id']);
    }

    private function listar()
    {
        return $this->PacientesModel->getMySQL()->getAll(self::TABELA);
    }

    private function deletar()
    {
        return $this->PacientesModel->getMySQL()->delete(self::TABELA, $this->dados['id']);
    }

    private function cadastrar()
    {
        [$nome, $data_nasc, $telefone] = [$this->dadosCorpoRequest['nome'], $this->dadosCorpoRequest['data_nasc'], $this->dadosCorpoRequest['telefone']];

        if ($nome && $data_nasc && $telefone) {
            if ($this->PacientesModel->insertPaciente($nome, $data_nasc, $telefone) > 0) {
                $idInserido = $this->PacientesModel->getMySQL()->getDb()->lastInsertId();
                $this->PacientesModel->getMySQL()->getDb()->commit();
                return ['id_inserido' => $idInserido];
            } else {
                throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
            }
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_TODOS_CAMPOS_OBRIGATORIOS);
        }
    }

    private function atualizar()
    {

        if ($this->PacientesModel->updatePaciente($this->dados['id'], $this->dadosCorpoRequest) > 0) {
            $this->PacientesModel->getMySQL()->getDb()->commit();
            return GenericConsts::MSG_ATUALIZADO_SUCESSO;
        } else {
            $this->PacientesModel->getMySQL()->getDb()->rollBack();
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_NAO_AFETADO);
        }
    }

    private function validarRetornoRequest($return): void
    {
        if ($return === null) {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
        }
    }
}
