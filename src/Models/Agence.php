<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Modele d'acces a la table `agence` (villes / implantations).
 *
 * Seul l'administrateur est autorise, cote controleur, a appeler les
 * methodes de creation / modification / suppression.
 */
final class Agence extends Model
{
    /**
     * @return array<int, array<string, mixed>> Toutes les agences, triees par nom.
     */

    public static function all(): array
    {
        $stmt = self::pdo()->query('SELECT * FROM agence ORDER BY nom_agence ASC');
        return $stmt->fetchAll();
    }

    /**
     * @return array<string, mixed>|null L'agence correspondant a l'id, ou null.
     */

    public static function find(int $id): ?array
    {
        $stmt = self::pdo()->prepare('SELECT * FROM agence WHERE id_agence = :id');
        $stmt->execute(['id' => $id]);
        $agence = $stmt->fetch();
        return $agence === false ? null : $agence;
    }

    /**
     * Cree une nouvelle agence.
     *
     * @return int L'identifiant de l'agence creee.
     */

    public static function create(string $nom): int
    {
        $stmt = self::pdo()->prepare('INSERT INTO agence (nom_agence) VALUES (:nom)');
        $stmt->execute(['nom' => $nom]);
        return (int) self::pdo()->lastInsertId();
    }

    /**
     * Modifie le nom d'une agence existante.
     */

    public static function update(int $id, string $nom): bool
    {
        $stmt = self::pdo()->prepare('UPDATE agence SET nom_agence = :nom WHERE id_agence = :id');
        return $stmt->execute(['nom' => $nom, 'id' => $id]);
    }

    /**
     * Supprime une agence. Echoue si des trajets y font encore reference
     * (contrainte de cle etrangere ON DELETE RESTRICT).
     */

    public static function delete(int $id): bool
    {
        $stmt = self::pdo()->prepare('DELETE FROM agence WHERE id_agence = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Indique si une agence est referencee par au moins un trajet
     * (utile pour interdire proprement sa suppression depuis la vue).
     */

    public static function isUsed(int $id): bool
    {
        $stmt = self::pdo()->prepare(
            'SELECT COUNT(*) FROM trajet WHERE id_agence_depart = :id OR id_agence_arrivee = :id2'
        );
        $stmt->execute(['id' => $id, 'id2' => $id]);
        return ((int) $stmt->fetchColumn()) > 0;
    }
}
