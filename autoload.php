<?php

/**
 * AUTOLOAD DE CLASSES DENTRO DO PACOTE 'Classes'
 * @param $classe
 */
function autoload($classe)
{
    $diretorioBase = __DIR__ . DS; // gera o giretório raiz com o separador de acordo com o sistema utilizado
    $classe = $diretorioBase . 'app' . DS . str_replace('\\', DS, $classe) . '.php'; // traz o diretório ao que o namespace pertence
    if (file_exists($classe) && !is_dir($classe)) {
        include $classe; // inclui o arquivo da clase, onde o mesmo está sendo chamado com o "use"
    }
}

spl_autoload_register('autoload'); //executa a função automaticamente ao utilizar o "use" no namespace da classe
