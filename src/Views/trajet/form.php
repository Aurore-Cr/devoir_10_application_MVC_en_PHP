<?php

use App\Core\Auth;

/**
 * @var array<int, array<string, mixed>> $agences
 * @var array<string, mixed>|null        $trajet
 * @var array<string, string>            $errors
 * @var string                           $action
 */

$user = Auth::user();
$isEdit = $trajet !== null && isset($trajet['id_trajet']);

$val = static function (string $key, mixed $default = '') use ($trajet) {
    return htmlspecialchars((string) ($trajet[$key] ?? $default));
};

$dateForInput = static function (?string $value): string {
    if (empty($value)) {
        return '';
    }
    try {
        return (new DateTime($value))->format('Y-m-d\TH:i');
    } catch (Exception) {
        return $value;
    }
};
?>

<h1 class="h4 mb-4"><?= $isEdit ? 'Modifier le trajet' : 'Proposer un trajet' ?></h1>

<?php foreach ($errors as $error) : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endforeach; ?>

<div class="row">
    <div class="col-md-8">
        <form method="post" action="<?= htmlspecialchars($action) ?>">

            <fieldset class="mb-4" disabled>
                <legend class="h6">Vos informations</legend>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prenom</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['email']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telephone</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['telephone']) ?>">
                    </div>
                </div>
            </fieldset>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_agence_depart" class="form-label">Agence de depart</label>
                    <select class="form-select" id="id_agence_depart" name="id_agence_depart" required>
                        <option value="">Choisir...</option>
                        <?php foreach ($agences as $agence) : ?>
                            <option value="<?= (int) $agence['id_agence'] ?>"
                                <?= (int) ($trajet['id_agence_depart'] ?? 0) === (int) $agence['id_agence'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['nom_agence']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="id_agence_arrivee" class="form-label">Agence d'arrivee</label>
                    <select class="form-select" id="id_agence_arrivee" name="id_agence_arrivee" required>
                        <option value="">Choisir...</option>
                        <?php foreach ($agences as $agence) : ?>
                            <option value="<?= (int) $agence['id_agence'] ?>"
                                <?= (int) ($trajet['id_agence_arrivee'] ?? 0) === (int) $agence['id_agence'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['nom_agence']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date_heure_depart" class="form-label">Date et heure de depart</label>
                    <input type="datetime-local" class="form-control" id="date_heure_depart" name="date_heure_depart"
                        value="<?= $dateForInput($trajet['date_heure_depart'] ?? null) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date_heure_arrivee" class="form-label">Date et heure d'arrivee</label>
                    <input type="datetime-local" class="form-control" id="date_heure_arrivee" name="date_heure_arrivee"
                        value="<?= $dateForInput($trajet['date_heure_arrivee'] ?? null) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nb_places_total" class="form-label">Nombre total de places</label>
                    <input type="number" min="1" max="9" class="form-control" id="nb_places_total" name="nb_places_total"
                        value="<?= $val('nb_places_total', 1) ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Creer le trajet' ?></button>
            <a href="/" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>