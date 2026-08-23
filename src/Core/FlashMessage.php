<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Messages "flash" affiches une seule fois apres une redirection
 * (exemple : "Le trajet a ete cree" apres un enregistrement en base).
 */

final class FlashMessage
{
    private const SESSION_KEY = 'flash_messages';

    /**
     * Enregistre un message a afficher lors du prochain affichage de page.
     *
     * @param string $type    Type du message : success, danger, info...
     * @param string $message Texte du message.
     */

    public static function set(string $type, string $message): void
    {
        $_SESSION[self::SESSION_KEY][] = ['type' => $type, 'message' => $message];
    }

    /**
     * Recupere les messages en attente puis les supprime de la session
     * (affichage "une seule fois").
     *
     * @return array<int, array{type: string, message: string}>
     */

    public static function pull(): array
    {
        $messages = $_SESSION[self::SESSION_KEY] ?? [];
        unset($_SESSION[self::SESSION_KEY]);
        return $messages;
    }
}
