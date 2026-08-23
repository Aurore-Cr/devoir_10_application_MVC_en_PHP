<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Modele d'acces a la table `utilisateur`.
 *
 * Conformement au brief, aucune methode de creation / modification /
 * suppression n'est exposee ici : les utilisateurs proviennent
 * uniquement du systeme RH (jeu d'essai importe par le script
 * database/seed.sql).
 */

final class User extends Model
{
    /**
     * @return array<int, array<string, mixed>> Tous les utilisateurs, tries par nom.
     */
    public static function all(): array
    {
        $stmt = self::pdo()->query(
            'SELECT id_utilisateur, nom, prenom, email, telephone, role FROM utilisateur ORDER BY nom ASC, prenom ASC'
        );
        return $stmt->fetchAll();
    }

    /**
     * @return array<string, mixed>|null L'utilisateur correspondant a l'id, ou null.
     */

    public static function find(int $id): ?array
    {
        $stmt = self::pdo()->prepare('SELECT * FROM utilisateur WHERE id_utilisateur = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user === false ? null : $user;
    }

    /**
     * @return array<string, mixed>|null L'utilisateur correspondant a l'email, ou null.
     */

    public static function findByEmail(string $email): ?array
    {
        $stmt = self::pdo()->prepare('SELECT * FROM utilisateur WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user === false ? null : $user;
    }
}
