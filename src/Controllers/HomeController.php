<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Trajet;

/**
 * Page d'accueil : liste des trajets disposant encore de places
 * disponibles, accessible a tous (visiteur, utilisateur connecte,
 * administrateur).
 */

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('home/index', [
            'title'   => 'Trajets proposes',
            'trajets' => Trajet::upcomingWithSeats(),
        ]);
    }
}
