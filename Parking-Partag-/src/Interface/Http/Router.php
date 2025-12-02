<?php

namespace Interface\Http;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * Enregistre une route GET
     */
    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * Enregistre une route POST
     */
    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Exécute la route correspondante
     */
    public function dispatch(string $uri, string $method)
    {
        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "404 - Route non trouvée";
            return;
        }

        [$controllerClass, $controllerMethod] = $this->routes[$method][$uri];

        $controller = new $controllerClass();

        return $controller->$controllerMethod();
    }
}
