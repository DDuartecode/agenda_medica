<?php

namespace Controller;

use InvalidArgumentException;
use Model\AgendamentoModel;
use Util\GenericConsts;

class AgendamentoController
{
    public const TABELA = 'agendamentos';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    private array $dados;

    private array $dadosCorpoRequest;

    private object $AgendamentoModel;

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->AgendamentoModel = new AgendamentoModel;
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
        return $this->AgendamentoModel->getMySQL()->getOneByKey(self::TABELA, $this->dados['id']);
    }

    private function listar()
    {
        return $this->AgendamentoModel->getMySQL()->getAll(self::TABELA);
    }

    private function deletar()
    {
        return $this->AgendamentoModel->getMySQL()->delete(self::TABELA, $this->dados['id']);
    }

    private function cadastrar()
    {

        [$idmedico, $idpaciente, $consulta_ini, $tempo_consulta] = [
            $this->dadosCorpoRequest['idmedico'],
            $this->dadosCorpoRequest['idpaciente'],
            $this->dadosCorpoRequest['consulta_ini'],
            $this->dadosCorpoRequest['tempo_consulta']
        ];

        //valida se o horário solicitado não está contido dentro de algum agendamento, do médigo solicitado.
        $this->AgendamentoModel->validateTime($idmedico, $consulta_ini);

        if ($idmedico && $idpaciente && $consulta_ini && $tempo_consulta) {

            if ($this->AgendamentoModel->insertAgendamento($idmedico, $idpaciente, $consulta_ini, $tempo_consulta) > 0) {
                $idInserido = $this->AgendamentoModel->getMySQL()->getDb()->lastInsertId();
                $this->AgendamentoModel->getMySQL()->getDb()->commit();
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

        //valida se o horário solicitado não está contido dentro de algum agendamento, do médigo solicitado.
        $this->AgendamentoModel->validateTime($this->dados['idmedico'], $this->dados['consulta_ini']);

        if ($this->AgendamentoModel->updateAgendamento($this->dados['id'], $this->dadosCorpoRequest) > 0) {
            $this->AgendamentoModel->getMySQL()->getDb()->commit();
            return GenericConsts::MSG_ATUALIZADO_SUCESSO;
        } else {
            $this->AgendamentoModel->getMySQL()->getDb()->rollBack();
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
