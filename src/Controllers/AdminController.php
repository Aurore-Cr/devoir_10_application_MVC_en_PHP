<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\FlashMessage;
use App\Models\Agence;
use App\Models\Trajet;
use App\Models\User;
use PDOException;

/**
 * Tableau de bord administrateur : consultation des utilisateurs et destrajets, gestion complete (CRUD) des agences.
 */

final class AdminController extends Controller
{
    public function dashboard(): void
    {
        Auth::requireAdmin();
        $this->render('admin/dashboard', ['title' => 'Tableau de bord administrateur']);
    }

    public function users(): void
    {
        Auth::requireAdmin();
        $this->render('admin/users', [
            'title' => 'Utilisateurs',
            'users' => User::all(),
        ]);
    }

    public function agences(): void
    {
        Auth::requireAdmin();
        $this->render('admin/agences', [
            'title'   => 'Agences',
            'agences' => Agence::all(),
        ]);
    }

    public function createAgence(): void
    {
        Auth::requireAdmin();
        $this->render('admin/agence_form', [
            'title'  => 'Creer une agence',
            'agence' => null,
            'error'  => null,
            'action' => '/admin/agences',
        ]);
    }

    public function storeAgence(): void
    {
        Auth::requireAdmin();

        $nom = trim((string) $this->input('nom_agence', ''));

        if ($nom === '') {
            $this->render('admin/agence_form', [
                'title'  => 'Creer une agence',
                'agence' => ['nom_agence' => $nom],
                'error'  => "Le nom de l'agence est obligatoire.",
                'action' => '/admin/agences',
            ]);
            return;
        }

        try {
            Agence::create($nom);
        } catch (PDOException) {
            $this->render('admin/agence_form', [
                'title'  => 'Creer une agence',
                'agence' => ['nom_agence' => $nom],
                'error'  => 'Cette agence existe deja.',
                'action' => '/admin/agences',
            ]);
            return;
        }

        FlashMessage::set('success', "L'agence a ete creee.");
        $this->redirect('/admin/agences');
    }

    public function editAgence(string $id): void
    {
        Auth::requireAdmin();
        $agence = Agence::find((int) $id);

        if ($agence === null) {
            FlashMessage::set('danger', 'Agence introuvable.');
            $this->redirect('/admin/agences');
        }

        $this->render('admin/agence_form', [
            'title'  => "Modifier l'agence",
            'agence' => $agence,
            'error'  => null,
            'action' => '/admin/agences/' . $agence['id_agence'] . '/modifier',
        ]);
    }

    public function updateAgence(string $id): void
    {
        Auth::requireAdmin();
        $nom = trim((string) $this->input('nom_agence', ''));

        if ($nom === '') {
            $this->render('admin/agence_form', [
                'title'  => "Modifier l'agence",
                'agence' => ['id_agence' => $id, 'nom_agence' => $nom],
                'error'  => "Le nom de l'agence est obligatoire.",
                'action' => '/admin/agences/' . $id . '/modifier',
            ]);
            return;
        }

        try {
            Agence::update((int) $id, $nom);
        } catch (PDOException) {
            $this->render('admin/agence_form', [
                'title'  => "Modifier l'agence",
                'agence' => ['id_agence' => $id, 'nom_agence' => $nom],
                'error'  => 'Cette agence existe deja.',
                'action' => '/admin/agences/' . $id . '/modifier',
            ]);
            return;
        }

        FlashMessage::set('success', "L'agence a ete modifiee.");
        $this->redirect('/admin/agences');
    }

    public function destroyAgence(string $id): void
    {
        Auth::requireAdmin();

        if (Agence::isUsed((int) $id)) {
            FlashMessage::set('danger', "Impossible de supprimer une agence utilisee par des trajets.");
            $this->redirect('/admin/agences');
        }

        Agence::delete((int) $id);
        FlashMessage::set('success', "L'agence a ete supprimee.");
        $this->redirect('/admin/agences');
    }

    public function trajets(): void
    {
        Auth::requireAdmin();
        $this->render('admin/trajets', [
            'title'   => 'Trajets',
            'trajets' => Trajet::all(),
        ]);
    }

    public function destroyTrajet(string $id): void
    {
        Auth::requireAdmin();
        Trajet::delete((int) $id);
        FlashMessage::set('success', 'Le trajet a ete supprime.');
        $this->redirect('/admin/trajets');
    }
}
