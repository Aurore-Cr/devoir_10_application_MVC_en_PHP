<?php
/** @var array<int, array<string, mixed>> $trajets */
?>
<h1 class="h4 mb-4">Trajets</h1>

<table class="table app-table">
    <thead>
        <tr>
            <th>Depart</th>
            <th>Date depart</th>
            <th>Destination</th>
            <th>Date arrivee</th>
            <th>Places</th>
            <th>Auteur</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($trajets === []) : ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Aucun trajet enregistre.</td></tr>
        <?php endif; ?>
        <?php foreach ($trajets as $trajet) :
            $depart = new DateTime($trajet['date_heure_depart']);
            $arrivee = new DateTime($trajet['date_heure_arrivee']);
        ?>
        <tr>
            <td><?= htmlspecialchars($trajet['agence_depart']) ?></td>
            <td><?= $depart->format('d/m/y H:i') ?></td>
            <td><?= htmlspecialchars($trajet['agence_arrivee']) ?></td>
            <td><?= $arrivee->format('d/m/y H:i') ?></td>
            <td><?= (int) $trajet['nb_places_disponibles'] ?> / <?= (int) $trajet['nb_places_total'] ?></td>
            <td><?= htmlspecialchars($trajet['auteur_prenom'] . ' ' . $trajet['auteur_nom']) ?></td>
            <td>
                <form method="post" action="/admin/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer" class="d-inline m-0"
                      onsubmit="return confirm('Supprimer ce trajet ?');">
                    <button type="submit" class="btn-icon text-danger" title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
