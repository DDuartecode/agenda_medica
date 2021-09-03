<?php

namespace Controller;

use InvalidArgumentException;
use Model\UsuariosModel;
use Util\GenericConsts;

class UsuariosController
{
    public const TABELA = 'usuarios';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    private array $dados;

    private array $dadosCorpoRequest;

    private object $UsuariosModel;

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->UsuariosModel = new UsuariosModel;
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
        return $this->UsuariosModel->getMySQL()->getOneByKey(self::TABELA, $this->dados['id']);
    }

    private function listar()
    {
        return $this->UsuariosModel->getMySQL()->getAll(self::TABELA);
    }

    private function deletar()
    {
        return $this->UsuariosModel->getMySQL()->delete(self::TABELA, $this->dados['id']);
    }

    private function cadastrar()
    {
        [$login, $senha, $nome] = [$this->dadosCorpoRequest['login'], $this->dadosCorpoRequest['senha'], $this->dadosCorpoRequest['nome']];

        if ($login && $senha && $nome) {
            if ($this->UsuariosModel->insertUser($login, $senha, $nome) > 0) {
                $idInserido = $this->UsuariosModel->getMySQL()->getDb()->lastInsertId();
                $this->UsuariosModel->getMySQL()->getDb()->commit();
                return ['id_inserido' => $idInserido];
            } else {
                throw new InvalidArgumentException(GenericConsts::MSG_ERRO_GENERICO);
            }
        } else {
            throw new InvalidArgumentException(GenericConsts::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
        }
    }

    private function atualizar()
    {

        if ($this->UsuariosModel->updateUser($this->dados['id'], $this->dadosCorpoRequest) > 0) {
            $this->UsuariosModel->getMySQL()->getDb()->commit();
            return GenericConsts::MSG_ATUALIZADO_SUCESSO;
        } else {
            $this->UsuariosModel->getMySQL()->getDb()->rollBack();
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
