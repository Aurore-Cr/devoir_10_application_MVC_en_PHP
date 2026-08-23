<?php

declare(strict_types=1);

/**
 * Point d'entree unique de l'application 
 * Toutes les requetes HTTP sont redirigees ici par le serveur puis
 * dispatchees vers le bon controleur par le Router.
 */

session_start();

$autoloadComposer = dirname(__DIR__) . '/vendor/autoload.php';
if (is_file($autoloadComposer)) {
    require $autoloadComposer;
} else {
    require dirname(__DIR__) . '/src/autoload.php';
}

use App\Core\Router;

$router = new Router();
(require dirname(__DIR__) . '/config/routes.php')($router);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
