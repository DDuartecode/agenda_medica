<?php

namespace Validator;

use Controller\AgendamentoController;
use Controller\MedicosController;
use Controller\PacientesController;
use Controller\UsuariosController;
use InvalidArgumentException;
use Util\GenericConsts;
use Util\JsonUtil;

class RequestValidator
{
    private $request;
    private array $dadosRequest;


    const GET = 'GET';
    const DELETE = 'DELETE';
    const USUARIOS = 'USUARIOS';
    const PACIENTES = 'PACIENTES';
    const MEDICOS = 'MEDICOS';
    const AGENDAMENTO = 'AGENDAMENTOS';

    /**
     * RequestValidator construtor
     *
     * @param [array] $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Processa os dados da requisição
     *
     * @return void
     */
    public function processRequest()
    {
        $return = utf8_encode(GenericConsts::MSG_ERRO_TIPO_ROTA);

        if (in_array($this->request['method'], GenericConsts::TIPO_REQUEST, true)) {
            $return = $this->directRequest();
        }

        return $return;
    }

    /**
     * Direciona a requisição de acordo com o método
     *
     * @return void
     */
    private function directRequest()
    {

        if ($this->request['method'] !== self::GET && $this->request['method'] !== self::DELETE) {
            // se o metodo for diferente de get e delete, será necessário tratar o corpo da requisição
            $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
        }

        $method = $this->request['method'];

        return $this->$method(); // executa a função de acordo com o valor contido na variável
    }

    /**
     * Busca dados
     *
     * @return void
     */
    private function get()
    {

        $return = GenericConsts::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['route'], GenericConsts::TIPO_GET, true)) {

            switch ($this->request['route']) {
                case self::USUARIOS:
                    $UsuariosController = new UsuariosController($this->request);
                    $return = $UsuariosController->validarGet();
                    break;

                case self::PACIENTES:
                    $PacientesController = new PacientesController($this->request);
                    $return = $PacientesController->validarGet();
                    break;

                case self::MEDICOS:
                    $MedicosController = new MedicosController($this->request);
                    $return = $MedicosController->validarGet();
                    break;

                case self::AGENDAMENTO:
                    $AgendamentoController = new AgendamentoController($this->request);
                    $return = $AgendamentoController->validarGet();
                    break;

                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $return;
    }

    /**
     * Exclui dados
     *
     * @return void
     */
    private function delete()
    {
        $return = GenericConsts::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['route'], GenericConsts::TIPO_DELETE, true)) {

            switch ($this->request['route']) {
                case self::USUARIOS:
                    $UsuariosController = new UsuariosController($this->request);
                    $return = $UsuariosController->validarDelete();
                    break;

                case self::PACIENTES:
                    $PacientesController = new PacientesController($this->request);
                    $return = $PacientesController->validarDelete();
                    break;

                case self::MEDICOS:
                    $MedicosController = new MedicosController($this->request);
                    $return = $MedicosController->validarDelete();
                    break;

                case self::AGENDAMENTO:
                    $AgendamentoController = new AgendamentoController($this->request);
                    $return = $AgendamentoController->validarDelete();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $return;
    }

    /**
     * Insere dados
     *
     * @return void
     */
    private function post()
    {
        $return = GenericConsts::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['route'], GenericConsts::TIPO_POST, true)) {
            switch ($this->request['route']) {
                case self::USUARIOS:
                    $UsuariosController = new UsuariosController($this->request);
                    $UsuariosController->setDadosCorpoRequest($this->dadosRequest);
                    $return = $UsuariosController->validarPost();
                    break;

                case self::PACIENTES:
                    $PacientesController = new PacientesController($this->request);
                    $PacientesController->setDadosCorpoRequest($this->dadosRequest);
                    $return = $PacientesController->validarPost();
                    break;

                case self::MEDICOS:
                    $MedicosController = new MedicosController($this->request);
                    $MedicosController->setDadosCorpoRequest($this->dadosRequest);
                    $return = $MedicosController->validarPost();
                    break;

                case self::AGENDAMENTO:
                    $AgendamentoController = new AgendamentoController($this->request);
                    $AgendamentoController->setDadosCorpoRequest($this->dadosRequest);
                    $return = $AgendamentoController->validarPost();
                    break;

                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $return;
    }

    /**
     * Atualiza Dados
     *
     * @return void
     */
    private function put()
    {
        $return = GenericConsts::MSG_ERRO_TIPO_ROTA;

        if (in_array($this->request['route'], GenericConsts::TIPO_PUT, true)) {

            switch ($this->request['route']) {
                case self::USUARIOS:
                    $UsuariosController = new UsuariosController($this->request);
                    $UsuariosController->setDadosCorpoRequest($this->dadosRequest);
                    $return = $UsuariosController->validarPut();
                    break;

                case self::PACIENTES:
                    $PacientesController = new PacientesController($this->request);
                    $PacientesController->setDadosCorpoRequest($this->dadosRequest);
                    $return = $PacientesController->validarPut();
                    break;

                case self::MEDICOS:
                    $MedicosController = new MedicosController($this->request);
                    $MedicosController->setDadosCorpoRequest($this->dadosRequest);
                    $return = $MedicosController->validarPut();
                    break;

                case self::AGENDAMENTO:
                    $AgendamentoController = new AgendamentoController($this->request);
                    $AgendamentoController->setDadosCorpoRequest($this->dadosRequest);
                    $return = $AgendamentoController->validarPut();
                    break;
                default:
                    throw new InvalidArgumentException(GenericConsts::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $return;
    }
}
