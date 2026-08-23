<?php

use App\Core\Auth;

/** @var array<int, array<string, mixed>> $trajets */
$connected = Auth::check();
$currentUserId = Auth::id();
?>

<?php if (!$connected) : ?>
    <h1 class="h4 mb-3">Pour obtenir plus d'informations sur un trajet, veuillez vous connecter</h1>
<?php else : ?>
    <h1 class="h4 mb-3">Trajets proposes</h1>
<?php endif; ?>

<table class="table app-table">
    <thead>
        <tr>
            <th>Depart</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Destination</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Places</th>
            <?php if ($connected) : ?><th></th><?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if ($trajets === []) : ?>
            <tr>
                <td colspan="<?= $connected ? 8 : 7 ?>" class="text-center text-muted py-4">
                    Aucun trajet disponible pour le moment.
                </td>
            </tr>
        <?php endif; ?>

        <?php foreach ($trajets as $trajet) :
            $depart = new DateTime($trajet['date_heure_depart']);
            $arrivee = new DateTime($trajet['date_heure_arrivee']);
            $isAuthor = $connected && $currentUserId === (int) $trajet['id_utilisateur'];
        ?>
        <tr>
            <td><?= htmlspecialchars($trajet['agence_depart']) ?></td>
            <td><?= $depart->format('d/m/y') ?></td>
            <td><?= $depart->format('H:i') ?></td>
            <td><?= htmlspecialchars($trajet['agence_arrivee']) ?></td>
            <td><?= $arrivee->format('d/m/y') ?></td>
            <td><?= $arrivee->format('H:i') ?></td>
            <td><?= (int) $trajet['nb_places_disponibles'] ?></td>
            <?php if ($connected) : ?>
                <td class="text-nowrap">
                    <button type="button" class="btn-icon" data-bs-toggle="modal" data-bs-target="#details-<?= (int) $trajet['id_trajet'] ?>" title="Details">
                        <i class="bi bi-eye"></i>
                    </button>
                    <?php if ($isAuthor) : ?>
                        <a class="btn-icon" href="/trajets/<?= (int) $trajet['id_trajet'] ?>/modifier" title="Modifier">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form method="post" action="/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer" class="d-inline m-0"
                              onsubmit="return confirm('Supprimer ce trajet ?');">
                            <button type="submit" class="btn-icon text-danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($connected) : ?>
    <?php foreach ($trajets as $trajet) : ?>
    <div class="modal fade" id="details-<?= (int) $trajet['id_trajet'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Details du trajet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Auteur :</strong> <?= htmlspecialchars($trajet['auteur_prenom'] . ' ' . $trajet['auteur_nom']) ?></p>
                    <p><strong>Telephone :</strong> <?= htmlspecialchars($trajet['auteur_telephone']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($trajet['auteur_email']) ?></p>
                    <p class="mb-0"><strong>Nombre total de places :</strong> <?= (int) $trajet['nb_places_total'] ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
