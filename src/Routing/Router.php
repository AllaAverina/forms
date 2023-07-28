<?php

namespace Forms\Routing;

use Forms\DI\Container;
use Forms\Helpers\Route;
use Forms\Views\View;

class Router
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run(): void
    {
        $route = $this->getRoute();

        if (!$route) {
            View::renderError(404);
        }

        if (isset($route['redirect'])) {
            Route::redirect($route['redirect']);
        }

        $controller = $route['controller'];
        $action = $route['action'];
        $params = $route['params'];

        $controller = $this->container->get($controller);
        $controller->$action(...$params);
    }

    private function getRoute(): ?array
    {
        $method = isset($_REQUEST['_method']) ? $_REQUEST['_method'] : $_SERVER['REQUEST_METHOD'];
        $routes = $this->container->get('routes')[$method];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($routes as $pattern => $values) {
            if (preg_match("~^$pattern$~", $url, $matches)) {
                array_shift($matches);
                return array_merge($values, ['params' => $matches]);
            }
        }

        return null;
    }
}
