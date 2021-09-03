<?php

namespace Util;

class Routes
{

    public static function getRoutes()
    {

        $url = self::getUrl();

        $request['route'] = strtoupper($url[1]);
        $request['resource'] = $url[2] ?? null;
        $request['id'] = $url[3] ?? null;
        $request['method'] = $_SERVER['REQUEST_METHOD'];

        return $request;
    }

    public static function getUrl()
    {
        $uri = str_replace('/' . DIR_PROJETO, '', $_SERVER['REQUEST_URI']);
        return explode('/', trim($uri, '/'));
    }
}
