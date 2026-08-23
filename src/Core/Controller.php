<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Controleur de base : fournit le rendu des vues avec layout commun et
 * l'acces aux donnees de la requete.
 */

abstract class Controller
{
    /**
     * Affiche une vue au sein du layout principal.
     *
     * @param string               $view La vue a afficher, ex : 'home/index'.
     * @param array<string, mixed> $data Donnees rendues disponibles dans la vue.
     */

    protected function render(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = dirname(__DIR__) . '/Views/' . $view . '.php';
        $layoutFile = dirname(__DIR__) . '/Views/layouts/main.php';

        if (!is_file($viewFile)) {
            throw new \RuntimeException("Vue introuvable : {$view}");
        }

        // La vue est capturee dans $content puis injectee dans le layout,
        // qui gere le header/footer communs a toutes les pages.

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }

    /**
     * Redirige vers un autre chemin de l'application.
     */

    protected function redirect(string $path): void
    {
        Response::redirect($path);
    }

    /**
     * Recupere une valeur du corps de la requete POST, avec valeur par
     * defaut si absente. 
     */

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }
}
