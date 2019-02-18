<?php

namespace Core;

class Router
{

    const defaultController = 'Home';

    const defaultAction = 'index';

    const controllerPrefix = 'Controller';


    /** @var array $requestURI */
    private $requestURI;

    /** @var string $controller */
    private $controller;

    /** @var string $action */
    private $action;

    /**
     * Router constructor
     */
    public function __construct()
    {
        $this->requestURI = explode('?', $_SERVER['REQUEST_URI'], 2);

        $segments = array_values(array_filter(explode('/', $this->requestURI[0])));

        if (empty($segments)) {
            $this->controller = self::defaultController . self::controllerPrefix;
            $this->action = self::defaultAction;

            return;
        }
        if (count($segments) === 1) {
            $this->controller = ucfirst($segments[0]) . self::controllerPrefix;
            $this->action = self::defaultAction;

            return;
        }

        $this->action = array_pop($segments);
        $this->controller = implode('\\', array_map('ucfirst', $segments)) . self::controllerPrefix;
    }

    /**
     * Executes controller's action
     */
    public function execute()
    {
        require_once(__DIR__ . '/../App/Controllers/' . $this->controller . '.php');

        $controller = '\\App\\Controllers\\' . $this->controller;

        (new $controller)->{$this->action}();

    }


}
