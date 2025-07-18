<?php

class Router
{
    private $defaultController;
    private $defaultMethod;
    private $configuration;

    public function __construct($defaultController, $defaultMethod, $configuration)
    {
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
        $this->configuration = $configuration;
    }

    public function go($controllerName, $methodName)
    {
        $controller = $this->getControllerFrom($controllerName);

        // Extraer parámetros desde la URL
        $url = $_SERVER["REQUEST_URI"];
        $parts = explode("/", trim($url, "/"));

        // Ejemplo: /juego/jugar/Ciencia => [juego, jugar, Ciencia]
        $param = $parts[2] ?? null; // Cambiá índice si cambia estructura

        $this->executeMethodFromController($controller, $methodName, $param);
    }

    private function getControllerFrom($controllerName)
    {
        $controllerName = 'get' . ucfirst($controllerName) . 'Controller';
        $validController = method_exists($this->configuration, $controllerName) ? $controllerName : $this->defaultController;
        return call_user_func(array($this->configuration, $validController));
    }

    private function executeMethodFromController($controller, $method, $param = null)
    {
        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;
        $ref = new ReflectionMethod($controller, $method);
        $paramCount = $ref->getNumberOfParameters();

        if($paramCount > 0){
            $controller->{$validMethod}($param);
        } else {
            $controller->{$validMethod}();
        }
    }
}