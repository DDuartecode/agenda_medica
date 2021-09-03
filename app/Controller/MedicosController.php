<?php

namespace Controller;

use InvalidArgumentException;
use Model\MedicosModel;
use Util\GenericConsts;

class MedicosController
{
    public const TABELA = 'medicos';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    private array $dados;

    private array $dadosCorpoRequest;

    private object $MedicosModel;

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->MedicosModel = new MedicosModel;
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
        return $this->MedicosModel->getMySQL()->getOneByKey(self::TABELA, $this->dados['id']);
    }

    private function listar()
    {
        return $this->MedicosModel->getMySQL()->getAll(self::TABELA);
    }

    private function deletar()
    {
        return $this->MedicosModel->getMySQL()->delete(self::TABELA, $this->dados['id']);
    }

    private function cadastrar()
    {

        [$nome, $especialidade, $crm] = [$this->dadosCorpoRequest['nome'], $this->dadosCorpoRequest['especialidade'], $this->dadosCorpoRequest['crm']];

        if ($nome && $especialidade && $crm) {
            if ($this->MedicosModel->insertMedico($nome, $especialidade, $crm) > 0) {
                $idInserido = $this->MedicosModel->getMySQL()->getDb()->lastInsertId();
                $this->MedicosModel->getMySQL()->getDb()->commit();
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

        if ($this->MedicosModel->updateMedico($this->dados['id'], $this->dadosCorpoRequest) > 0) {
            $this->MedicosModel->getMySQL()->getDb()->commit();
            return GenericConsts::MSG_ATUALIZADO_SUCESSO;
        } else {
            $this->MedicosModel->getMySQL()->getDb()->rollBack();
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
