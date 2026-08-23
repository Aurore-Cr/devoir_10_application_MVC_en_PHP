<?php
/** @var array<int, array<string, mixed>> $agences */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 m-0">Agences</h1>
    <a href="/admin/agences/creer" class="btn btn-primary">Creer une agence</a>
</div>

<table class="table app-table">
    <thead>
        <tr>
            <th>Nom</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agences as $agence) : ?>
        <tr>
            <td><?= htmlspecialchars($agence['nom_agence']) ?></td>
            <td class="text-nowrap">
                <a class="btn-icon" href="/admin/agences/<?= (int) $agence['id_agence'] ?>/modifier" title="Modifier">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form method="post" action="/admin/agences/<?= (int) $agence['id_agence'] ?>/supprimer" class="d-inline m-0"
                      onsubmit="return confirm('Supprimer cette agence ?');">
                    <button type="submit" class="btn-icon text-danger" title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
