<?php

ini_set('display_erros', 1);
ini_set('display_startup´_errors', 1);
error_reporting(E_ERROR);

define(HOST, 'localhost');
define(BANCO, 'agendamento');
define(USUARIO, 'root');
define(SENHA, '');

define(DS, DIRECTORY_SEPARATOR); // defino uma outra variável global para o directory_separator, tornando ela mais curta
define(DIR_PROJETO, 'agenda');

if (file_exists('autoload.php')) {
    include 'aoutoload.php';
} else {
    echo 'Erro ao incluir bootstrap';
    exit;
}
