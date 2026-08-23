<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, array{0: class-string, 1: string}>> */
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    /**
     * Declare une route GET.
     *
     * @param string       $path       Chemin, ex : '/trajets/{id}'.
     * @param class-string $controller Nom de la classe controleur.
     * @param string       $method     Methode a appeler sur le controleur.
     */

    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['GET'][$path] = [$controller, $method];
    }

    /**
     * Declare une route POST.
     *
     * @param string       $path       Chemin, ex : '/trajets/{id}/supprimer'.
     * @param class-string $controller Nom de la classe controleur.
     * @param string       $method     Methode a appeler sur le controleur.
     */

    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['POST'][$path] = [$controller, $method];
    }

    /**
     * Resout la requete courante : trouve la route correspondante,
     * instancie le controleur et appelle la methode avec les parametres
     * extraits de l'URL.
     */

    public function dispatch(string $httpMethod, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';
        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        foreach ($this->routes[$httpMethod] ?? [] as $pattern => [$controllerClass, $action]) {
            $params = $this->match($pattern, $uri);
            if ($params === null) {
                continue;
            }

            $controller = new $controllerClass();
            call_user_func_array([$controller, $action], $params);
            return;
        }

        http_response_code(404);
        echo '404 - Page non trouvee';
    }

    /**
     * Compare un motif de route (avec parametres {xxx}) a l'URI reelle.
     *
     * @return array<string>|null Liste ordonnee des parametres captures,
     *                            ou null si le motif ne correspond pas.
     */

    private function match(string $pattern, string $uri): ?array
    {
        $patternRegex = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $pattern);
        $patternRegex = '#^' . $patternRegex . '$#';

        if (preg_match($patternRegex, $uri, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return null;
    }
}
