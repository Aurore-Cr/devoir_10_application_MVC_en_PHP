<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Classe de base pour les modeles : expose la connexion PDO partagee.
 */

abstract class Model
{
    /**
     * Raccourci vers la connexion PDO 
     */

    protected static function pdo(): PDO
    {
        return Database::getInstance();
    }
}
