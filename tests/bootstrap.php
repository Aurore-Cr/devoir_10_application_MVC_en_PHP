<?php

declare(strict_types=1);

/**
 * Bootstrap execute avant chaque suite de tests PHPUnit.
 */

require dirname(__DIR__) . '/src/autoload.php';

use App\Core\Database;

/**
 * Cree une base SQLite en memoire a partir du schema de test et
 * l'injecte comme connexion active de l'application.
 */

function resetTestDatabase(): PDO
{
    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');

    $schema = file_get_contents(__DIR__ . '/sqlite_schema.sql');
    $pdo->exec($schema);

    Database::setInstance($pdo);

    return $pdo;
}
