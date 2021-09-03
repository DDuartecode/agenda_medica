<?php

namespace Util;

use InvalidArgumentException;
use JsonException;

class JsonUtil
{
    public function processarArrayParaRetornar($return)
    {
        $dados = [];
        $dados[GenericConsts::TIPO] = GenericConsts::TIPO_ERRO;

        if (is_array($return) && count($return) > 0 || strlen($return) > 10) {
            $dados[GenericConsts::TIPO] = GenericConsts::TIPO_SUCESSO;
            $dados[GenericConsts::RESPOSTA] = $return;
        }

        $this->retornarJson($dados);
    }

    private function retornarJson($json)
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Access-Contro-allow-Methods: GET, POST, PUT, DELETE');

        echo json_encode($json);
        exit;
    }


    public static function tratarCorpoRequisicaoJson()
    {

        try {
            $postJason = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidArgumentException(GenericConsts::MSG_ERR0_JSON_VAZIO);
        }

        if (is_array($postJason) && count($postJason) > 0) {
            return $postJason;
        }
    }
}
