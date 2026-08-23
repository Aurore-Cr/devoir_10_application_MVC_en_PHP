<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

/**
 * Gestion de l'authentification via la session PHP.
 */

final class Auth
{
    private const SESSION_KEY = 'user_id';

    /**
     * Tente de connecter un utilisateur a partir de son email et mot de
     * passe en clair.
     *
     * @return bool true si les identifiants sont valides.
     */

    public static function attempt(string $email, string $password): bool
    {
        $user = User::findByEmail($email);

        if ($user === null || !password_verify($password, $user['mot_de_passe'])) {
            return false;
        }

        $_SESSION[self::SESSION_KEY] = $user['id_utilisateur'];
        return true;
    }

    /**
     * Deconnecte l'utilisateur courant en detruisant la session.
     */

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    /**
     * @return bool true si un utilisateur est connecte.
     */

    public static function check(): bool
    {
        return isset($_SESSION[self::SESSION_KEY]);
    }

    /**
     * @return array<string, mixed>|null Les donnees de l'utilisateur
     *                                   connecte, ou null.
     */

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return User::find((int) $_SESSION[self::SESSION_KEY]);
    }

    /**
     * @return int|null L'identifiant de l'utilisateur connecte.
     */

    public static function id(): ?int
    {
        return self::check() ? (int) $_SESSION[self::SESSION_KEY] : null;
    }

    /**
     * @return bool true si l'utilisateur connecte est administrateur.
     */

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user !== null && $user['role'] === 'admin';
    }

    /**
     * Redirige vers la page de connexion si aucun utilisateur n'est
     * connecte. A appeler en debut des actions de controleur protegees.
     */

    public static function requireLogin(): void
    {
        if (!self::check()) {
            Response::redirect('/connexion');
        }
    }

    /**
     * Redirige vers l'accueil si l'utilisateur connecte n'est pas
     * administrateur. A appeler en debut des actions reservees a
     * l'administrateur.
     */

    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            Response::redirect('/');
        }
    }
}
