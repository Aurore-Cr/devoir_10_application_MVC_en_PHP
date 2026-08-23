<?php

declare(strict_types=1);

/**
 * Layout principal : header (variable selon le statut de connexion),
 * zone de contenu, footer. $content et $title sont fournis par
 * Controller::render().
 *
 * @var string $content
 * @var string $title
 */

use App\Core\Auth;
use App\Core\FlashMessage;

$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Touche pas au klaxon') ?> - Touche pas au klaxon</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<header class="app-header d-flex justify-content-between align-items-center px-4 py-3 mb-4">
    <?php if ($currentUser !== null && $currentUser['role'] === 'admin') : ?>
        <a class="app-brand" href="/admin">Touche pas au klaxon</a>
        <nav class="d-flex align-items-center gap-2">
            <a class="btn btn-secondary" href="/admin/utilisateurs">Utilisateurs</a>
            <a class="btn btn-secondary" href="/admin/agences">Agences</a>
            <a class="btn btn-secondary" href="/admin/trajets">Trajets</a>
            <span class="app-username">Bonjour <?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom']) ?></span>
            <form method="post" action="/deconnexion" class="m-0">
                <button type="submit" class="btn btn-dark">Deconnexion</button>
            </form>
        </nav>
    <?php elseif ($currentUser !== null) : ?>
        <a class="app-brand" href="/">Touche pas au klaxon</a>
        <nav class="d-flex align-items-center gap-2">
            <a class="btn btn-dark" href="/trajets/creer">Creer un trajet</a>
            <span class="app-username">Bonjour <?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom']) ?></span>
            <form method="post" action="/deconnexion" class="m-0">
                <button type="submit" class="btn btn-dark">Deconnexion</button>
            </form>
        </nav>
    <?php else : ?>
        <a class="app-brand" href="/">Touche pas au klaxon</a>
        <nav>
            <a class="btn btn-dark" href="/connexion">Connexion</a>
        </nav>
    <?php endif; ?>
</header>

<main class="container">
    <?php foreach (FlashMessage::pull() as $flash) : ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>" role="alert">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endforeach; ?>

    <?= $content ?>
</main>

<footer class="app-footer text-center py-4 mt-5">
    Touche pas au klaxon &copy; <?= date('Y') ?> - CENEF - MVC PHP
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
