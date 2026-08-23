<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Agence;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;

/**
 * Teste les operations d'ecriture du modele Agence : creation,
 * modification, suppression, detection d'utilisation.
 */

final class AgenceTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = resetTestDatabase();
    }

    public function testCreateInsertsANewAgence(): void
    {
        $id = Agence::create('Paris');

        $this->assertGreaterThan(0, $id);
        $agence = Agence::find($id);
        $this->assertNotNull($agence);
        $this->assertSame('Paris', $agence['nom_agence']);
    }

    public function testCreateRejectsDuplicateName(): void
    {
        Agence::create('Lyon');

        $this->expectException(PDOException::class);
        Agence::create('Lyon');
    }

    public function testUpdateChangesTheAgenceName(): void
    {
        $id = Agence::create('Marseille');

        $result = Agence::update($id, 'Marseille-Provence');

        $this->assertTrue($result);
        $agence = Agence::find($id);
        $this->assertSame('Marseille-Provence', $agence['nom_agence']);
    }

    public function testDeleteRemovesTheAgence(): void
    {
        $id = Agence::create('Toulouse');

        Agence::delete($id);

        $this->assertNull(Agence::find($id));
    }

    public function testIsUsedReturnsFalseWhenNoTripReferencesTheAgency(): void
    {
        $id = Agence::create('Nice');

        $this->assertFalse(Agence::isUsed($id));
    }

    public function testIsUsedReturnsTrueWhenATripReferencesTheAgency(): void
    {
        $departId = Agence::create('Nantes');
        $arriveeId = Agence::create('Strasbourg');

        $this->pdo->exec(
            "INSERT INTO utilisateur (nom, prenom, email, telephone, mot_de_passe, role)
             VALUES ('Doe', 'Jane', 'jane@example.com', '0600000000', 'hash', 'utilisateur')"
        );

        $this->pdo->exec(sprintf(
            "INSERT INTO trajet
                (id_agence_depart, id_agence_arrivee, date_heure_depart, date_heure_arrivee,
                 nb_places_total, nb_places_disponibles, id_utilisateur)
             VALUES (%d, %d, '2030-01-01 08:00:00', '2030-01-01 11:00:00', 3, 3, 1)",
            $departId,
            $arriveeId
        ));

        $this->assertTrue(Agence::isUsed($departId));
        $this->assertTrue(Agence::isUsed($arriveeId));
    }
}
