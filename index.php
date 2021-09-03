<?php

use Util\GenericConsts;
use Util\JsonUtil;
use Util\Routes;
use Validator\RequestValidator;

include 'bootstrap.php';

try {

    $request = new RequestValidator(Routes::getRoutes());
    $return = $request->processRequest();

    $JsonUtil = new JsonUtil();
    $JsonUtil->processarArrayParaRetornar($return);
} catch (Exception $e) {
    echo json_encode([
        GenericConsts::TIPO => GenericConsts::TIPO_ERRO,
        GenericConsts::RESPOSTA => $e->getMessage()
    ]);
}
