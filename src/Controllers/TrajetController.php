<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\FlashMessage;
use App\Core\Validator;
use App\Models\Agence;
use App\Models\Trajet;
use DateTime;

/**
 * CRUD des trajets pour les employes connectes : creation, modification
 * et suppression des trajets dont ils sont l'auteur (le "contact").
 */

final class TrajetController extends Controller
{
    /**
     * Affiche le formulaire de creation d'un trajet.
     */

    public function create(): void
    {
        Auth::requireLogin();

        $this->render('trajet/form', [
            'title'   => 'Proposer un trajet',
            'agences' => Agence::all(),
            'trajet'  => null,
            'errors'  => [],
            'action'  => '/trajets',
        ]);
    }

    /**
     * Enregistre un nouveau trajet.
     */

    public function store(): void
    {
        Auth::requireLogin();

        $data = $this->collectInput();
        $validator = $this->validate($data);

        if (!$validator->passes()) {
            $this->render('trajet/form', [
                'title'   => 'Proposer un trajet',
                'agences' => Agence::all(),
                'trajet'  => $data,
                'errors'  => $validator->errors(),
                'action'  => '/trajets',
            ]);
            return;
        }

        Trajet::create([
            'id_agence_depart'   => $data['id_agence_depart'],
            'id_agence_arrivee'  => $data['id_agence_arrivee'],
            'date_heure_depart'  => $data['date_heure_depart'],
            'date_heure_arrivee' => $data['date_heure_arrivee'],
            'nb_places_total'    => $data['nb_places_total'],
            'id_utilisateur'     => Auth::id(),
        ]);

        FlashMessage::set('success', 'Le trajet a ete cree.');
        $this->redirect('/');
    }

    /**
     * Affiche le formulaire de modification d'un trajet (auteur uniquement).
     */

    public function edit(string $id): void
    {
        Auth::requireLogin();

        $trajet = $this->findOwnedTrajet((int) $id);

        $this->render('trajet/form', [
            'title'   => 'Modifier le trajet',
            'agences' => Agence::all(),
            'trajet'  => $trajet,
            'errors'  => [],
            'action'  => '/trajets/' . $trajet['id_trajet'] . '/modifier',
        ]);
    }

    /**
     * Met a jour un trajet existant (auteur uniquement).
     */

    public function update(string $id): void
    {
        Auth::requireLogin();

        $existing = $this->findOwnedTrajet((int) $id);
        $data = $this->collectInput();
        $data['nb_places_disponibles'] = $this->recomputeAvailableSeats(
            (int) $existing['nb_places_total'],
            (int) $existing['nb_places_disponibles'],
            (int) $data['nb_places_total']
        );

        $validator = $this->validate($data);

        if (!$validator->passes()) {
            $this->render('trajet/form', [
                'title'   => 'Modifier le trajet',
                'agences' => Agence::all(),
                'trajet'  => array_merge($existing, $data, ['id_trajet' => $existing['id_trajet']]),
                'errors'  => $validator->errors(),
                'action'  => '/trajets/' . $existing['id_trajet'] . '/modifier',
            ]);
            return;
        }

        Trajet::update((int) $existing['id_trajet'], $data);

        FlashMessage::set('success', 'Le trajet a ete modifie.');
        $this->redirect('/');
    }

    /**
     * Supprime un trajet (auteur uniquement).
     */

    public function destroy(string $id): void
    {
        Auth::requireLogin();

        $trajet = $this->findOwnedTrajet((int) $id);
        Trajet::delete((int) $trajet['id_trajet']);

        FlashMessage::set('success', 'Le trajet a ete supprime.');
        $this->redirect('/');
    }

    /**
     * Recupere un trajet en verifiant que l'utilisateur connecte en est
     * bien l'auteur (sinon redirection vers l'accueil).
     *
     * @return array<string, mixed>
     */

    private function findOwnedTrajet(int $id): array
    {
        $trajet = Trajet::find($id);

        if ($trajet === null || (int) $trajet['id_utilisateur'] !== Auth::id()) {
            FlashMessage::set('danger', 'Ce trajet est introuvable ou ne vous appartient pas.');
            $this->redirect('/');
        }

        return $trajet;
    }

    /**
     * Lorsque l'utilisateur modifie le nombre total de places, on
     * conserve le nombre de places deja reservees (places_total initial
     * moins places_disponibles) et on l'applique au nouveau total.
     */

    private function recomputeAvailableSeats(int $oldTotal, int $oldAvailable, int $newTotal): int
    {
        $reserved = $oldTotal - $oldAvailable;
        return max(0, $newTotal - $reserved);
    }

    /**
     * @return array<string, mixed>
     */

    private function collectInput(): array
    {
        return [
            'id_agence_depart'   => (int) $this->input('id_agence_depart', 0),
            'id_agence_arrivee'  => (int) $this->input('id_agence_arrivee', 0),
            'date_heure_depart'  => (string) $this->input('date_heure_depart', ''),
            'date_heure_arrivee' => (string) $this->input('date_heure_arrivee', ''),
            'nb_places_total'    => (int) $this->input('nb_places_total', 0),
        ];
    }

    /**
     * Applique les controles de coherence :
     * agences differentes, arrivee posterieure au depart, places > 0.
     *
     * @param array<string, mixed> $data
     */

    private function validate(array &$data): Validator
    {
        $validator = new Validator();

        $validator->check(
            $data['id_agence_depart'] <= 0 || $data['id_agence_arrivee'] <= 0,
            'agences',
            "Veuillez selectionner une agence de depart et d'arrivee."
        );

        $validator->check(
            $data['id_agence_depart'] === $data['id_agence_arrivee'],
            'agences',
            "L'agence de depart et l'agence d'arrivee doivent etre differentes."
        );

        $depart = DateTime::createFromFormat('Y-m-d\TH:i', $data['date_heure_depart'])
            ?: DateTime::createFromFormat('Y-m-d H:i:s', $data['date_heure_depart']);
        $arrivee = DateTime::createFromFormat('Y-m-d\TH:i', $data['date_heure_arrivee'])
            ?: DateTime::createFromFormat('Y-m-d H:i:s', $data['date_heure_arrivee']);

        $validator->check(
            $depart === false || $arrivee === false,
            'dates',
            'Les dates de depart et arrivee sont invalides.'
        );

        if ($depart !== false && $arrivee !== false) {
            $validator->check(
                $arrivee <= $depart,
                'dates',
                "La date d'arrivee doit etre posterieure a la date de depart."
            );

            $validator->check(
                $depart < new DateTime(),
                'dates',
                'La date de depart ne peut pas etre dans le passe.'
            );

            // Normalisation au format MySQL pour l'enregistrement en base.
            $data['date_heure_depart'] = $depart->format('Y-m-d H:i:s');
            $data['date_heure_arrivee'] = $arrivee->format('Y-m-d H:i:s');
        }

        $validator->check(
            $data['nb_places_total'] < 1 || $data['nb_places_total'] > 9,
            'nb_places_total',
            'Le nombre de places doit etre compris entre 1 et 9.'
        );

        return $validator;
    }
}
