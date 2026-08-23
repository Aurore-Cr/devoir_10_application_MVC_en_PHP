<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Modele d'acces a la table `trajet`.
 *
 * Regroupe les requetes de lecture (liste publique, detail) et
 * d'ecriture (creation, modification, suppression) sur les trajets
 * inter-sites proposes par les employes.
 */

final class Trajet extends Model
{
    /**
     * Liste des trajets a venir disposant encore de places libres,
     * triee par date de depart croissante. Utilisee par la page
     * d'accueil (visiteur et utilisateur connecte).
     *
     * @return array<int, array<string, mixed>>
     */

    public static function upcomingWithSeats(): array
    {
        $sql = <<<SQL
            SELECT
                t.*,
                ad.nom_agence AS agence_depart,
                aa.nom_agence AS agence_arrivee,
                u.nom AS auteur_nom,
                u.prenom AS auteur_prenom,
                u.telephone AS auteur_telephone,
                u.email AS auteur_email
            FROM trajet t
            INNER JOIN agence ad ON ad.id_agence = t.id_agence_depart
            INNER JOIN agence aa ON aa.id_agence = t.id_agence_arrivee
            INNER JOIN utilisateur u ON u.id_utilisateur = t.id_utilisateur
            WHERE t.nb_places_disponibles > 0
              AND t.date_heure_depart >= CURRENT_TIMESTAMP
            ORDER BY t.date_heure_depart ASC
        SQL;

        return self::pdo()->query($sql)->fetchAll();
    }

    /**
     * Tous les trajets (y compris passes ou complets), pour le tableau
     * de bord administrateur.
     *
     * @return array<int, array<string, mixed>>
     */

    public static function all(): array
    {
        $sql = <<<SQL
            SELECT
                t.*,
                ad.nom_agence AS agence_depart,
                aa.nom_agence AS agence_arrivee,
                u.nom AS auteur_nom,
                u.prenom AS auteur_prenom
            FROM trajet t
            INNER JOIN agence ad ON ad.id_agence = t.id_agence_depart
            INNER JOIN agence aa ON aa.id_agence = t.id_agence_arrivee
            INNER JOIN utilisateur u ON u.id_utilisateur = t.id_utilisateur
            ORDER BY t.date_heure_depart DESC
        SQL;

        return self::pdo()->query($sql)->fetchAll();
    }

    /**
     * @return array<string, mixed>|null Le trajet correspondant a l'id, avec ses jointures, ou null.
     */

    public static function find(int $id): ?array
    {
        $sql = <<<SQL
            SELECT
                t.*,
                ad.nom_agence AS agence_depart,
                aa.nom_agence AS agence_arrivee,
                u.nom AS auteur_nom,
                u.prenom AS auteur_prenom,
                u.telephone AS auteur_telephone,
                u.email AS auteur_email
            FROM trajet t
            INNER JOIN agence ad ON ad.id_agence = t.id_agence_depart
            INNER JOIN agence aa ON aa.id_agence = t.id_agence_arrivee
            INNER JOIN utilisateur u ON u.id_utilisateur = t.id_utilisateur
            WHERE t.id_trajet = :id
        SQL;

        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $trajet = $stmt->fetch();
        return $trajet === false ? null : $trajet;
    }

    /**
     * Cree un nouveau trajet.
     *
     * @param array{
     *     id_agence_depart: int,
     *     id_agence_arrivee: int,
     *     date_heure_depart: string,
     *     date_heure_arrivee: string,
     *     nb_places_total: int,
     *     id_utilisateur: int
     * } $data Donnees validees du trajet.
     *
     * @return int L'identifiant du trajet cree.
     */

    public static function create(array $data): int
    {
        $sql = <<<SQL
            INSERT INTO trajet
                (id_agence_depart, id_agence_arrivee, date_heure_depart, date_heure_arrivee,
                 nb_places_total, nb_places_disponibles, id_utilisateur)
            VALUES
                (:depart, :arrivee, :date_depart, :date_arrivee, :total, :total_dispo, :user)
        SQL;

        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([
            'depart'      => $data['id_agence_depart'],
            'arrivee'     => $data['id_agence_arrivee'],
            'date_depart' => $data['date_heure_depart'],
            'date_arrivee' => $data['date_heure_arrivee'],
            'total'       => $data['nb_places_total'],
            'total_dispo' => $data['nb_places_total'],
            'user'        => $data['id_utilisateur'],
        ]);

        return (int) self::pdo()->lastInsertId();
    }

    /**
     * Met a jour un trajet existant.
     *
     * @param array{
     *     id_agence_depart: int,
     *     id_agence_arrivee: int,
     *     date_heure_depart: string,
     *     date_heure_arrivee: string,
     *     nb_places_total: int,
     *     nb_places_disponibles: int
     * } $data Donnees validees du trajet.
     */

    public static function update(int $id, array $data): bool
    {
        $sql = <<<SQL
            UPDATE trajet SET
                id_agence_depart = :depart,
                id_agence_arrivee = :arrivee,
                date_heure_depart = :date_depart,
                date_heure_arrivee = :date_arrivee,
                nb_places_total = :total,
                nb_places_disponibles = :dispo
            WHERE id_trajet = :id
        SQL;

        $stmt = self::pdo()->prepare($sql);
        return $stmt->execute([
            'depart'      => $data['id_agence_depart'],
            'arrivee'     => $data['id_agence_arrivee'],
            'date_depart' => $data['date_heure_depart'],
            'date_arrivee' => $data['date_heure_arrivee'],
            'total'       => $data['nb_places_total'],
            'dispo'       => $data['nb_places_disponibles'],
            'id'          => $id,
        ]);
    }

    /**
     * Supprime un trajet.
     */

    public static function delete(int $id): bool
    {
        $stmt = self::pdo()->prepare('DELETE FROM trajet WHERE id_trajet = :id');
        return $stmt->execute(['id' => $id]);
    }
}
