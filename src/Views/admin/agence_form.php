<?php

/**
 * @var array<string, mixed>|null $agence
 * @var string|null               $error
 * @var string                    $action
 */

$isEdit = $agence !== null && isset($agence['id_agence']);
?>

<h1 class="h4 mb-4"><?= $isEdit ? "Modifier l'agence" : 'Creer une agence' ?></h1>

<?php if ($error !== null) : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <form method="post" action="<?= htmlspecialchars($action) ?>">
            <div class="mb-3">
                <label for="nom_agence" class="form-label">Nom de l'agence</label>
                <input type="text" class="form-control" id="nom_agence" name="nom_agence"
                    value="<?= htmlspecialchars($agence['nom_agence'] ?? '') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Creer' ?></button>
            <a href="/admin/agences" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>