<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Petits utilitaires de reponse HTTP (redirections).
 */

final class Response
{
    /**
     * Redirige immediatement le navigateur vers un autre chemin et
     * arrete l'execution du script courant.
     *
     * @param string $path Chemin relatif, ex : '/trajets'.
     */

    public static function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
