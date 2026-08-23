<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\FlashMessage;

/**
 * Gere la connexion et la deconnexion des utilisateurs.
 */

final class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */

    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $this->render('auth/login', ['title' => 'Connexion']);
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */

    public function login(): void
    {
        $email = trim((string) $this->input('email', ''));
        $password = (string) $this->input('password', '');

        if ($email === '' || $password === '' || !Auth::attempt($email, $password)) {
            FlashMessage::set('danger', 'Identifiant ou mot de passe incorrect.');
            $this->redirect('/connexion');
        }

        FlashMessage::set('success', 'Vous etes connecte.');

        if (Auth::isAdmin()) {
            $this->redirect('/admin');
        }
        $this->redirect('/');
    }

    /**
     * Deconnecte l'utilisateur courant.
     */

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }
}
