<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Centralise la configuration de connexion (DSN, options PDO) afin que
 * tous les modeles partagent la meme instance et les memes reglages
 * (mode d'erreur exceptions, recuperation en tableau associatif...).
 */

final class Database
{
    private static ?PDO $instance = null;

    /**
     * Empeche l'instanciation directe 
     */

    private function __construct() {}

    /**
     * Retourne l'instance PDO unique, en la creant si necessaire a
     * partir des variables d'environnement (voir .env / config.php).
     *
     * @return PDO Connexion active a la base de donnees.
     */

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require dirname(__DIR__, 2) . '/config/config.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $config['db']['host'],
                $config['db']['port'],
                $config['db']['name']
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['db']['user'],
                    $config['db']['password'],
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES    => false,
                    ]
                );
            } catch (PDOException $exception) {
                throw new PDOException(
                    'Connexion a la base de donnees impossible : ' . $exception->getMessage()
                );
            }
        }

        return self::$instance;
    }

    /**
     * Permet d'injecter une connexion PDO personnalisee (utilise par les
     * tests unitaires pour brancher une base SQLite en memoire).
     *
     * @param PDO $pdo Connexion a utiliser pour le reste de l'execution.
     */

    public static function setInstance(PDO $pdo): void
    {
        self::$instance = $pdo;
    }
}
