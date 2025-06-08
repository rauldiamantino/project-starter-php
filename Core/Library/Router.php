<?php

namespace Core\Library;

use Core\Library\Request;
use Core\Library\Response;
use Core\Library\Controller;
use Closure;
use Exception;
use DI\Container;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use App\Controllers\NotFoundController;
use App\Controllers\MethodNotAllowedController;

use function FastRoute\simpleDispatcher;

class Router
{
    private array $routes;
    private array $group = [];

    public function __construct(
        private Container $container,
        public readonly Request  $request,
    ) {
    }

    public function group(string $prefix, Closure $callback)
    {
        $this->group[$prefix] = $callback;
    }

    public function add(string $method, string $uri, array|Closure $controller)
    {
        $this->routes[] = [$method, $uri, $controller];
    }

    public function run()
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {

            if ($this->group) {
                $this->group_routes($r);
            }

            foreach ($this->routes as $route) {
                $r->addRoute(...$route);
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];

        if (
            $httpMethod === 'POST' &&
            isset($this->request->post['__method']) &&
            in_array($this->request->post['__method'], ['PUT', 'DELETE'], true)
        ) {
            $httpMethod = $this->request->post['__method'];
        }

        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        $this->handle($routeInfo);
    }

    private function group_routes(RouteCollector $r)
    {
        foreach ($this->group as $prefix => $routes) {
            $r->addGroup($prefix, function (RouteCollector $r) use ($routes) {
                foreach ($routes() as $route) {
                    $r->addRoute(...$route);
                }
            });
        }
    }

    private function handle(array $routeInfo)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $controller = NotFoundController::class;
                $method = 'index';
                $vars = [];

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $controller = MethodNotAllowedController::class;
                $method = 'index';
                $vars = [];

                break;
            case Dispatcher::FOUND:
                [, $controller, $vars] = $routeInfo;

                if (is_callable($controller)) {
                    $method = null;
                }

                if (is_array($controller)) {
                    [$controller, $method] = $controller;
                }

                break;
        }

        $this->send($controller, $method, $vars);
    }

    private function send(string|Closure $controller, ?string $method, array $vars)
    {
        if (is_callable($controller) && is_null($method)) {
            $closure = $controller->bindTo($this);

            return call_user_func_array($closure, $vars);
        }

        $controller = $this->container->get($controller);

        if (is_subclass_of($controller, Controller::class)) {
            $controller->setRequest($this->request);
        }

        $response = call_user_func_array([$controller, $method], $vars);
        $controller_namespace = get_class($controller);

        if (!$response or !$response instanceof Response) {
            throw new Exception('Response not found in ' . $controller_namespace . 'controller and ' . $method . ' method.');
        }

        $response->send();
    }
}
