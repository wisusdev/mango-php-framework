<?php

namespace Core;

use App\Exceptions\NotFoundException;
use Exception;

class Route
{

    public static function load(string $file): Route
    {
        $route = new static;
        require $file;
        return $route;
    }

    /**
     * @throws Exception
     */
    function add($route, $action): void
    {
        $requestURI = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        //almacenará todos los valores de los parámetros
        $params = [];

        //almacenará todos los nombres de los parámetros
        $paramKey = [];

        //buscar si hay algún parámetro {?} en $route
        preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);

        //configuración de los nombres de los parámetros
        foreach ($paramMatches[0] as $key) {
            $paramKey[] = $key;
        }

        //sustitución de la primera y la última barras inclinadas
        //$_SERVER['REQUEST_URI'] estará vacío si req uri es /

        if (!empty($requestURI)) {
            $route = preg_replace("/(^\/)|(\/$)/", "", $route);
            $reqUri =  preg_replace("/(^\/)|(\/$)/", "", $requestURI);
        } else {
            $reqUri = "/";
        }

        //si la ruta no contiene ningún parámetro llama a simpleRoute();
        if (empty($paramMatches[0])) {
            if ($reqUri == $route) {
                $controller = $action[0];
                $action = $action[1];
    
                $this->callAction($controller, $action);
            }

            return;
        }

        //dividimos la ruta
        $uri = explode("/", $route);

        //almacenará el número de índice donde se requiera el parámetro {?} en la $ruta
        $indexNum = [];

        //almacenamos el número de índice, donde se requiere el parámetro {?} con la ayuda de regex
        foreach ($uri as $index => $param) {
            if (preg_match("/{.*}/", $param)) {
                $indexNum[] = $index;
            }
        }

        //exploding request uri string to array to get
        //the exact index number value of parameter from $_SERVER['REQUEST_URI']
        $reqUri = explode("/", $reqUri);

        //ejecutando cada bucle para establecer el número de índice exacto
        //esto ayudará en la coincidencia de ruta
        foreach ($indexNum as $key => $index) {

            //en caso de que req uri con param index esté vacío entonces devuelve return
            //porque la url no es válida para esta ruta
            if (empty($reqUri[$index])) {
                return;
            }

            //establecer parámetros con nombres de parámetros
            $params[$paramKey[$key]] = $reqUri[$index];

            //esto es para crear una regex para comparar la dirección de la ruta
            $reqUri[$index] = "{.*}";
        }

        //Convertimos array a sting
        $reqUri = implode("/", $reqUri);

        //Reemplazamos todos los / con \/
        //regex para que coincida con la ruta está listo
        $reqUri = str_replace("/", '\\/', $reqUri);

        //ahora ruta coincidente
        if (preg_match("/$reqUri/", $route)) {
            $controller = $action[0];
            $action = $action[1];

            $this->callAction($controller, $action, $params);
        }
    }

    /**
     * @throws Exception
     */
    protected function callAction(string $controller, string $action, array $params = [])
    {
        $controller = "App\\Controllers\\$controller";
        $controller = new $controller;

        if (!method_exists($controller, $action)) {
            throw new Exception("$controller does not have the $action method");
        }

        return $controller->$action($params);
    }
}
