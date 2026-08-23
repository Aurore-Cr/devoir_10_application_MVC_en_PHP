<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Agence;
use App\Models\Trajet;
use PDO;
use PHPUnit\Framework\TestCase;


/**
 * Teste les operations d'ecriture du modele Trajet : creation,
 * modification, suppression, et la visibilite des trajets sur la page
 * d'accueil (places disponibles, trajets passes exclus).
 */

final class TrajetTest extends TestCase
{
    private PDO $pdo;
    private int $userId;
    private int $paris;
    private int $lyon;

    protected function setUp(): void
    {
        $this->pdo = resetTestDatabase();

        $this->pdo->exec(
            "INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role)
             VALUES ('Martin', 'Alex', 'alex.martin@example.com', '0600000000', 'hash', 'utilisateur')"
        );
        $this->userId = (int) $this->pdo->lastInsertId();

        $this->paris = Agence::create('Paris');
        $this->lyon = Agence::create('Lyon');
    }

    private function tripData(array $overrides = []): array
    {
        return array_merge([
            'id_agence_depart'   => $this->paris,
            'id_agence_arrivee'  => $this->lyon,
            'date_heure_depart'  => '2030-06-01 08:00:00',
            'date_heure_arrivee' => '2030-06-01 12:00:00',
            'nb_places_total'    => 4,
            'id_utilisateur'     => $this->userId,
        ], $overrides);
    }

    public function testCreateInsertsATripWithFullAvailability(): void
    {
        $id = Trajet::create($this->tripData());

        $trajet = Trajet::find($id);
        $this->assertNotNull($trajet);
        $this->assertSame(4, (int) $trajet['nb_places_total']);
        $this->assertSame(4, (int) $trajet['nb_places_disponibles']);
        $this->assertSame('Paris', $trajet['agence_depart']);
        $this->assertSame('Lyon', $trajet['agence_arrivee']);
    }

    public function testUpdateModifiesTripFields(): void
    {
        $id = Trajet::create($this->tripData());

        Trajet::update($id, [
            'id_agence_depart'      => $this->lyon,
            'id_agence_arrivee'     => $this->paris,
            'date_heure_depart'     => '2030-07-01 09:00:00',
            'date_heure_arrivee'    => '2030-07-01 13:00:00',
            'nb_places_total'       => 3,
            'nb_places_disponibles' => 2,
        ]);

        $trajet = Trajet::find($id);
        $this->assertSame('Lyon', $trajet['agence_depart']);
        $this->assertSame('Paris', $trajet['agence_arrivee']);
        $this->assertSame(2, (int) $trajet['nb_places_disponibles']);
    }

    public function testDeleteRemovesTheTrip(): void
    {
        $id = Trajet::create($this->tripData());

        Trajet::delete($id);

        $this->assertNull(Trajet::find($id));
    }

    public function testUpcomingWithSeatsExcludesFullTrips(): void
    {
        Trajet::create($this->tripData(['nb_places_total' => 4]));
        $fullTripId = Trajet::create($this->tripData());
        Trajet::update($fullTripId, [
            'id_agence_depart'      => $this->paris,
            'id_agence_arrivee'     => $this->lyon,
            'date_heure_depart'     => '2030-06-02 08:00:00',
            'date_heure_arrivee'    => '2030-06-02 12:00:00',
            'nb_places_total'       => 4,
            'nb_places_disponibles' => 0,
        ]);

        $upcoming = Trajet::upcomingWithSeats();

        $this->assertCount(1, $upcoming);
    }

    public function testUpcomingWithSeatsExcludesPastTrips(): void
    {
        Trajet::create($this->tripData([
            'date_heure_depart'  => '2000-01-01 08:00:00',
            'date_heure_arrivee' => '2000-01-01 12:00:00',
        ]));

        $upcoming = Trajet::upcomingWithSeats();

        $this->assertCount(0, $upcoming);
    }

    public function testUpcomingWithSeatsAreSortedByDepartureDateAscending(): void
    {
        Trajet::create($this->tripData(['date_heure_depart' => '2030-08-01 08:00:00', 'date_heure_arrivee' => '2030-08-01 12:00:00']));
        Trajet::create($this->tripData(['date_heure_depart' => '2030-06-01 08:00:00', 'date_heure_arrivee' => '2030-06-01 12:00:00']));

        $upcoming = Trajet::upcomingWithSeats();

        $this->assertSame('2030-06-01 08:00:00', $upcoming[0]['date_heure_depart']);
        $this->assertSame('2030-08-01 08:00:00', $upcoming[1]['date_heure_depart']);
    }
}
