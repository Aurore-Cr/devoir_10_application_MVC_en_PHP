<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TrajetController;
use App\Core\Router;

/**
 * Table de routage de l'application. Chaque ligne associe une methode
 * HTTP et un chemin a un [Controleur, action].
 *
 * @param Router $router
 */
return function (Router $router): void {
    // Accueil (visiteur, utilisateur, admin)
    $router->get('/', HomeController::class, 'index');

    // Authentification
    $router->get('/connexion', AuthController::class, 'showLogin');
    $router->post('/connexion', AuthController::class, 'login');
    $router->post('/deconnexion', AuthController::class, 'logout');

    // Trajets (utilisateur connecte)
    $router->get('/trajets/creer', TrajetController::class, 'create');
    $router->post('/trajets', TrajetController::class, 'store');
    $router->get('/trajets/{id}/modifier', TrajetController::class, 'edit');
    $router->post('/trajets/{id}/modifier', TrajetController::class, 'update');
    $router->post('/trajets/{id}/supprimer', TrajetController::class, 'destroy');

    // Administration
    $router->get('/admin', AdminController::class, 'dashboard');
    $router->get('/admin/utilisateurs', AdminController::class, 'users');
    $router->get('/admin/agences', AdminController::class, 'agences');
    $router->get('/admin/agences/creer', AdminController::class, 'createAgence');
    $router->post('/admin/agences', AdminController::class, 'storeAgence');
    $router->get('/admin/agences/{id}/modifier', AdminController::class, 'editAgence');
    $router->post('/admin/agences/{id}/modifier', AdminController::class, 'updateAgence');
    $router->post('/admin/agences/{id}/supprimer', AdminController::class, 'destroyAgence');
    $router->get('/admin/trajets', AdminController::class, 'trajets');
    $router->post('/admin/trajets/{id}/supprimer', AdminController::class, 'destroyTrajet');
};
