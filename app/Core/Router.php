<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$this->normalizePath($path)] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = $this->normalizePath(parse_url($uri, PHP_URL_PATH) ?: '/');
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        [$controllerClass, $action] = $handler;
        $controller = new $controllerClass();
        $controller->$action();
    }

    private function normalizePath(string $path): string
    {
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        return $path === '' ? '/' : $path;
    }
}
